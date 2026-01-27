@extends('admin.layouts.app')

@section('title', 'View Email')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title mb-0">{{ $email->subject }}</h5>
                    <small class="text-muted">From: {{ $email->from_name }} &lt;{{ $email->from_email }}&gt;</small>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.emails.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="ri-arrow-left-line me-1"></i>Back
                    </a>
                    <a href="{{ route('admin.emails.compose', ['reply_to' => $email->id]) }}" class="btn btn-primary btn-sm">
                        <i class="ri-reply-line me-1"></i>Reply
                    </a>
                </div>
            </div>
            
            <div class="card-body">
                <div class="email-header mb-4 pb-4 border-bottom">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <strong>From:</strong> {{ $email->from_name }} &lt;{{ $email->from_email }}&gt;
                            </div>
                            <div class="mb-2">
                                <strong>To:</strong> 
                                @if(is_array($email->to))
                                    @foreach($email->to as $to)
                                        {{ $to['name'] ?? '' }} &lt;{{ $to['email'] }}&gt;@if(!$loop->last), @endif
                                    @endforeach
                                @else
                                    {{ $email->to }}
                                @endif
                            </div>
                            @if($email->cc)
                            <div class="mb-2">
                                <strong>CC:</strong> 
                                @if(is_array($email->cc))
                                    @foreach($email->cc as $cc)
                                        {{ $cc['email'] }}@if(!$loop->last), @endif
                                    @endforeach
                                @endif
                            </div>
                            @endif
                            <div class="mb-2">
                                <strong>Date:</strong> {{ $email->received_at->format('F d, Y h:i A') }}
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <div class="mb-2">
                                @if($email->is_important)
                                    <span class="badge bg-label-warning me-1">Important</span>
                                @endif
                                @if($email->is_starred)
                                    <span class="badge bg-label-warning me-1">Starred</span>
                                @endif
                                <span class="badge bg-label-{{ $email->status === 'unread' ? 'primary' : 'success' }}">
                                    {{ ucfirst($email->status) }}
                                </span>
                            </div>
                            @if($email->has_attachments)
                                <div class="mb-2">
                                    <i class="ri-attachment-line"></i> {{ $email->attachments->count() }} attachment(s)
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                @if($email->attachments->count() > 0)
                <div class="email-attachments mb-4 pb-4 border-bottom">
                    <h6 class="mb-3">Attachments</h6>
                    <div class="row g-2">
                        @foreach($email->attachments as $attachment)
                        <div class="col-md-3">
                            <div class="card border">
                                <div class="card-body p-2">
                                    <div class="d-flex align-items-center">
                                        <i class="ri-file-line ri-24px me-2"></i>
                                        <div class="flex-grow-1">
                                            <small class="d-block text-truncate" style="max-width: 150px;">{{ $attachment->original_filename }}</small>
                                            <small class="text-muted">{{ $attachment->size_human }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
                
                <div class="email-body">
                    @if($email->body_html)
                        <div class="email-content">
                            {!! $email->body_html !!}
                        </div>
                    @else
                        <div class="email-content">
                            <pre style="white-space: pre-wrap; font-family: inherit;">{{ $email->body_text }}</pre>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="card-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <form action="{{ route('admin.emails.update-status', $email) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="{{ $email->status === 'unread' ? 'read' : 'unread' }}">
                            <button type="submit" class="btn btn-sm btn-outline-secondary">
                                <i class="ri-{{ $email->status === 'unread' ? 'mail-open' : 'mail' }}-line me-1"></i>
                                Mark as {{ $email->status === 'unread' ? 'Read' : 'Unread' }}
                            </button>
                        </form>
                    </div>
                    <div>
                        <a href="{{ route('admin.emails.compose', ['reply_to' => $email->id]) }}" class="btn btn-primary">
                            <i class="ri-reply-line me-1"></i>Reply
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection




