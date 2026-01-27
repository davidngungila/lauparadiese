<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Document') - {{ \App\Models\OrganizationSetting::getSettings()->organization_name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.6;
            background: #fff;
        }
        
        .pdf-container {
            max-width: 210mm;
            margin: 0 auto;
            padding: 20mm;
            background: #fff;
        }
        
        /* Header Styles - Invoice Style */
        .pdf-header {
            padding-bottom: 20px;
            margin-bottom: 30px;
            border-bottom: 2px solid #e5e7eb;
        }
        
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }
        
        .company-info {
            flex: 1;
        }
        
        .company-logo {
            font-size: 28px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 8px;
        }
        
        .company-name {
            font-size: 20px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 5px;
        }
        
        .company-details {
            font-size: 11px;
            color: #666;
            line-height: 1.6;
        }
        
        .document-info {
            text-align: right;
        }
        
        .document-type {
            font-size: 18px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 8px;
        }
        
        .document-number {
            font-size: 13px;
            color: #666;
            margin-bottom: 5px;
        }
        
        .document-date {
            font-size: 11px;
            color: #666;
        }
        
        /* Invoice To / Bill To Section */
        .billing-section {
            display: flex;
            justify-content: space-between;
            margin: 25px 0;
            flex-wrap: wrap;
        }
        
        .billing-box {
            flex: 1;
            min-width: 200px;
            margin-right: 20px;
        }
        
        .billing-box:last-child {
            margin-right: 0;
        }
        
        .billing-title {
            font-size: 13px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 10px;
        }
        
        .billing-content {
            font-size: 11px;
            color: #333;
            line-height: 1.8;
        }
        
        .billing-table {
            font-size: 11px;
        }
        
        .billing-table td {
            padding: 3px 0;
        }
        
        .billing-table td:first-child {
            padding-right: 15px;
            color: #666;
        }
        
        /* Content Styles */
        .pdf-content {
            margin: 20px 0;
        }
        
        .section {
            margin-bottom: 25px;
        }
        
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-label {
            display: table-cell;
            font-weight: bold;
            color: #555;
            padding: 6px 15px 6px 0;
            width: 35%;
            vertical-align: top;
        }
        
        .info-value {
            display: table-cell;
            padding: 6px 0;
            color: #333;
        }
        
        /* Table Styles - Invoice Style */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            border: 1px solid #e5e7eb;
        }
        
        .data-table thead {
            background-color: #f9fafb;
        }
        
        .data-table th {
            padding: 12px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
            color: #374151;
            border-bottom: 2px solid #e5e7eb;
        }
        
        .data-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 11px;
        }
        
        .data-table tbody tr:last-child td {
            border-bottom: none;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        /* Summary Box - Invoice Style */
        .summary-section {
            margin-top: 20px;
            display: flex;
            justify-content: flex-end;
        }
        
        .summary-box {
            width: 300px;
            border: 1px solid #e5e7eb;
            border-radius: 5px;
            padding: 15px;
            background: #f9fafb;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .summary-row:last-child {
            border-bottom: none;
            font-weight: bold;
            font-size: 14px;
            padding-top: 12px;
            margin-top: 5px;
            border-top: 2px solid #2563eb;
        }
        
        .summary-label {
            color: #666;
        }
        
        .summary-value {
            font-weight: bold;
            color: #333;
        }
        
        .total-row {
            color: #2563eb;
            font-size: 16px;
        }
        
        /* Notes and Terms */
        .notes-section {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 12px 15px;
            margin: 20px 0;
        }
        
        .notes-title {
            font-weight: bold;
            color: #92400e;
            margin-bottom: 8px;
        }
        
        .terms-section {
            background-color: #f3f4f6;
            border: 1px solid #d1d5db;
            padding: 15px;
            margin: 20px 0;
            font-size: 10px;
        }
        
        .terms-title {
            font-weight: bold;
            color: #374151;
            margin-bottom: 10px;
        }
        
        /* Footer Styles */
        .pdf-footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            font-size: 9px;
            color: #666;
        }
        
        .footer-info {
            margin-bottom: 5px;
        }
        
        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .status-sent {
            background-color: #dbeafe;
            color: #1e40af;
        }
        
        .status-accepted {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .status-rejected {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .status-expired {
            background-color: #f3f4f6;
            color: #6b7280;
        }
        
        .status-pending-payment {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .status-confirmed {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .status-cancelled {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .status-completed {
            background-color: #dbeafe;
            color: #1e40af;
        }
        
        /* Page Break */
        .page-break {
            page-break-before: always;
        }
        
        /* Utility Classes */
        .mb-10 { margin-bottom: 10px; }
        .mb-15 { margin-bottom: 15px; }
        .mb-20 { margin-bottom: 20px; }
        .mt-10 { margin-top: 10px; }
        .mt-15 { margin-top: 15px; }
        .mt-20 { margin-top: 20px; }
        
        .text-primary { color: #2563eb; }
        .text-success { color: #059669; }
        .text-danger { color: #dc2626; }
        .text-warning { color: #d97706; }
        
        .font-bold { font-weight: bold; }
        .font-normal { font-weight: normal; }
    </style>
    @stack('styles')
</head>
<body>
    <div class="pdf-container">
        <!-- Header -->
        <div class="pdf-header">
            <div class="header-top">
                <div class="company-info">
                    @php
                        $org = \App\Models\OrganizationSetting::getSettings();
                    @endphp
                    <div class="company-logo">🌍</div>
                    <div class="company-name">{{ $org->organization_name }}</div>
                    <div class="company-details">
                        @if($org->address)
                            {{ $org->address }}<br>
                        @endif
                        @if($org->city || $org->state || $org->country)
                            {{ trim(implode(', ', array_filter([$org->city, $org->state, $org->country]))) }}<br>
                        @endif
                        @if($org->postal_code)
                            {{ $org->postal_code }}<br>
                        @endif
                        @if($org->phone)
                            Phone: {{ $org->phone }}
                            @if($org->email) | @endif
                        @endif
                        @if($org->email)
                            Email: {{ $org->email }}
                        @endif
                        @if($org->website)
                            <br>Website: {{ $org->website }}
                        @endif
                    </div>
                </div>
                <div class="document-info">
                    <div class="document-type">@yield('document-type', 'Document')</div>
                    @hasSection('document-number')
                    <div class="document-number">@yield('document-number')</div>
                    @endif
                    @hasSection('document-date')
                    <div class="document-date">@yield('document-date', date('F d, Y'))</div>
                    @endif
                    @hasSection('document-status')
                    <div style="margin-top: 10px;">
                        <span class="status-badge status-@yield('document-status')">
                            @yield('document-status')
                        </span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Content -->
        <div class="pdf-content">
            @yield('content')
        </div>
        
        <!-- Footer -->
        <div class="pdf-footer">
            <div class="footer-info">
                <strong>{{ $org->organization_name }}</strong>
                @if($org->invoice_footer_note)
                    | {{ $org->invoice_footer_note }}
                @endif
            </div>
            <div class="footer-info">
                This document was generated on {{ date('F d, Y \a\t h:i A') }}
            </div>
            @hasSection('footer-extra')
            <div class="footer-info">
                @yield('footer-extra')
            </div>
            @endif
        </div>
    </div>
</body>
</html>
