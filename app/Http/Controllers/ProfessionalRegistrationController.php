<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterProfessionalRequest;
use App\Models\District;
use App\Models\Professional;
use App\Models\ProfessionalCategory;
use App\Models\ProfessionalDocument;
use App\Models\ProfessionalLocation;
use App\Models\ProfessionalPhoto;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfessionalRegistrationController extends Controller
{
    /**
     * Show the free professional registration form.
     */
    public function showRegistrationForm()
    {
        if (!Auth::check()) {
            return redirect()->guest(route('login'))->with('info', 'Please sign in or create an account first to list your professional service.');
        }

        $categories = ProfessionalCategory::active()
            ->ordered()
            ->with(['services' => function ($q) {
                $q->active()->ordered();
            }])
            ->get();

        $states = State::orderBy('name')->get();
        $districts = District::orderBy('name')->get();

        return view('services.register', compact('categories', 'states', 'districts'));
    }

    /**
     * Handle submission of the free professional registration form.
     */
    public function register(RegisterProfessionalRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();

            // 1. Resolve or create associated User account
            if (!$user) {
                $email = strtolower(trim($request->input('email', '')));
                if (empty($email)) {
                    return redirect()->route('login')->with('info', 'Please sign in or create an account first to list your professional service.');
                }

                $existingUser = User::where('email', $email)->first();
                if ($existingUser) {
                    // If user exists but not logged in, require them to log in first
                    return back()
                        ->withInput()
                        ->with('error', 'An account with this email already exists. Please log in before listing your service.');
                }

                $user = User::create([
                    'name' => $request->input('full_name') ?: 'Professional Provider',
                    'email' => $email,
                    'phone' => $request->input('phone'),
                    'password' => Hash::make($request->input('password', Str::random(12))),
                    'role' => 'professional',
                ]);

                Auth::login($user, true);
            } else {
                // If user doesn't have phone, populate it
                if (empty($user->phone) && $request->filled('phone')) {
                    $user->update(['phone' => $request->input('phone')]);
                }
            }

            // Check if user already registered a professional listing
            if ($user->professional()->exists()) {
                DB::rollBack();
                return redirect()->route('professional.dashboard')
                    ->with('info', 'You already have a registered professional listing. You can update it here.');
            }

            // Name and email automatically fetched from account profile created in beginning
            $fullName = $user ? $user->name : ($request->input('full_name') ?: 'Professional Provider');
            $email = $user ? $user->email : $request->input('email');

            // 2. Generate unique slug
            $baseSlug = Str::slug($request->input('business_name'));
            if (empty($baseSlug)) {
                $baseSlug = Str::slug($fullName . ' ' . $request->input('city'));
            }
            $slug = $baseSlug;
            $counter = 1;
            while (Professional::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . (++$counter);
            }

            // 3. Process Profile Photo
            $profilePhotoPath = null;
            if ($request->hasFile('profile_photo')) {
                $photo = $request->file('profile_photo');
                $photoName = 'prof_avatar_' . time() . '_' . Str::random(8) . '.' . $photo->getClientOriginalExtension();
                $profilePhotoPath = $photo->storeAs('professionals/avatars', $photoName, 'public');

                // If user doesn't have an avatar in their base profile, update it too
                if ($user && empty($user->avatar)) {
                    $user->update(['avatar' => $profilePhotoPath]);
                }
            } elseif ($user && !empty($user->avatar)) {
                $profilePhotoPath = $user->avatar;
            }

            // 4. Create Professional Record
            $professional = Professional::create([
                'user_id' => $user->id,
                'category_id' => $request->input('category_id'),
                'business_name' => $request->input('business_name'),
                'slug' => $slug,
                'full_name' => $fullName,
                'profile_photo' => $profilePhotoPath,
                'phone' => $request->input('phone'),
                'whatsapp_number' => $request->input('whatsapp_number') ?: $request->input('phone'),
                'email' => $email,
                'description' => $request->input('description') ?: ('Verified and experienced ' . ($request->input('business_name') ?: 'professional') . ' with ' . $request->input('years_experience', 5) . '+ years of experience serving ' . $request->input('city') . ' and nearby localities. Prompt doorstep service with customer satisfaction guaranteed.'),
                'years_experience' => $request->input('years_experience', 0),
                'starting_price' => $request->input('starting_price'),
                'price_type' => $request->input('price_type', 'contact'),
                'home_visit' => $request->boolean('home_visit', true),
                'emergency_service' => $request->boolean('emergency_service', false),
                'available_today' => $request->boolean('available_today', true),
                'address' => $request->input('address'),
                'state' => $request->input('state'),
                'district' => $request->input('district'),
                'city' => $request->input('city'),
                'locality' => $request->input('locality'),
                'pincode' => $request->input('pincode'),
                'latitude' => $request->input('latitude'),
                'longitude' => $request->input('longitude'),
                'service_radius_km' => $request->input('service_radius_km', 20),
                'status' => 'pending', // Requires admin approval
                'verification_status' => 'unverified',
                'views_count' => 0,
                'whatsapp_clicks' => 0,
                'call_clicks' => 0,
                'lead_count' => 0,
                'average_rating' => 0.00,
                'review_count' => 0,
            ]);

            // 5. Sync Selected Services
            $selectedServices = $request->input('services') ?? $request->input('service_ids');
            if (!empty($selectedServices) && is_array($selectedServices)) {
                $professional->services()->sync($selectedServices);
            }

            // 6. Create Primary Service Location
            ProfessionalLocation::create([
                'professional_id' => $professional->id,
                'state' => $request->input('state'),
                'district' => $request->input('district'),
                'city' => $request->input('city'),
                'locality' => $request->input('locality'),
                'pincode' => $request->input('pincode'),
                'latitude' => $request->input('latitude'),
                'longitude' => $request->input('longitude'),
                'service_radius_km' => $request->input('service_radius_km', 20),
            ]);

            // 7. Process Work Photos
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $idx => $photoFile) {
                    if ($photoFile && $photoFile->isValid()) {
                        $pName = 'work_' . $professional->id . '_' . time() . '_' . Str::random(6) . '.' . $photoFile->getClientOriginalExtension();
                        $savedPath = $photoFile->storeAs('professionals/work_photos', $pName, 'public');
                        
                        ProfessionalPhoto::create([
                            'professional_id' => $professional->id,
                            'image' => $savedPath,
                            'caption' => 'Work sample ' . ($idx + 1),
                            'sort_order' => $idx,
                            'status' => 'active',
                        ]);
                    }
                }
            }

            // 8. Process Private KYC Documents (Stored securely in local private storage)
            if ($request->hasFile('document_file')) {
                $docFile = $request->file('document_file');
                if ($docFile && $docFile->isValid()) {
                    $docName = 'doc_' . $professional->id . '_' . time() . '_' . Str::random(10) . '.' . $docFile->getClientOriginalExtension();
                    // Store in private disk (not public url)
                    $docPath = $docFile->storeAs('private_documents/professionals', $docName, 'local');

                    ProfessionalDocument::create([
                        'professional_id' => $professional->id,
                        'document_type' => $request->input('document_type', 'ID Proof'),
                        'document_number' => $request->input('document_number'),
                        'file_path' => $docPath,
                        'status' => 'pending',
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('professional.dashboard')
                ->with('success', 'Congratulations! Your professional listing has been submitted successfully for verification. You can track your leads and update your profile from this dashboard.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Professional registration error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Unable to complete your registration right now: ' . $e->getMessage());
        }
    }
}
