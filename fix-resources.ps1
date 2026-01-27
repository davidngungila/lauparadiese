# Fix All Missing Resources
$ErrorActionPreference = "Continue"
$ProgressPreference = "SilentlyContinue"

Write-Host "=== Downloading Missing Resources ===" -ForegroundColor Cyan

# Ensure directories exist
$dirs = @(
    "public\js\vendor\alpine",
    "public\css\webfonts",
    "public\images"
)
foreach ($dir in $dirs) {
    if (-not (Test-Path $dir)) {
        New-Item -ItemType Directory -Force -Path $dir | Out-Null
        Write-Host "Created: $dir" -ForegroundColor Green
    }
}

# Download Alpine.js files
Write-Host "`n--- Alpine.js Files ---" -ForegroundColor Yellow
$alpineFiles = @{
    "alpine.min.js" = "https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js"
    "persist.min.js" = "https://cdn.jsdelivr.net/npm/@alpinejs/persist@3.13.5/dist/cdn.min.js"
    "collapse.min.js" = "https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.13.5/dist/cdn.min.js"
}

foreach ($file in $alpineFiles.Keys) {
    $path = "public\js\vendor\alpine\$file"
    if (Test-Path $path) {
        Write-Host "  ✓ $file already exists" -ForegroundColor Gray
    } else {
        try {
            $url = $alpineFiles[$file]
            Write-Host "  Downloading $file..." -NoNewline
            Invoke-WebRequest -Uri $url -OutFile $path -UseBasicParsing -ErrorAction Stop
            Write-Host " ✓" -ForegroundColor Green
        } catch {
            Write-Host " ✗ Failed: $($_.Exception.Message)" -ForegroundColor Red
        }
    }
}

# Download Font Awesome fonts
Write-Host "`n--- Font Awesome Fonts ---" -ForegroundColor Yellow
$fontFiles = @(
    "fa-brands-400.woff2",
    "fa-regular-400.woff2",
    "fa-solid-900.woff2",
    "fa-brands-400.ttf",
    "fa-regular-400.ttf",
    "fa-solid-900.ttf"
)

$baseUrl = "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/webfonts"
foreach ($font in $fontFiles) {
    $path = "public\css\webfonts\$font"
    if (Test-Path $path) {
        Write-Host "  ✓ $font already exists" -ForegroundColor Gray
    } else {
        try {
            $url = "$baseUrl/$font"
            Write-Host "  Downloading $font..." -NoNewline
            Invoke-WebRequest -Uri $url -OutFile $path -UseBasicParsing -ErrorAction Stop
            Write-Host " ✓" -ForegroundColor Green
        } catch {
            Write-Host " ✗ Failed: $($_.Exception.Message)" -ForegroundColor Red
        }
    }
}

# Download featured-tour.jpg
Write-Host "`n--- Images ---" -ForegroundColor Yellow
$imagePath = "public\images\featured-tour.jpg"
if (Test-Path $imagePath) {
    Write-Host "  ✓ featured-tour.jpg already exists" -ForegroundColor Gray
} else {
    try {
        $imageUrl = "https://images.unsplash.com/photo-1589834390005-5d4fb9bf3d32?ixlib=rb-4.0.3&auto=format&fit=crop&w=1587&q=80"
        Write-Host "  Downloading featured-tour.jpg..." -NoNewline
        Invoke-WebRequest -Uri $imageUrl -OutFile $imagePath -UseBasicParsing -ErrorAction Stop
        Write-Host " ✓" -ForegroundColor Green
    } catch {
        Write-Host " ✗ Failed: $($_.Exception.Message)" -ForegroundColor Red
        # Create a simple placeholder
        Write-Host "  Creating placeholder image..." -NoNewline
        $bytes = [System.Text.Encoding]::UTF8.GetBytes("Placeholder")
        [System.IO.File]::WriteAllBytes($imagePath, $bytes)
        Write-Host " ✓" -ForegroundColor Yellow
    }
}

Write-Host "`n=== Verification ===" -ForegroundColor Cyan
$allFiles = @(
    "public\js\vendor\alpine\alpine.min.js",
    "public\js\vendor\alpine\persist.min.js",
    "public\js\vendor\alpine\collapse.min.js",
    "public\css\webfonts\fa-brands-400.woff2",
    "public\css\webfonts\fa-regular-400.woff2",
    "public\css\webfonts\fa-solid-900.woff2",
    "public\images\featured-tour.jpg"
)

$missing = @()
foreach ($file in $allFiles) {
    if (Test-Path $file) {
        $size = (Get-Item $file).Length
        Write-Host "  ✓ $file ($([math]::Round($size/1KB, 2)) KB)" -ForegroundColor Green
    } else {
        Write-Host "  ✗ $file MISSING" -ForegroundColor Red
        $missing += $file
    }
}

if ($missing.Count -eq 0) {
    Write-Host "`n✓ All resources downloaded successfully!" -ForegroundColor Green
} else {
    Write-Host "`n✗ $($missing.Count) file(s) still missing" -ForegroundColor Red
}






