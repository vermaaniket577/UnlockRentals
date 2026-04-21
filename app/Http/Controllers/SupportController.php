<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function storeFeedback(Request $request)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        Feedback::create([
            'user_id' => auth()->id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => 'new'
        ]);

        if ($request->ajax()) {
            return response()->json(['message' => 'Feedback submitted successfully!']);
        }

    }
    
    public function saveChatMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'sender' => 'required|in:user,bot',
            'session_id' => 'required|string'
        ]);

        \App\Models\ChatbotMessage::create([
            'user_id' => auth()->id(),
            'session_id' => $request->session_id,
            'message' => $request->message,
            'sender' => $request->sender
        ]);

        return response()->json(['status' => 'success']);
    }

    public function requestCallback(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'session_id' => 'required|string',
            'name' => 'nullable|string'
        ]);

        \App\Models\CallbackRequest::create([
            'user_id' => auth()->id(),
            'session_id' => $request->session_id,
            'name' => $request->name,
            'phone' => $request->phone,
            'status' => 'new'
        ]);

        return response()->json(['status' => 'success', 'message' => 'Agent will call you soon!']);
    }
}
