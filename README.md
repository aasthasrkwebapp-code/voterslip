# Voter E-Slip

Laravel 12 app for voter lists, admin PDF import, and voter e-slips (View / Print PDF).

## Ward 1 voter data (Shahjahanpur, Kotputli-Behror)

`database/data/ward1_voters.json` holds all 363 voters extracted from the Ward No-001 PDF (pages 3–22), with photos embedded as base64. All text is kept in Hindi exactly as in the PDF. All voters are booth 84, room 01.

- Voters with a `DELETED` / `E` / `S` status keep it in the `status` column.
- 16 voters have no EPIC in the PDF, so they can't be searched by EPIC.

## Setup (Windows / XAMPP)

1. Install dependencies:
   ```
   composer install
   composer require mpdf/mpdf:^8.2
   ```
2. Copy `.env.example` to `.env`, then run `php artisan key:generate` and set your DB settings.
3. Copy the Hindi font for the slip PDF:
   copy `C:\Windows\Fonts\Nirmala.ttf` into `storage\fonts\Nirmala.ttf` (create the `storage\fonts` folder if needed).
4. Run migrations and load Ward 1:
   ```
   php artisan migrate
   php artisan db:seed --class=WardOneVoterSeeder
   ```
   This creates the election, wards 1–16 (the booth-to-ward mapping for booths 84–87), the polling stations, the 363 voters, and their photos under `public/voter-photos/ward1/`.
5. Start the app: `php artisan serve`, then open
   `http://127.0.0.1:8000/voter-search?epic=IED1540749`.

## Voter search and e-slip

- `GET /voter-search?epic=...` shows the voter card for an EPIC number.
- The card has two buttons: **View E-Slip** (PDF opens in a new tab) and **Print Slip** (PDF downloads).
- `GET /voters/{voter}/slip` renders the PDF inline. Add `?download=1` to download it.

## Admin

- Admin login at `/admin/login`. PDF uploads are under `/admin/pdf-imports/create`.

## Deploy to server

1. Copy the project folder to the server (the `public/` folder must be the web root).
2. Install PHP dependencies without dev packages:
   ```
   composer install --no-dev --optimize-autoloader
   composer require mpdf/mpdf:^8.2
   ```
3. Create `.env` from `.env.example`, set `APP_ENV=production`, `APP_DEBUG=false`, your `APP_URL`, and the database settings. Then run:
   ```
   php artisan key:generate
   ```
4. Make these folders writable by the web server user: `storage/` and `bootstrap/cache/`.
5. Copy the Hindi font into `storage/fonts/`:
   `storage/fonts/NotoSansDevanagari-Regular.ttf` (download from Google Fonts, or copy from `Noto_Sans_Devanagari/static/` if it is in the project).
6. Run the database steps:
   ```
   php artisan migrate --force
   php artisan db:seed --class=WardOneVoterSeeder --force
   ```
7. Clear caches:
   ```
   php artisan optimize:clear
   php artisan config:cache
   php artisan route:cache
   ```
8. Point the web server (Apache/Nginx) document root at the `public/` folder.
9. Check `https://your-domain/voter-search?epic=IED1540749`, then the View E-Slip and Print Slip buttons, and `https://your-domain/voter-slips?ward=1&page=1`.

Notes:
- Keep `.env` out of git. It holds the database password and `APP_KEY`.
- The seeder can be run again safely. It updates existing rows rather than duplicating them.
