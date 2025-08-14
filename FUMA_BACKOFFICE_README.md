# FUMA Backoffice - Football Tournament Management System

## Overview

FUMA Backoffice adalah sistem manajemen turnamen sepak bola yang terintegrasi penuh dengan API backend. Sistem ini menyediakan interface web yang modern dan responsif untuk mengelola semua aspek turnamen sepak bola.

## Fitur Utama

### 🔐 Authentication & Authorization
- Login dengan email dan password
- Role-based access control (RBAC)
- Session management dengan JWT token
- Middleware untuk proteksi route

### 🏆 Tournament Management
- **Admin & Organizer**: CRUD turnamen
- Manajemen detail turnamen (overview, standings, matches)
- Upload logo turnamen
- Status tracking (upcoming, ongoing, completed)

### ⚽ Team Management
- **Admin & Manager**: CRUD tim
- Upload logo tim
- Manajemen pemain dalam tim
- Rating dan statistik tim

### 👤 Player Management
- **Admin, Manager, Organizer**: CRUD pemain
- Upload foto pemain
- Statistik pemain (goals, assists, cards)
- Kategorisasi berdasarkan posisi

### 🥅 Match Management
- **Admin, Organizer, Committee**: CRUD pertandingan
- Penjadwalan pertandingan
- Update skor dan status
- Manajemen match events

### 👥 Committee Management
- **Admin & Organizer**: CRUD panitia
- Assignment ke turnamen tertentu
- Role-based permissions

### 📊 Dashboard & Analytics
- Statistik ringkas (tournaments, teams, players, matches)
- Top rated teams dan players
- Tournament status overview
- Quick actions berdasarkan role

## Role & Permissions

### Admin
- Akses penuh ke semua fitur
- Manajemen user dan role
- CRUD semua resource

### Organizer
- Manajemen turnamen
- Manajemen committee
- CRUD pemain
- Manajemen pertandingan

### Manager
- Manajemen tim
- CRUD pemain dalam tim
- Update statistik pemain

### Committee
- View pertandingan
- Update match events
- Update skor pertandingan

## Teknologi yang Digunakan

### Backend
- **Framework**: Laravel 10
- **Database**: MySQL
- **Authentication**: Laravel Sanctum (JWT)
- **File Storage**: Laravel Storage

### Frontend
- **Template Engine**: Blade
- **CSS Framework**: Bootstrap 5
- **JavaScript**: Vanilla JS + ES6+
- **Icons**: Remix Icons
- **DataTables**: Bootstrap 5 DataTables

### Integrasi
- **API**: RESTful API dengan HTTP methods standar
- **HTTP Client**: Laravel HTTP Client untuk komunikasi internal
- **Validation**: Laravel Form Request Validation
- **File Upload**: Multipart form data dengan preview

## Struktur File

```
resources/
├── views/
│   └── fuma/
│       ├── layouts/
│       │   └── fuma.blade.php
│       ├── _partials/
│       │   └── sidebar_menu.blade.php
│       ├── auth/
│       │   └── login.blade.php
│       ├── dashboard.blade.php
│       ├── tournaments/
│       ├── teams/
│       ├── players/
│       ├── matches/
│       ├── committees/
│       ├── users/
│       ├── roles/
│       ├── statistics/
│       └── standings/
├── js/
│   └── fuma-backoffice.js
└── css/
    └── app.css

app/
├── Http/
│   ├── Controllers/
│   │   └── Fuma/
│   │       ├── DashboardController.php
│   │       ├── TournamentController.php
│   │       ├── TeamController.php
│   │       ├── PlayerController.php
│   │       ├── MatchController.php
│   │       ├── CommitteeController.php
│   │       ├── UserController.php
│   │       ├── RoleController.php
│   │       ├── StatisticsController.php
│   │       ├── StandingsController.php
│   │       ├── ProfileController.php
│   │       └── AuthController.php
│   └── Middleware/
│       ├── FumaAuth.php
│       └── FumaRole.php

routes/
└── web.php (FUMA routes)
```

## Instalasi & Setup

