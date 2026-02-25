# POS Mobile App

Mobile app built with **Nuxt 3** (front-end) and **Laravel** (back-end), packaged for iOS/Android via **Capacitor**.

## Project structure

- **pos-api** – Laravel API (Sanctum auth, REST, CORS)
- **pos-fe** – Nuxt 3 SPA + Capacitor (mobile-first UI)

## Quick start

### Backend (Laravel)

```bash
cd pos-api
cp .env.example .env   # if not already
php artisan key:generate
php artisan migrate
php artisan serve      # API at http://localhost:8000
```

### Frontend (Nuxt)

```bash
cd pos-fe
cp .env.example .env   # set NUXT_PUBLIC_API_BASE if needed
npm install
npm run dev            # App at http://localhost:3000
```

For device/emulator, set `NUXT_PUBLIC_API_BASE` to your machine’s LAN IP (e.g. `http://192.168.1.100:8000`) so the app can reach the API.

### Native build (Capacitor)

1. Build and sync web assets into native projects:

   ```bash
   cd pos-fe
   npm run cap:sync
   ```

2. Open and run in Android Studio or Xcode:

   ```bash
   npm run cap:android   # or npm run cap:ios
   ```

## API auth

- **POST /api/register** – register (name, email, password, password_confirmation)
- **POST /api/login** – login (email, password) → returns `user` and `token`
- **GET /api/user** – current user (header: `Authorization: Bearer <token>`)
- **POST /api/logout** – logout (invalidates token)

The Nuxt app stores the token in `localStorage` and sends it as `Authorization: Bearer` on API requests.
