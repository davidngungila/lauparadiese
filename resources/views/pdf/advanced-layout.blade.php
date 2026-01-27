<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $documentTitle ?? 'DOCUMENT' }} - {{ \App\Models\OrganizationSetting::getSettings()->organization_name ?? 'Lau Paradise Adventures' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #343a40;
            line-height: 1.6;
            background: #fff;
        }
        
        .pdf-container {
            max-width: 210mm;
            margin: 0 auto;
            padding: 15mm 20mm;
            background: #fff;
        }
        
        /* Header Image Section */
        .pdf-header-image {
            margin-bottom: 20px;
            width: 100%;
        }
        
        .pdf-header-image img {
            width: 100%;
            height: auto;
            display: block;
            max-height: 150px;
            object-fit: contain;
        }
        
        .header-image-placeholder {
            width: 100%;
            height: 150px;
            background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);
            border: 2px dashed #d1d5db;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            color: #9ca3af;
            text-align: center;
            padding: 20px;
        }
        
        /* Document Header Section */
        .document-header {
            margin-bottom: 25px;
            border-bottom: 3px solid {{ $mainColor ?? '#3ea572' }};
            padding-bottom: 15px;
        }
        
        .document-header table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .document-header td {
            vertical-align: top;
        }
        
        .document-title {
            font-size: 24px;
            font-weight: bold;
            color: {{ $mainColor ?? '#3ea572' }};
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .document-reference {
            font-size: 12px;
            color: #6c757d;
            margin-top: 5px;
        }
        
        .document-reference strong {
            color: #343a40;
        }
        
        .document-reference .ref-value {
            color: {{ $mainColor ?? '#3ea572' }};
            font-weight: 600;
        }
        
        .document-date-info {
            text-align: right;
        }
        
        .document-date {
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 5px;
        }
        
        .document-date strong {
            color: #343a40;
        }
        
        .document-date .date-value {
            color: {{ $darkBlue ?? '#2d7a5f' }};
            font-weight: 600;
        }
        
        .system-name {
            font-size: 11px;
            color: #999;
            margin-top: 5px;
            font-style: italic;
        }
        
        /* Logo Section */
        .logo-section {
            margin-bottom: 20px;
            text-align: center;
        }
        
        .logo-section img {
            max-height: 80px;
            max-width: 250px;
            height: auto;
            width: auto;
            object-fit: contain;
        }
        
        /* Content Section */
        .pdf-content {
            margin: 20px 0;
            min-height: 300px;
        }
        
        /* Billing/Info Boxes */
        .billing-section {
            display: flex;
            justify-content: space-between;
            margin: 25px 0;
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .billing-box {
            flex: 1;
            min-width: 200px;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
        }
        
        .billing-title {
            font-size: 13px;
            font-weight: bold;
            color: {{ $mainColor ?? '#3ea572' }};
            margin-bottom: 10px;
            padding-bottom: 8px;
            border-bottom: 2px solid {{ $mainColor ?? '#3ea572' }};
        }
        
        .billing-content {
            font-size: 11px;
            color: #343a40;
            line-height: 1.8;
        }
        
        .billing-content p {
            margin: 5px 0;
        }
        
        /* Data Tables */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            border: 1px solid #dee2e6;
            font-size: 11px;
        }
        
        .data-table thead {
            background: linear-gradient(135deg, {{ $mainColor ?? '#3ea572' }} 0%, {{ $darkBlue ?? '#2d7a5f' }} 100%);
            color: #fff;
        }
        
        .data-table th {
            padding: 12px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
            color: #fff;
            border-bottom: 2px solid #fff;
        }
        
        .data-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #dee2e6;
            font-size: 11px;
        }
        
        .data-table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .data-table tbody tr:last-child td {
            border-bottom: none;
        }
        
        .data-table tbody tr:hover {
            background-color: #f3f4f6;
        }
        
        /* Summary Box */
        .summary-section {
            margin-top: 20px;
            display: flex;
            justify-content: flex-end;
        }
        
        .summary-box {
            width: 300px;
            border: 2px solid {{ $mainColor ?? '#3ea572' }};
            border-radius: 5px;
            padding: 15px;
            background: #fff;
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
            border-top: 2px solid {{ $mainColor ?? '#3ea572' }};
            color: {{ $mainColor ?? '#3ea572' }};
        }
        
        .summary-label {
            color: #6c757d;
        }
        
        .summary-value {
            font-weight: bold;
            color: #343a40;
        }
        
        /* Section Styles */
        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: {{ $mainColor ?? '#3ea572' }};
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 2px solid {{ $mainColor ?? '#3ea572' }};
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .section-subtitle {
            font-size: 13px;
            font-weight: bold;
            color: {{ $darkBlue ?? '#2d7a5f' }};
            margin-bottom: 10px;
            margin-top: 15px;
        }
        
        /* Info Grid */
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
            color: #6c757d;
            padding: 6px 15px 6px 0;
            width: 35%;
            vertical-align: top;
        }
        
        .info-value {
            display: table-cell;
            padding: 6px 0;
            color: #343a40;
        }
        
        /* Notes and Terms */
        .notes-section {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border-left: 4px solid #f59e0b;
            padding: 12px 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        
        .notes-title {
            font-weight: bold;
            color: #92400e;
            margin-bottom: 8px;
            font-size: 12px;
        }
        
        .notes-content {
            font-size: 11px;
            color: #78350f;
            line-height: 1.6;
        }
        
        .terms-section {
            background-color: #f3f4f6;
            border: 1px solid #d1d5db;
            padding: 15px;
            margin: 20px 0;
            font-size: 10px;
            border-radius: 4px;
        }
        
        .terms-title {
            font-weight: bold;
            color: #374151;
            margin-bottom: 10px;
            font-size: 11px;
        }
        
        .terms-content {
            color: #4b5563;
            line-height: 1.6;
        }
        
        /* Status Badges */
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
        
        .status-confirmed {
            background-color: #e6f4ed;
            color: #2d7a5f;
        }
        
        .status-cancelled {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .status-completed {
            background-color: #dbeafe;
            color: #1e40af;
        }
        
        /* Utility Classes */
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-left {
            text-align: left;
        }
        
        .mb-10 { margin-bottom: 10px; }
        .mb-15 { margin-bottom: 15px; }
        .mb-20 { margin-bottom: 20px; }
        .mb-25 { margin-bottom: 25px; }
        .mt-10 { margin-top: 10px; }
        .mt-15 { margin-top: 15px; }
        .mt-20 { margin-top: 20px; }
        .mt-25 { margin-top: 25px; }
        
        .text-primary { color: {{ $mainColor ?? '#3ea572' }}; }
        .text-secondary { color: {{ $darkBlue ?? '#2d7a5f' }}; }
        .text-success { color: #2d7a5f; }
        .text-danger { color: #dc2626; }
        .text-warning { color: #6cbe8f; }
        
        .font-bold { font-weight: bold; }
        .font-normal { font-weight: normal; }
        
        /* Page Break */
        .page-break {
            page-break-before: always;
        }
        
        .page-break-inside-avoid {
            page-break-inside: avoid;
        }
        
        /* Generated Footer */
        .generated-footer {
            margin-top: 30px;
            padding-top: 15px;
            font-size: 9px;
            color: #6c757d;
            text-align: center;
            page-break-inside: avoid;
            border-top: 1px solid #dee2e6;
        }
        
        .generated-footer p {
            margin: 0;
            font-style: italic;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="pdf-container">
        @php
            // Get organization settings
            $orgSettings = \App\Models\OrganizationSetting::getSettings();
            $systemName = $orgSettings->organization_name ?? 'Lau Paradise Adventures';
            
            // Logo path
            $logoPath = public_path('lau-adventuress.png');
            $logoSrc = null;
            
            // Load and convert logo to base64 for PDF compatibility
            if (file_exists($logoPath) && is_file($logoPath) && is_readable($logoPath)) {
                try {
                    $imageData = file_get_contents($logoPath);
                    if ($imageData !== false) {
                        $imageInfo = getimagesize($logoPath);
                        $mimeType = $imageInfo['mime'] ?? 'image/png';
                        $imageBase64 = base64_encode($imageData);
                        $logoSrc = 'data:' . $mimeType . ';base64,' . $imageBase64;
                    }
                } catch (\Exception $e) {
                    \Log::warning('Failed to load logo for PDF: ' . $e->getMessage());
                    $logoSrc = null;
                }
            }
            
            // Header image path (optional)
            $headerImagePath = public_path('assets/img/LETTER COVER HEADER-01.jpg');
            $headerImageSrc = null;
            
            // Load and convert header image to base64 for PDF compatibility
            if (file_exists($headerImagePath) && is_file($headerImagePath) && is_readable($headerImagePath)) {
                try {
                    $imageData = file_get_contents($headerImagePath);
                    if ($imageData !== false) {
                        $imageBase64 = base64_encode($imageData);
                        $headerImageSrc = 'data:image/jpeg;base64,' . $imageBase64;
                    }
                } catch (\Exception $e) {
                    \Log::warning('Failed to load header image for PDF: ' . $e->getMessage());
                    $headerImageSrc = null;
                }
            }
            
            // Get document information
            $documentTitle = $documentTitle ?? 'DOCUMENT';
            $documentRef = $documentRef ?? 'N/A';
            $documentDate = $documentDate ?? now()->setTimezone(config('app.timezone', 'Africa/Dar_es_Salaam'))->format('d M Y');
            $mainColor = $mainColor ?? '#3ea572';
            $darkBlue = $darkBlue ?? '#2d7a5f';
        @endphp
        
        <!-- Header Image (Optional) -->
        @if(isset($headerImageSrc) && $headerImageSrc)
        <div class="pdf-header-image">
            <img src="{{ $headerImageSrc }}" alt="Header" style="width: 100%; height: auto; display: block;">
        </div>
        @endif
        
        <!-- Organization Details Header -->
        @include('components.pdf-header', [
            'documentTitle' => $documentTitle ?? 'DOCUMENT',
            'documentRef' => $documentRef ?? 'N/A',
            'documentDate' => $documentDate ?? now()->setTimezone(config('app.timezone', 'Africa/Dar_es_Salaam'))->format('d M Y'),
            'mainColor' => $mainColor ?? '#3ea572'
        ])
        
        <!-- Content -->
        <div class="pdf-content">
            @yield('content')
        </div>
        
        <!-- Generated Disclaimer -->
        @include('components.pdf-disclaimer')
    </div>
    
    <!-- DomPDF Footer Script -->
    @include('components.pdf-footer')
</body>
</html>




