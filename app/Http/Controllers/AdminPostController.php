<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostStoreRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Models\Post;
use App\Services\Posts\PostService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminPostController extends Controller
{
    public function __construct(
        protected PostService $postService
    ) {}

    public function index(): View
    {
        $this->authorize('viewAny', Post::class);

        $posts = Post::query()
            ->with('author')
            ->latest()
            ->paginate(20);

        return view('admin.posts.index', [
            'posts' => $posts,
            'enums' => [
                'status' => ['draft', 'published'],
            ],
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Post::class);

        return view('admin.posts.create', [
            'enums' => [
                'status' => ['draft', 'published'],
            ],
            'defaults' => [
                'status' => 'draft',
                'is_indexable' => true,
                'is_followable' => true,
            ],
        ]);
    }

    public function store(PostStoreRequest $request): RedirectResponse
    {
        $this->authorize('create', Post::class);

        $user = auth()->user();
        $tz = (string) ($user->timezone ?? config('app.timezone', 'UTC'));

        $post = $this->postService->create(
            data: $request->validated(),
            authorId: (int) $user->id,
            authorTimezone: $tz,
            featuredImage: $request->file('featured_image')
        );

        return redirect()
            ->route('admin.posts.edit', $post)
            ->with('success', 'Post created.');
    }

    public function show(Post $post): View
    {
        // Admin can view drafts too
        $this->authorize('view', $post);

        $post->load('author');

        return view('admin.posts.show', [
            'post' => $post,
        ]);
    }

    public function edit(Post $post): View
    {
        $this->authorize('update', $post);

        $post->load('author');

        return view('admin.posts.edit', [
            'post' => $post,
            'enums' => [
                'status' => ['draft', 'published'],
            ],
        ]);
    }

    public function update(PostUpdateRequest $request, Post $post): RedirectResponse
    {
        $this->authorize('update', $post);

        $user = auth()->user();
        $tz = (string) ($user->timezone ?? config('app.timezone', 'UTC'));

        $updated = $this->postService->update(
            post: $post,
            data: $request->validated(),
            actorTimezone: $tz,
            featuredImage: $request->file('featured_image')
        );

        return redirect()
            ->route('admin.posts.edit', $updated)
            ->with('success', 'Post updated.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        $this->authorize('delete', $post);

        $this->postService->delete($post);

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Post deleted.');
    }
}