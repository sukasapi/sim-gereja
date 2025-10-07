<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::withCount('posts')
                            ->orderBy('name')
                            ->paginate(15);
        
        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Superadmin tidak dapat membuat kategori baru
        if (Auth::user()->isSuperAdmin()) {
            abort(403, 'Superadmin tidak dapat membuat kategori baru. Silakan hubungi admin gereja.');
        }
        
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Superadmin tidak dapat membuat kategori baru
        if (Auth::user()->isSuperAdmin()) {
            abort(403, 'Superadmin tidak dapat membuat kategori baru. Silakan hubungi admin gereja.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string|max:500',
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'is_active' => 'boolean',
        ]);

        $categoryData = $request->only(['name', 'description', 'color', 'is_active']);
        $categoryData['slug'] = Str::slug($request->name);

        Category::create($categoryData);

        return redirect()->route('categories.index')
                        ->with('success', 'Kategori berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $posts = $category->posts()
                         ->with(['user', 'church'])
                         ->published()
                         ->orderBy('published_at', 'desc')
                         ->paginate(10);
        
        return view('categories.show', compact('category', 'posts'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        // Superadmin tidak dapat mengedit kategori
        if (Auth::user()->isSuperAdmin()) {
            abort(403, 'Superadmin tidak dapat mengedit kategori. Silakan hubungi admin gereja.');
        }
        
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        // Superadmin tidak dapat mengedit kategori
        if (Auth::user()->isSuperAdmin()) {
            abort(403, 'Superadmin tidak dapat mengedit kategori. Silakan hubungi admin gereja.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string|max:500',
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'is_active' => 'boolean',
        ]);

        $categoryData = $request->only(['name', 'description', 'color', 'is_active']);
        $categoryData['slug'] = Str::slug($request->name);

        $category->update($categoryData);

        return redirect()->route('categories.index')
                        ->with('success', 'Kategori berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // Superadmin tidak dapat menghapus kategori
        if (Auth::user()->isSuperAdmin()) {
            abort(403, 'Superadmin tidak dapat menghapus kategori. Silakan hubungi admin gereja.');
        }
        
        // Check if category has posts
        if ($category->posts()->count() > 0) {
            return redirect()->route('categories.index')
                            ->with('error', 'Tidak dapat menghapus kategori yang memiliki posting!');
        }

        $category->delete();

        return redirect()->route('categories.index')
                        ->with('success', 'Kategori berhasil dihapus!');
    }

    /**
     * Toggle active status
     */
    public function toggleActive(Category $category)
    {
        $category->update(['is_active' => !$category->is_active]);
        
        return response()->json([
            'success' => true,
            'is_active' => $category->is_active
        ]);
    }
}
