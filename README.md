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
