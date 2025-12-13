#!/bin/bash
# Quick deployment script for Railway

echo "🚀 Word Tracker - Railway Deployment Helper"
echo "==========================================="
echo ""

# Check if git is initialized
if [ ! -d ".git" ]; then
    echo "📦 Initializing Git repository..."
    git init
    git add .
    git commit -m "Initial commit - Prepare for Railway deployment"
else
    echo "✅ Git repository already initialized"
fi

# Check if remote exists
if ! git remote | grep -q "origin"; then
    echo ""
    echo "⚠️  No git remote found!"
    echo "Please add your GitHub repository:"
    echo "git remote add origin https://github.com/amanjeetsingh348-creator/word-tracker.git"
    echo ""
    read -p "Press Enter to continue after adding remote..."
fi

# Commit any changes
echo ""
echo "📝 Committing latest changes..."
git add .
git commit -m "Update for Railway deployment - $(date +%Y-%m-%d)" || echo "No changes to commit"

# Push to GitHub
echo ""
echo "⬆️  Pushing to GitHub..."
git push -u origin main || git push -u origin master

echo ""
echo "✅ Code pushed to GitHub!"
echo ""
echo "📋 Next Steps:"
echo "1. Go to https://railway.app/new"
echo "2. Click 'Deploy from GitHub repo'"
echo "3. Select your 'word-tracker' repository"
echo "4. Add MySQL database (+ New → Database → MySQL)"
echo "5. Deploy backend (+ New → GitHub Repo → Set root directory: backend-php)"
echo "6. Deploy frontend (+ New → GitHub Repo → Leave root directory empty)"
echo "7. Visit backend-url/init_railway_db.php to setup database"
echo "8. Update frontend environment with backend URL"
echo ""
echo "📖 See RAILWAY_DEPLOYMENT.md for detailed instructions"
