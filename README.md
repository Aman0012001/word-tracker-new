# Word Tracker

A comprehensive word tracking application with Angular frontend and PHP backend.

## 🚀 Quick Deploy to Railway

### Option 1: Use Deployment Script (Recommended)
```bash
# Windows PowerShell
.\deploy.ps1

# Linux/Mac
chmod +x deploy.sh
./deploy.sh
```

### Option 2: Manual Deployment
See [RAILWAY_DEPLOYMENT.md](RAILWAY_DEPLOYMENT.md) for detailed instructions.

## 📁 Project Structure

```
word-tracker/
├── frontend/              # Angular application
│   ├── src/
│   ├── package.json
│   └── angular.json
├── backend-php/          # PHP REST API
│   ├── api/             # API endpoints
│   ├── config/          # Configuration files
│   ├── models/          # Data models
│   └── nixpacks.toml    # Railway build config
├── database/            # Database schema
├── package.json         # Root package for Railway
└── RAILWAY_DEPLOYMENT.md # Deployment guide
```

## 🛠️ Local Development

### Prerequisites
- Node.js 18+
- PHP 8.2+
- MySQL 5.7+
- XAMPP (for Windows) or LAMP/MAMP

### Frontend Setup
```bash
cd frontend
npm install
npm start
# Opens at http://localhost:4200
```

### Backend Setup
1. Start XAMPP (Apache + MySQL)
2. Create database `word_tracker`
3. Import schema from `database/schema.sql`
4. Access backend at `http://localhost/word-tracker`

## 🌐 Production Deployment

### Backend (Railway)
- **Platform**: Railway
- **Runtime**: PHP 8.2
- **Database**: MySQL
- **Root Directory**: `backend-php`

### Frontend (Railway or Vercel)
- **Platform**: Railway or Vercel (recommended)
- **Framework**: Angular 17
- **Build**: `npm run build`
- **Output**: `dist/word-tracker/browser`

## 📋 Features

- ✅ User Authentication (Login/Register)
- ✅ Word Tracking & Goals
- ✅ Writing Plans & Checklists
- ✅ Progress Analytics
- ✅ Community Challenges
- ✅ Calendar Integration
- ✅ Project Organization

## 🔧 Configuration Files

### Railway Configuration
- `backend-php/railway.json` - Backend deployment config
- `backend-php/nixpacks.toml` - PHP build configuration
- `railway.frontend.json` - Frontend deployment config
- `package.json` - Root package for frontend deployment

### Environment Files
- `frontend/src/environments/environment.ts` - Local development
- `frontend/src/environments/environment.prod.ts` - Production

## 🔐 Environment Variables (Railway)

### Backend Service
Automatically set by Railway MySQL:
- `MYSQLHOST`
- `MYSQLDATABASE`
- `MYSQLUSER`
- `MYSQLPASSWORD`
- `MYSQLPORT`

### Frontend Service
- `NODE_VERSION=18`

## 📊 Database Schema

The database schema is automatically initialized when you visit:
```
https://your-backend.railway.app/init_railway_db.php
```

## 🐛 Troubleshooting

### CORS Errors
- Update `backend-php/config/cors.php` with your frontend domain
- Ensure origin is in the allowed list

### Database Connection
- Verify Railway MySQL environment variables
- Check `backend-php/config/database.php`

### Build Failures
- Check Railway deployment logs
- Verify Node.js version (18+)
- Ensure all dependencies are in package.json

## 📞 Support

For deployment issues, check:
1. Railway deployment logs
2. Browser console errors
3. Backend health check: `/api/ping.php`

## 📄 License

MIT License - See LICENSE file for details

## 👥 Contributing

1. Fork the repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

---

**Deployed with ❤️ on Railway**
