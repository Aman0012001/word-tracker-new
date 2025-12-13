# Word Tracker - Railway Deployment (PowerShell)
# Quick deployment script for Windows

Write-Host "🚀 Word Tracker - Railway Deployment Helper" -ForegroundColor Cyan
Write-Host "===========================================" -ForegroundColor Cyan
Write-Host ""

# Check if git is initialized
if (-not (Test-Path ".git")) {
    Write-Host "📦 Initializing Git repository..." -ForegroundColor Yellow
    git init
    git add .
    git commit -m "Initial commit - Prepare for Railway deployment"
}
else {
    Write-Host "✅ Git repository already initialized" -ForegroundColor Green
}

# Check if remote exists
$remotes = git remote
if ($remotes -notcontains "origin") {
    Write-Host ""
    Write-Host "⚠️  No git remote found!" -ForegroundColor Red
    Write-Host "Please add your GitHub repository:" -ForegroundColor Yellow
    Write-Host "git remote add origin https://github.com/ankitverma3490/word-tracker.git" -ForegroundColor White
    Write-Host ""
    Read-Host "Press Enter to continue after adding remote"
}

# Commit any changes
Write-Host ""
Write-Host "📝 Committing latest changes..." -ForegroundColor Yellow
git add .
$commitDate = Get-Date -Format "yyyy-MM-dd HH:mm"
git commit -m "Update for Railway deployment - $commitDate"
if ($LASTEXITCODE -ne 0) {
    Write-Host "No changes to commit" -ForegroundColor Gray
}

# Push to GitHub
Write-Host ""
Write-Host "⬆️  Pushing to GitHub..." -ForegroundColor Yellow
git push -u origin main 2>&1 | Out-Null
if ($LASTEXITCODE -ne 0) {
    Write-Host "Trying master branch..." -ForegroundColor Yellow
    git push -u origin master 2>&1 | Out-Null
}

Write-Host ""
Write-Host "✅ Code pushed to GitHub!" -ForegroundColor Green
Write-Host ""
Write-Host "📋 Next Steps:" -ForegroundColor Cyan
Write-Host "1. Go to https://railway.app/new" -ForegroundColor White
Write-Host "2. Click 'Deploy from GitHub repo'" -ForegroundColor White
Write-Host "3. Select your 'word-tracker' repository" -ForegroundColor White
Write-Host "4. Add MySQL database (+ New → Database → MySQL)" -ForegroundColor White
Write-Host "5. Deploy backend (+ New → GitHub Repo → Set root directory: backend-php)" -ForegroundColor White
Write-Host "6. Deploy frontend (+ New → GitHub Repo → Leave root directory empty)" -ForegroundColor White
Write-Host "7. Visit backend-url/init_railway_db.php to setup database" -ForegroundColor White
Write-Host "8. Update frontend environment with backend URL" -ForegroundColor White
Write-Host ""
Write-Host "📖 See RAILWAY_DEPLOYMENT.md for detailed instructions" -ForegroundColor Cyan
Write-Host ""
Read-Host "Press Enter to exit"
