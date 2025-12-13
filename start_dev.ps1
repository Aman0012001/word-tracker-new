$phpPaths = @("php", "C:\xampp\php\php.exe", "C:\php\php.exe", "C:\wamp64\bin\php\php8.2.12\php.exe", "C:\wamp64\bin\php\php\php.exe")
$phpExe = $null

# Find PHP
foreach ($path in $phpPaths) {
    try {
        if (Get-Command $path -ErrorAction Stop) {
            $phpExe = $path
            break
        }
    } catch {
        if (Test-Path $path) {
            $phpExe = $path
            break
        }
    }
}

if (-not $phpExe) {
    Write-Host "⚠️ PHP not found in PATH or common XAMPP/WAMP locations." -ForegroundColor Yellow
    Write-Host "Please install PHP or edit this script to point to your php.exe." -ForegroundColor Yellow
    Write-Host "The Backend will NOT start." -ForegroundColor Red
} else {
    Write-Host "✅ Found PHP: $phpExe" -ForegroundColor Green
    Write-Host "🚀 Starting Backend Server at http://localhost:8000..." -ForegroundColor Cyan
    # Start PHP server in a new window
    Start-Process -FilePath $phpExe -ArgumentList "-S localhost:8000 router.php" -WorkingDirectory "backend-php"
}

# Start Frontend
Write-Host "🚀 Starting Frontend..." -ForegroundColor Cyan
Set-Location "frontend"
npm start
