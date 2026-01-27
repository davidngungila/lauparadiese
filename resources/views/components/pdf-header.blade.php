@php
use App\Models\OrganizationSetting;
use Illuminate\Support\Facades\Storage;

$orgSettings = OrganizationSetting::getSettings();
$systemName = config('app.name', 'Lau Paradise Adventures');

// Get organization info - ensure all values are strings, not arrays
$companyName = is_array($orgSettings->organization_name ?? null) 
    ? config('app.name', 'Company Name') 
    : (string)($orgSettings->organization_name ?? config('app.name', 'Company Name'));

$companyAddress = is_array($orgSettings->address ?? null) ? '' : (string)($orgSettings->address ?? '');
$companyCity = is_array($orgSettings->city ?? null) ? '' : (string)($orgSettings->city ?? '');
$companyState = is_array($orgSettings->state ?? null) ? '' : (string)($orgSettings->state ?? '');
$companyCountry = is_array($orgSettings->country ?? null) ? 'Tanzania' : (string)($orgSettings->country ?? 'Tanzania');
$companyPostalCode = is_array($orgSettings->postal_code ?? null) ? '' : (string)($orgSettings->postal_code ?? '');

// Build full address - ensure all components are strings
$addressParts = array_filter([
    $companyAddress,
    $companyCity,
    $companyState,
    $companyPostalCode,
    $companyCountry
], function($part) {
    return !empty($part) && is_string($part);
});

$fullAddress = trim(implode(', ', $addressParts));

$companyPhone = is_array($orgSettings->phone ?? null) ? '' : (string)($orgSettings->phone ?? '');
$companyEmail = is_array($orgSettings->email ?? null) ? '' : (string)($orgSettings->email ?? '');
$companyWebsite = is_array($orgSettings->website ?? null) ? '' : (string)($orgSettings->website ?? '');
$companyTaxId = is_array($orgSettings->tax_id ?? null) ? '' : (string)($orgSettings->tax_id ?? '');

// Logo handling - try multiple paths
$logoPath = null;
$logoSrc = null;
$possiblePaths = [];

// First, try logo_url from organization settings
if ($orgSettings && $orgSettings->logo_url) {
    $logoUrl = $orgSettings->logo_url;
    
    // If it's a URL, try to download or use directly
    if (filter_var($logoUrl, FILTER_VALIDATE_URL)) {
        $possiblePaths[] = $logoUrl; // Will be used as URL
    } else {
        // Treat as file path
        $possiblePaths[] = storage_path('app/public/' . $logoUrl);
        $possiblePaths[] = public_path('storage/' . $logoUrl);
        $possiblePaths[] = public_path($logoUrl);
    }
}

// Fallback: Check for default logo in assets folder
$defaultLogoPaths = [
    public_path('lau-adventuress.png'),
    public_path('assets/img/logo.png'),
    public_path('assets/img/company-logo.png'),
    public_path('images/logo.png'),
    public_path('image.png'),
];

$possiblePaths = array_merge($possiblePaths, $defaultLogoPaths);
$possiblePaths = array_filter(array_unique($possiblePaths));

// Find the first existing logo file or use URL
foreach ($possiblePaths as $path) {
    if (filter_var($path, FILTER_VALIDATE_URL)) {
        // It's a URL, use it directly
        $logoSrc = $path;
        break;
    } elseif ($path && file_exists($path) && is_file($path) && is_readable($path)) {
        // Verify it's actually an image file
        $imageInfo = @getimagesize($path);
        if ($imageInfo !== false) {
            $logoPath = $path;
            break;
        }
    }
}

// Convert local file to base64 for PDF compatibility
if ($logoPath && !filter_var($logoPath, FILTER_VALIDATE_URL)) {
    try {
        $logoData = file_get_contents($logoPath);
        if ($logoData !== false) {
            $logoBase64 = base64_encode($logoData);
            $logoExtension = strtolower(pathinfo($logoPath, PATHINFO_EXTENSION));
            
            // Map extensions to MIME types
            $mimeTypes = [
                'jpg' => 'jpeg',
                'jpeg' => 'jpeg',
                'png' => 'png',
                'gif' => 'gif',
                'webp' => 'webp',
                'svg' => 'svg+xml'
            ];
            
            $mimeType = $mimeTypes[$logoExtension] ?? 'jpeg';
            $logoSrc = 'data:image/' . $mimeType . ';base64,' . $logoBase64;
        }
    } catch (\Exception $e) {
        \Log::warning('Failed to load logo for PDF: ' . $e->getMessage());
        $logoSrc = null;
    }
}

