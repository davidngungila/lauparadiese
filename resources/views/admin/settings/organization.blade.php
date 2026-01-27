@extends('admin.layouts.app')

@section('title', 'Organization Settings')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="ri-building-line me-2"></i>Organization Settings
                    </h4>
                    <p class="text-muted mb-0">Configure your organization information for invoices, quotations, and other documents</p>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.settings.organization.update') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row g-3">
                            <div class="col-12">
                                <h5 class="mb-3">Basic Information</h5>
                            </div>
                            
                            <div class="col-md-12">
                                <label class="form-label">Organization Name <span class="text-danger">*</span></label>
                                <input type="text" name="organization_name" class="form-control" 
                                       value="{{ old('organization_name', $settings->organization_name) }}" required>
                            </div>
                            
                            <div class="col-md-12">
                                <label class="form-label">Logo URL</label>
                                <input type="url" name="logo_url" class="form-control" 
                                       value="{{ old('logo_url', $settings->logo_url) }}" 
                                       placeholder="https://example.com/logo.png">
                                <small class="text-muted">URL to your organization logo</small>
                            </div>
                            
                            <div class="col-12">
                                <h5 class="mb-3 mt-4">Address Information</h5>
                            </div>
                            
                            <div class="col-md-12">
                                <label class="form-label">Address</label>
                                <textarea name="address" class="form-control" rows="2">{{ old('address', $settings->address) }}</textarea>
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label">City</label>
                                <input type="text" name="city" class="form-control" 
                                       value="{{ old('city', $settings->city) }}">
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label">State/Province</label>
                                <input type="text" name="state" class="form-control" 
                                       value="{{ old('state', $settings->state) }}">
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label">Country</label>
                                <input type="text" name="country" class="form-control" 
                                       value="{{ old('country', $settings->country) }}">
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label">Postal Code</label>
                                <input type="text" name="postal_code" class="form-control" 
                                       value="{{ old('postal_code', $settings->postal_code) }}">
                            </div>
                            
                            <div class="col-12">
                                <h5 class="mb-3 mt-4">Contact Information</h5>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control" 
                                       value="{{ old('phone', $settings->phone) }}" 
                                       placeholder="+255 754 123 456">
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" 
                                       value="{{ old('email', $settings->email) }}" 
                                       placeholder="info@example.com">
                            </div>
                            
                            <div class="col-md-12">
                                <label class="form-label">Website</label>
                                <input type="url" name="website" class="form-control" 
                                       value="{{ old('website', $settings->website) }}" 
                                       placeholder="https://www.example.com">
                            </div>
                            
                            <div class="col-12">
                                <h5 class="mb-3 mt-4">Legal Information</h5>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Tax ID</label>
                                <input type="text" name="tax_id" class="form-control" 
                                       value="{{ old('tax_id', $settings->tax_id) }}">
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Registration Number</label>
                                <input type="text" name="registration_number" class="form-control" 
                                       value="{{ old('registration_number', $settings->registration_number) }}">
                            </div>
                            
                            <div class="col-12">
                                <h5 class="mb-3 mt-4">Banking Information</h5>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Bank Name</label>
                                <input type="text" name="bank_name" class="form-control" 
                                       value="{{ old('bank_name', $settings->bank_name) }}">
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Bank Country</label>
                                <input type="text" name="bank_country" class="form-control" 
                                       value="{{ old('bank_country', $settings->bank_country) }}">
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">IBAN</label>
                                <input type="text" name="iban" class="form-control" 
                                       value="{{ old('iban', $settings->iban) }}">
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">SWIFT Code</label>
                                <input type="text" name="swift_code" class="form-control" 
                                       value="{{ old('swift_code', $settings->swift_code) }}">
                            </div>
                            
                            <div class="col-12">
                                <h5 class="mb-3 mt-4">Document Settings</h5>
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label">Currency <span class="text-danger">*</span></label>
                                <input type="text" name="currency" class="form-control" 
                                       value="{{ old('currency', $settings->currency) }}" 
                                       maxlength="3" required placeholder="USD">
                                <small class="text-muted">3-letter currency code (e.g., USD, EUR, TZS)</small>
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label">Invoice Prefix</label>
                                <input type="text" name="invoice_prefix" class="form-control" 
                                       value="{{ old('invoice_prefix', $settings->invoice_prefix) }}" 
                                       maxlength="10" placeholder="INV">
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label">Quotation Prefix</label>
                                <input type="text" name="quotation_prefix" class="form-control" 
                                       value="{{ old('quotation_prefix', $settings->quotation_prefix) }}" 
                                       maxlength="10" placeholder="QT">
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label">Booking Prefix</label>
                                <input type="text" name="booking_prefix" class="form-control" 
                                       value="{{ old('booking_prefix', $settings->booking_prefix) }}" 
                                       maxlength="10" placeholder="BK">
                            </div>
                            
                            <div class="col-12">
                                <h5 class="mb-3 mt-4">Invoice Defaults</h5>
                            </div>
                            
                            <div class="col-md-12">
                                <label class="form-label">Invoice Footer Note</label>
                                <textarea name="invoice_footer_note" class="form-control" rows="3">{{ old('invoice_footer_note', $settings->invoice_footer_note) }}</textarea>
                                <small class="text-muted">This note will appear at the bottom of invoices</small>
                            </div>
                            
                            <div class="col-md-12">
                                <label class="form-label">Invoice Terms & Conditions</label>
                                <textarea name="invoice_terms" class="form-control" rows="5">{{ old('invoice_terms', $settings->invoice_terms) }}</textarea>
                                <small class="text-muted">Default terms and conditions for invoices</small>
                            </div>
                            
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ri-save-line me-1"></i>Save Settings
                                </button>
                                <a href="{{ route('admin.settings') }}" class="btn btn-outline-secondary">
                                    <i class="ri-arrow-left-line me-1"></i>Back to Settings
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

