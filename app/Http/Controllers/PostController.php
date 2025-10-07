<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
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
        $user = Auth::user();
        
        // Superadmin dapat melihat semua post
        if ($user->isSuperAdmin()) {
            $posts = Post::with(['category', 'user', 'church'])
                        ->orderBy('created_at', 'desc')
                        ->paginate(15);
        } else {
            // Admin gereja hanya dapat melihat post gerejanya
            $posts = Post::with(['category', 'user', 'church'])
                        ->where('church_id', $user->church_id)
                        ->orderBy('created_at', 'desc')
                        ->paginate(15);
        }

        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Superadmin tidak dapat membuat post baru
        if (Auth::user()->isSuperAdmin()) {
            abort(403, 'Superadmin tidak dapat membuat posting baru. Silakan hubungi admin gereja.');
        }
        
        $categories = Category::active()->get();
        return view('posts.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Superadmin tidak dapat membuat post baru
        if (Auth::user()->isSuperAdmin()) {
            abort(403, 'Superadmin tidak dapat membuat posting baru. Silakan hubungi admin gereja.');
        }
        
        $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,published,archived',
            'is_featured' => 'boolean',
        ]);

        $user = Auth::user();
        
        $postData = $request->only([
            'title', 'excerpt', 'content', 'category_id', 'status', 'is_featured'
        ]);
        
        $postData['user_id'] = $user->id;
        $postData['church_id'] = $user->church_id;
        $postData['slug'] = Str::slug($request->title);

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $image = $request->file('featured_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/posts', $imageName);
            $postData['featured_image'] = 'posts/' . $imageName;
        }

        Post::create($postData);

        return redirect()->route('posts.index')
                        ->with('success', 'Post berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        // Increment views
        $post->increment('views');
        
        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        // Superadmin tidak dapat mengedit post
        if (Auth::user()->isSuperAdmin()) {
            abort(403, 'Superadmin tidak dapat mengedit posting. Silakan hubungi admin gereja.');
        }
        
        $this->authorize('update', $post);
        
        $categories = Category::active()->get();
        return view('posts.edit', compact('post', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        // Superadmin tidak dapat mengedit post
        if (Auth::user()->isSuperAdmin()) {
            abort(403, 'Superadmin tidak dapat mengedit posting. Silakan hubungi admin gereja.');
        }
        
        $this->authorize('update', $post);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,published,archived',
            'is_featured' => 'boolean',
        ]);

        $postData = $request->only([
            'title', 'excerpt', 'content', 'category_id', 'status', 'is_featured'
        ]);
        
        $postData['slug'] = Str::slug($request->title);

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($post->featured_image) {
                Storage::delete('public/' . $post->featured_image);
            }
            
            $image = $request->file('featured_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/posts', $imageName);
            $postData['featured_image'] = 'posts/' . $imageName;
        }

        $post->update($postData);

        return redirect()->route('posts.index')
                        ->with('success', 'Post berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        // Superadmin tidak dapat menghapus post
        if (Auth::user()->isSuperAdmin()) {
            abort(403, 'Superadmin tidak dapat menghapus posting. Silakan hubungi admin gereja.');
        }
        
        $this->authorize('delete', $post);
        
        // Delete featured image
        if ($post->featured_image) {
            Storage::delete('public/' . $post->featured_image);
        }
        
        $post->delete();

        return redirect()->route('posts.index')
                        ->with('success', 'Post berhasil dihapus!');
    }

    /**
     * Toggle featured status
     */
    public function toggleFeatured(Post $post)
    {
        $this->authorize('update', $post);
        
        $post->update(['is_featured' => !$post->is_featured]);
        
        return response()->json([
            'success' => true,
            'is_featured' => $post->is_featured
        ]);
    }

    /**
     * Toggle post status (for superadmin only)
     */
    public function toggleStatus(Post $post)
    {
        $this->authorize('toggleStatus', $post);
        
        $newStatus = $post->status === 'published' ? 'archived' : 'published';
        $post->update(['status' => $newStatus]);
        
        return response()->json([
            'success' => true,
            'status' => $newStatus,
            'message' => 'Status posting berhasil diubah menjadi ' . ($newStatus === 'published' ? 'Aktif' : 'Non Aktif')
        ]);
    }
}
