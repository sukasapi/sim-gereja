<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;

class PublicPostController extends Controller
{
    /**
     * Display a listing of published posts
     */
    public function index(Request $request)
    {
        $query = Post::with(['category', 'user', 'church'])
                    ->published()
                    ->orderBy('published_at', 'desc');

        // Filter by category if specified
        if ($request->has('category') && $request->category) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter by church if specified
        if ($request->has('church') && $request->church) {
            $query->where('church_id', $request->church);
        }

        $posts = $query->paginate(10);
        
        // Get featured posts
        $featuredPosts = Post::with(['category', 'user', 'church'])
                            ->published()
                            ->featured()
                            ->orderBy('published_at', 'desc')
                            ->limit(3)
                            ->get();

        // Get categories for sidebar
        $categories = Category::active()
                            ->withCount(['posts' => function($query) {
                                $query->published();
                            }])
                            ->orderBy('name')
                            ->get();

        return view('public.posts.index', compact('posts', 'featuredPosts', 'categories'));
    }

    /**
     * Display the specified post
     */
    public function show(Post $post)
    {
        // Only show published posts
        if ($post->status !== 'published') {
            abort(404);
        }

        // Increment views
        $post->increment('views');

        // Get related posts (same category, excluding current post)
        $relatedPosts = Post::with(['category', 'user', 'church'])
                           ->published()
                           ->where('category_id', $post->category_id)
                           ->where('id', '!=', $post->id)
                           ->orderBy('published_at', 'desc')
                           ->limit(4)
                           ->get();

        return view('public.posts.show', compact('post', 'relatedPosts'));
    }

    /**
     * Display posts by category
     */
    public function category(Category $category)
    {
        $posts = Post::with(['category', 'user', 'church'])
                    ->published()
                    ->where('category_id', $category->id)
                    ->orderBy('published_at', 'desc')
                    ->paginate(10);

        // Get categories for sidebar
        $categories = Category::active()
                            ->withCount(['posts' => function($query) {
                                $query->published();
                            }])
                            ->orderBy('name')
                            ->get();

        return view('public.posts.category', compact('posts', 'category', 'categories'));
    }
}
