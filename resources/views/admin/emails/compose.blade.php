@extends('admin.layouts.app')

@section('title', 'Compose Email - Lau Paradise Adventures')
@section('description', 'Advanced email composition')

@push('styles')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
    .compose-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        color: white;
    }
    
    .compose-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    
    .compose-toolbar {
        background: #f9fafb;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .editor-toolbar {
        background: white;
        border-bottom: 1px solid #e5e7eb;
        padding: 0.75rem 1rem;
    }
    
    .editor-container {
        min-height: 400px;
        max-height: 600px;
        overflow-y: auto;
    }
    
    .recipient-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 0.5rem;
    }
    
    .recipient-tag {
        background: #667eea;
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .recipient-tag .remove {
        cursor: pointer;
        font-weight: bold;
    }
    
    .template-selector {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .template-card {
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        padding: 1.5rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .template-card:hover {
        border-color: #667eea;
        transform: translateY(-5px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .template-card.active {
        border-color: #667eea;
        background: rgba(102, 126, 234, 0.05);
    }
    
    .attachment-item {
        background: #f9fafb;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
    }
    
    .compose-actions {
        background: #f9fafb;
        padding: 1rem 1.5rem;
        border-top: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .priority-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .priority-high { background: #fee2e2; color: #dc2626; }
    .priority-normal { background: #dbeafe; color: #2563eb; }
    .priority-low { background: #f3f4f6; color: #6b7280; }
    
    .schedule-picker {
        background: white;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        padding: 1rem;
        margin-top: 1rem;
    }
</style>
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="compose-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="mb-2 text-white">
                    <i class="ri-mail-add-line me-2"></i>Compose Email
                </h2>
                <p class="text-white-50 mb-0">Create and send professional emails</p>
            </div>
            <div class="col-md-4 text-end">
                <a href="{{ route('admin.emails.index') }}" class="btn btn-light">
                    <i class="ri-arrow-left-line me-2"></i>Back to Inbox
                </a>
            </div>
        </div>
    </div>

    <form id="composeForm" method="POST" action="{{ route('admin.emails.send') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="save_draft" id="saveDraft" value="0">
        <input type="hidden" name="body" id="emailBody">
        
        <div class="compose-card">
            <!-- Toolbar -->
            <div class="compose-toolbar">
                <div class="d-flex gap-2 align-items-center">
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="saveDraftBtn">
                        <i class="ri-save-line me-1"></i>Save Draft
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="previewEmail()">
                        <i class="ri-eye-line me-1"></i>Preview
                    </button>
                </div>
                <div class="d-flex gap-2 align-items-center">
                    <select class="form-select form-select-sm" id="priority" name="priority" style="width: auto;">
                        <option value="normal">Normal Priority</option>
                        <option value="high">High Priority</option>
                        <option value="low">Low Priority</option>
                    </select>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="scheduleBtn">
                        <i class="ri-time-line me-1"></i>Schedule
                    </button>
                </div>
            </div>

            <div class="p-4">
                <!-- Email Templates -->
                <div class="mb-4">
                    <label class="form-label fw-semibold mb-2">
                        <i class="ri-file-text-line me-2"></i>Email Templates
                    </label>
                    <div class="template-selector">
                        <div class="template-card" data-template="">
                            <i class="ri-file-blank-line" style="font-size: 2rem; color: #667eea;"></i>
                            <h6 class="mt-2 mb-0">Blank</h6>
                        </div>
                        <div class="template-card" data-template="greeting">
                            <i class="ri-hand-heart-line" style="font-size: 2rem; color: #10b981;"></i>
                            <h6 class="mt-2 mb-0">Greeting</h6>
                        </div>
                        <div class="template-card" data-template="booking">
                            <i class="ri-calendar-check-line" style="font-size: 2rem; color: #f59e0b;"></i>
                            <h6 class="mt-2 mb-0">Booking</h6>
                        </div>
                        <div class="template-card" data-template="invoice">
                            <i class="ri-file-list-line" style="font-size: 2rem; color: #ef4444;"></i>
                            <h6 class="mt-2 mb-0">Invoice</h6>
                        </div>
                        <div class="template-card" data-template="followup">
                            <i class="ri-customer-service-line" style="font-size: 2rem; color: #3b82f6;"></i>
                            <h6 class="mt-2 mb-0">Follow Up</h6>
                        </div>
                    </div>
                </div>

                <!-- From Account -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            From <span class="text-danger">*</span>
                        </label>
                        <select name="email_account_id" class="form-select @error('email_account_id') is-invalid @enderror" required>
                            <option value="">Select Email Account</option>
                            @foreach($accounts ?? [] as $account)
                                <option value="{{ $account->id }}" {{ old('email_account_id') == $account->id ? 'selected' : '' }}>
                                    {{ $account->name }} ({{ $account->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('email_account_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Priority</label>
                        <div id="priorityDisplay" class="priority-badge priority-normal">Normal Priority</div>
                    </div>
                </div>

                @if($replyTo ?? null)
                <div class="alert alert-info mb-3">
                    <strong><i class="ri-reply-line me-1"></i>Replying to:</strong> {{ $replyTo->subject }}<br>
                    <small>From: {{ $replyTo->from_name }} &lt;{{ $replyTo->from_email }}&gt;</small>
                    <input type="hidden" name="reply_to_id" value="{{ $replyTo->id }}">
                </div>
                @endif

                <!-- Recipients -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        To <span class="text-danger">*</span>
                    </label>
                    <input type="text" id="toInput" class="form-control" 
                           value="{{ old('to', $replyTo->from_email ?? '') }}" 
                           placeholder="Enter email addresses (comma separated)">
                    <div class="recipient-tags" id="toTags"></div>
                    <input type="hidden" name="to" id="toHidden" required>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">CC</label>
                        <input type="text" id="ccInput" class="form-control" 
                               value="{{ old('cc') }}" placeholder="CC recipients">
                        <div class="recipient-tags" id="ccTags"></div>
                        <input type="hidden" name="cc" id="ccHidden">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">BCC</label>
                        <input type="text" id="bccInput" class="form-control" 
                               value="{{ old('bcc') }}" placeholder="BCC recipients">
                        <div class="recipient-tags" id="bccTags"></div>
                        <input type="hidden" name="bcc" id="bccHidden">
                    </div>
                </div>

                <!-- Subject -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Subject <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror" 
                           value="{{ old('subject', $replyTo ? 'Re: ' . $replyTo->subject : '') }}" 
                           placeholder="Email subject" required>
                    @error('subject')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Rich Text Editor -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Message <span class="text-danger">*</span>
                    </label>
                    <div class="editor-toolbar">
                        <div id="editorToolbar"></div>
                    </div>
                    <div id="editor" class="editor-container"></div>
                    @error('body')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Attachments -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        <i class="ri-attachment-line me-2"></i>Attachments
                    </label>
                    <div class="border rounded p-3">
                        <input type="file" id="attachmentInput" name="attachments[]" multiple class="d-none">
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="document.getElementById('attachmentInput').click()">
                            <i class="ri-add-line me-1"></i>Add Files
                        </button>
                        <div id="attachmentList" class="mt-3"></div>
                    </div>
                </div>

                <!-- Schedule Section -->
                <div class="schedule-picker" id="scheduleSection" style="display: none;">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Schedule Date</label>
                            <input type="date" name="schedule_date" id="scheduleDate" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Schedule Time</label>
                            <input type="time" name="schedule_time" id="scheduleTime" class="form-control">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="compose-actions">
                <div>
                    <small class="text-muted">
                        <i class="ri-save-line me-1"></i>Auto-save: <span id="autoSaveStatus">Enabled</span>
                    </small>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-secondary" id="saveDraftBtn2">
                        <i class="ri-save-line me-1"></i>Save Draft
                    </button>
                    <button type="submit" class="btn btn-primary" id="sendBtn">
                        <i class="ri-send-plane-line me-1"></i>Send Email
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Email Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="emailPreview"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
let quill;
let attachments = [];
let recipients = { to: [], cc: [], bcc: [] };

// Initialize Quill Editor
document.addEventListener('DOMContentLoaded', function() {
    quill = new Quill('#editor', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'align': [] }],
                ['link', 'image'],
                ['clean']
            ]
        }
    });

    // Set initial content if replying
    @if($replyTo ?? null)
    quill.root.innerHTML = '<p><br></p><p>--- Original Message ---</p><p>' + 
        '{{ Str::limit(strip_tags($replyTo->body_text ?? $replyTo->body_html ?? ''), 500) }}' + 
        '</p>';
    @endif

    // Auto-save
    let autoSaveTimer;
    quill.on('text-change', function() {
        clearTimeout(autoSaveTimer);
        autoSaveTimer = setTimeout(autoSave, 30000);
    });

    // Recipient input handlers
    setupRecipientInput('toInput', 'toTags', 'toHidden', 'to');
    setupRecipientInput('ccInput', 'ccTags', 'ccHidden', 'cc');
    setupRecipientInput('bccInput', 'bccTags', 'bccHidden', 'bcc');

    // Priority change
    document.getElementById('priority').addEventListener('change', function() {
        const display = document.getElementById('priorityDisplay');
        display.className = 'priority-badge priority-' + this.value;
        display.textContent = this.value.charAt(0).toUpperCase() + this.value.slice(1) + ' Priority';
    });

    // Schedule button
    document.getElementById('scheduleBtn').addEventListener('click', function() {
        const section = document.getElementById('scheduleSection');
        section.style.display = section.style.display === 'none' ? 'block' : 'none';
    });

    // Template selection
    document.querySelectorAll('.template-card').forEach(card => {
        card.addEventListener('click', function() {
            document.querySelectorAll('.template-card').forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            loadTemplate(this.dataset.template);
        });
    });

    // Save draft buttons
    document.getElementById('saveDraftBtn').addEventListener('click', saveDraft);
    document.getElementById('saveDraftBtn2').addEventListener('click', saveDraft);

    // Form submit
    document.getElementById('composeForm').addEventListener('submit', function(e) {
        document.getElementById('emailBody').value = quill.root.innerHTML;
        updateRecipientInputs();
    });

    // Attachment handler
    document.getElementById('attachmentInput').addEventListener('change', function(e) {
        Array.from(e.target.files).forEach(file => {
            addAttachment(file);
        });
    });
});

function setupRecipientInput(inputId, tagsId, hiddenId, type) {
    const input = document.getElementById(inputId);
    const tags = document.getElementById(tagsId);
    const hidden = document.getElementById(hiddenId);

    input.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' || e.key === ',') {
            e.preventDefault();
            const email = this.value.trim();
            if (email && isValidEmail(email)) {
                addRecipient(email, type);
                this.value = '';
            }
        }
    });

    input.addEventListener('blur', function() {
        const email = this.value.trim();
        if (email && isValidEmail(email)) {
            addRecipient(email, type);
            this.value = '';
        }
    });
}

function addRecipient(email, type) {
    if (recipients[type].includes(email)) return;
    
    recipients[type].push(email);
    const tags = document.getElementById(type + 'Tags');
    const tag = document.createElement('div');
    tag.className = 'recipient-tag';
    tag.innerHTML = `
        <span>${email}</span>
        <span class="remove" onclick="removeRecipient('${email}', '${type}')">×</span>
    `;
    tags.appendChild(tag);
    
    updateRecipientInputs();
}

function removeRecipient(email, type) {
    recipients[type] = recipients[type].filter(e => e !== email);
    document.querySelectorAll(`#${type}Tags .recipient-tag`).forEach(tag => {
        if (tag.textContent.includes(email)) tag.remove();
    });
    updateRecipientInputs();
}

function updateRecipientInputs() {
    document.getElementById('toHidden').value = recipients.to.join(',');
    document.getElementById('ccHidden').value = recipients.cc.join(',');
    document.getElementById('bccHidden').value = recipients.bcc.join(',');
}

function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

function loadTemplate(template) {
    let content = '';
    switch(template) {
        case 'greeting':
            content = '<h2>Dear Valued Customer,</h2><p>Thank you for contacting Lau Paradise Adventures. We appreciate your inquiry and will respond to you as soon as possible.</p><p>Best regards,<br><strong>Lau Paradise Adventures Team</strong></p>';
            break;
        case 'booking':
            content = '<h2>Booking Confirmation</h2><p>Dear Customer,</p><p>We are pleased to confirm your booking with Lau Paradise Adventures.</p><h3>Booking Details:</h3><ul><li><strong>Booking Reference:</strong> [REFERENCE]</li><li><strong>Date:</strong> [DATE]</li><li><strong>Tour:</strong> [TOUR_NAME]</li></ul><p>Thank you for choosing us!</p><p>Best regards,<br><strong>Lau Paradise Adventures</strong></p>';
            break;
        case 'invoice':
            content = '<h2>Invoice</h2><p>Dear Customer,</p><p>Please find attached your invoice for the services provided.</p><p>If you have any questions regarding this invoice, please don\'t hesitate to contact us.</p><p>Best regards,<br><strong>Lau Paradise Adventures</strong></p>';
            break;
        case 'followup':
            content = '<h2>Follow Up</h2><p>Dear Customer,</p><p>We wanted to follow up on your recent inquiry. Is there anything else we can help you with?</p><p>We look forward to hearing from you.</p><p>Best regards,<br><strong>Lau Paradise Adventures</strong></p>';
            break;
    }
    if (content) {
        quill.root.innerHTML = content;
    }
}

function addAttachment(file) {
    attachments.push(file);
    const list = document.getElementById('attachmentList');
    const item = document.createElement('div');
    item.className = 'attachment-item';
    item.innerHTML = `
        <div>
            <i class="ri-file-line me-2"></i>
            <strong>${file.name}</strong>
            <small class="text-muted ms-2">(${(file.size / 1024).toFixed(2)} KB)</small>
        </div>
        <button type="button" class="btn btn-sm btn-link text-danger" onclick="removeAttachment('${file.name}')">
            <i class="ri-close-line"></i>
        </button>
    `;
    item.dataset.filename = file.name;
    list.appendChild(item);
}

function removeAttachment(filename) {
    attachments = attachments.filter(f => f.name !== filename);
    document.querySelector(`[data-filename="${filename}"]`)?.remove();
}

function autoSave() {
    document.getElementById('saveDraft').value = '1';
    const formData = new FormData(document.getElementById('composeForm'));
    formData.append('body', quill.root.innerHTML);
    
    fetch('{{ route("admin.emails.send") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('autoSaveStatus').textContent = 'Saved ' + new Date().toLocaleTimeString();
    });
}

function saveDraft() {
    document.getElementById('saveDraft').value = '1';
    document.getElementById('emailBody').value = quill.root.innerHTML;
    updateRecipientInputs();
    document.getElementById('composeForm').submit();
}

function previewEmail() {
    const preview = document.getElementById('emailPreview');
    preview.innerHTML = `
        <div class="mb-3">
            <strong>To:</strong> ${recipients.to.join(', ') || 'Not set'}<br>
            <strong>CC:</strong> ${recipients.cc.join(', ') || 'None'}<br>
            <strong>BCC:</strong> ${recipients.bcc.join(', ') || 'None'}<br>
            <strong>Subject:</strong> ${document.querySelector('[name="subject"]').value || 'No subject'}
        </div>
        <div class="border rounded p-3">
            ${quill.root.innerHTML}
        </div>
    `;
    new bootstrap.Modal(document.getElementById('previewModal')).show();
}
</script>
@endpush
                
                <div class="card-body">
                    <form id="composeForm" method="POST" action="{{ route('admin.emails.send') }}">
                        @csrf
                        <input type="hidden" name="save_draft" id="saveDraft" value="0">
                        
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">From Account <span class="text-danger">*</span></label>
                                <select name="email_account_id" class="form-select @error('email_account_id') is-invalid @enderror" required>
                                    <option value="">Select Email Account</option>
                                    @foreach($accounts as $account)
                                        <option value="{{ $account->id }}" {{ old('email_account_id', $accounts->first()->id ?? '') == $account->id ? 'selected' : '' }}>
                                            {{ $account->name }} ({{ $account->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('email_account_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Template</label>
                                <select id="emailTemplate" class="form-select">
                                    <option value="">Select Template</option>
                                    <option value="greeting">Greeting Template</option>
                                    <option value="booking_confirmation">Booking Confirmation</option>
                                    <option value="invoice">Invoice Template</option>
                                    <option value="follow_up">Follow Up</option>
                                </select>
                            </div>
                        </div>
                        
                        @if($replyTo)
                        <div class="alert alert-info mb-3">
                            <strong><i class="ri-reply-line me-1"></i>Replying to:</strong> {{ $replyTo->subject }}<br>
                            <small>From: {{ $replyTo->from_name }} &lt;{{ $replyTo->from_email }}&gt;</small>
                            <input type="hidden" name="reply_to_id" value="{{ $replyTo->id }}">
                        </div>
                        @endif
                        
                        <div class="mb-3">
                            <label class="form-label">To <span class="text-danger">*</span></label>
                            <input type="text" name="to" id="toInput" class="form-control @error('to') is-invalid @enderror" 
                                   value="{{ old('to', $replyTo ? $replyTo->from_email : '') }}" 
                                   placeholder="email@example.com" required>
                            <small class="text-muted">Separate multiple emails with commas</small>
                            @error('to')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">CC</label>
                                <input type="text" name="cc" class="form-control @error('cc') is-invalid @enderror" 
                                       value="{{ old('cc') }}" placeholder="email@example.com">
                                <small class="text-muted">Separate multiple emails with commas</small>
                                @error('cc')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">BCC</label>
                                <input type="text" name="bcc" class="form-control @error('bcc') is-invalid @enderror" 
                                       value="{{ old('bcc') }}" placeholder="email@example.com">
                                <small class="text-muted">Separate multiple emails with commas</small>
                                @error('bcc')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Subject <span class="text-danger">*</span></label>
                            <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror" 
                                   value="{{ old('subject', $replyTo ? 'Re: ' . $replyTo->subject : '') }}" required>
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Rich Text Editor -->
                        <div class="mb-3">
                            <label class="form-label">Message <span class="text-danger">*</span></label>
                            <div class="border rounded">
                                <div id="editorToolbar" class="p-2 border-bottom bg-light">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-outline-secondary" onclick="formatText('bold')" title="Bold">
                                            <i class="ri-bold"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary" onclick="formatText('italic')" title="Italic">
                                            <i class="ri-italic"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary" onclick="formatText('underline')" title="Underline">
                                            <i class="ri-underline"></i>
                                        </button>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" title="Font Size">
                                                <i class="ri-font-size"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#" onclick="formatText('fontSize', '12px'); return false;">12px</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="formatText('fontSize', '14px'); return false;">14px</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="formatText('fontSize', '16px'); return false;">16px</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="formatText('fontSize', '18px'); return false;">18px</a></li>
                                            </ul>
                                        </div>
                                        <button type="button" class="btn btn-outline-secondary" onclick="formatText('justifyLeft')" title="Align Left">
                                            <i class="ri-align-left"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary" onclick="formatText('justifyCenter')" title="Align Center">
                                            <i class="ri-align-center"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary" onclick="formatText('justifyRight')" title="Align Right">
                                            <i class="ri-align-right"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary" onclick="formatText('insertUnorderedList')" title="Bullet List">
                                            <i class="ri-list-unordered"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary" onclick="formatText('insertOrderedList')" title="Numbered List">
                                            <i class="ri-list-ordered"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary" onclick="formatText('createLink')" title="Insert Link">
                                            <i class="ri-links-line"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary" onclick="insertImage()" title="Insert Image">
                                            <i class="ri-image-line"></i>
                                        </button>
                                    </div>
                                </div>
                                <div id="emailBody" contenteditable="true" class="p-3" style="min-height: 400px; max-height: 600px; overflow-y: auto;">
                                    {{ old('body', $replyTo ? "\n\n--- Original Message ---\n" . strip_tags($replyTo->body_text) : '') }}
                                </div>
                            </div>
                            <textarea name="body" id="bodyTextarea" class="d-none" required>{{ old('body', $replyTo ? "\n\n--- Original Message ---\n" . strip_tags($replyTo->body_text) : '') }}</textarea>
                            @error('body')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Attachments -->
                        <div class="mb-3">
                            <label class="form-label">Attachments</label>
                            <div class="border rounded p-3">
                                <input type="file" id="attachmentInput" multiple class="d-none">
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="document.getElementById('attachmentInput').click()">
                                    <i class="ri-attachment-line me-1"></i>Add Attachment
                                </button>
                                <div id="attachmentList" class="mt-2"></div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">Auto-save: <span id="autoSaveStatus">Enabled</span></small>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-secondary" id="saveDraftBtn2">
                                    <i class="ri-save-line me-1"></i>Save Draft
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ri-send-plane-line me-1"></i>Send Email
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let attachments = [];
let autoSaveInterval;

// Rich text editor functions
function formatText(command, value = null) {
    document.execCommand(command, false, value);
    document.getElementById('emailBody').focus();
    updateBodyTextarea();
}

function insertImage() {
    const url = prompt('Enter image URL:');
    if (url) {
        document.execCommand('insertImage', false, url);
        updateBodyTextarea();
    }
}

function updateBodyTextarea() {
    document.getElementById('bodyTextarea').value = document.getElementById('emailBody').innerHTML;
}

// Auto-save functionality
function autoSave() {
    const formData = new FormData(document.getElementById('composeForm'));
    formData.append('save_draft', '1');
    
    fetch('{{ route("admin.emails.send") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('autoSaveStatus').textContent = 'Saved ' + new Date().toLocaleTimeString();
    })
    .catch(error => {
        console.error('Auto-save error:', error);
    });
}

// Start auto-save every 30 seconds
document.getElementById('emailBody').addEventListener('input', function() {
    updateBodyTextarea();
    clearTimeout(autoSaveInterval);
    autoSaveInterval = setTimeout(autoSave, 30000);
});

// Update textarea before form submit
document.getElementById('composeForm').addEventListener('submit', function(e) {
    updateBodyTextarea();
});

// Save draft button
document.getElementById('saveDraftBtn').addEventListener('click', function() {
    document.getElementById('saveDraft').value = '1';
    document.getElementById('composeForm').submit();
});

document.getElementById('saveDraftBtn2').addEventListener('click', function() {
    document.getElementById('saveDraft').value = '1';
    document.getElementById('composeForm').submit();
});

// Email templates
document.getElementById('emailTemplate').addEventListener('change', function() {
    const template = this.value;
    let content = '';
    
    switch(template) {
        case 'greeting':
            content = '<p>Dear Customer,</p><p>Thank you for contacting us. We appreciate your inquiry and will respond as soon as possible.</p><p>Best regards,<br>Lau Paradise Adventures Team</p>';
            break;
        case 'booking_confirmation':
            content = '<p>Dear Customer,</p><p>We are pleased to confirm your booking with Lau Paradise Adventures.</p><p><strong>Booking Details:</strong></p><ul><li>Booking Reference: [REFERENCE]</li><li>Date: [DATE]</li><li>Tour: [TOUR_NAME]</li></ul><p>Thank you for choosing us!</p><p>Best regards,<br>Lau Paradise Adventures</p>';
            break;
        case 'invoice':
            content = '<p>Dear Customer,</p><p>Please find attached your invoice for the services provided.</p><p>If you have any questions, please don\'t hesitate to contact us.</p><p>Best regards,<br>Lau Paradise Adventures</p>';
            break;
        case 'follow_up':
            content = '<p>Dear Customer,</p><p>We wanted to follow up on your recent inquiry. Is there anything else we can help you with?</p><p>We look forward to hearing from you.</p><p>Best regards,<br>Lau Paradise Adventures</p>';
            break;
    }
    
    if (content) {
        document.getElementById('emailBody').innerHTML = content;
        updateBodyTextarea();
    }
});

// Attachment handling
document.getElementById('attachmentInput').addEventListener('change', function(e) {
    Array.from(e.target.files).forEach(file => {
        attachments.push(file);
        const div = document.createElement('div');
        div.className = 'd-flex justify-content-between align-items-center mt-2 p-2 bg-light rounded';
        div.innerHTML = `
            <span><i class="ri-file-line me-1"></i>${file.name} (${(file.size / 1024).toFixed(2)} KB)</span>
            <button type="button" class="btn btn-sm btn-link text-danger" onclick="removeAttachment('${file.name}')">
                <i class="ri-close-line"></i>
            </button>
        `;
        div.dataset.filename = file.name;
        document.getElementById('attachmentList').appendChild(div);
    });
});

function removeAttachment(filename) {
    attachments = attachments.filter(f => f.name !== filename);
    document.querySelector(`[data-filename="${filename}"]`).remove();
}

// Initialize
updateBodyTextarea();
</script>
@endsection

