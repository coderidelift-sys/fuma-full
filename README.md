# FUMA - Football Tournament Management System

Sistem manajemen turnamen sepak bola yang lengkap dengan fitur CRUD untuk turnamen, tim, pemain, dan pertandingan.

## Fitur Utama

- **Authentication & User Management**: Login, register, user profiles dengan role-based access control
- **Tournament Management**: CRUD turnamen dengan status (upcoming, ongoing, completed)
- **Team Management**: CRUD tim dengan logo, lokasi, rating, dan statistik
- **Player Management**: CRUD pemain dengan posisi, statistik, dan rating
- **Match Management**: CRUD pertandingan dengan jadwal, venue, dan status
- **Committee Management**: Manajemen panitia turnamen
- **Statistics & Dashboard**: Quick stats, standings, dan match results

## Role & Permission

- **Admin**: Akses penuh ke semua fitur
- **Organizer**: Dapat mengelola turnamen dan pertandingan
- **Committee**: Dapat mengelola pertandingan dan events
- **Manager**: Dapat mengelola tim dan pemain

## Teknologi

- **Backend**: Laravel 10 (PHP)
- **Database**: MySQL dengan ORM Eloquent
- **Authentication**: Laravel Sanctum (JWT-based)
- **API**: RESTful API dengan validasi dan error handling
- **File Storage**: Laravel Storage untuk upload gambar

## Instalasi

### Prerequisites
- PHP 8.1+
- Composer
- MySQL 8.0+
- Node.js & NPM (untuk frontend)

### Backend Setup

1. Clone repository
```bash
git clone <repository-url>
cd fuma-full
```

2. Install dependencies
```bash
composer install
```

3. Copy environment file
```bash
cp .env.example .env
```

4. Configure database di `.env`
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=fuma_db
DB_USERNAME=root
DB_PASSWORD=
```

5. Generate application key
```bash
php artisan key:generate
```

6. Run migrations
```bash
php artisan migrate
```

7. Seed database
```bash
php artisan db:seed
```

8. Create storage link
```bash
php artisan storage:link
```

9. Start server
```bash
php artisan serve
```

### Frontend Setup

1. Install dependencies
```bash
npm install
```

2. Build assets
```bash
npm run build
```

## API Endpoints

### Authentication
- `POST /api/register` - User registration
- `POST /api/login` - User login
- `POST /api/logout` - User logout (protected)
- `GET /api/me` - Get current user (protected)

### Tournaments
- `GET /api/tournaments` - List tournaments
- `GET /api/tournaments/{id}` - Get tournament detail
- `GET /api/tournaments/{id}/standings` - Get tournament standings
- `POST /api/tournaments` - Create tournament (Admin/Organizer)
- `PUT /api/tournaments/{id}` - Update tournament (Admin/Organizer)
- `DELETE /api/tournaments/{id}` - Delete tournament (Admin/Organizer)
- `POST /api/tournaments/{id}/teams` - Add team to tournament (Admin/Organizer)

### Teams
- `GET /api/teams` - List teams
- `GET /api/teams/{id}` - Get team detail
- `POST /api/teams` - Create team (Admin/Manager)
- `PUT /api/teams/{id}` - Update team (Admin/Manager)
- `DELETE /api/teams/{id}` - Delete team (Admin/Manager)
- `POST /api/teams/{id}/players` - Add player to team (Admin/Manager)

### Players
- `GET /api/players` - List players
- `GET /api/players/{id}` - Get player detail
- `POST /api/players` - Create player (Admin/Manager/Organizer)
- `PUT /api/players/{id}` - Update player (Admin/Manager/Organizer)
- `DELETE /api/players/{id}` - Delete player (Admin/Manager/Organizer)
- `PUT /api/players/{id}/stats` - Update player stats (Admin/Manager/Organizer)

### Matches
- `GET /api/matches` - List matches
- `GET /api/matches/{id}` - Get match detail
- `POST /api/matches` - Create match (Admin/Organizer/Committee)
- `PUT /api/matches/{id}` - Update match (Admin/Organizer/Committee)
- `DELETE /api/matches/{id}` - Delete match (Admin/Organizer/Committee)
- `POST /api/matches/{id}/events` - Add match event (Admin/Organizer/Committee)
- `PUT /api/matches/{id}/score` - Update match score (Admin/Organizer/Committee)

### Committees
- `GET /api/committees` - List committees
- `GET /api/committees/{id}` - Get committee detail
- `POST /api/committees` - Create committee (Admin/Organizer)
- `PUT /api/committees/{id}` - Update committee (Admin/Organizer)
- `DELETE /api/committees/{id}` - Delete committee (Admin/Organizer)

## Database Structure

### Tables
- `users` - User accounts
- `roles` - User roles
- `user_roles` - User-role relationships
- `tournaments` - Tournament information
- `teams` - Team information
- `players` - Player information
- `matches` - Match information
- `match_events` - Match events (goals, cards, etc.)
- `tournament_teams` - Tournament-team relationships with standings
- `committees` - Committee members for tournaments

## Testing

### Default Users
Setelah running seeder, tersedia user default:

- **Admin**: admin@fuma.com / password
- **Organizer**: organizer@fuma.com / password
- **Manager**: manager@fuma.com / password
- **Committee**: committee@fuma.com / password

### API Testing
Gunakan Postman atau tools API testing lainnya:

1. Register/Login untuk mendapatkan token
2. Gunakan token di header: `Authorization: Bearer {token}`
3. Test endpoint sesuai role user

## File Upload

Sistem mendukung upload file untuk:
- Tournament logos: `/storage/tournaments/logos/`
- Team logos: `/storage/teams/logos/`
- Player avatars: `/storage/players/avatars/`

## Error Handling

API menggunakan standard HTTP status codes:
- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `500` - Server Error

## Security Features

- JWT-based authentication dengan Laravel Sanctum
- Role-based access control (RBAC)
- Input validation dan sanitization
- CSRF protection untuk web routes
- Rate limiting untuk API endpoints

## Contributing

1. Fork repository
2. Create feature branch
3. Commit changes
4. Push to branch
5. Create Pull Request

## License

This project is licensed under the MIT License.

## Support

Untuk pertanyaan dan dukungan, silakan buat issue di repository ini.
