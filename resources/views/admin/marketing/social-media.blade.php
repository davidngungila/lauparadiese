@extends('admin.layouts.app')

@section('title', 'Social Media Scheduler')
@section('description', 'Schedule and manage social media posts')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0">Social Media Posts</h5>
                <a href="{{ route('admin.marketing.social-media.create') }}" class="btn btn-primary">
                    <i class="icon-base ri ri-add-line me-2"></i>Create Post
                </a>
            </div>
            <div class="card-body">
                <!-- Filters -->
                <form method="GET" action="{{ route('admin.marketing.social-media') }}" class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Search posts..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="platform" class="form-select">
                                <option value="">All Platforms</option>
                                <option value="facebook" {{ request('platform') == 'facebook' ? 'selected' : '' }}>Facebook</option>
                                <option value="twitter" {{ request('platform') == 'twitter' ? 'selected' : '' }}>Twitter</option>
                                <option value="instagram" {{ request('platform') == 'instagram' ? 'selected' : '' }}>Instagram</option>
                                <option value="linkedin" {{ request('platform') == 'linkedin' ? 'selected' : '' }}>LinkedIn</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">Filter</button>
                        </div>
                    </div>
                </form>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Platform</th>
                                <th>Content</th>
                                <th>Status</th>
                                <th>Scheduled At</th>
                                <th>Published At</th>
                                <th>Engagement</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($posts as $post)
                            <tr>
                                <td>
                                    @if($post->platform == 'facebook')
                                        <i class="icon-base ri ri-facebook-fill text-primary"></i> Facebook
                                    @elseif($post->platform == 'twitter')
                                        <i class="icon-base ri ri-twitter-fill text-info"></i> Twitter
                                    @elseif($post->platform == 'instagram')
                                        <i class="icon-base ri ri-instagram-fill text-danger"></i> Instagram
                                    @else
                                        <i class="icon-base ri ri-linkedin-fill text-primary"></i> LinkedIn
                                    @endif
                                </td>
                                <td>{{ Str::limit($post->content, 50) }}</td>
                                <td>
                                    @if($post->status == 'draft')
                                        <span class="badge bg-label-secondary">Draft</span>
                                    @elseif($post->status == 'scheduled')
                                        <span class="badge bg-label-info">Scheduled</span>
                                    @else
                                        <span class="badge bg-label-success">Published</span>
                                    @endif
                                </td>
                                <td>{{ $post->scheduled_at ? $post->scheduled_at->format('M d, Y H:i') : '-' }}</td>
                                <td>{{ $post->published_at ? $post->published_at->format('M d, Y H:i') : '-' }}</td>
                                <td>
                                    @if($post->status == 'published')
                                        <small>{{ $post->likes ?? 0 }} likes, {{ $post->shares ?? 0 }} shares</small>
                                    @else
                                        <small class="text-muted">-</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.marketing.social-media.edit', $post->id) }}" class="btn btn-sm btn-icon">
                                            <i class="icon-base ri ri-edit-line"></i>
                                        </a>
                                        @if($post->status != 'published')
                                        <form action="{{ route('admin.marketing.social-media.publish', $post->id) }}" method="POST" class="publish-form">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-icon text-success" onclick="return confirm('Publish this post now?')">
                                                <i class="icon-base ri ri-send-plane-line"></i>
                                            </button>
                                        </form>
                                        @endif
                                        <form action="{{ route('admin.marketing.social-media.destroy', $post->id) }}" method="POST" class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-icon text-danger" onclick="return confirm('Are you sure?')">
                                                <i class="icon-base ri ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="icon-base ri ri-share-line icon-48px mb-2 d-block"></i>
                                        <p>No social media posts found</p>
                                        <a href="{{ route('admin.marketing.social-media.create') }}" class="btn btn-primary btn-sm">Create Your First Post</a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($posts->hasPages())
                <div class="mt-4">
                    {{ $posts->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection






