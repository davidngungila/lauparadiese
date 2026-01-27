# PowerShell script to download vendor assets
# Run this script to download all external dependencies locally

$ErrorActionPreference = "Stop"

Write-Host "Downloading vendor assets..." -ForegroundColor Green

# Create directories
New-Item -ItemType Directory -Force -Path "public\css\vendor" | Out-Null
New-Item -ItemType Directory -Force -Path "public\js\vendor" | Out-Null
New-Item -ItemType Directory -Force -Path "public\fonts\fontawesome" | Out-Null

# Download Swiper CSS
Write-Host "Downloading Swiper CSS..." -ForegroundColor Yellow
Invoke-WebRequest -Uri "https://unpkg.com/swiper@10/swiper-bundle.min.css" -OutFile "public\css\vendor\swiper-bundle.min.css"

# Download AOS CSS
Write-Host "Downloading AOS CSS..." -ForegroundColor Yellow
Invoke-WebRequest -Uri "https://unpkg.com/aos@2.3.1/dist/aos.css" -OutFile "public\css\vendor\aos.css"

# Download Font Awesome CSS
Write-Host "Downloading Font Awesome CSS..." -ForegroundColor Yellow
Invoke-WebRequest -Uri "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" -OutFile "public\css\vendor\fontawesome.min.css"

# Download Swiper JS
Write-Host "Downloading Swiper JS..." -ForegroundColor Yellow
Invoke-WebRequest -Uri "https://unpkg.com/swiper@10/swiper-bundle.min.js" -OutFile "public\js\vendor\swiper-bundle.min.js"

# Download AOS JS
Write-Host "Downloading AOS JS..." -ForegroundColor Yellow
Invoke-WebRequest -Uri "https://unpkg.com/aos@2.3.1/dist/aos.js" -OutFile "public\js\vendor\aos.js"

# Download GSAP
Write-Host "Downloading GSAP..." -ForegroundColor Yellow
Invoke-WebRequest -Uri "https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js" -OutFile "public\js\vendor\gsap.min.js"

# Download ScrollTrigger
Write-Host "Downloading ScrollTrigger..." -ForegroundColor Yellow
Invoke-WebRequest -Uri "https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js" -OutFile "public\js\vendor\ScrollTrigger.min.js"

Write-Host "All vendor assets downloaded successfully!" -ForegroundColor Green




