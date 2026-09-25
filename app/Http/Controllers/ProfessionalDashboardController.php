<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Professional;
use App\Models\ProfessionalAvailability;
use App\Models\ProfessionalCategory;
use App\Models\ProfessionalDocument;
use App\Models\ProfessionalLead;
use App\Models\ProfessionalLocation;
use App\Models\ProfessionalPhoto;
use App\Models\ProfessionalService;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfessionalDashboardController extends Controller
{
    /**
     * Get the authenticated user's professional model or abort.
     */
    protected function getProfessional(): Professional
    {
        $user = Auth::user();
        $professional = $user->professional;

        if (!$professional) {
            abort(403, 'You do not have a registered professional listing.');
        }

        return $professional;
    }

    /**
     * Professional Dashboard Homepage.
     */
    public function index()
    {
        $user = Auth::user();
        $professional = $user->professional;

        if (!$professional) {
            return redirect()->route('services.register')
                ->with('info', 'Please complete your free professional listing first.');
        }

        // Calculate Profile Completion %
        $completion = $professional->profile_completion;

        // Statistics
        $totalLeads = $professional->leads()->count();
        $newLeads = $professional->leads()->where('status', 'new')->count();
        $acceptedLeads = $professional->leads()->where('status', 'accepted')->count();
        $completedJobs = $professional->leads()->where('status', 'completed')->count();
        $cancelledLeads = $professional->leads()->where('status', 'rejected')->count();

        // Recent 5 leads
        $recentLeads = $professional->leads()
            ->with(['serviceRequest.category', 'serviceRequest.service', 'customer'])
            ->latest()
            ->take(5)
            ->get();

        // Recent 5 reviews
        $recentReviews = $professional->reviews()
            ->with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('professional.dashboard', compact(
            'professional',
            'completion',
            'totalLeads',
            'newLeads',
            'acceptedLeads',
            'completedJobs',
            'cancelledLeads',
            'recentLeads',
            'recentReviews'
        ));
    }

    /**
     * Leads CRM view for professional.
     */
    public function leads(Request $request)
    {
        $professional = $this->getProfessional();
        $status = $request->input('status', 'all');

        $query = $professional->leads()
            ->with(['serviceRequest.category', 'serviceRequest.service', 'serviceRequest.attachments', 'customer']);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $leads = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'all' => $professional->leads()->count(),
            'new' => $professional->leads()->where('status', 'new')->count(),
            'contacted' => $professional->leads()->where('status', 'contacted')->count(),
            'accepted' => $professional->leads()->where('status', 'accepted')->count(),
            'completed' => $professional->leads()->where('status', 'completed')->count(),
            'rejected' => $professional->leads()->where('status', 'rejected')->count(),
        ];

        return view('professional.leads', compact('professional', 'leads', 'stats', 'status'));
    }

    /**
     * Update lead status (Accept, Reject, Contacted, Completed).
     */
    public function updateLeadStatus(Request $request, ProfessionalLead $lead)
    {
        $professional = $this->getProfessional();

        if ($lead->professional_id !== $professional->id) {
            abort(403, 'Unauthorized access to this lead.');
        }

        $request->validate([
            'status' => 'required|in:viewed,contacted,accepted,rejected,completed,cancelled',
            'response' => 'nullable|string|max:500',
        ]);

        $status = $request->input('status');
        $updates = [
            'status' => $status,
            'professional_response' => $request->input('response', $lead->professional_response),
        ];

        if ($status === 'contacted' && !$lead->contacted_at) {
            $updates['contacted_at'] = now();
        } elseif ($status === 'accepted' && !$lead->accepted_at) {
            $updates['accepted_at'] = now();
        } elseif ($status === 'completed' && !$lead->completed_at) {
            $updates['completed_at'] = now();
        }

        $lead->update($updates);

        // Update corresponding service request status if appropriate
        if ($lead->serviceRequest) {
            if ($status === 'accepted') {
                $lead->serviceRequest->update(['status' => 'accepted']);
            } elseif ($status === 'completed') {
                $lead->serviceRequest->update(['status' => 'completed']);
            }
        }

        return back()->with('success', 'Lead status updated to ' . ucfirst($status) . '.');
    }

    /**
     * Edit professional profile page.
     */
    public function profile()
    {
        $professional = $this->getProfessional();
        $categories = ProfessionalCategory::active()->ordered()->get();
        $states = State::orderBy('name')->get();
        $districts = District::orderBy('name')->get();

        return view('professional.profile', compact('professional', 'categories', 'states', 'districts'));
    }

    /**
     * Save profile updates.
     */
    public function updateProfile(Request $request)
    {
        $professional = $this->getProfessional();

        $request->validate([
            'business_name' => 'required|string|max:255',
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
            'email' => 'required|email|max:255',
            'description' => 'required|string|min:20|max:3000',
            'years_experience' => 'required|integer|min:0|max:60',
            'starting_price' => 'nullable|numeric|min:0',
            'price_type' => 'required|in:hourly,per_visit,per_service,negotiable,contact',
            'city' => 'required|string|max:100',
            'locality' => 'nullable|string|max:150',
            'pincode' => 'nullable|string|max:10',
            'address' => 'nullable|string|max:255',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'home_visit' => 'nullable|boolean',
            'emergency_service' => 'nullable|boolean',
            'available_today' => 'nullable|boolean',
        ]);

        $updates = [
            'business_name' => $request->input('business_name'),
            'full_name' => $request->input('full_name'),
            'phone' => $request->input('phone'),
            'whatsapp_number' => $request->input('whatsapp_number') ?: $request->input('phone'),
            'email' => $request->input('email'),
            'description' => $request->input('description'),
            'years_experience' => $request->input('years_experience'),
            'starting_price' => $request->input('starting_price'),
            'price_type' => $request->input('price_type'),
            'city' => $request->input('city'),
            'locality' => $request->input('locality'),
            'pincode' => $request->input('pincode'),
            'address' => $request->input('address'),
            'home_visit' => $request->boolean('home_visit', false),
            'emergency_service' => $request->boolean('emergency_service', false),
            'available_today' => $request->boolean('available_today', false),
        ];

        // Handle profile photo update
        if ($request->hasFile('profile_photo')) {
            $photo = $request->file('profile_photo');
            $photoName = 'prof_avatar_' . time() . '_' . Str::random(8) . '.' . $photo->getClientOriginalExtension();
            $updates['profile_photo'] = $photo->storeAs('professionals/avatars', $photoName, 'public');
        }

        $professional->update($updates);

        return back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Services management page.
     */
    public function services()
    {
        $professional = $this->getProfessional();
        $categoryServices = $professional->category->services()->active()->ordered()->get();
        $selectedServiceIds = $professional->services()->pluck('professional_services.id')->toArray();

        return view('professional.services', compact('professional', 'categoryServices', 'selectedServiceIds'));
    }

    /**
     * Update services offered.
     */
    public function updateServices(Request $request)
    {
        $professional = $this->getProfessional();

        $request->validate([
            'services' => 'required|array|min:1',
            'services.*' => 'exists:professional_services,id',
        ]);

        $professional->services()->sync($request->input('services'));

        return back()->with('success', 'Services updated successfully!');
    }

    /**
     * Manage service areas / locations.
     */
    public function locations()
    {
        $professional = $this->getProfessional();
        $locations = $professional->locations()->latest()->get();

        return view('professional.locations', compact('professional', 'locations'));
    }

    /**
     * Add a service area.
     */
    public function storeLocation(Request $request)
    {
        $professional = $this->getProfessional();

        $request->validate([
            'city' => 'required|string|max:100',
            'locality' => 'nullable|string|max:150',
            'pincode' => 'nullable|string|max:10',
            'service_radius_km' => 'required|integer|min:1|max:100',
        ]);

        ProfessionalLocation::create([
            'professional_id' => $professional->id,
            'state' => $professional->state,
            'district' => $professional->district,
            'city' => $request->input('city'),
            'locality' => $request->input('locality'),
            'pincode' => $request->input('pincode'),
            'service_radius_km' => $request->input('service_radius_km'),
        ]);

        return back()->with('success', 'Service area added successfully!');
    }

    /**
     * Remove a service area.
     */
    public function destroyLocation(ProfessionalLocation $location)
    {
        $professional = $this->getProfessional();

        if ($location->professional_id !== $professional->id) {
            abort(403);
        }

        // Don't allow deleting the last location
        if ($professional->locations()->count() <= 1) {
            return back()->with('error', 'You must have at least one service location.');
        }

        $location->delete();

        return back()->with('success', 'Service area removed.');
    }

    /**
     * Work photos management.
     */
    public function photos()
    {
        $professional = $this->getProfessional();
        $photos = $professional->photos()->ordered()->get();

        return view('professional.photos', compact('professional', 'photos'));
    }

    /**
     * Upload new work photo.
     */
    public function storePhoto(Request $request)
    {
        $professional = $this->getProfessional();

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:4096',
            'caption' => 'nullable|string|max:150',
        ]);

        $photoFile = $request->file('image');
        $pName = 'work_' . $professional->id . '_' . time() . '_' . Str::random(6) . '.' . $photoFile->getClientOriginalExtension();
        $savedPath = $photoFile->storeAs('professionals/work_photos', $pName, 'public');

        ProfessionalPhoto::create([
            'professional_id' => $professional->id,
            'image' => $savedPath,
            'caption' => $request->input('caption', 'Work sample'),
            'sort_order' => $professional->photos()->count(),
            'status' => 'active',
        ]);

        return back()->with('success', 'Work photo added successfully!');
    }

    /**
     * Delete work photo.
     */
    public function destroyPhoto(ProfessionalPhoto $photo)
    {
        $professional = $this->getProfessional();

        if ($photo->professional_id !== $professional->id) {
            abort(403);
        }

        Storage::disk('public')->delete($photo->image);
        $photo->delete();

        return back()->with('success', 'Photo removed.');
    }

    /**
     * Manage KYC Documents privately.
     */
    public function documents()
    {
        $professional = $this->getProfessional();
        $documents = $professional->documents()->latest()->get();

        return view('professional.documents', compact('professional', 'documents'));
    }

    /**
     * Upload private KYC Document.
     */
    public function storeDocument(Request $request)
    {
        $professional = $this->getProfessional();

        $request->validate([
            'document_type' => 'required|string|max:50',
            'document_number' => 'nullable|string|max:100',
            'document_file' => 'required|file|mimes:pdf,jpeg,png,jpg|max:5120',
        ]);

        $docFile = $request->file('document_file');
        $docName = 'doc_' . $professional->id . '_' . time() . '_' . Str::random(10) . '.' . $docFile->getClientOriginalExtension();
        $docPath = $docFile->storeAs('private_documents/professionals', $docName, 'local');

        ProfessionalDocument::create([
            'professional_id' => $professional->id,
            'document_type' => $request->input('document_type'),
            'document_number' => $request->input('document_number'),
            'file_path' => $docPath,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Document uploaded securely. It will be reviewed by admin for verification.');
    }

    /**
     * Secure download of private KYC document (guarded by authorization).
     */
    public function downloadPrivateDocument(ProfessionalDocument $document)
    {
        $user = Auth::user();

        // Allowed only for the owning professional or an admin
        $isOwner = ($user->professional && $user->professional->id === $document->professional_id);
        $isAdmin = $user->isAdmin();

        if (!$isOwner && !$isAdmin) {
            abort(403, 'Unauthorized access to this document.');
        }

        if (!Storage::disk('local')->exists($document->file_path)) {
            abort(404, 'Document file not found on server.');
        }

        return Storage::disk('local')->download($document->file_path);
    }

    /**
     * Manage availability schedule.
     */
    public function availability()
    {
        $professional = $this->getProfessional();
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $schedules = $professional->availability->keyBy('day_of_week');

        return view('professional.availability', compact('professional', 'days', 'schedules'));
    }

    /**
     * Update availability schedule.
     */
    public function updateAvailability(Request $request)
    {
        $professional = $this->getProfessional();
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        foreach ($days as $day) {
            $isAvail = $request->boolean("avail_{$day}", false);
            $start = $request->input("start_{$day}", '09:00');
            $end = $request->input("end_{$day}", '19:00');

            ProfessionalAvailability::updateOrCreate(
                [
                    'professional_id' => $professional->id,
                    'day_of_week' => $day,
                ],
                [
                    'is_available' => $isAvail,
                    'start_time' => $start,
                    'end_time' => $end,
                ]
            );
        }

        // Toggle immediate flags
        $professional->update([
            'available_today' => $request->boolean('available_today', true),
            'emergency_service' => $request->boolean('emergency_service', false),
        ]);

        return back()->with('success', 'Availability schedule updated successfully!');
    }
}
