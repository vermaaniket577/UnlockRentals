<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

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

        Cache::forget('admin_notifications');

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

        if ($request->sender === 'user') {
            Cache::forget('admin_notifications');
        }

        return response()->json(['status' => 'success']);
    }

    public function getChatHistory($session_id)
    {
        $messages = \App\Models\ChatbotMessage::where('session_id', $session_id)
            ->orderBy('created_at', 'asc')
            ->get(['message', 'sender', 'created_at']);
            
        return response()->json(['messages' => $messages]);
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

        Cache::forget('admin_notifications');

        return response()->json(['status' => 'success', 'message' => 'Agent will call you soon!']);
    }
}
