# Leave Request API 📅

## Overview 🌟

Leave Request API adalah aplikasi berbasis Laravel yang menyediakan sistem manajemen permintaan cuti untuk perusahaan. Aplikasi ini memungkinkan karyawan untuk mengajukan permintaan cuti, melampirkan dokumen pendukung, dan admin untuk menyetujui atau menolak permintaan tersebut. Sistem ini juga mendukung autentikasi melalui email/password atau Google OAuth.

## Actors 👥

- **Employee** 🧑‍💼: Karyawan yang dapat mendaftar, login, mengelola profil, mengajukan permintaan cuti, melihat status permintaan, dan membatalkan permintaan.
- **Admin** 👨‍💼: Administrator yang dapat melihat semua permintaan cuti, menyetujui atau menolak permintaan, dan memberikan catatan admin.

## Link ERD 🔗

[Entity Relationship Diagram](https://drive.google.com/file/d/129e1A08ihoBM0lUUdDxRcEi6hzmJlmaP/view?usp=sharing)

## Functional Requirements 📋

### Authentication 🔐
- Registrasi akun baru dengan email dan password
- Login dengan email/password atau Google OAuth
- Logout
- Pengelolaan profil (update nama, dll.)

### Leave Request Management (Employee) 📝
- Mengajukan permintaan cuti dengan tanggal mulai, akhir, alasan, dan lampiran
- Melihat daftar permintaan cuti sendiri
- Melihat detail permintaan cuti
- Update permintaan cuti (jika masih pending)
- Membatalkan permintaan cuti

### Admin Management 👑
- Melihat semua permintaan cuti dari karyawan
- Melihat detail permintaan cuti
- Menyetujui permintaan cuti
- Menolak permintaan cuti dengan catatan

### Additional Features ✨
- Upload lampiran menggunakan Cloudinary
- Kuota cuti per karyawan (default 12 hari)
- Status permintaan: pending, approved, rejected, cancelled

## API Design 🔌

API menggunakan versioning v1 dan autentikasi via Laravel Sanctum.

### Authentication Endpoints 🔑
- `POST /api/v1/auth/register` - Registrasi
- `POST /api/v1/auth/login` - Login
- `GET /api/v1/auth/google/redirect` - Redirect ke Google OAuth
- `GET /api/v1/auth/google/callback` - Callback Google OAuth
- `POST /api/v1/auth/logout` - Logout (authenticated)

### User Endpoints (Authenticated) 👤
- `GET /api/v1/profile` - Get profile
- `PATCH /api/v1/profile` - Update profile

### Leave Request Endpoints (Employee) 📅
- `POST /api/v1/leave-requests` - Create leave request
- `GET /api/v1/leave-requests` - List own leave requests
- `GET /api/v1/leave-requests/{id}` - Get leave request detail
- `PATCH /api/v1/leave-requests/{id}` - Update leave request
- `PATCH /api/v1/leave-requests/{id}/cancel` - Cancel leave request

### Admin Leave Request Endpoints (Admin) 👑
- `GET /api/v1/admin/leave-requests` - List all leave requests
- `GET /api/v1/admin/leave-requests/{id}` - Get leave request detail
- `PATCH /api/v1/admin/leave-requests/{id}/approve` - Approve leave request

## Link Dokumentasi Postman 📖

[Dokumentasi Postman](https://documenter.getpostman.com/view/41537989/2sBXigLD9h)

## Tech Stack 🛠️

- **Backend**: PHP 8.2+, Laravel 12
- **Database**: MySQL / SQLite
- **Authentication**: Laravel Sanctum
- **OAuth**: Laravel Socialite (Google)
- **File Storage**: Cloudinary
- **Testing**: PHPUnit
- **Code Quality**: Laravel Pint
- **Development**: Laravel Sail (Docker)

## Architecture Pattern 🏗️

Aplikasi menggunakan pola arsitektur **MVC (Model-View-Controller)** dengan lapisan **Service** untuk logika bisnis.

- **Models**: User, LeaveRequest
- **Controllers**: AuthController, UserController, LeaveRequestController
- **Services**: AuthService, UserService, LeaveRequestService
- **Middleware**: Authentication, Role-based access
- **Resources**: API Resources untuk response formatting

## Folder Structure 📁

```
leave_request_api/
├── app/
│   ├── Helper/
│   │   └── ApiResponse.php
│   ├── Http/
│   │   ├── Controllers/
│   │   ├── Middleware/
│   │   ├── Requests/
│   │   └── Resources/
│   ├── Models/
│   │   ├── LeaveRequest.php
│   │   └── User.php
│   ├── Providers/
│   │   └── AppServiceProvider.php
│   └── Services/
│       ├── AuthService.php
│       ├── LeaveRequestService.php
│       └── UserService.php
├── bootstrap/
├── config/
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
├── public/
├── resources/
├── routes/
│   ├── api.php
│   └── web.php
├── storage/
├── tests/
└── vendor/
```

## Env ⚙️

Variabel environment yang diperlukan:

- `APP_NAME`: Nama aplikasi
- `APP_ENV`: Environment (local, production)
- `APP_KEY`: Application key
- `APP_DEBUG`: Debug mode
- `APP_URL`: Base URL
- `DB_CONNECTION`: Database connection (mysql, sqlite)
- `DB_HOST`: Database host
- `DB_PORT`: Database port
- `DB_DATABASE`: Database name
- `DB_USERNAME`: Database username
- `DB_PASSWORD`: Database password
- `CLOUDINARY_URL`: Cloudinary configuration
- `GOOGLE_CLIENT_ID`: Google OAuth client ID
- `GOOGLE_CLIENT_SECRET`: Google OAuth client secret

## Installation Setup 🚀

1. **Clone repository** 📥:
   ```bash
   git clone <repository-url>
   cd leave_request_api
   ```

2. **Install dependencies** 📦:
   ```bash
   composer install
   npm install
   ```

3. **Environment setup** 🔧:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database setup** 🗄️:
   ```bash
   php artisan migrate
   ```

5. **Build assets** 🏗️:
   ```bash
   npm run build
   ```

6. **Run application** ▶️:
   ```bash
   php artisan serve
   ```

   Atau menggunakan script dev:
   ```bash
   composer run dev
   ```

## Author 👤

**Mukti Abdi Syukur**
* *Backend Developer (Internship Applicant)* 👨‍💻
