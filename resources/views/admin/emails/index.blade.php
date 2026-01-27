@extends('admin.layouts.app')

@section('title', 'Email Management - Lau Paradise Adventures')
@section('description', 'Advanced email management system')

@push('styles')
<style>
    .email-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        color: white;
    }
    
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: transform 0.3s, box-shadow 0.3s;
        height: 100%;
        border-left: 4px solid;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    }
    
    .stat-card.primary { border-left-color: #667eea; }
    .stat-card.success { border-left-color: #10b981; }
    .stat-card.warning { border-left-color: #f59e0b; }
    .stat-card.info { border-left-color: #3b82f6; }
    
    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 1rem;
    }
    
    .stat-card.primary .stat-icon { background: rgba(102, 126, 234, 0.1); color: #667eea; }
    .stat-card.success .stat-icon { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .stat-card.warning .stat-icon { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .stat-card.info .stat-icon { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    
    .folder-sidebar {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    
    .folder-item {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #f3f4f6;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .folder-item:hover {
        background: #f9fafb;
    }
    
    .folder-item.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .folder-item.active .badge {
        background: rgba(255,255,255,0.3);
        color: white;
    }
    
    .email-list {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    
    .email-item {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #f3f4f6;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .email-item:hover {
        background: #f9fafb;
    }
    
    .email-item.unread {
        background: #f0f9ff;
        border-left: 3px solid #3b82f6;
    }
    
    .email-item.selected {
        background: #eef2ff;
    }
    
    .email-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 18px;
        flex-shrink: 0;
    }
    
    .email-content {
        flex: 1;
        min-width: 0;
    }
    
    .email-subject {
        font-weight: 600;
        color: #111827;
        margin-bottom: 0.25rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .email-preview {
        color: #6b7280;
        font-size: 0.875rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .email-meta {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        color: #9ca3af;
    }
    
    .email-actions {
        display: flex;
        gap: 0.5rem;
        opacity: 0;
        transition: opacity 0.2s;
    }
    
    .email-item:hover .email-actions {
        opacity: 1;
    }
    
    .search-box {
        position: relative;
    }
    
    .search-box input {
        border-radius: 10px;
        padding-left: 2.5rem;
        border: 2px solid #e5e7eb;
        transition: all 0.3s;
    }
    
    .search-box input:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    
    .search-box i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
    }
    
    .bulk-actions-bar {
        background: #667eea;
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        margin-bottom: 1rem;
        display: none;
    }
    
    .bulk-actions-bar.active {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .filter-chips {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin-bottom: 1rem;
    }
    
    .filter-chip {
        padding: 0.5rem 1rem;
        background: white;
        border: 2px solid #e5e7eb;
        border-radius: 20px;
        cursor: pointer;
        transition: all 0.3s;
        font-size: 0.875rem;
        font-weight: 500;
    }
    
    .filter-chip:hover {
        border-color: #667eea;
        color: #667eea;
    }
    
    .filter-chip.active {
        background: #667eea;
        color: white;
        border-color: #667eea;
    }
    
    @media (max-width: 768px) {
        .email-header {
            padding: 1rem;
        }
        
        .email-item {
            flex-wrap: wrap;
        }
        
        .email-actions {
            opacity: 1;
            width: 100%;
            justify-content: flex-end;
            margin-top: 0.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="email-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="mb-2 text-white">
                    <i class="ri-mail-line me-2"></i>Email Management
                </h2>
                <p class="text-white-50 mb-0">Manage all your emails in one place</p>
            </div>
            <div class="col-md-4 text-end">
                <a href="{{ route('admin.emails.compose') }}" class="btn btn-light">
                    <i class="ri-mail-add-line me-2"></i>Compose Email
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stat-card primary">
                <div class="stat-icon">
                    <i class="ri-mail-unread-line"></i>
                </div>
                <h3 class="mb-1">{{ number_format($stats['unread'] ?? 0) }}</h3>
                <p class="text-muted mb-0">Unread Emails</p>
                <small class="text-primary">
                    <i class="ri-arrow-up-line"></i> {{ $stats['unread_today'] ?? 0 }} today
                </small>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stat-card success">
                <div class="stat-icon">
                    <i class="ri-inbox-line"></i>
                </div>
                <h3 class="mb-1">{{ number_format($stats['inbox'] ?? 0) }}</h3>
                <p class="text-muted mb-0">Total Inbox</p>
                <small class="text-success">
                    <i class="ri-check-line"></i> All accounts
                </small>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stat-card warning">
                <div class="stat-icon">
                    <i class="ri-star-line"></i>
                </div>
                <h3 class="mb-1">{{ number_format($stats['important'] ?? 0) }}</h3>
                <p class="text-muted mb-0">Important</p>
                <small class="text-warning">
                    <i class="ri-alert-line"></i> Requires attention
                </small>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stat-card info">
                <div class="stat-icon">
                    <i class="ri-send-plane-line"></i>
                </div>
                <h3 class="mb-1">{{ number_format($stats['sent'] ?? 0) }}</h3>
                <p class="text-muted mb-0">Sent Today</p>
                <small class="text-info">
                    <i class="ri-time-line"></i> Last 24 hours
                </small>
            </div>
        </div>
    </div>

    <!-- Bulk Actions Bar -->
    <div class="bulk-actions-bar" id="bulkActionsBar">
        <div>
            <strong id="selectedCount">0</strong> email(s) selected
        </div>
        <div class="d-flex gap-2">
            <select class="form-select form-select-sm" id="bulkAction" style="width: auto;">
                <option value="">Choose Action</option>
                <option value="mark_read">Mark as Read</option>
                <option value="mark_unread">Mark as Unread</option>
                <option value="mark_important">Mark as Important</option>
                <option value="archive">Archive</option>
                <option value="mark_spam">Mark as Spam</option>
                <option value="delete">Delete</option>
            </select>
            <button class="btn btn-light btn-sm" onclick="applyBulkAction()">
                <i class="ri-check-line me-1"></i>Apply
            </button>
            <button class="btn btn-light btn-sm" onclick="clearSelection()">
                <i class="ri-close-line"></i>
            </button>
        </div>
    </div>

    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-3 col-md-4 mb-4">
            <div class="folder-sidebar">
                <div class="p-3 border-bottom">
                    <div class="search-box">
                        <i class="ri-search-line"></i>
                        <input type="text" class="form-control" id="emailSearch" placeholder="Search emails...">
                    </div>
                </div>
                
                <div class="folder-list">
                    <a href="{{ route('admin.emails.index', ['folder' => 'inbox']) }}" 
                       class="folder-item {{ ($folder ?? 'inbox') == 'inbox' ? 'active' : '' }}">
                        <div>
                            <i class="ri-inbox-line me-2"></i>Inbox
                        </div>
                        @if(($stats['unread'] ?? 0) > 0)
                            <span class="badge bg-primary rounded-pill">{{ $stats['unread'] }}</span>
                        @endif
                    </a>
                    
                    <a href="{{ route('admin.emails.index', ['folder' => 'sent']) }}" 
                       class="folder-item {{ ($folder ?? '') == 'sent' ? 'active' : '' }}">
                        <div>
                            <i class="ri-send-plane-line me-2"></i>Sent
                        </div>
                    </a>
                    
                    <a href="{{ route('admin.emails.index', ['folder' => 'drafts']) }}" 
                       class="folder-item {{ ($folder ?? '') == 'drafts' ? 'active' : '' }}">
                        <div>
                            <i class="ri-file-edit-line me-2"></i>Drafts
                        </div>
                        @if(($stats['drafts'] ?? 0) > 0)
                            <span class="badge bg-secondary rounded-pill">{{ $stats['drafts'] }}</span>
                        @endif
                    </a>
                    
                    <a href="{{ route('admin.emails.index', ['folder' => 'important']) }}" 
                       class="folder-item {{ ($folder ?? '') == 'important' ? 'active' : '' }}">
                        <div>
                            <i class="ri-star-line me-2"></i>Important
                        </div>
                    </a>
                    
                    <a href="{{ route('admin.emails.index', ['folder' => 'spam']) }}" 
                       class="folder-item {{ ($folder ?? '') == 'spam' ? 'active' : '' }}">
                        <div>
                            <i class="ri-spam-line me-2"></i>Spam
                        </div>
                    </a>
                    
                    <a href="{{ route('admin.emails.index', ['folder' => 'trash']) }}" 
                       class="folder-item {{ ($folder ?? '') == 'trash' ? 'active' : '' }}">
                        <div>
                            <i class="ri-delete-bin-line me-2"></i>Trash
                        </div>
                    </a>
                </div>
                
                <div class="p-3 border-top">
                    <h6 class="mb-2 text-muted">Quick Filters</h6>
                    <div class="filter-chips">
                        <div class="filter-chip" data-filter="unread">
                            <i class="ri-mail-unread-line me-1"></i>Unread
                        </div>
                        <div class="filter-chip" data-filter="today">
                            <i class="ri-calendar-line me-1"></i>Today
                        </div>
                        <div class="filter-chip" data-filter="attachments">
                            <i class="ri-attachment-line me-1"></i>With Attachments
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Email List -->
        <div class="col-lg-9 col-md-8">
            <div class="email-list">
                <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="ri-{{ ($folder ?? 'inbox') == 'inbox' ? 'inbox' : (($folder ?? '') == 'sent' ? 'send-plane' : 'file-edit') }}-line me-2"></i>
                        {{ ucfirst($folder ?? 'Inbox') }}
                    </h5>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-secondary" onclick="refreshEmails()">
                            <i class="ri-refresh-line"></i>
                        </button>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="ri-more-2-line"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#"><i class="ri-download-2-line me-2"></i>Export</a></li>
                                <li><a class="dropdown-item" href="#"><i class="ri-settings-3-line me-2"></i>Settings</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="email-items" id="emailItems">
                    @forelse($messages ?? [] as $message)
                    <div class="email-item {{ ($message->status ?? 'read') == 'unread' ? 'unread' : '' }}" 
                         data-id="{{ $message->id ?? $loop->index }}">
                        <div class="form-check">
                            <input class="form-check-input email-checkbox" type="checkbox" 
                                   value="{{ $message->id ?? $loop->index }}" 
                                   data-id="{{ $message->id ?? $loop->index }}">
                        </div>
                        
                        <button class="btn btn-sm btn-link p-0 star-btn" 
                                data-id="{{ $message->id ?? $loop->index }}" 
                                data-starred="{{ ($message->is_starred ?? false) ? 1 : 0 }}">
                            @if($message->is_starred ?? false)
                                <i class="ri-star-fill text-warning"></i>
                            @else
                                <i class="ri-star-line text-muted"></i>
                            @endif
                        </button>
                        
                        <div class="email-avatar" style="background: {{ ['#667eea', '#10b981', '#f59e0b', '#ef4444', '#3b82f6'][$loop->index % 5] }}20; color: {{ ['#667eea', '#10b981', '#f59e0b', '#ef4444', '#3b82f6'][$loop->index % 5] }};">
                            {{ strtoupper(substr(($message->from_name ?? $message->from_email ?? 'U'), 0, 1)) }}
                        </div>
                        
                        <div class="email-content" onclick="viewEmail({{ $message->id ?? $loop->index }})">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <div class="email-subject">
                                    {{ $message->subject ?? 'No Subject' }}
                                    @if($message->has_attachments ?? false)
                                        <i class="ri-attachment-line text-muted ms-1"></i>
                                    @endif
                                </div>
                                <div class="email-meta">
                                    <span>{{ ($message->received_at ?? now())->format('M d') }}</span>
                                </div>
                            </div>
                            <div class="email-preview">
                                {{ Str::limit(strip_tags($message->body_text ?? $message->body_html ?? 'No preview available'), 100) }}
                            </div>
                            <div class="email-meta mt-1">
                                <span>{{ $message->from_name ?? $message->from_email ?? 'Unknown' }}</span>
                                @if($message->account ?? null)
                                    <span class="badge bg-label-info">{{ $message->account->name }}</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="email-actions">
                            <button class="btn btn-sm btn-link" onclick="archiveEmail({{ $message->id ?? $loop->index }})" title="Archive">
                                <i class="ri-archive-line"></i>
                            </button>
                            <button class="btn btn-sm btn-link text-danger" onclick="deleteEmail({{ $message->id ?? $loop->index }})" title="Delete">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5">
                        <i class="ri-inbox-line" style="font-size: 4rem; color: #d1d5db;"></i>
                        <p class="text-muted mt-3">No emails in {{ ucfirst($folder ?? 'inbox') }}</p>
                        <a href="{{ route('admin.emails.compose') }}" class="btn btn-primary">
                            <i class="ri-mail-add-line me-2"></i>Compose Email
                        </a>
                    </div>
                    @endforelse
                </div>
                
                @if(isset($messages) && $messages->hasPages())
                <div class="p-3 border-top">
                    {{ $messages->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let selectedEmails = new Set();

// Checkbox selection
document.addEventListener('DOMContentLoaded', function() {
    // Select all checkbox
    const selectAll = document.createElement('input');
    selectAll.type = 'checkbox';
    selectAll.className = 'form-check-input';
    selectAll.id = 'selectAll';
    selectAll.addEventListener('change', function() {
        document.querySelectorAll('.email-checkbox').forEach(cb => {
            cb.checked = this.checked;
            if (this.checked) {
                selectedEmails.add(cb.dataset.id);
            } else {
                selectedEmails.delete(cb.dataset.id);
            }
        });
        updateBulkActions();
    });
    
    // Add select all to header if emails exist
    const emailItems = document.getElementById('emailItems');
    if (emailItems && emailItems.querySelector('.email-item')) {
        const firstItem = emailItems.querySelector('.email-item');
        const checkContainer = firstItem.querySelector('.form-check');
        if (checkContainer) {
            checkContainer.insertBefore(selectAll, checkContainer.firstChild);
        }
    }
    
    // Individual checkboxes
    document.querySelectorAll('.email-checkbox').forEach(cb => {
        cb.addEventListener('change', function() {
            if (this.checked) {
                selectedEmails.add(this.dataset.id);
                this.closest('.email-item').classList.add('selected');
            } else {
                selectedEmails.delete(this.dataset.id);
                this.closest('.email-item').classList.remove('selected');
            }
            updateBulkActions();
        });
    });
    
    // Star/unstar
    document.querySelectorAll('.star-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleStar(this);
        });
    });
    
    // Search
    const searchInput = document.getElementById('emailSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const term = e.target.value.toLowerCase();
            document.querySelectorAll('.email-item').forEach(item => {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(term) ? '' : 'none';
            });
        });
    }
    
    // Filter chips
    document.querySelectorAll('.filter-chip').forEach(chip => {
        chip.addEventListener('click', function() {
            document.querySelectorAll('.filter-chip').forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            // Implement filter logic
        });
    });
});

function updateBulkActions() {
    const bar = document.getElementById('bulkActionsBar');
    const count = document.getElementById('selectedCount');
    
    if (selectedEmails.size > 0) {
        bar.classList.add('active');
        count.textContent = selectedEmails.size;
    } else {
        bar.classList.remove('active');
    }
}

function clearSelection() {
    selectedEmails.clear();
    document.querySelectorAll('.email-checkbox').forEach(cb => {
        cb.checked = false;
        cb.closest('.email-item')?.classList.remove('selected');
    });
    const selectAll = document.getElementById('selectAll');
    if (selectAll) selectAll.checked = false;
    updateBulkActions();
}

function applyBulkAction() {
    const action = document.getElementById('bulkAction').value;
    if (!action || selectedEmails.size === 0) return;
    
    if (confirm(`Apply "${action}" to ${selectedEmails.size} email(s)?`)) {
        // Implement bulk action
        console.log('Bulk action:', action, Array.from(selectedEmails));
        clearSelection();
    }
}

function viewEmail(id) {
    window.location.href = `/admin/emails/${id}`;
}

function toggleStar(btn) {
    const id = btn.dataset.id;
    const isStarred = btn.dataset.starred == '1';
    
    fetch(`/admin/emails/${id}/star`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ starred: !isStarred })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            btn.dataset.starred = isStarred ? '0' : '1';
            const icon = btn.querySelector('i');
            if (isStarred) {
                icon.classList.remove('ri-star-fill', 'text-warning');
                icon.classList.add('ri-star-line', 'text-muted');
            } else {
                icon.classList.remove('ri-star-line', 'text-muted');
                icon.classList.add('ri-star-fill', 'text-warning');
            }
        }
    });
}

function archiveEmail(id) {
    if (confirm('Archive this email?')) {
        // Implement archive
        console.log('Archive:', id);
    }
}

function deleteEmail(id) {
    if (confirm('Delete this email?')) {
        fetch(`/admin/emails/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(() => {
            document.querySelector(`[data-id="${id}"]`).remove();
        });
    }
}

function refreshEmails() {
    window.location.reload();
}
</script>
@endpush
