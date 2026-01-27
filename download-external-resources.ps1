# Download External Resources Script
# This script downloads all external resources and stores them locally

$basePath = "C:\laragon\www\tour\Tour\public"

# Create directories
New-Item -ItemType Directory -Force -Path "$basePath\js\vendor\alpine" | Out-Null
New-Item -ItemType Directory -Force -Path "$basePath\images" | Out-Null
New-Item -ItemType Directory -Force -Path "$basePath\css\webfonts" | Out-Null

Write-Host "Downloading Alpine.js and plugins..."

# Download Alpine.js
$alpineUrl = "https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js"
$alpinePath = "$basePath\js\vendor\alpine\alpine.min.js"
if (-not (Test-Path $alpinePath)) {
    Invoke-WebRequest -Uri $alpineUrl -OutFile $alpinePath
    Write-Host "✓ Downloaded Alpine.js"
} else {
    Write-Host "✓ Alpine.js already exists"
}

# Download Alpine Persist plugin
$persistUrl = "https://cdn.jsdelivr.net/npm/@alpinejs/persist@3.13.5/dist/cdn.min.js"
$persistPath = "$basePath\js\vendor\alpine\persist.min.js"
if (-not (Test-Path $persistPath)) {
    Invoke-WebRequest -Uri $persistUrl -OutFile $persistPath
    Write-Host "✓ Downloaded Alpine Persist plugin"
} else {
    Write-Host "✓ Alpine Persist plugin already exists"
}

# Download Alpine Collapse plugin
$collapseUrl = "https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.13.5/dist/cdn.min.js"
$collapsePath = "$basePath\js\vendor\alpine\collapse.min.js"
if (-not (Test-Path $collapsePath)) {
    Invoke-WebRequest -Uri $collapseUrl -OutFile $collapsePath
    Write-Host "✓ Downloaded Alpine Collapse plugin"
} else {
    Write-Host "✓ Alpine Collapse plugin already exists"
}

Write-Host "`nDownloading Font Awesome fonts..."

# Download Font Awesome fonts
$faFonts = @(
    @{Url="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/webfonts/fa-brands-400.woff2"; File="fa-brands-400.woff2"},
    @{Url="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/webfonts/fa-regular-400.woff2"; File="fa-regular-400.woff2"},
    @{Url="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/webfonts/fa-solid-900.woff2"; File="fa-solid-900.woff2"}
)

foreach ($font in $faFonts) {
    $fontPath = "$basePath\css\webfonts\$($font.File)"
    if (-not (Test-Path $fontPath)) {
        Invoke-WebRequest -Uri $font.Url -OutFile $fontPath
        Write-Host "✓ Downloaded $($font.File)"
    } else {
        Write-Host "✓ $($font.File) already exists"
    }
}

Write-Host "`nDownloading featured tour image..."

# Download featured tour image
$imageUrl = "https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&q=80"
$imagePath = "$basePath\images\featured-tour.jpg"
if (-not (Test-Path $imagePath)) {
    Invoke-WebRequest -Uri $imageUrl -OutFile $imagePath
    Write-Host "✓ Downloaded featured-tour.jpg"
} else {
    Write-Host "✓ featured-tour.jpg already exists"
}

Write-Host "`n✓ All resources downloaded successfully!"






