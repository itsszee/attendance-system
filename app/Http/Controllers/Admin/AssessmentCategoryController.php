<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssessmentCategory;
use Illuminate\Http\Request;

class AssessmentCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = AssessmentCategory::latest()->get();
        return view('admin.assessment-categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.assessment-categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        AssessmentCategory::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'type' => $validated['type'] ?? 'General',
            'is_active' => request()->has('is_active') ? true : false,
        ]);

        return redirect()->route('assessment-categories.index')
            ->with('success', 'Kategori Penilaian berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(AssessmentCategory $assessmentCategory)
    {
        // Not used
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AssessmentCategory $assessmentCategory)
    {
        return view('admin.assessment-categories.edit', compact('assessmentCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AssessmentCategory $assessmentCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'nullable|string|max:255',
            'is_active' => 'nullable',
        ]);

        $assessmentCategory->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'type' => $validated['type'] ?? 'General',
            'is_active' => request()->has('is_active') ? true : false,
        ]);

        return redirect()->route('assessment-categories.index')
            ->with('success', 'Kategori Penilaian berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AssessmentCategory $assessmentCategory)
    {
        $assessmentCategory->delete();

        return redirect()->route('assessment-categories.index')
            ->with('success', 'Kategori Penilaian berhasil dihapus.');
    }
}
