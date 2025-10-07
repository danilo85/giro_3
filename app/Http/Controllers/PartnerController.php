<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class PartnerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Partner::forUser(Auth::id())->ordered();

        // Search functionality
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $partners = $query->get();

        return view('partners.index', compact('partners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $nextSortOrder = Partner::getNextSortOrder(Auth::id());
        return view('partners.create', compact('nextSortOrder'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'website_url' => 'nullable|url|max:500',
            'description' => 'nullable|string',
            'logo' => 'required|image|mimes:png,jpg,jpeg|max:2048', // 2MB max
            'is_active' => 'boolean',
        ]);

        try {
            // Handle logo upload
            $logoPath = $request->file('logo')->store('partners', 'public');

            // Create partner
            $partner = Partner::create([
                'user_id' => Auth::id(),
                'name' => $validated['name'],
                'website_url' => $validated['website_url'],
                'description' => $validated['description'],
                'logo_path' => $logoPath,
                'is_active' => $validated['is_active'] ?? true,
                'sort_order' => Partner::getNextSortOrder(Auth::id()),
            ]);

            return redirect()->route('partners.index')
                ->with('success', 'Parceiro criado com sucesso!');

        } catch (\Exception $e) {
            Log::error('Error creating partner: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Erro ao criar parceiro. Tente novamente.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Partner $partner)
    {
        $this->authorize('view', $partner);
        return view('partners.show', compact('partner'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Partner $partner)
    {
        $this->authorize('update', $partner);
        return view('partners.edit', compact('partner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Partner $partner)
    {
        $this->authorize('update', $partner);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'website_url' => 'nullable|url|max:500',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'is_active' => 'boolean',
        ]);

        try {
            // Handle logo upload if new logo is provided
            if ($request->hasFile('logo')) {
                // Delete old logo
                if ($partner->logo_path && Storage::disk('public')->exists($partner->logo_path)) {
                    Storage::disk('public')->delete($partner->logo_path);
                }
                
                // Store new logo
                $validated['logo_path'] = $request->file('logo')->store('partners', 'public');
            }

            // Update partner
            $partner->update([
                'name' => $validated['name'],
                'website_url' => $validated['website_url'],
                'description' => $validated['description'],
                'logo_path' => $validated['logo_path'] ?? $partner->logo_path,
                'is_active' => $validated['is_active'] ?? $partner->is_active,
            ]);

            return redirect()->route('partners.index')
                ->with('success', 'Parceiro atualizado com sucesso!');

        } catch (\Exception $e) {
            Log::error('Error updating partner: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Erro ao atualizar parceiro. Tente novamente.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Partner $partner)
    {
        $this->authorize('delete', $partner);

        try {
            $partner->delete(); // This will also delete the logo file via the model's delete method
            
            return redirect()->route('partners.index')
                ->with('success', 'Parceiro excluído com sucesso!');

        } catch (\Exception $e) {
            Log::error('Error deleting partner: ' . $e->getMessage());
            return back()->with('error', 'Erro ao excluir parceiro. Tente novamente.');
        }
    }

    /**
     * Toggle the active status of a partner.
     */
    public function toggleStatus(Partner $partner)
    {
        $this->authorize('update', $partner);

        try {
            $partner->toggleStatus();

            return response()->json([
                'success' => true,
                'is_active' => $partner->is_active,
                'message' => 'Status atualizado com sucesso!'
            ]);

        } catch (\Exception $e) {
            Log::error('Error toggling partner status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar status.'
            ], 500);
        }
    }

    /**
     * Update the order of partners via drag and drop.
     */
    public function updateOrder(Request $request)
    {
        $validated = $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:partners,id',
        ]);

        try {
            $partnerIds = $validated['order'];
            
            // Verify all partners belong to the authenticated user
            $partners = Partner::whereIn('id', $partnerIds)
                ->where('user_id', Auth::id())
                ->get();

            if ($partners->count() !== count($partnerIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Acesso negado ou parceiros não encontrados.'
                ], 403);
            }

            // Update sort_order for each partner based on the new order
            foreach ($partnerIds as $index => $partnerId) {
                Partner::where('id', $partnerId)
                    ->where('user_id', Auth::id())
                    ->update(['sort_order' => $index + 1]);
            }

            Log::info('Partner order updated', [
                'user_id' => Auth::id(),
                'new_order' => $partnerIds
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Ordem atualizada com sucesso!'
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating partner order: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar ordem.'
            ], 500);
        }
    }
}
