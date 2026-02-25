<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\View\View;

class PostPublicController extends Controller
{
    /**
     * Public archive of posts (published only).
     */
    public function index(): View
    {
        $posts = Post::query()
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->with('author')
            ->orderByDesc('published_at')
            ->paginate(12);

        return view('posts.index', [
            'posts' => $posts,
        ]);
    }

    /**
     * Public show of a published post.
     * Uses Route Model Binding on slug.
     */
    public function show(Post $post): View
    {
        // Hide drafts from public
        if ($post->status !== 'published') {
            abort(404);
        }

        // Increment view_count safely (no race issues with atomic increment)
        $post->increment('view_count');

        $post->load('author');

        return view('posts.show', [
            'post' => $post,
        ]);
    }
}