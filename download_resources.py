#!/usr/bin/env python3
"""Download all missing resources for the Tour application"""
import os
import urllib.request
import urllib.error

# Create directories
dirs = [
    "public/js/vendor/alpine",
    "public/css/webfonts",
    "public/images"
]

for dir_path in dirs:
    os.makedirs(dir_path, exist_ok=True)
    print(f"✓ Created directory: {dir_path}")

# Files to download
files = {
    # Alpine.js
    "public/js/vendor/alpine/alpine.min.js": "https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js",
    "public/js/vendor/alpine/persist.min.js": "https://cdn.jsdelivr.net/npm/@alpinejs/persist@3.13.5/dist/cdn.min.js",
    "public/js/vendor/alpine/collapse.min.js": "https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.13.5/dist/cdn.min.js",
    
    # Font Awesome fonts
    "public/css/webfonts/fa-brands-400.woff2": "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/webfonts/fa-brands-400.woff2",
    "public/css/webfonts/fa-regular-400.woff2": "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/webfonts/fa-regular-400.woff2",
    "public/css/webfonts/fa-solid-900.woff2": "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/webfonts/fa-solid-900.woff2",
    "public/css/webfonts/fa-brands-400.ttf": "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/webfonts/fa-brands-400.ttf",
    "public/css/webfonts/fa-regular-400.ttf": "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/webfonts/fa-regular-400.ttf",
    "public/css/webfonts/fa-solid-900.ttf": "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/webfonts/fa-solid-900.ttf",
    
    # Images
    "public/images/featured-tour.jpg": "https://images.unsplash.com/photo-1589834390005-5d4fb9bf3d32?ixlib=rb-4.0.3&auto=format&fit=crop&w=1587&q=80"
}

print("\n=== Downloading Files ===\n")

success_count = 0
fail_count = 0

for file_path, url in files.items():
    if os.path.exists(file_path):
        size = os.path.getsize(file_path)
        print(f"✓ {file_path} already exists ({size:,} bytes)")
        success_count += 1
        continue
    
    try:
        print(f"Downloading {file_path}...", end=" ")
        urllib.request.urlretrieve(url, file_path)
        size = os.path.getsize(file_path)
        print(f"✓ ({size:,} bytes)")
        success_count += 1
    except urllib.error.URLError as e:
        print(f"✗ Failed: {e}")
        fail_count += 1
    except Exception as e:
        print(f"✗ Error: {e}")
        fail_count += 1

print(f"\n=== Summary ===")
print(f"✓ Successfully downloaded/verified: {success_count} files")
if fail_count > 0:
    print(f"✗ Failed: {fail_count} files")
else:
    print("✓ All files downloaded successfully!")






