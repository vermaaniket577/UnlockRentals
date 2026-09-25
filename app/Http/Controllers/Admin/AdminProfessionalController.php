<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Professional;
use App\Models\ProfessionalCategory;
use App\Models\ProfessionalDocument;
use App\Models\ProfessionalLead;
use App\Models\ProfessionalReport;
use App\Models\ProfessionalReview;
use App\Models\ProfessionalService;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminProfessionalController extends Controller
{
    /**
     * Local Professionals Admin CRM Dashboard.
     */
    public function dashboard()
    {
        $stats = [
            'total_professionals' => Professional::count(),
            'pending_professionals' => Professional::where('status', 'pending')->count(),
            'approved_professionals' => Professional::where('status', 'approved')->count(),
            'verified_professionals' => Professional::where('verification_status', 'verified')->count(),
            'suspended_professionals' => Professional::where('status', 'suspended')->count(),

            'total_requests' => ServiceRequest::count(),
            'new_requests' => ServiceRequest::where('status', 'new')->count(),
            'total_leads' => ProfessionalLead::count(),
            'completed_jobs' => ProfessionalLead::where('status', 'completed')->count(),

            'pending_reviews' => ProfessionalReview::where('status', 'pending')->count(),
            'total_reviews' => ProfessionalReview::count(),
            'pending_reports' => ProfessionalReport::where('status', 'pending')->count(),
            'pending_documents' => ProfessionalDocument::where('status', 'pending')->count(),
        ];

        $pendingApprovals = Professional::where('status', 'pending')
            ->with(['category', 'user'])
            ->latest()
            ->take(6)
            ->get();

        $recentLeads = ProfessionalLead::with(['professional', 'serviceRequest'])
            ->latest()
            ->take(6)
            ->get();

        $topCategories = ProfessionalCategory::withCount(['professionals' => function ($q) {
            $q->approved();
        }])->orderByDesc('professionals_count')->take(6)->get();

        return view('admin.professionals.dashboard', compact('stats', 'pendingApprovals', 'recentLeads', 'topCategories'));
    }

    /**
     * Directory of all Professionals with filters and bulk actions.
     */
    public function index(Request $request)
    {
        $status = $request->input('status', 'all');
        $query = Professional::with(['category', 'user', 'documents']);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        if ($request->filled('verification')) {
            $query->where('verification_status', $request->input('verification'));
        }

        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->input('city') . '%');
        }

        if ($request->filled('q')) {
            $q = trim($request->input('q'));
            $query->where(function ($sub) use ($q) {
                $sub->where('business_name', 'like', "%{$q}%")
                    ->orWhere('full_name', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }

        $professionals = $query->latest()->paginate(20)->withQueryString();
        $categories = ProfessionalCategory::ordered()->get();

        $counts = [
            'all' => Professional::count(),
            'pending' => Professional::where('status', 'pending')->count(),
            'approved' => Professional::where('status', 'approved')->count(),
            'suspended' => Professional::where('status', 'suspended')->count(),
            'rejected' => Professional::where('status', 'rejected')->count(),
            'verified' => Professional::where('verification_status', 'verified')->count(),
        ];

        return view('admin.professionals.index', compact('professionals', 'categories', 'counts', 'status'));
    }

    /**
     * View detailed professional profile in Admin CRM.
     */
    public function show(Professional $professional)
    {
        $professional->load([
            'category',
            'services',
            'locations',
            'photos',
            'documents',
            'reviews.user',
            'leads.serviceRequest',
            'reports.user',
            'user',
        ]);

        return view('admin.professionals.show', compact('professional'));
    }

    /**
     * Approve professional.
     */
    public function approve(Professional $professional)
    {
        $professional->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => Auth::id(),
        ]);

        return back()->with('success', 'Professional listing has been approved and is now live!');
    }

    /**
     * Reject professional with reason.
     */
    public function reject(Request $request, Professional $professional)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $professional->update([
            'status' => 'rejected',
        ]);

        return back()->with('success', 'Professional listing has been rejected.');
    }

    /**
     * Toggle verified badge status.
     */
    public function toggleVerification(Professional $professional)
    {
        $newStatus = $professional->verification_status === 'verified' ? 'unverified' : 'verified';
        $professional->update([
            'verification_status' => $newStatus,
        ]);

        return back()->with('success', 'Verification status changed to ' . ucfirst($newStatus) . '.');
    }

    /**
     * Toggle featured status.
     */
    public function toggleFeatured(Professional $professional)
    {
        $professional->update([
            'featured' => !$professional->featured,
        ]);

        return back()->with('success', 'Featured status updated.');
    }

    /**
     * Suspend professional listing.
     */
    public function suspend(Professional $professional)
    {
        $professional->update([
            'status' => 'suspended',
        ]);

        return back()->with('success', 'Professional listing suspended.');
    }

    /**
     * Handle bulk actions.
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,reject,suspend,verify',
            'ids' => 'required|array',
            'ids.*' => 'exists:professionals,id',
        ]);

        $ids = $request->input('ids');
        $action = $request->input('action');

        switch ($action) {
            case 'approve':
                Professional::whereIn('id', $ids)->update([
                    'status' => 'approved',
                    'approved_at' => now(),
                    'approved_by' => Auth::id(),
                ]);
                break;
            case 'reject':
                Professional::whereIn('id', $ids)->update(['status' => 'rejected']);
                break;
            case 'suspend':
                Professional::whereIn('id', $ids)->update(['status' => 'suspended']);
                break;
            case 'verify':
                Professional::whereIn('id', $ids)->update(['verification_status' => 'verified']);
                break;
        }

        return back()->with('success', 'Bulk action applied to ' . count($ids) . ' professionals.');
    }

    /**
     * Manage Categories.
     */
    public function categories()
    {
        $categories = ProfessionalCategory::ordered()
            ->withCount(['services', 'professionals'])
            ->get();

        return view('admin.professionals.categories', compact('categories'));
    }

    /**
     * Store new Category.
     */
    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:professional_categories,name',
            'short_description' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer',
            'status' => 'required|in:active,inactive',
        ]);

        $slug = Str::slug($request->input('name'));

        ProfessionalCategory::create([
            'name' => $request->input('name'),
            'slug' => $slug,
            'short_description' => $request->input('short_description'),
            'description' => $request->input('description'),
            'icon' => $request->input('icon', 'ph-wrench'),
            'sort_order' => $request->input('sort_order', 0),
            'status' => $request->input('status', 'active'),
            'seo_title' => 'Find Best ' . $request->input('name') . ' Near You | UnlockRentals',
            'seo_description' => 'Hire verified and trusted ' . $request->input('name') . ' for your home and office services with transparent pricing.',
        ]);

        return back()->with('success', 'Category created successfully!');
    }

    /**
     * Update Category.
     */
    public function updateCategory(Request $request, ProfessionalCategory $category)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:professional_categories,name,' . $category->id,
            'short_description' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer',
            'status' => 'required|in:active,inactive',
        ]);

        $category->update([
            'name' => $request->input('name'),
            'short_description' => $request->input('short_description'),
            'description' => $request->input('description'),
            'icon' => $request->input('icon', $category->icon),
            'sort_order' => $request->input('sort_order', $category->sort_order),
            'status' => $request->input('status'),
        ]);

        return back()->with('success', 'Category updated successfully!');
    }

    /**
     * Delete Category.
     */
    public function destroyCategory(ProfessionalCategory $category)
    {
        if ($category->professionals()->count() > 0) {
            return back()->with('error', 'Cannot delete category that has existing professionals.');
        }

        $category->delete();
        return back()->with('success', 'Category deleted.');
    }

    /**
     * Manage Sub-services.
     */
    public function services(Request $request)
    {
        $categories = ProfessionalCategory::ordered()->get();
        $selectedCatId = $request->input('category_id', $categories->first()?->id);

        $services = ProfessionalService::where('category_id', $selectedCatId)
            ->ordered()
            ->withCount('professionals')
            ->get();

        return view('admin.professionals.services', compact('categories', 'services', 'selectedCatId'));
    }

    /**
     * Store new Service.
     */
    public function storeService(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:professional_categories,id',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'status' => 'required|in:active,inactive',
        ]);

        ProfessionalService::create([
            'category_id' => $request->input('category_id'),
            'name' => $request->input('name'),
            'slug' => Str::slug($request->input('name')),
            'description' => $request->input('description'),
            'sort_order' => $request->input('sort_order', 0),
            'status' => $request->input('status', 'active'),
        ]);

        return back()->with('success', 'Service created successfully!');
    }

    /**
     * Delete Service.
     */
    public function destroyService(ProfessionalService $service)
    {
        $service->delete();
        return back()->with('success', 'Service deleted.');
    }

    /**
     * Leads CRM overview across platform.
     */
    public function leads(Request $request)
    {
        $query = ProfessionalLead::with(['professional.category', 'serviceRequest', 'customer']);

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $leads = $query->latest()->paginate(20)->withQueryString();

        return view('admin.professionals.leads', compact('leads'));
    }

    /**
     * Review Moderation.
     */
    public function reviews(Request $request)
    {
        $query = ProfessionalReview::with(['professional', 'user']);

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $reviews = $query->latest()->paginate(20)->withQueryString();

        return view('admin.professionals.reviews', compact('reviews'));
    }

    /**
     * Moderate review status (approve, reject, flag).
     */
    public function moderateReview(Request $request, ProfessionalReview $review)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected,flagged',
            'admin_response' => 'nullable|string|max:500',
        ]);

        $review->update([
            'status' => $request->input('status'),
            'admin_response' => $request->input('admin_response'),
        ]);

        // Recalculate average rating for professional
        $review->professional->recalculateRating();

        return back()->with('success', 'Review status updated to ' . ucfirst($request->input('status')) . '.');
    }

    /**
     * Fraud and Abuse Reports.
     */
    public function reports(Request $request)
    {
        $query = ProfessionalReport::with(['professional', 'user']);

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $reports = $query->latest()->paginate(20)->withQueryString();

        return view('admin.professionals.reports', compact('reports'));
    }

    /**
     * Resolve report.
     */
    public function resolveReport(Request $request, ProfessionalReport $report)
    {
        $request->validate([
            'status' => 'required|in:reviewed,resolved,dismissed',
            'admin_notes' => 'nullable|string|max:1000',
            'suspend_professional' => 'nullable|boolean',
        ]);

        $report->update([
            'status' => $request->input('status'),
            'admin_notes' => $request->input('admin_notes'),
        ]);

        if ($request->boolean('suspend_professional')) {
            $report->professional->update(['status' => 'suspended']);
        }

        return back()->with('success', 'Report resolution updated.');
    }

    /**
     * Secure admin download of private KYC document.
     */
    public function downloadDocument(ProfessionalDocument $document)
    {
        if (!Storage::disk('local')->exists($document->file_path)) {
            abort(404, 'File not found on server.');
        }

        return Storage::disk('local')->download($document->file_path);
    }

    /**
     * Verify/Reject KYC Document.
     */
    public function verifyDocument(Request $request, ProfessionalDocument $document)
    {
        $request->validate([
            'status' => 'required|in:verified,rejected',
            'rejection_reason' => 'nullable|string|max:255',
        ]);

        $status = $request->input('status');
        $document->update([
            'status' => $status,
            'verified_at' => $status === 'verified' ? now() : null,
            'verified_by' => Auth::id(),
            'rejection_reason' => $request->input('rejection_reason'),
        ]);

        return back()->with('success', 'Document status updated to ' . ucfirst($status) . '.');
    }
}
