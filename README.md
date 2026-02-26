# Tabulation System

A real-time data tabulation platform for managing events, scoring, and live scoreboards. Built with Laravel 12, Vue 3, InertiaJS, and WebSockets.

## Tech Stack

- **Backend:** Laravel 12, PHP 8.2+
- **Frontend:** Vue 3 (Composition API), InertiaJS
- **Styling:** Tailwind CSS 4, daisyUI 5
- **Auth:** Laravel Passport (API tokens + session auth)
- **Real-time:** Laravel Reverb (WebSockets)
- **Database:** MySQL
- **Notifications:** vue-sonner (toasts)

## Features

- **Event Management** — Create and manage tabulation events with contestants, criteria, and judges
- **Role-Based Access** — Super Admin, Admin, Judge, Viewer with route-level and policy-level authorization
- **Judge Scoring** — Score input grid (contestant x criteria matrix) with blind/open scoring modes
- **Live Scoreboard** — Public real-time scoreboard with WebSocket-powered rank updates
- **Reports** — Overall ranking, judge scoresheets, contestant details, criteria breakdown, score comparison, event summary, audit log
- **Confirm Modals** — daisyUI dialog-based confirm/alert system replacing native browser dialogs
- **Toast Notifications** — Automatic flash message toasts + manual toast API

## Architecture

The backend follows SOLID principles:

- **Repository Pattern** — Eloquent repositories with contract interfaces
- **Service Layer** — Business logic in dedicated service classes
- **Form Requests** — Validation in request classes
- **Policies** — Authorization logic per model
- **Enums** — UserRole, EventStatus, ScoringMode

## Prerequisites

- PHP 8.2+
- Composer
- Node.js 18+
- MySQL
- Laravel Reverb (for real-time features)

## Installation

```bash
# Clone the repository
git clone <repo-url> tabulation-system
cd tabulation-system

# Install PHP dependencies
composer install

# Install JS dependencies
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Configure your database in .env, then:
php artisan migrate --seed

# Generate Passport keys
php artisan passport:keys
```

## Development

```bash
# Start all services
php artisan serve        # Laravel dev server
npm run dev              # Vite dev server with HMR
php artisan reverb:start # WebSocket server (for real-time features)
```

## Project Structure

```
app/
├── Enums/                  # UserRole, EventStatus, ScoringMode
├── Events/                 # ScoreSubmitted, EventStatusChanged (broadcasting)
├── Http/
│   ├── Controllers/
│   │   ├── Api/V1/         # API controllers (Passport-protected)
│   │   └── Web/            # Inertia web controllers
│   ├── Middleware/          # RoleMiddleware, HandleInertiaRequests
│   └── Requests/           # Form request validation
├── Models/                 # User, Event, Contestant, Criteria, Score
├── Policies/               # EventPolicy, ScorePolicy, UserPolicy
├── Reports/                # 7 report generators with contract interface
├── Repositories/           # Eloquent repositories with contracts
└── Services/               # Business logic layer

resources/js/
├── Components/             # ConfirmModal (globally mounted)
├── Composables/            # 11 composables (useAuth, useEvents, useScoring, etc.)
├── Layouts/                # AppLayout, AuthLayout, GuestLayout
├── Pages/                  # 25 page components (shells ready for design)
├── app.js                  # App entry point
├── bootstrap.js            # Axios + Echo setup
└── theme.js                # Theme configuration for designer

docs/
├── DESIGNER-GUIDE.md       # Frontend design guide with composable reference
└── SOLID-GUIDE.md          # Backend architecture guide
```

## User Roles

| Role | Access |
|---|---|
| Super Admin | Full access + user management + audit log |
| Admin | Events, contestants, criteria, judges, scores, reports |
| Judge | Dashboard + scoring interface |
| Viewer | Dashboard (read-only) |

## Frontend Status

The backend is fully functional. Vue pages are **empty shells** with composables wired in, waiting for UI design. Each page has JSDoc comments with suggested daisyUI components.

See [docs/DESIGNER-GUIDE.md](docs/DESIGNER-GUIDE.md) for the full frontend design guide including composable reference, route names, InertiaJS navigation, and daisyUI patterns.

## License

This project is proprietary software.
