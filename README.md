# Event Apparel

An elegant boutique rental and fitting system for formal wear — gowns, tuxedos, and custom-tailored pieces for special occasions.

## What it does

- **Catalog** — browse rental apparel by category, with per-size quantities and colors
- **Reservations** — reserve pieces for event dates; admin confirms and tracks returns
- **Customizations** — customers submit fitting/alteration requests on packages or single pieces
- **Appointments** — book fitting appointments, admin schedules and confirms
- **Payments** — record and track payments against reservations
- **Admin** — manage items, categories, packages, customers, reservations, reports, and notifications

## Stack

Laravel 12 + Breeze (Blade + Alpine), Tailwind CSS, MySQL/MariaDB (XAMPP), Symfony Mailer.

## Setup

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan storage:link
npm run build
```

Set `DB_DATABASE=gms_db` (or your database name) in `.env`.

## Mail / OTP

Email verification and password reset use six-digit one-time codes (hashed at rest, 3-min TTL for verification, 10-min for reset, max 5 attempts, single-use).

Mail is configured as a **failover** chain — SMTP first, log file fallback (same approach as the reference PHPMailer setup):

```dotenv
MAIL_MAILER=failover
MAIL_SCHEME=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_FROM_ADDRESS="${MAIL_USERNAME}"
MAIL_FROM_NAME="Event Apparel"
```

For Gmail, generate an **App Password** (Google Account → Security → 2-Step Verification → App passwords) — the account password will not authenticate over SMTP. If SMTP fails, messages land in `storage/logs/laravel.log` instead of erroring.

Test delivery:

```bash
php artisan mail:test you@example.com
```

## Development

```bash
php artisan serve
npm run dev
```

Tests: `php artisan test` (sqlite in-memory). OTP cleanup runs via `model:prune` on the daily schedule.


run = npm install
        npm run build
        cd C:\xampp\htdocs\gms
C:\xampp\php\php.exe artisan serve --port=8090

127.0.0.1:8090

Brevo - otp smtp website