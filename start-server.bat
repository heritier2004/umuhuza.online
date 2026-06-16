@echo off
REM ============================================================================
REM UMUHUZA.ONLINE - Development Server Starter
REM ============================================================================
REM This script starts the PHP built-in web server for local development
REM ============================================================================

echo.
echo ╔════════════════════════════════════════════════════════════════════╗
echo ║  UMUHUZA.ONLINE - Development Server                             ║
echo ╚════════════════════════════════════════════════════════════════════╝
echo.

REM Check if PHP is available
where php >nul 2>nul
if %errorlevel% neq 0 (
    echo ❌ ERROR: PHP not found in PATH
    echo.
    echo Please install PHP or add it to your system PATH
    echo.
    pause
    exit /b 1
)

REM Display PHP version
echo ✓ PHP Found:
php -v
echo.

REM Set variables
set HOST=localhost
set PORT=8000
set DOCROOT=%~dp0

REM Display startup info
echo ┌────────────────────────────────────────────────────────────────────┐
echo │ Starting Development Server                                        │
echo ├────────────────────────────────────────────────────────────────────┤
echo │ Server:     %HOST%:%PORT%
echo │ Document Root: %DOCROOT%
echo │ URL:        http://%HOST%:%PORT%/?route=register
echo └────────────────────────────────────────────────────────────────────┘
echo.
echo Press CTRL+C to stop the server
echo.

REM Start the server
cd /d "%DOCROOT%"
php -S %HOST%:%PORT%

REM If we get here, something went wrong
echo.
echo ❌ Server stopped unexpectedly
pause
