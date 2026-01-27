@echo off
echo Creating directories...
if not exist "public\js\vendor\alpine" mkdir "public\js\vendor\alpine"
if not exist "public\css\webfonts" mkdir "public\css\webfonts"
if not exist "public\images" mkdir "public\images"

echo.
echo Downloading Alpine.js files...
powershell -Command "Invoke-WebRequest -Uri 'https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js' -OutFile 'public\js\vendor\alpine\alpine.min.js'"
powershell -Command "Invoke-WebRequest -Uri 'https://cdn.jsdelivr.net/npm/@alpinejs/persist@3.13.5/dist/cdn.min.js' -OutFile 'public\js\vendor\alpine\persist.min.js'"
powershell -Command "Invoke-WebRequest -Uri 'https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.13.5/dist/cdn.min.js' -OutFile 'public\js\vendor\alpine\collapse.min.js'"

echo.
echo Downloading Font Awesome fonts...
powershell -Command "Invoke-WebRequest -Uri 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/webfonts/fa-brands-400.woff2' -OutFile 'public\css\webfonts\fa-brands-400.woff2'"
powershell -Command "Invoke-WebRequest -Uri 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/webfonts/fa-regular-400.woff2' -OutFile 'public\css\webfonts\fa-regular-400.woff2'"
powershell -Command "Invoke-WebRequest -Uri 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/webfonts/fa-solid-900.woff2' -OutFile 'public\css\webfonts\fa-solid-900.woff2'"
powershell -Command "Invoke-WebRequest -Uri 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/webfonts/fa-brands-400.ttf' -OutFile 'public\css\webfonts\fa-brands-400.ttf'"
powershell -Command "Invoke-WebRequest -Uri 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/webfonts/fa-regular-400.ttf' -OutFile 'public\css\webfonts\fa-regular-400.ttf'"
powershell -Command "Invoke-WebRequest -Uri 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/webfonts/fa-solid-900.ttf' -OutFile 'public\css\webfonts\fa-solid-900.ttf'"

echo.
echo Downloading featured-tour.jpg...
powershell -Command "Invoke-WebRequest -Uri 'https://images.unsplash.com/photo-1589834390005-5d4fb9bf3d32?ixlib=rb-4.0.3&auto=format&fit=crop&w=1587&q=80' -OutFile 'public\images\featured-tour.jpg'"

echo.
echo Verifying files...
dir /b public\js\vendor\alpine\*.js
dir /b public\css\webfonts\*.woff2
dir /b public\images\*.jpg
echo.
echo Done!

