# Download All Missing Resources
$ErrorActionPreference = "Stop"
$ProgressPreference = "SilentlyContinue"

$baseDir = "public"
$webClient = New-Object System.Net.WebClient

# Create directories
Write-Host "Creating directories..." -ForegroundColor Cyan
@(
    "$baseDir\js\vendor\alpine",
    "$baseDir\css\webfonts",
    "$baseDir\images"
) | ForEach-Object {
    New-Item -ItemType Directory -Force -Path $_ | Out-Null
    Write-Host "  Created: $_" -ForegroundColor Green
}

# Download Alpine.js files
Write-Host "`nDownloading Alpine.js files..." -ForegroundColor Cyan
$alpineFiles = @{
    "https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js" = "$baseDir\js\vendor\alpine\alpine.min.js"
    "https://cdn.jsdelivr.net/npm/@alpinejs/persist@3.13.5/dist/cdn.min.js" = "$baseDir\js\vendor\alpine\persist.min.js"
    "https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.13.5/dist/cdn.min.js" = "$baseDir\js\vendor\alpine\collapse.min.js"
}

foreach ($url in $alpineFiles.Keys) {
    $file = $alpineFiles[$url]
    try {
        Write-Host "  Downloading: $(Split-Path $file -Leaf)..." -NoNewline
        $webClient.DownloadFile($url, $file)
        Write-Host " OK" -ForegroundColor Green
    } catch {
        Write-Host " FAILED: $($_.Exception.Message)" -ForegroundColor Red
    }
}

# Download Font Awesome fonts
Write-Host "`nDownloading Font Awesome fonts..." -ForegroundColor Cyan
$fontFiles = @(
    "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/webfonts/fa-brands-400.woff2",
    "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/webfonts/fa-regular-400.woff2",
    "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/webfonts/fa-solid-900.woff2",
    "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/webfonts/fa-brands-400.ttf",
    "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/webfonts/fa-regular-400.ttf",
    "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/webfonts/fa-solid-900.ttf"
)

foreach ($url in $fontFiles) {
    $fileName = Split-Path $url -Leaf
    $filePath = "$baseDir\css\webfonts\$fileName"
    try {
        Write-Host "  Downloading: $fileName..." -NoNewline
        $webClient.DownloadFile($url, $filePath)
        Write-Host " OK" -ForegroundColor Green
    } catch {
        Write-Host " FAILED: $($_.Exception.Message)" -ForegroundColor Red
    }
}

# Download featured-tour.jpg placeholder
Write-Host "`nDownloading featured-tour.jpg..." -ForegroundColor Cyan
$imageUrl = "https://images.unsplash.com/photo-1589834390005-5d4fb9bf3d32?ixlib=rb-4.0.3&auto=format&fit=crop&w=1587&q=80"
$imagePath = "$baseDir\images\featured-tour.jpg"
try {
    Write-Host "  Downloading: featured-tour.jpg..." -NoNewline
    $webClient.DownloadFile($imageUrl, $imagePath)
    Write-Host " OK" -ForegroundColor Green
} catch {
    Write-Host " FAILED: $($_.Exception.Message)" -ForegroundColor Red
    # Create a placeholder if download fails
    Write-Host "  Creating placeholder image..." -NoNewline
    $placeholder = [System.Drawing.Bitmap]::new(800, 600)
    $graphics = [System.Drawing.Graphics]::FromImage($placeholder)
    $graphics.Clear([System.Drawing.Color]::LightGray)
    $font = New-Object System.Drawing.Font("Arial", 24)
    $brush = New-Object System.Drawing.SolidBrush([System.Drawing.Color]::DarkGray)
    $graphics.DrawString("Featured Tour", $font, $brush, 300, 280)
    $placeholder.Save($imagePath, [System.Drawing.Imaging.ImageFormat]::Jpeg)
    $graphics.Dispose()
    $placeholder.Dispose()
    Write-Host " OK" -ForegroundColor Green
}

$webClient.Dispose()
Write-Host "`nDone! All resources downloaded." -ForegroundColor Green






