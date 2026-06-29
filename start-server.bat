@echo off
title E-ADMISI LITE SERVER
cd /d "%~dp0"
echo ========================================
echo   E-ADMISI LITE - Development Server
echo ========================================
echo.
echo Server: http://localhost:8000
echo.
echo Press Ctrl+C to stop
echo.

:loop
php artisan serve --host=0.0.0.0 --port=8000
echo.
echo [WARNING] Server stopped unexpectedly!
echo Restarting in 3 seconds...
timeout /t 3 /nobreak >nul
goto loop