### 1. Prerequisites
- PHP 8.1+
- Composer
- MySQL 8.0+
- Node.js & NPM

### 2. Setup Backend
```bash
# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure database di .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=fuma_db
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Create storage link
php artisan storage:link
```

### 3. Setup Frontend
```bash
# Install dependencies
npm install

# Build assets
npm run build

# Development mode
npm run dev
```

### 4. Access Backoffice
```
URL: http://your-domain/fuma
Login: admin@fuma.com / password
```

## API Integration

### Authentication Flow
1. User login melalui `/fuma/login`
2. Credentials dikirim ke `/api/login`
3. JWT token disimpan di session
4. Token digunakan untuk semua request API

### Data Flow
1. Controller memanggil API internal menggunakan Laravel HTTP Client
2. Response dari API diproses dan dikirim ke view
3. File uploads dikirim langsung ke API endpoint
4. Error handling dan validation di level controller

### Example API Call
```php
$response = Http::withHeaders([
    'Authorization' => 'Bearer ' . session('fuma_token'),
    'Accept' => 'application/json'
])->get(config('app.url') . '/api/tournaments');

if ($response->successful()) {
    $tournaments = $response->json()['data'];
    return view('fuma.tournaments.index', compact('tournaments'));
}
```

## Fitur UI/UX

### Responsive Design
- Mobile-first approach
- Bootstrap 5 grid system
- Collapsible sidebar untuk mobile
- Touch-friendly interface

### Data Tables
- Search dan filter
- Pagination
- Export (CSV, Excel, PDF)
- Responsive columns

### Form Handling
- Real-time validation
- File upload dengan preview
- Auto-save draft
- Success/error notifications

### Navigation
- Breadcrumb navigation
- Role-based menu visibility
- Quick actions
- Recent items

## Security Features

### Authentication
- CSRF protection
- Session security
- JWT token validation
- Password hashing

### Authorization
- Role-based access control
- Route protection
- Resource ownership validation
- Audit logging

### Data Protection
- Input validation
- SQL injection prevention
- XSS protection
- File upload security

## Performance Optimization

### Frontend
- Lazy loading untuk images
- Minified CSS/JS
- CDN untuk external libraries
- Browser caching

### Backend
- Database query optimization
- Eager loading untuk relationships
- Caching untuk static data
- Pagination untuk large datasets

## Monitoring & Logging

### Error Handling
- Try-catch blocks
- User-friendly error messages
- Error logging
- Debug mode untuk development

### Performance Monitoring
- Query execution time
- Memory usage
- Response time
- User activity tracking

## Deployment

### Production Checklist
- [ ] Environment variables configured
- [ ] Database optimized
- [ ] File permissions set
- [ ] SSL certificate installed
- [ ] Backup strategy implemented
- [ ] Monitoring tools configured

### Environment Variables
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com
DB_CONNECTION=mysql
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

## Troubleshooting

### Common Issues

#### 1. JWT Token Expired
- Clear session data
- Re-login user
- Check token expiration time

#### 2. File Upload Failed
- Check storage permissions
- Verify file size limits
- Check disk space

#### 3. API Connection Error
- Verify API endpoints
- Check network connectivity
- Validate authentication headers

### Debug Mode
```php
// Enable debug logging
Log::debug('API Response', ['data' => $response->json()]);

// Check session data
dd(session('fuma_token'));
```

## Contributing

### Development Workflow
1. Create feature branch
2. Implement changes
3. Write tests
4. Submit pull request
5. Code review
6. Merge to main

### Coding Standards
- PSR-12 coding style
- Laravel best practices
- Meaningful commit messages
- Comprehensive documentation

## Support & Documentation

### Resources
- [Laravel Documentation](https://laravel.com/docs)
- [Bootstrap Documentation](https://getbootstrap.com/docs)
- [API Documentation](FUMA_API_README.md)

### Contact
- Technical Support: support@fuma.com
- Development Team: dev@fuma.com
- Bug Reports: bugs@fuma.com

---

**FUMA Backoffice** - Football Tournament Management System  
Version: 1.0.0  
Last Updated: {{ date('Y-m-d') }}
