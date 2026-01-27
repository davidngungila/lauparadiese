@extends('admin.layouts.app')

@section('title', 'Blog Posts - Lau Paradise Adventures')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-article-line me-2"></i>Blog Posts Management
                    </h4>
                    <a href="{{ route('admin.homepage.blog-posts.create') }}" class="btn btn-primary">
                        <i class="ri-add-line me-1"></i>Create New Post
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.homepage.blog-posts') }}" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Search</label>
                            <input type="text" name="search" class="form-control" placeholder="Search by title or content..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All Statuses</option>
                                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            </select>
                            <small class="text-muted">Note: Status is based on published_at date</small>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="ri-search-line me-1"></i>Filter
                            </button>
                            <a href="{{ route('admin.homepage.blog-posts') }}" class="btn btn-outline-secondary">
                                <i class="ri-refresh-line me-1"></i>Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Blog Posts Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($posts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th width="60">Image</th>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Author</th>
                                        <th>Status</th>
                                        <th>Published At</th>
                                        <th>Created</th>
                                        <th width="150" class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($posts as $post)
                                        <tr>
                                            <td>
                                                @if($post->image_url)
                                                    <img src="{{ $post->image_url }}" alt="{{ $post->title }}" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                        <i class="ri-image-line text-muted"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="fw-medium">{{ Str::limit($post->title, 50) }}</div>
                                                @if($post->excerpt)
                                                    <small class="text-muted">{{ Str::limit($post->excerpt, 60) }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                @if($post->category)
                                                    <span class="badge bg-label-info">{{ $post->category->name }}</span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($post->author)
                                                    {{ $post->author->name }}
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $status = 'draft';
                                                    if ($post->published_at) {
                                                        $status = $post->published_at <= now() ? 'published' : 'scheduled';
                                                    }
                                                    $statusClass = match($status) {
                                                        'published' => 'success',
                                                        'scheduled' => 'info',
                                                        default => 'warning'
                                                    };
                                                @endphp
                                                <span class="badge bg-label-{{ $statusClass }}">
                                                    {{ ucfirst($status) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($post->published_at)
                                                    {{ $post->published_at->format('M d, Y') }}
                                                @else
                                                    <span class="text-muted">Not published</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $post->created_at->diffForHumans() }}</small>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-end gap-2">
                                                    <a href="{{ route('admin.homepage.blog-posts.edit', $post->id) }}" class="btn btn-sm btn-icon btn-outline-primary" title="Edit">
                                                        <i class="ri-pencil-line"></i>
                                                    </a>
                                                    <form action="{{ route('admin.homepage.blog-posts.destroy', $post->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this post?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-icon btn-outline-danger" title="Delete">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $posts->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="ri-article-line" style="font-size: 64px; color: #ccc;"></i>
                            <h5 class="mt-3">No Blog Posts Found</h5>
                            <p class="text-muted">Get started by creating your first blog post.</p>
                            <a href="{{ route('admin.homepage.blog-posts.create') }}" class="btn btn-primary mt-3">
                                <i class="ri-add-line me-1"></i>Create First Post
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
