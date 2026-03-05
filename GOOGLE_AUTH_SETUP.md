# Google Authentication Setup Guide

## Overview
Sistem authentication telah diupdate untuk mendukung Google Sign-In/Sign-Up dengan UI yang konsisten di semua halaman auth.

## What's Been Implemented

### 1. **Consistent Auth UI/Template**
- Semua halaman authentication (login, register, forgot password, reset password, confirm password, verify email) menggunakan layout yang sama
- Desain modern dengan gradient background dan animasi yang smooth
- Responsive design untuk mobile dan desktop

### 2. **Google OAuth Integration**
- Implementasi Laravel Socialite untuk Google authentication
- Tombol "Sign in/Sign up with Google" di halaman login dan register
- Auto-create user account jika email belum terdaftar
- Auto-login jika user sudah terdaftar dengan email yang sama

### 3. **Database Changes**
- Tambahan 3 kolom di tabel `users`:
  - `google_id` (unique)
  - `google_token` (untuk API access)
  - `google_refresh_token` (untuk refresh token)

## Setup Instructions

### Step 1: Configure Google OAuth Credentials

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select existing one
3. Enable Google+ API
4. Create OAuth 2.0 credentials (OAuth consent screen):
   - User Type: External
   - Add required scopes (email, profile)
   - Add test users jika masih development

5. Create OAuth 2.0 Client ID (Web application):
   - Authorized JavaScript origins: `http://localhost:8000`, `http://localhost`, `https://yourdomain.com`
   - Authorized redirect URIs: `http://localhost:8000/auth/google/callback`, `https://yourdomain.com/auth/google/callback`

### Step 2: Update Environment Variables

Copy Google credentials ke `.env` file:

```env
GOOGLE_CLIENT_ID=your_google_client_id_here
GOOGLE_CLIENT_SECRET=your_google_client_secret_here
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

### Step 3: Run Migrations

```bash
php artisan migrate
```

This akan menambahkan `google_id`, `google_token`, dan `google_refresh_token` columns ke tabel users.

## File Structure

### New/Modified Files

#### Components
- `resources/views/components/auth-layout.blade.php` - Shared auth layout component

#### Auth Views (Updated with consistent UI)
- `resources/views/auth/login.blade.php`
- `resources/views/auth/register.blade.php`
- `resources/views/auth/forgot-password.blade.php`
- `resources/views/auth/reset-password.blade.php`
- `resources/views/auth/confirm-password.blade.php`
- `resources/views/auth/verify-email.blade.php`

#### Controllers
- `app/Http/Controllers/Auth/GoogleAuthController.php` - Google OAuth handler

#### Configuration
- `config/services.php` - Added Google OAuth config

#### Database
- `database/migrations/2025_03_05_000000_add_google_auth_to_users.php`

#### Routes
- `routes/web.php` - Added Google OAuth routes:
  - `GET /auth/google` - Redirect ke Google login
  - `GET /auth/google/callback` - Google callback handler

#### Models
- `app/Models/User.php` - Updated fillable untuk google fields

## Usage

### Sign In with Google
1. User klik tombol "Sign in with Google" di halaman login
2. Redirect ke Google login page
3. User login dengan Google account
4. Redirect kembali ke aplikasi dan auto-login

### Sign Up with Google
1. User klik tombol "Sign up with Google" di halaman register
2. Redirect ke Google login page
3. Jika email belum terdaftar, auto-create account
4. Redirect kembali ke aplikasi dan auto-login
5. Email otomatis terverifikasi

### User Flow

```
Login Page
├── Email & Password → Login Form
├── Sign in with Google → Google OAuth
└── Create Account Link

Register Page
├── Name, Email & Password → Register Form
├── Sign up with Google → Google OAuth
└── Sign in Link

Forgot Password Page
├── Email → Reset Link

Reset Password Page
├── Email, New Password → Update Password

Verify Email Page
├── Resend Link
└── Logout

Confirm Password Page
├── Password → Confirm
└── Logout
```

## Security Considerations

1. **Environment Variables**: Jangan commit `.env` file ke repository
2. **HTTPS**: Gunakan HTTPS di production
3. **Redirect URL**: Pastikan redirect URL di Google Console sesuai dengan deploy URL
4. **Token Storage**: Google tokens disimpen di database, pastikan database ter-encrypt
5. **Email Verification**: Email otomatis terverifikasi saat sign up dengan Google

## Troubleshooting

### Error: "Call to undefined method redirect()"
- Pastikan sudah run `composer install`
- Pastikan Laravel Socialite sudah installed

### Error: "Client ID not found"
- Pastikan `.env` file memiliki `GOOGLE_CLIENT_ID` dan `GOOGLE_CLIENT_SECRET`
- Pastikan keys sudah di-copy dengan benar dari Google Console

### Error: "Redirect URI mismatch"
- Pastikan `GOOGLE_REDIRECT_URI` di `.env` sesuai dengan settings di Google Console
- Pastikan URL di Google Console URL-encoded (gunakan `http://localhost:8000/auth/google/callback`)

### Redirect Loop
- Clear cookies di browser
- Check `APP_URL` di `.env` sesuai dengan domain access
- Restart server

## Testing

### Local Development
1. Update `.env`:
   ```env
   APP_URL=http://localhost:8000
   GOOGLE_CLIENT_ID=your_dev_id
   GOOGLE_CLIENT_SECRET=your_dev_secret
   GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
   ```

2. Tambahkan test user di Google Console

3. Test di browser: `http://localhost:8000/login`

### Production Deployment
1. Set environment variables di hosting
2. Update redirect URL di Google Console
3. Test sign up dan sign in flow

## Notes

- Semua halaman auth menggunakan layout yang sama untuk consistency
- Password tidak required jika user sign up dengan Google (bisa set nanti)
- Email otomatis terverifikasi saat sign up dengan Google
- User bisa login dengan email+password atau Google account untuk email yang sama

Selamat! Sistem authentication dengan Google OAuth sudah siap digunakan! 🎉
