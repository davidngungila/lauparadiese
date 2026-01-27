@echo off
echo ========================================
echo Downloading DataTables Files
echo ========================================
echo.

REM Create directories
if not exist "public\assets\assets\vendor\libs\datatables-bs5" mkdir "public\assets\assets\vendor\libs\datatables-bs5"
if not exist "public\assets\assets\vendor\libs\datatables-responsive-bs5" mkdir "public\assets\assets\vendor\libs\datatables-responsive-bs5"
if not exist "public\assets\assets\vendor\libs\datatables-buttons-bs5" mkdir "public\assets\assets\vendor\libs\datatables-buttons-bs5"

echo Downloading CSS files...
powershell -Command "Invoke-WebRequest -Uri 'https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css' -OutFile 'public\assets\assets\vendor\libs\datatables-bs5\datatables.bootstrap5.css'"
powershell -Command "Invoke-WebRequest -Uri 'https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css' -OutFile 'public\assets\assets\vendor\libs\datatables-responsive-bs5\responsive.bootstrap5.css'"
powershell -Command "Invoke-WebRequest -Uri 'https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css' -OutFile 'public\assets\assets\vendor\libs\datatables-buttons-bs5\buttons.bootstrap5.css'"

echo Downloading JavaScript files...
powershell -Command "Invoke-WebRequest -Uri 'https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js' -OutFile 'public\assets\assets\vendor\libs\datatables-bs5\jquery.dataTables.min.js'"
powershell -Command "Invoke-WebRequest -Uri 'https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js' -OutFile 'public\assets\assets\vendor\libs\datatables-bs5\datatables-bootstrap5.js'"
powershell -Command "Invoke-WebRequest -Uri 'https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js' -OutFile 'public\assets\assets\vendor\libs\datatables-responsive-bs5\dataTables.responsive.min.js'"
powershell -Command "Invoke-WebRequest -Uri 'https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js' -OutFile 'public\assets\assets\vendor\libs\datatables-responsive-bs5\responsive.bootstrap5.min.js'"
powershell -Command "Invoke-WebRequest -Uri 'https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js' -OutFile 'public\assets\assets\vendor\libs\datatables-buttons-bs5\dataTables.buttons.min.js'"
powershell -Command "Invoke-WebRequest -Uri 'https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js' -OutFile 'public\assets\assets\vendor\libs\datatables-buttons-bs5\buttons.bootstrap5.js'"

echo.
echo Verifying downloads...
if exist "public\assets\assets\vendor\libs\datatables-bs5\datatables.bootstrap5.css" (
    echo [OK] datatables.bootstrap5.css
) else (
    echo [FAIL] datatables.bootstrap5.css
)

if exist "public\assets\assets\vendor\libs\datatables-bs5\datatables-bootstrap5.js" (
    echo [OK] datatables-bootstrap5.js
) else (
    echo [FAIL] datatables-bootstrap5.js
)

echo.
echo ========================================
echo Download complete!
echo ========================================
pause






