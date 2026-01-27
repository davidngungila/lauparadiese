@extends('admin.layouts.app')

@section('title', 'About Page Management - Lau Paradise Adventures')
@section('description', 'Manage about page content')

@php
use Illuminate\Support\Str;
@endphp

@push('styles')
<style>
    .section-card {
        transition: all 0.3s;
    }
    .section-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .team-member-image {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 50%;
    }
    .icon-preview {
        font-size: 1.5rem;
        margin-right: 10px;
    }
    /* Ensure all modals scroll if content overflows */
    .modal-body {
        max-height: 70vh;
        overflow-y: auto;
    }
    .toast-container {
        position: fixed;
        top: 1rem;
        right: 1rem;
        z-index: 1090;
    }
</style>
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Toasts for feedback -->
    <div class="toast-container" id="toastContainer">
        @if(session('success'))
            <div class="toast align-items-center text-bg-success border-0 show" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        {{ session('success') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="toast align-items-center text-bg-danger border-0 show" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        {{ session('error') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        @endif
        @if ($errors->any())
            <div class="toast align-items-center text-bg-warning border-0 show" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        Please fix the highlighted errors and try again.
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        @endif
    </div>

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="ri-information-line me-2"></i>About Page Management</h4>
                    <p class="text-muted mb-0">Manage all content sections of the about page</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card card-border-shadow-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-3">
                            <span class="avatar-initial rounded bg-label-primary">
                                <i class="ri-file-list-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ $sections->count() }}</h5>
                            <small class="text-muted">Sections</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card card-border-shadow-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-3">
                            <span class="avatar-initial rounded bg-label-success">
                                <i class="ri-team-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ $teamMembers->count() }}</h5>
                            <small class="text-muted">Team Members</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card card-border-shadow-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-3">
                            <span class="avatar-initial rounded bg-label-warning">
                                <i class="ri-star-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ $values->count() }}</h5>
                            <small class="text-muted">Core Values</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card card-border-shadow-info">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-3">
                            <span class="avatar-initial rounded bg-label-info">
                                <i class="ri-award-line"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ $recognitions->count() }}</h5>
                            <small class="text-muted">Recognitions</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="card mb-4">
        <div class="card-body">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <button type="button" class="nav-link active" data-bs-toggle="tab" data-bs-target="#sections" role="tab">
                        <i class="ri-file-list-line me-1"></i> Sections
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#team" role="tab">
                        <i class="ri-team-line me-1"></i> Team Members
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#values" role="tab">
                        <i class="ri-star-line me-1"></i> Values
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#recognitions" role="tab">
                        <i class="ri-award-line me-1"></i> Recognitions
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#timeline" role="tab">
                        <i class="ri-time-line me-1"></i> Timeline
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#statistics" role="tab">
                        <i class="ri-bar-chart-line me-1"></i> Statistics
                    </button>
                </li>
            </ul>
        </div>
    </div>

    <!-- Tab Content -->
    <div class="tab-content">
        <!-- Sections Tab -->
        <div class="tab-pane fade show active" id="sections" role="tabpanel">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Page Sections</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($sections as $section)
                        <div class="col-md-6 mb-3">
                            <div class="card section-card">
                                <div class="card-body">
                                    <h6 class="card-title">{{ $section->section_name }}</h6>
                                    <p class="text-muted small mb-2">Key: <code>{{ $section->section_key }}</code></p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge {{ $section->is_active ? 'bg-label-success' : 'bg-label-secondary' }}">
                                            {{ $section->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                        <button class="btn btn-sm btn-primary" onclick="editSection({{ $section->id }})">
                                            <i class="ri-edit-line"></i> Edit
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Team Members Tab -->
        <div class="tab-pane fade" id="team" role="tabpanel">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Team Members</h5>
                    <button class="btn btn-primary btn-sm" onclick="addTeamMember()">
                        <i class="ri-add-line"></i> Add Member
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($teamMembers as $member)
                                <tr>
                                    <td>
                                        <img src="{{ $member->image_url ? (str_starts_with($member->image_url, 'http') ? $member->image_url : asset($member->image_url)) : 'https://via.placeholder.com/60' }}" 
                                             alt="{{ $member->name }}" 
                                             class="team-member-image">
                                    </td>
                                    <td>{{ $member->name }}</td>
                                    <td>{{ $member->role }}</td>
                                    <td>
                                        <span class="badge {{ $member->is_active ? 'bg-label-success' : 'bg-label-secondary' }}">
                                            {{ $member->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" onclick="editTeamMember({{ $member->id }})">
                                            <i class="ri-edit-line"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="deleteTeamMember({{ $member->id }})">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No team members found. <a href="#" onclick="addTeamMember()">Add one</a></td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Values Tab -->
        <div class="tab-pane fade" id="values" role="tabpanel">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Core Values</h5>
                    <button class="btn btn-primary btn-sm" onclick="addValue()">
                        <i class="ri-add-line"></i> Add Value
                    </button>
                </div>
                <div class="card-body">
                    <div class="row">
                        @forelse($values as $value)
                        <div class="col-md-6 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="{{ $value->icon ?? 'ri-star-line' }} icon-preview"></i>
                                        <h6 class="mb-0">{{ $value->title }}</h6>
                                    </div>
                                    <p class="text-muted small">{{ Str::limit($value->description, 100) }}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge {{ $value->is_active ? 'bg-label-success' : 'bg-label-secondary' }}">
                                            {{ $value->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                        <div>
                                            <button class="btn btn-sm btn-primary" onclick="editValue({{ $value->id }})">
                                                <i class="ri-edit-line"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" onclick="deleteValue({{ $value->id }})">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="alert alert-info">No values found. <a href="#" onclick="addValue()">Add one</a></div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Recognitions Tab -->
        <div class="tab-pane fade" id="recognitions" role="tabpanel">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recognitions</h5>
                    <button class="btn btn-primary btn-sm" onclick="addRecognition()">
                        <i class="ri-add-line"></i> Add Recognition
                    </button>
                </div>
                <div class="card-body">
                    <div class="row">
                        @forelse($recognitions as $recognition)
                        <div class="col-md-6 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="{{ $recognition->icon ?? 'ri-award-line' }} icon-preview"></i>
                                        <h6 class="mb-0">{{ $recognition->title }}</h6>
                                    </div>
                                    <p class="text-muted small">{{ Str::limit($recognition->description, 100) }}</p>
                                    @if($recognition->year)
                                    <span class="badge bg-label-info mb-2">{{ $recognition->year }}</span>
                                    @endif
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge {{ $recognition->is_active ? 'bg-label-success' : 'bg-label-secondary' }}">
                                            {{ $recognition->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                        <div>
                                            <button class="btn btn-sm btn-primary" onclick="editRecognition({{ $recognition->id }})">
                                                <i class="ri-edit-line"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" onclick="deleteRecognition({{ $recognition->id }})">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="alert alert-info">No recognitions found. <a href="#" onclick="addRecognition()">Add one</a></div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Timeline Tab -->
        <div class="tab-pane fade" id="timeline" role="tabpanel">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Timeline Items</h5>
                    <button class="btn btn-primary btn-sm" onclick="addTimelineItem()">
                        <i class="ri-add-line"></i> Add Item
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Year</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($timelineItems as $item)
                                <tr>
                                    <td><strong>{{ $item->year }}</strong></td>
                                    <td>{{ $item->title }}</td>
                                    <td>{{ Str::limit($item->description, 80) }}</td>
                                    <td>
                                        <span class="badge {{ $item->is_active ? 'bg-label-success' : 'bg-label-secondary' }}">
                                            {{ $item->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" onclick="editTimelineItem({{ $item->id }})">
                                            <i class="ri-edit-line"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="deleteTimelineItem({{ $item->id }})">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No timeline items found. <a href="#" onclick="addTimelineItem()">Add one</a></td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Tab -->
        <div class="tab-pane fade" id="statistics" role="tabpanel">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Statistics</h5>
                    <button class="btn btn-primary btn-sm" onclick="addStatistic()">
                        <i class="ri-add-line"></i> Add Statistic
                    </button>
                </div>
                <div class="card-body">
                    <div class="row">
                        @forelse($statistics as $stat)
                        <div class="col-md-6 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="{{ $stat->icon ?? 'ri-bar-chart-line' }} icon-preview"></i>
                                        <h6 class="mb-0">{{ $stat->label }}</h6>
                                    </div>
                                    <p class="h4 mb-1">{{ $stat->value }}</p>
                                    @if($stat->description)
                                    <p class="text-muted small">{{ $stat->description }}</p>
                                    @endif
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <span class="badge {{ $stat->is_active ? 'bg-label-success' : 'bg-label-secondary' }}">
                                            {{ $stat->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                        <div>
                                            <button class="btn btn-sm btn-primary" onclick="editStatistic({{ $stat->id }})">
                                                <i class="ri-edit-line"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" onclick="deleteStatistic({{ $stat->id }})">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="alert alert-info">No statistics found. <a href="#" onclick="addStatistic()">Add one</a></div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Section Modal -->
<div class="modal fade" id="sectionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Section</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="sectionForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Section Name</label>
                            <input type="text" class="form-control" name="section_name" id="section_name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Section Key</label>
                            <input type="text" class="form-control" id="section_key" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Image URL</label>
                            <input type="text" class="form-control" name="image_url" id="section_image_url" placeholder="images/your-image.jpg or https://...">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Display Order</label>
                            <input type="number" class="form-control" name="display_order" id="section_display_order" min="0">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <div class="form-check">
                                <input type="hidden" name="is_active" value="0">
                                <input class="form-check-input" type="checkbox" name="is_active" id="section_is_active" value="1">
                                <label class="form-check-label" for="section_is_active">Active</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Content</label>
                            <textarea class="form-control" name="content" id="section_content" rows="3" placeholder="Optional HTML or text content"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Data (JSON)</label>
                            <textarea class="form-control font-monospace" name="data_json" id="section_data_json" rows="6" placeholder='{"title": "Example", "items": []}'></textarea>
                            <small class="text-muted">Provide valid JSON for structured data used by this section.</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Team Member Modal -->
<div class="modal fade" id="teamModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="teamModalTitle">Add Team Member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="teamForm" method="POST">
                @csrf
                <input type="hidden" name="_method" id="team_form_method" value="POST">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" id="team_name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Role</label>
                            <input type="text" class="form-control" name="role" id="team_role" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Image URL</label>
                            <input type="text" class="form-control" name="image_url" id="team_image_url" placeholder="images/your-image.jpg or https://...">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Display Order</label>
                            <input type="number" class="form-control" name="display_order" id="team_display_order" min="0">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <div class="form-check">
                                <input type="hidden" name="is_active" value="0">
                                <input class="form-check-input" type="checkbox" name="is_active" id="team_is_active" value="1">
                                <label class="form-check-label" for="team_is_active">Active</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Bio</label>
                            <textarea class="form-control" name="bio" id="team_bio" rows="3"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Expertise (comma separated)</label>
                            <input type="text" class="form-control" name="expertise_text" id="team_expertise" placeholder="Safari Expert, Wildlife Guide">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Social Links (comma separated URLs)</label>
                            <input type="text" class="form-control" name="social_links_text" id="team_social_links" placeholder="https://linkedin..., https://twitter...">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Value Modal -->
<div class="modal fade" id="valueModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="valueModalTitle">Add Value</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="valueForm" method="POST">
                @csrf
                <input type="hidden" name="_method" id="value_form_method" value="POST">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Title</label>
                            <input type="text" class="form-control" name="title" id="value_title" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Icon</label>
                            <input type="text" class="form-control" name="icon" id="value_icon" placeholder="ri-star-line">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Display Order</label>
                            <input type="number" class="form-control" name="display_order" id="value_display_order" min="0">
                        </div>
                        <div class="col-12 d-flex align-items-center">
                            <div class="form-check">
                                <input type="hidden" name="is_active" value="0">
                                <input class="form-check-input" type="checkbox" name="is_active" id="value_is_active" value="1">
                                <label class="form-check-label" for="value_is_active">Active</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="value_description" rows="3" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Recognition Modal -->
<div class="modal fade" id="recognitionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="recognitionModalTitle">Add Recognition</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="recognitionForm" method="POST">
                @csrf
                <input type="hidden" name="_method" id="recognition_form_method" value="POST">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Title</label>
                            <input type="text" class="form-control" name="title" id="recognition_title" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Icon</label>
                            <input type="text" class="form-control" name="icon" id="recognition_icon" placeholder="ri-award-line">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Year</label>
                            <input type="text" class="form-control" name="year" id="recognition_year" placeholder="2025">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Display Order</label>
                            <input type="number" class="form-control" name="display_order" id="recognition_display_order" min="0">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <div class="form-check">
                                <input type="hidden" name="is_active" value="0">
                                <input class="form-check-input" type="checkbox" name="is_active" id="recognition_is_active" value="1">
                                <label class="form-check-label" for="recognition_is_active">Active</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="recognition_description" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Timeline Modal -->
<div class="modal fade" id="timelineModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="timelineModalTitle">Add Timeline Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="timelineForm" method="POST">
                @csrf
                <input type="hidden" name="_method" id="timeline_form_method" value="POST">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Year</label>
                            <input type="text" class="form-control" name="year" id="timeline_year" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Title</label>
                            <input type="text" class="form-control" name="title" id="timeline_title" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Display Order</label>
                            <input type="number" class="form-control" name="display_order" id="timeline_display_order" min="0">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <div class="form-check">
                                <input type="hidden" name="is_active" value="0">
                                <input class="form-check-input" type="checkbox" name="is_active" id="timeline_is_active" value="1">
                                <label class="form-check-label" for="timeline_is_active">Active</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="timeline_description" rows="3" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Statistic Modal -->
<div class="modal fade" id="statModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statModalTitle">Add Statistic</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="statForm" method="POST">
                @csrf
                <input type="hidden" name="_method" id="stat_form_method" value="POST">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Label</label>
                            <input type="text" class="form-control" name="label" id="stat_label" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Value</label>
                            <input type="text" class="form-control" name="value" id="stat_value" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Display Order</label>
                            <input type="number" class="form-control" name="display_order" id="stat_display_order" min="0">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Icon</label>
                            <input type="text" class="form-control" name="icon" id="stat_icon" placeholder="ri-bar-chart-line">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <div class="form-check">
                                <input type="hidden" name="is_active" value="0">
                                <input class="form-check-input" type="checkbox" name="is_active" id="stat_is_active" value="1">
                                <label class="form-check-label" for="stat_is_active">Active</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="stat_description" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const sectionsData = @json($sections);
    const teamMembersData = @json($teamMembers);
    const valuesData = @json($values);
    const recognitionsData = @json($recognitions);
    const timelineData = @json($timelineItems);
    const statisticsData = @json($statistics);
    const sectionUpdateUrlTemplate = '/admin/about-page/sections/__ID__';
    const teamUpdateUrlTemplate = '/admin/about-page/team-members/__ID__';
    const valueUpdateUrlTemplate = '/admin/about-page/values/__ID__';
    const recognitionUpdateUrlTemplate = '/admin/about-page/recognitions/__ID__';
    const timelineUpdateUrlTemplate = '/admin/about-page/timeline-items/__ID__';
    const statUpdateUrlTemplate = '/admin/about-page/statistics/__ID__';

    let sectionModal;
    let teamModal;
    let valueModal;
    let recognitionModal;
    let timelineModal;
    let statModal;

    function showToast(type, message) {
        const container = document.getElementById('toastContainer');
        if (!container) return;
        const colorClass = type === 'error' ? 'text-bg-danger' : (type === 'warning' ? 'text-bg-warning' : 'text-bg-success');
        const toastEl = document.createElement('div');
        toastEl.className = `toast align-items-center ${colorClass} border-0 show`;
        toastEl.setAttribute('role', 'alert');
        toastEl.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        `;
        container.appendChild(toastEl);
        const toast = new bootstrap.Toast(toastEl, { delay: 4000 });
        toast.show();
    }

    function validateRequired(form, fields) {
        const missing = [];
        fields.forEach(id => {
            const el = document.getElementById(id);
            if (!el) return;
            const val = (el.value ?? '').trim();
            if (!val) missing.push(el.previousElementSibling ? el.previousElementSibling.textContent : id);
        });
        return missing;
    }

    document.addEventListener('DOMContentLoaded', () => {
        sectionModal = new bootstrap.Modal(document.getElementById('sectionModal'));
        teamModal = new bootstrap.Modal(document.getElementById('teamModal'));
        valueModal = new bootstrap.Modal(document.getElementById('valueModal'));
        recognitionModal = new bootstrap.Modal(document.getElementById('recognitionModal'));
        timelineModal = new bootstrap.Modal(document.getElementById('timelineModal'));
        statModal = new bootstrap.Modal(document.getElementById('statModal'));

        // Auto-init toasts for feedback
        document.querySelectorAll('.toast').forEach(toastEl => {
            const toast = new bootstrap.Toast(toastEl, { delay: 4000 });
            toast.show();
        });

        const sectionForm = document.getElementById('sectionForm');
        sectionForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const missing = validateRequired(sectionForm, ['section_name']);
            if (missing.length) {
                showToast('error', 'Please fill required fields: ' + missing.join(', '));
                return;
            }
            const dataTextarea = document.getElementById('section_data_json');
            const rawJson = dataTextarea.value.trim();
            if (rawJson) {
                try {
                    JSON.parse(rawJson);
                } catch (err) {
                    showToast('error', 'Please provide valid JSON in the Data field.');
                    return;
                }
            }
            
            const formData = new FormData(sectionForm);
            const action = sectionForm.action;
            
            try {
                const response = await fetch(action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                
                let result;
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    result = await response.json();
                } else {
                    if (response.ok || response.redirected) {
                        showToast('success', 'Section updated successfully!');
                        sectionModal.hide();
                        setTimeout(() => location.reload(), 1000);
                        return;
                    }
                    result = { success: false, message: 'Failed to update section. Please try again.' };
                }
                
                if (response.ok && result.success !== false) {
                    showToast('success', result.message || 'Section updated successfully!');
                    sectionModal.hide();
                    setTimeout(() => location.reload(), 1000);
                } else {
                    let errorMsg = result.message || 'Failed to update section. Please try again.';
                    if (result.errors) {
                        const errorList = Object.values(result.errors).flat().join(', ');
                        errorMsg = errorList || errorMsg;
                    }
                    showToast('error', errorMsg);
                    console.error('Validation errors:', result);
                }
            } catch (error) {
                showToast('error', 'An error occurred. Please try again.');
                console.error('Error:', error);
            }
        });

        const teamForm = document.getElementById('teamForm');
        teamForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const missing = validateRequired(teamForm, ['team_name', 'team_role']);
            if (missing.length) {
                showToast('error', 'Please fill required fields: ' + missing.join(', '));
                return;
            }
            
            const formData = new FormData(teamForm);
            const method = formData.get('_method') || 'POST';
            const action = teamForm.action;
            
            try {
                const response = await fetch(action, {
                    method: method === 'PUT' ? 'POST' : 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                
                let result;
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    result = await response.json();
                } else {
                    // If it's a redirect or HTML response, just reload
                    if (response.ok || response.redirected) {
                        showToast('success', 'Team member saved successfully!');
                        teamModal.hide();
                        setTimeout(() => location.reload(), 1000);
                        return;
                    }
                    result = { success: false, message: 'Failed to save team member. Please try again.' };
                }
                
                if (response.ok && result.success !== false) {
                    showToast('success', result.message || 'Team member saved successfully!');
                    teamModal.hide();
                    setTimeout(() => location.reload(), 1000);
                } else {
                    // Handle validation errors (422 status)
                    let errorMsg = result.message || 'Failed to save team member. Please try again.';
                    if (result.errors) {
                        const errorList = Object.values(result.errors).flat().join(', ');
                        errorMsg = errorList || errorMsg;
                    }
                    showToast('error', errorMsg);
                    console.error('Validation errors:', result);
                }
            } catch (error) {
                showToast('error', 'An error occurred. Please try again.');
                console.error('Error:', error);
            }
        });

        const valueForm = document.getElementById('valueForm');
        valueForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const missing = validateRequired(valueForm, ['value_title', 'value_description']);
            if (missing.length) {
                showToast('error', 'Please fill required fields: ' + missing.join(', '));
                return;
            }
            
            const formData = new FormData(valueForm);
            const method = formData.get('_method') || 'POST';
            const action = valueForm.action;
            
            try {
                const response = await fetch(action, {
                    method: method === 'PUT' ? 'POST' : 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                
                let result;
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    result = await response.json();
                } else {
                    if (response.ok || response.redirected) {
                        showToast('success', 'Value saved successfully!');
                        valueModal.hide();
                        setTimeout(() => location.reload(), 1000);
                        return;
                    }
                    result = { success: false, message: 'Failed to save value. Please try again.' };
                }
                
                if (response.ok && result.success !== false) {
                    showToast('success', result.message || 'Value saved successfully!');
                    valueModal.hide();
                    setTimeout(() => location.reload(), 1000);
                } else {
                    let errorMsg = result.message || 'Failed to save value. Please try again.';
                    if (result.errors) {
                        const errorList = Object.values(result.errors).flat().join(', ');
                        errorMsg = errorList || errorMsg;
                    }
                    showToast('error', errorMsg);
                    console.error('Validation errors:', result);
                }
            } catch (error) {
                showToast('error', 'An error occurred. Please try again.');
                console.error('Error:', error);
            }
        });

        const recognitionForm = document.getElementById('recognitionForm');
        recognitionForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const missing = validateRequired(recognitionForm, ['recognition_title']);
            if (missing.length) {
                showToast('error', 'Please fill required fields: ' + missing.join(', '));
                return;
            }
            
            const formData = new FormData(recognitionForm);
            const method = formData.get('_method') || 'POST';
            const action = recognitionForm.action;
            
            try {
                const response = await fetch(action, {
                    method: method === 'PUT' ? 'POST' : 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                
                let result;
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    result = await response.json();
                } else {
                    if (response.ok || response.redirected) {
                        showToast('success', 'Recognition saved successfully!');
                        recognitionModal.hide();
                        setTimeout(() => location.reload(), 1000);
                        return;
                    }
                    result = { success: false, message: 'Failed to save recognition. Please try again.' };
                }
                
                if (response.ok && result.success !== false) {
                    showToast('success', result.message || 'Recognition saved successfully!');
                    recognitionModal.hide();
                    setTimeout(() => location.reload(), 1000);
                } else {
                    let errorMsg = result.message || 'Failed to save recognition. Please try again.';
                    if (result.errors) {
                        const errorList = Object.values(result.errors).flat().join(', ');
                        errorMsg = errorList || errorMsg;
                    }
                    showToast('error', errorMsg);
                    console.error('Validation errors:', result);
                }
            } catch (error) {
                showToast('error', 'An error occurred. Please try again.');
                console.error('Error:', error);
            }
        });

        const timelineForm = document.getElementById('timelineForm');
        timelineForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const missing = validateRequired(timelineForm, ['timeline_year', 'timeline_title', 'timeline_description']);
            if (missing.length) {
                showToast('error', 'Please fill required fields: ' + missing.join(', '));
                return;
            }
            
            const formData = new FormData(timelineForm);
            const method = formData.get('_method') || 'POST';
            const action = timelineForm.action;
            
            try {
                const response = await fetch(action, {
                    method: method === 'PUT' ? 'POST' : 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                
                let result;
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    result = await response.json();
                } else {
                    if (response.ok || response.redirected) {
                        showToast('success', 'Timeline item saved successfully!');
                        timelineModal.hide();
                        setTimeout(() => location.reload(), 1000);
                        return;
                    }
                    result = { success: false, message: 'Failed to save timeline item. Please try again.' };
                }
                
                if (response.ok && result.success !== false) {
                    showToast('success', result.message || 'Timeline item saved successfully!');
                    timelineModal.hide();
                    setTimeout(() => location.reload(), 1000);
                } else {
                    let errorMsg = result.message || 'Failed to save timeline item. Please try again.';
                    if (result.errors) {
                        const errorList = Object.values(result.errors).flat().join(', ');
                        errorMsg = errorList || errorMsg;
                    }
                    showToast('error', errorMsg);
                    console.error('Validation errors:', result);
                }
            } catch (error) {
                showToast('error', 'An error occurred. Please try again.');
                console.error('Error:', error);
            }
        });

        const statForm = document.getElementById('statForm');
        statForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const missing = validateRequired(statForm, ['stat_label', 'stat_value']);
            if (missing.length) {
                showToast('error', 'Please fill required fields: ' + missing.join(', '));
                return;
            }
            
            const formData = new FormData(statForm);
            const method = formData.get('_method') || 'POST';
            const action = statForm.action;
            
            try {
                const response = await fetch(action, {
                    method: method === 'PUT' ? 'POST' : 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                
                let result;
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    result = await response.json();
                } else {
                    if (response.ok || response.redirected) {
                        showToast('success', 'Statistic saved successfully!');
                        statModal.hide();
                        setTimeout(() => location.reload(), 1000);
                        return;
                    }
                    result = { success: false, message: 'Failed to save statistic. Please try again.' };
                }
                
                if (response.ok && result.success !== false) {
                    showToast('success', result.message || 'Statistic saved successfully!');
                    statModal.hide();
                    setTimeout(() => location.reload(), 1000);
                } else {
                    let errorMsg = result.message || 'Failed to save statistic. Please try again.';
                    if (result.errors) {
                        const errorList = Object.values(result.errors).flat().join(', ');
                        errorMsg = errorList || errorMsg;
                    }
                    showToast('error', errorMsg);
                    console.error('Validation errors:', result);
                }
            } catch (error) {
                showToast('error', 'An error occurred. Please try again.');
                console.error('Error:', error);
            }
        });
    });

    function editSection(id) {
        const section = sectionsData.find(s => s.id === id);
        if (!section) return;

        const form = document.getElementById('sectionForm');
        form.action = sectionUpdateUrlTemplate.replace('__ID__', id);

        document.getElementById('section_name').value = section.section_name ?? '';
        document.getElementById('section_key').value = section.section_key ?? '';
        document.getElementById('section_image_url').value = section.image_url ?? '';
        document.getElementById('section_display_order').value = section.display_order ?? '';
        document.getElementById('section_content').value = section.content ?? '';
        document.getElementById('section_is_active').checked = !!section.is_active;
        document.getElementById('section_data_json').value = section.data ? JSON.stringify(section.data, null, 2) : '';

        sectionModal.show();
    }

    // Team Members
    function resetTeamForm() {
        document.getElementById('team_form_method').value = 'POST';
        document.getElementById('teamModalTitle').textContent = 'Add Team Member';
        document.getElementById('teamForm').action = '/admin/about-page/team-members';
        document.getElementById('team_name').value = '';
        document.getElementById('team_role').value = '';
        document.getElementById('team_image_url').value = '';
        document.getElementById('team_display_order').value = '';
        document.getElementById('team_is_active').checked = true;
        document.getElementById('team_bio').value = '';
        document.getElementById('team_expertise').value = '';
        document.getElementById('team_social_links').value = '';
    }

    function addTeamMember() {
        resetTeamForm();
        teamModal.show();
    }

    function editTeamMember(id) {
        const member = teamMembersData.find(m => m.id === id);
        if (!member) return;
        resetTeamForm();
        document.getElementById('team_form_method').value = 'PUT';
        document.getElementById('teamModalTitle').textContent = 'Edit Team Member';
        document.getElementById('teamForm').action = teamUpdateUrlTemplate.replace('__ID__', id);
        document.getElementById('team_name').value = member.name ?? '';
        document.getElementById('team_role').value = member.role ?? '';
        document.getElementById('team_image_url').value = member.image_url ?? '';
        document.getElementById('team_display_order').value = member.display_order ?? '';
        document.getElementById('team_is_active').checked = !!member.is_active;
        document.getElementById('team_bio').value = member.bio ?? '';
        document.getElementById('team_expertise').value = Array.isArray(member.expertise) ? member.expertise.join(', ') : '';
        document.getElementById('team_social_links').value = Array.isArray(member.social_links) ? member.social_links.join(', ') : '';
        teamModal.show();
    }
    
    function deleteTeamMember(id) {
        if (confirm('Are you sure you want to delete this team member?')) {
            fetch(`/admin/about-page/team-members/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(() => location.reload());
        }
    }
    
    // Values
    function resetValueForm() {
        document.getElementById('value_form_method').value = 'POST';
        document.getElementById('valueModalTitle').textContent = 'Add Value';
        document.getElementById('valueForm').action = '/admin/about-page/values';
        document.getElementById('value_title').value = '';
        document.getElementById('value_icon').value = '';
        document.getElementById('value_display_order').value = '';
        document.getElementById('value_is_active').checked = true;
        document.getElementById('value_description').value = '';
    }

    function addValue() {
        resetValueForm();
        valueModal.show();
    }
    
    function editValue(id) {
        const value = valuesData.find(v => v.id === id);
        if (!value) return;
        resetValueForm();
        document.getElementById('value_form_method').value = 'PUT';
        document.getElementById('valueModalTitle').textContent = 'Edit Value';
        document.getElementById('valueForm').action = valueUpdateUrlTemplate.replace('__ID__', id);
        document.getElementById('value_title').value = value.title ?? '';
        document.getElementById('value_icon').value = value.icon ?? '';
        document.getElementById('value_display_order').value = value.display_order ?? '';
        document.getElementById('value_is_active').checked = !!value.is_active;
        document.getElementById('value_description').value = value.description ?? '';
        valueModal.show();
    }
    
    function deleteValue(id) {
        if (confirm('Are you sure you want to delete this value?')) {
            fetch(`/admin/about-page/values/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(() => location.reload());
        }
    }
    
    // Recognitions
    function resetRecognitionForm() {
        document.getElementById('recognition_form_method').value = 'POST';
        document.getElementById('recognitionModalTitle').textContent = 'Add Recognition';
        document.getElementById('recognitionForm').action = '/admin/about-page/recognitions';
        document.getElementById('recognition_title').value = '';
        document.getElementById('recognition_icon').value = '';
        document.getElementById('recognition_year').value = '';
        document.getElementById('recognition_display_order').value = '';
        document.getElementById('recognition_is_active').checked = true;
        document.getElementById('recognition_description').value = '';
    }

    function addRecognition() {
        resetRecognitionForm();
        recognitionModal.show();
    }
    
    function editRecognition(id) {
        const rec = recognitionsData.find(r => r.id === id);
        if (!rec) return;
        resetRecognitionForm();
        document.getElementById('recognition_form_method').value = 'PUT';
        document.getElementById('recognitionModalTitle').textContent = 'Edit Recognition';
        document.getElementById('recognitionForm').action = recognitionUpdateUrlTemplate.replace('__ID__', id);
        document.getElementById('recognition_title').value = rec.title ?? '';
        document.getElementById('recognition_icon').value = rec.icon ?? '';
        document.getElementById('recognition_year').value = rec.year ?? '';
        document.getElementById('recognition_display_order').value = rec.display_order ?? '';
        document.getElementById('recognition_is_active').checked = !!rec.is_active;
        document.getElementById('recognition_description').value = rec.description ?? '';
        recognitionModal.show();
    }
    
    function deleteRecognition(id) {
        if (confirm('Are you sure you want to delete this recognition?')) {
            fetch(`/admin/about-page/recognitions/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(() => location.reload());
        }
    }
    
    // Timeline
    function resetTimelineForm() {
        document.getElementById('timeline_form_method').value = 'POST';
        document.getElementById('timelineModalTitle').textContent = 'Add Timeline Item';
        document.getElementById('timelineForm').action = '/admin/about-page/timeline-items';
        document.getElementById('timeline_year').value = '';
        document.getElementById('timeline_title').value = '';
        document.getElementById('timeline_display_order').value = '';
        document.getElementById('timeline_is_active').checked = true;
        document.getElementById('timeline_description').value = '';
    }

    function addTimelineItem() {
        resetTimelineForm();
        timelineModal.show();
    }
    
    function editTimelineItem(id) {
        const item = timelineData.find(t => t.id === id);
        if (!item) return;
        resetTimelineForm();
        document.getElementById('timeline_form_method').value = 'PUT';
        document.getElementById('timelineModalTitle').textContent = 'Edit Timeline Item';
        document.getElementById('timelineForm').action = timelineUpdateUrlTemplate.replace('__ID__', id);
        document.getElementById('timeline_year').value = item.year ?? '';
        document.getElementById('timeline_title').value = item.title ?? '';
        document.getElementById('timeline_display_order').value = item.display_order ?? '';
        document.getElementById('timeline_is_active').checked = !!item.is_active;
        document.getElementById('timeline_description').value = item.description ?? '';
        timelineModal.show();
    }
    
    function deleteTimelineItem(id) {
        if (confirm('Are you sure you want to delete this timeline item?')) {
            fetch(`/admin/about-page/timeline-items/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(() => location.reload());
        }
    }
    
    // Statistics
    function resetStatisticForm() {
        document.getElementById('stat_form_method').value = 'POST';
        document.getElementById('statModalTitle').textContent = 'Add Statistic';
        document.getElementById('statForm').action = '/admin/about-page/statistics';
        document.getElementById('stat_label').value = '';
        document.getElementById('stat_value').value = '';
        document.getElementById('stat_display_order').value = '';
        document.getElementById('stat_icon').value = '';
        document.getElementById('stat_is_active').checked = true;
        document.getElementById('stat_description').value = '';
    }

    function addStatistic() {
        resetStatisticForm();
        statModal.show();
    }
    
    function editStatistic(id) {
        const stat = statisticsData.find(s => s.id === id);
        if (!stat) return;
        resetStatisticForm();
        document.getElementById('stat_form_method').value = 'PUT';
        document.getElementById('statModalTitle').textContent = 'Edit Statistic';
        document.getElementById('statForm').action = statUpdateUrlTemplate.replace('__ID__', id);
        document.getElementById('stat_label').value = stat.label ?? '';
        document.getElementById('stat_value').value = stat.value ?? '';
        document.getElementById('stat_display_order').value = stat.display_order ?? '';
        document.getElementById('stat_icon').value = stat.icon ?? '';
        document.getElementById('stat_is_active').checked = !!stat.is_active;
        document.getElementById('stat_description').value = stat.description ?? '';
        statModal.show();
    }
    
    function deleteStatistic(id) {
        if (confirm('Are you sure you want to delete this statistic?')) {
            fetch(`/admin/about-page/statistics/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(() => location.reload());
        }
    }
</script>
@endpush
@endsection

