<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<h1 align="center">Horizon Lab</h1>

<p align="center">A biomedical equipment service management platform connecting healthcare facilities with field engineers.</p>

## About Horizon Lab

Horizon Lab is a Laravel application that helps healthcare facilities manage the maintenance, calibration, and repair of their biomedical equipment. It brings together three types of users on a single platform:

- **Healthy Facility** — hospitals and clinics that submit service requests, track equipment, and review service history for their biomedical devices.
- **Engineer** — field engineers who receive assigned service requests, submit service reports, and manage their toolkit, credentials, and ratings.
- **Admin** — platform administrators who approve engineers, assign service requests, oversee facilities, and manage platform-wide settings and reports.

### Key features

- Facility dashboards summarizing total, in-progress, pending, and completed service requests, with breakdowns by service type (maintenance, calibration, repair).
- Equipment tracking, including alerts for equipment due for service.
- Service request creation, assignment, and tracking from submission through completion.
- Engineer onboarding with credential verification, toolkits, and performance ratings.
- Service reports with parts usage and attachments.
- Role-based authentication for facilities, engineers, and admins, each with dedicated login and dashboard flows.
- Public marketing pages for products (medical beds, patient handling, physiotherapy, pressure care, etc.) and company information.

### Tech stack

- [Laravel 13](https://laravel.com) running on PHP 8.3+
- [Pest](https://pestphp.com) for testing and [Laravel Pint](https://laravel.com/docs/pint) for code style
- [Vite](https://vite.dev) for frontend asset bundling
- SQLite by default for local development (configurable via `.env`)
## Getting started

```bash
composer install
npm install

cp .env.example .env
php artisan key:generate
php artisan migrate

npm run build
```

Start the application with:

```bash
php artisan serve
```

The app will be available at [http://127.0.0.1:8000](http://127.0.0.1:8000). Sign in through one of the role-specific login routes: `/facility/login`, `/engineer/login`, or `/admin/login`.

If you're working on frontend assets and want hot reloading, run Vite in a second terminal instead of using `npm run build`:

```bash
npm run dev
```

Alternatively, to run the server, queue worker, and Vite together in one command:

```bash
composer run dev
```