// Get document details - ensure all are strings
$documentTitle = is_array($documentTitle ?? null) ? 'Document' : (string)($documentTitle ?? 'Document');
$documentRef = is_array($documentRef ?? null) ? null : ($documentRef ?? null);
if ($documentRef !== null && !is_string($documentRef)) {
    $documentRef = (string)$documentRef;
}

// Safely get timezone and date format
$headerTimezone = $orgSettings->timezone ?? config('app.timezone', 'Africa/Dar_es_Salaam');
if (is_array($headerTimezone)) {
    $headerTimezone = config('app.timezone', 'Africa/Dar_es_Salaam');
} else {
    $headerTimezone = (string)$headerTimezone;
}

$headerDateFormat = $orgSettings->date_format ?? 'd M Y';
if (is_array($headerDateFormat)) {
    $headerDateFormat = 'd M Y';
} else {
    $headerDateFormat = (string)$headerDateFormat;
}

$documentDate = is_array($documentDate ?? null) 
    ? now()->setTimezone($headerTimezone)->format($headerDateFormat) 
    : ($documentDate ?? now()->setTimezone($headerTimezone)->format($headerDateFormat));

// Main color - can be overridden (using green theme)
$mainColor = $mainColor ?? '#3ea572';
@endphp

<div class="pdf-header" style="border-bottom: 3px solid {{ $mainColor }}; padding-bottom: 15px; margin-bottom: 20px;">
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="width: 20%; vertical-align: top; text-align: left;">
                @if(isset($logoSrc) && $logoSrc)
                <img src="{{ $logoSrc }}" alt="Company Logo" style="max-width: 120px; max-height: 120px; height: auto; width: auto;">
                @else
                <div style="width: 100px; height: 100px; background-color: #f0f0f0; border: 1px solid #ddd; display: flex; align-items: center; justify-content: center; font-size: 9px; color: #999;">
                    No Logo
                </div>
                @endif
            </td>
            <td style="width: 60%; vertical-align: top; text-align: center;">
                <h1 style="color: {{ $mainColor }}; margin: 0; font-size: 22px; font-weight: bold;">{{ htmlspecialchars($companyName) }}</h1>
                <div style="margin-top: 8px; font-size: 10px; color: #555; line-height: 1.4;">
                    @if($fullAddress)
                    <div>{{ htmlspecialchars($fullAddress) }}</div>
                    @endif
                    <div style="margin-top: 3px;">
                        @if($companyPhone)
                        {{ htmlspecialchars($companyPhone) }}
                        @endif
                        @if($companyEmail)
                            @if($companyPhone) | @endif
                             {{ htmlspecialchars($companyEmail) }}
                        @endif
                        @if($companyWebsite)
                            @if($companyPhone || $companyEmail) | @endif
                           {{ htmlspecialchars($companyWebsite) }}
                        @endif
                    </div>
                    @if($companyTaxId)
                    <div style="margin-top: 3px;">TIN: {{ htmlspecialchars($companyTaxId) }}</div>
                    @endif
                   {{--  @if($orgSettings->registration_number)
                    <div style="margin-top: 3px;">Reg No: {{ htmlspecialchars($orgSettings->registration_number) }}</div>
                    @endif --}}
                </div>
            </td>
            <td style="width: 20%; vertical-align: top; text-align: right;">
                <div style="font-size: 10px; color: #666;">
                    <div><strong>Date:</strong></div>
                    <div style="margin-top: 3px;">{{ $documentDate }}</div>
                </div>
            </td>
        </tr>
    </table>
    
    <div style="margin-top: 15px; padding: 8px; background-color: {{ $mainColor }}; color: white; text-align: center; font-weight: bold; font-size: 14px;">
        {{ htmlspecialchars($documentTitle) }}
        @if($documentRef)
            | Ref: {{ htmlspecialchars($documentRef) }}
        @endif
    </div>
</div>

