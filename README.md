# 📱 AttendX - Sistem Absensi QR Code

Sistem absensi modern berbasis QR Code dengan GPS tracking, PWA support, dan leave management.

## 🚀 Features

- ✅ QR Code Attendance
- ✅ GPS Geofencing
- ✅ Leave Management
- ✅ Real-time Notifications
- ✅ Admin Dashboard & Reports
- ✅ PWA (Progressive Web App)
- ✅ Email Notifications
- ✅ User Management
- ✅ Export PDF Reports

## 🏗️ Tech Stack

**Backend (API):**
- Laravel 12
- PostgreSQL
- Laravel Sanctum (Auth)
- DomPDF (Reports)

**Frontend:**
- PHP
- TailwindCSS
- Chart.js
- PWA Service Worker

## 📂 Project Structure

```
attendx/
├── api/          # Laravel Backend API
├── frontend/     # PHP Frontend
└── README.md
```

## 🚀 Deployment

### Railway (Recommended)

1. **Deploy API:**
   - Connect repo to Railway
   - Add PostgreSQL database
   - Set environment variables
   - Deploy!

2. **Deploy Frontend:**
   - Use Railway PHP runtime or separate hosting

### Manual VPS (Tencent Cloud, DigitalOcean, etc.)

See `DEPLOYMENT-TENCENT-CLOUD.md` for detailed guide.

## 🔧 Environment Variables

See `api/.env.example` for required variables.

Key variables:
- `DB_*` - Database credentials
- `MAIL_*` - Email SMTP settings
- `GEOFENCING_*` - GPS office location

## 👨‍💻 Development

```bash
# Backend
cd api
composer install
php artisan migrate
php artisan serve

# Frontend
cd frontend
# Serve dengan XAMPP/Apache atau PHP built-in server
php -S localhost:8080
```

## 📝 License

MIT License

## 👤 Author

Ferdinand Wahyudha
