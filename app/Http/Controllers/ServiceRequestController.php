<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateServiceRequestRequest;
use App\Models\Professional;
use App\Models\ProfessionalCategory;
use App\Models\ProfessionalLead;
use App\Models\ProfessionalService;
use App\Models\ServiceRequest;
use App\Models\ServiceRequestAttachment;
use App\Services\ProfessionalMatchingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ServiceRequestController extends Controller
{
    /**
     * Submit a customer service request / lead.
     */
    public function store(CreateServiceRequestRequest $request, ProfessionalMatchingService $matchingService)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();

            $serviceRequest = ServiceRequest::create([
                'user_id' => $user?->id,
                'category_id' => $request->input('category_id'),
                'service_id' => $request->input('service_id'),
                'name' => $request->input('name'),
                'phone' => $request->input('phone'),
                'email' => $request->input('email'),
                'description' => $request->input('description'),
                'address' => $request->input('address'),
                'city' => $request->input('city'),
                'locality' => $request->input('locality'),
                'pincode' => $request->input('pincode'),
                'preferred_date' => $request->input('preferred_date'),
                'preferred_time' => $request->input('preferred_time'),
                'budget' => $request->input('budget'),
                'status' => 'new',
            ]);

            // Save attachments if any
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    if ($file && $file->isValid()) {
                        $fName = 'req_' . $serviceRequest->id . '_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
                        $savedPath = $file->storeAs('service_requests/attachments', $fName, 'public');

                        ServiceRequestAttachment::create([
                            'service_request_id' => $serviceRequest->id,
                            'file_path' => $savedPath,
                        ]);
                    }
                }
            }

            // Direct lead if requesting a specific professional
            $directProfessionalId = $request->input('target_professional_id');
            if ($directProfessionalId) {
                $targetProf = Professional::approved()->find($directProfessionalId);
                if ($targetProf) {
                    ProfessionalLead::create([
                        'service_request_id' => $serviceRequest->id,
                        'professional_id' => $targetProf->id,
                        'customer_id' => $user?->id,
                        'lead_source' => 'Profile',
                        'status' => 'new',
                    ]);

                    $targetProf->increment('lead_count');
                }
            } else {
                // Run automatic matching engine
                $matchingService->matchAndDispatch($serviceRequest, 4);
            }

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Your service request has been submitted successfully! Verified professionals will contact you shortly.',
                    'request_id' => $serviceRequest->id,
                ]);
            }

            return back()->with('success', 'Your service request has been submitted successfully! Verified professionals will contact you shortly.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Service request creation failed: ' . $e->getMessage());

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unable to submit your request at this time. Please try again or call the professional directly.',
                ], 500);
            }

            return back()->withInput()->with('error', 'Unable to submit your request right now. Please try again.');
        }
    }

    /**
     * Customer portal to view their submitted service requests.
     */
    public function myRequests()
    {
        $user = Auth::user();

        $requests = ServiceRequest::where('user_id', $user->id)
            ->with(['category', 'service', 'leads.professional', 'attachments'])
            ->latest()
            ->paginate(10);

        return view('services.my-requests', compact('requests'));
    }
}
