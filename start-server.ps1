# ============================================================================
# Rwanda Marketplace - Development Server Starter (PowerShell)
# ============================================================================
# This script starts the PHP built-in web server for local development
# ============================================================================

Write-Host "`n╔════════════════════════════════════════════════════════════════════╗" -ForegroundColor Cyan
Write-Host "║  Rwanda Marketplace - Development Server                          ║" -ForegroundColor Cyan
Write-Host "╚════════════════════════════════════════════════════════════════════╝`n" -ForegroundColor Cyan

# Check if PHP is available
try {
    $phpVersion = php -v 2>&1
    if ($LASTEXITCODE -ne 0) {
        throw "PHP not found"
    }
} catch {
    Write-Host "❌ ERROR: PHP not found in PATH" -ForegroundColor Red
    Write-Host "`nPlease install PHP or add it to your system PATH`n" -ForegroundColor Yellow
    Read-Host "Press Enter to exit"
    exit 1
}

# Display PHP version
Write-Host "✓ PHP Found:" -ForegroundColor Green
php -v
Write-Host ""

# Set variables
$HOST = "localhost"
$PORT = 8000
$DOCROOT = Split-Path -Parent $MyInvocation.MyCommand.Path

# Display startup info
Write-Host "┌────────────────────────────────────────────────────────────────────┐" -ForegroundColor Cyan
Write-Host "│ Starting Development Server                                        │" -ForegroundColor Cyan
Write-Host "├────────────────────────────────────────────────────────────────────┤" -ForegroundColor Cyan
Write-Host "│ Server:         ${HOST}:${PORT}" -ForegroundColor Cyan
Write-Host "│ Document Root:  $DOCROOT" -ForegroundColor Cyan
Write-Host "│ URL:            http://${HOST}:${PORT}/?route=register" -ForegroundColor Cyan
Write-Host "└────────────────────────────────────────────────────────────────────┘" -ForegroundColor Cyan
Write-Host ""
Write-Host "Press CTRL+C to stop the server" -ForegroundColor Yellow
Write-Host ""

# Start the server
Set-Location $DOCROOT
& php -S ${HOST}:${PORT}

# If we get here, server stopped
Write-Host "`n❌ Server stopped unexpectedly" -ForegroundColor Red
Read-Host "Press Enter to exit"
