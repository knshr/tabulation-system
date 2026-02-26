# Real-Time Data Tabulation Platform - Design Document

**Date:** 2026-02-26
**Repository:** https://github.com/knshr/tabulation-system.git

## Overview

A real-time data tabulation platform for general competitions (pageants, talent shows, hackathons, etc.). Judges score contestants on configurable criteria, scores broadcast in real-time, and admins generate comprehensive reports.

## Tech Stack

- **Backend:** Laravel 11 (PHP 8.2+)
- **Frontend:** Vue 3 (Composition API, `<script setup>`)
- **SPA Bridge:** InertiaJS 2.x
- **API Auth:** Laravel Passport (OAuth2)
- **WebSockets:** Laravel Reverb
- **CSS:** Tailwind CSS 4
- **Build:** Vite
- **Database:** MySQL 8

## Architecture: Monolith with Inertia

Single Laravel application. Inertia serves Vue pages for web users. Passport provides API endpoints for future mobile/tablet judge apps. Reverb handles real-time score broadcasting.

### Backend Responsibility Split

The backend is **fully functional** — all controllers, services, repositories, validation, authorization, and API endpoints are complete. Vue pages are **empty shells** that receive Inertia props but contain no design. A frontend developer will build the UI.

## Project Structure

### Backend

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Api/V1/              # Passport-protected API controllers
│   │   │   ├── AuthController.php
│   │   │   ├── EventController.php
│   │   │   └── ScoreController.php
│   │   └── Web/                 # Inertia web controllers
│   │       ├── DashboardController.php
│   │       ├── EventController.php
│   │       ├── ContestantController.php
│   │       ├── CriteriaController.php
│   │       ├── JudgeController.php
│   │       ├── ScoringController.php
│   │       ├── ReportController.php
│   │       ├── ScoreboardController.php
│   │       └── UserController.php
│   ├── Requests/                # Form request validation classes
│   ├── Resources/               # API resource transformers
│   └── Middleware/
│       └── RoleMiddleware.php
├── Models/
│   ├── User.php
│   ├── Event.php
│   ├── Contestant.php
│   ├── Criteria.php
│   └── Score.php
├── Services/                    # Business logic
│   ├── EventService.php
│   ├── ContestantService.php
│   ├── CriteriaService.php
│   ├── ScoringService.php
│   ├── ReportService.php
│   └── UserService.php
├── Repositories/
│   ├── Contracts/               # Interfaces
│   │   ├── EventRepositoryInterface.php
│   │   ├── ContestantRepositoryInterface.php
│   │   ├── CriteriaRepositoryInterface.php
│   │   ├── ScoreRepositoryInterface.php
│   │   ├── ScoreQueryInterface.php
│   │   └── UserRepositoryInterface.php
│   ├── EloquentEventRepository.php
│   ├── EloquentContestantRepository.php
│   ├── EloquentCriteriaRepository.php
│   ├── EloquentScoreRepository.php
│   └── EloquentUserRepository.php
├── Policies/
│   ├── EventPolicy.php
│   ├── ContestantPolicy.php
│   ├── CriteriaPolicy.php
│   ├── ScorePolicy.php
│   └── UserPolicy.php
├── Events/                      # Broadcasting events
│   ├── ScoreSubmitted.php
│   └── EventStatusChanged.php
├── Listeners/
│   └── ...
├── Enums/
│   ├── UserRole.php             # super_admin, admin, judge, viewer
│   ├── EventStatus.php          # draft, active, completed, archived
│   └── ScoringMode.php          # blind, open
└── Observers/
    └── ScoreObserver.php
```

### Frontend

```
resources/js/
├── app.js                       # Inertia app bootstrap
├── theme.js                     # Designer-editable theme config
├── Layouts/
│   ├── AppLayout.vue            # Authenticated app shell
│   ├── GuestLayout.vue          # Public pages (scoreboard)
│   └── AuthLayout.vue           # Login/register pages
├── Pages/
│   ├── Auth/
│   │   └── Login.vue
│   ├── Dashboard/
│   │   └── Index.vue
│   ├── Events/
│   │   ├── Index.vue
│   │   ├── Create.vue
│   │   ├── Show.vue
│   │   └── Edit.vue
│   ├── Contestants/
│   │   ├── Index.vue
│   │   └── Create.vue
│   ├── Criteria/
│   │   └── Index.vue
│   ├── Judges/
│   │   └── Index.vue
│   ├── Scoring/
│   │   └── Index.vue
│   ├── Scores/
│   │   └── Index.vue
│   ├── Reports/
│   │   ├── OverallRanking.vue
│   │   ├── JudgeScoresheet.vue
│   │   ├── ContestantDetail.vue
│   │   ├── CriteriaBreakdown.vue
│   │   ├── ScoreComparison.vue
│   │   ├── EventSummary.vue
│   │   └── AuditLog.vue
│   ├── Scoreboard/
│   │   └── Show.vue
│   └── Users/
│       ├── Index.vue
│       └── Create.vue
├── Components/                  # Reusable components (empty, for designer)
├── Composables/                 # Vue composables
│   └── useEcho.js               # Reverb/Echo helper
└── Stores/                      # Pinia stores (if needed)
```

## Data Model

### users
| Column | Type | Notes |
|--------|------|-------|
| id | bigint | PK |
| name | string | |
| email | string | unique |
| password | string | |
| role | enum | super_admin, admin, judge, viewer |
| avatar | string | nullable |
| phone | string | nullable |
| is_active | boolean | default true |
| timestamps | | |

### events
| Column | Type | Notes |
|--------|------|-------|
| id | bigint | PK |
| name | string | |
| description | text | nullable |
| venue | string | nullable |
| event_date | datetime | |
| status | enum | draft, active, completed, archived |
| scoring_mode | enum | blind, open |
| created_by | bigint | FK users |
| timestamps | | |

### contestants
| Column | Type | Notes |
|--------|------|-------|
| id | bigint | PK |
| name | string | |
| nickname | string | nullable |
| description | text | nullable |
| photo | string | nullable |
| contestant_number | integer | |
| timestamps | | |

### event_contestant (pivot)
| Column | Type | Notes |
|--------|------|-------|
| id | bigint | PK |
| event_id | bigint | FK events |
| contestant_id | bigint | FK contestants |
| order | integer | display order |
| timestamps | | |

### criteria
| Column | Type | Notes |
|--------|------|-------|
| id | bigint | PK |
| event_id | bigint | FK events |
| name | string | |
| description | text | nullable |
| max_score | decimal(8,2) | |
| percentage_weight | decimal(5,2) | |
| order | integer | display order |
| timestamps | | |

### scores
| Column | Type | Notes |
|--------|------|-------|
| id | bigint | PK |
| event_id | bigint | FK events |
| judge_id | bigint | FK users |
| contestant_id | bigint | FK contestants |
| criteria_id | bigint | FK criteria |
| score | decimal(8,2) | |
| remarks | text | nullable |
| timestamps | | |
| | | UNIQUE(event_id, judge_id, contestant_id, criteria_id) |

## User Roles

| Role | Capabilities |
|------|-------------|
| **SuperAdmin** | Full system access, manage users, all reports, audit log |
| **Admin** | Manage events, contestants, criteria, assign judges, view reports |
| **Judge** | Score contestants in assigned events only |
| **Viewer** | View public scoreboards only |

## Routes

### Web Routes (Inertia)

| Method | Route | Controller | Middleware |
|--------|-------|-----------|-----------|
| GET | `/login` | Auth | guest |
| POST | `/login` | Auth | guest |
| POST | `/logout` | Auth | auth |
| GET | `/` | DashboardController@index | auth |
| GET | `/events` | EventController@index | auth, role:super_admin,admin |
| GET | `/events/create` | EventController@create | auth, role:super_admin,admin |
| POST | `/events` | EventController@store | auth, role:super_admin,admin |
| GET | `/events/{event}` | EventController@show | auth |
| GET | `/events/{event}/edit` | EventController@edit | auth, role:super_admin,admin |
| PUT | `/events/{event}` | EventController@update | auth, role:super_admin,admin |
| DELETE | `/events/{event}` | EventController@destroy | auth, role:super_admin,admin |
| GET | `/events/{event}/contestants` | ContestantController@index | auth, role:super_admin,admin |
| GET | `/events/{event}/contestants/create` | ContestantController@create | auth, role:super_admin,admin |
| POST | `/events/{event}/contestants` | ContestantController@store | auth, role:super_admin,admin |
| DELETE | `/events/{event}/contestants/{contestant}` | ContestantController@destroy | auth, role:super_admin,admin |
| GET | `/events/{event}/criteria` | CriteriaController@index | auth, role:super_admin,admin |
| POST | `/events/{event}/criteria` | CriteriaController@store | auth, role:super_admin,admin |
| PUT | `/events/{event}/criteria/{criteria}` | CriteriaController@update | auth, role:super_admin,admin |
| DELETE | `/events/{event}/criteria/{criteria}` | CriteriaController@destroy | auth, role:super_admin,admin |
| GET | `/events/{event}/judges` | JudgeController@index | auth, role:super_admin,admin |
| POST | `/events/{event}/judges` | JudgeController@assign | auth, role:super_admin,admin |
| DELETE | `/events/{event}/judges/{judge}` | JudgeController@remove | auth, role:super_admin,admin |
| GET | `/events/{event}/scoring` | ScoringController@index | auth, role:judge |
| POST | `/events/{event}/scoring` | ScoringController@store | auth, role:judge |
| GET | `/events/{event}/scores` | ScoreController@index | auth, role:super_admin,admin |
| GET | `/reports/overall-ranking/{event}` | ReportController@overallRanking | auth, role:super_admin,admin |
| GET | `/reports/judge-scoresheet/{event}` | ReportController@judgeScoresheet | auth, role:super_admin,admin |
| GET | `/reports/contestant-detail/{event}` | ReportController@contestantDetail | auth, role:super_admin,admin |
| GET | `/reports/criteria-breakdown/{event}` | ReportController@criteriaBreakdown | auth, role:super_admin,admin |
| GET | `/reports/score-comparison/{event}` | ReportController@scoreComparison | auth, role:super_admin,admin |
| GET | `/reports/event-summary/{event}` | ReportController@eventSummary | auth, role:super_admin,admin |
| GET | `/reports/audit-log/{event}` | ReportController@auditLog | auth, role:super_admin |
| GET | `/scoreboard/{event}` | ScoreboardController@show | (public) |
| GET | `/users` | UserController@index | auth, role:super_admin |
| GET | `/users/create` | UserController@create | auth, role:super_admin |
| POST | `/users` | UserController@store | auth, role:super_admin |
| PUT | `/users/{user}` | UserController@update | auth, role:super_admin |
| DELETE | `/users/{user}` | UserController@destroy | auth, role:super_admin |

### API Routes (Passport)

| Method | Route | Controller | Scope |
|--------|-------|-----------|-------|
| POST | `/api/v1/auth/login` | Api\V1\AuthController@login | - |
| GET | `/api/v1/events` | Api\V1\EventController@index | auth:api |
| GET | `/api/v1/events/{event}` | Api\V1\EventController@show | auth:api |
| GET | `/api/v1/events/{event}/scoring` | Api\V1\ScoreController@index | auth:api, role:judge |
| POST | `/api/v1/events/{event}/scores` | Api\V1\ScoreController@store | auth:api, role:judge |
| GET | `/api/v1/events/{event}/scores` | Api\V1\ScoreController@scores | auth:api |

## SOLID Patterns

### Single Responsibility Principle (SRP)

Each class has one reason to change:

- **Controllers** — HTTP layer only (receive request, delegate to service, return Inertia/JSON response)
- **Services** — Business logic only (validation rules, calculations, orchestration)
- **Repositories** — Data access only (queries, persistence)
- **Form Requests** — Input validation only
- **Policies** — Authorization only
- **Events/Listeners** — Side effects (broadcasting, notifications)

### Open/Closed Principle (OCP)

- **Report generation:** Each report type is a separate class implementing `ReportGeneratorInterface`. Adding a new report = adding a new class, not modifying existing ones.
- **Scoring strategies:** Different calculation methods (weighted average, rank-based) implement `ScoringStrategyInterface`.

### Liskov Substitution Principle (LSP)

- All repository implementations are interchangeable via their interfaces. `EloquentScoreRepository` can be swapped with `CacheDecoratedScoreRepository` without breaking consumers.

### Interface Segregation Principle (ISP)

- `ScoreRepositoryInterface` — write operations (save, update, delete)
- `ScoreQueryInterface` — read/reporting operations (getByEvent, getByJudge, aggregate)
- Clients depend only on the interface they need.

### Dependency Inversion Principle (DIP)

- Services depend on repository **interfaces**, not Eloquent models directly.
- Bindings registered in `AppServiceProvider`:
  ```php
  $this->app->bind(ScoreRepositoryInterface::class, EloquentScoreRepository::class);
  ```

### Example Flow: Judge Submits Score

```
1. POST /events/{event}/scoring
2. ScoringController receives request
3. StoreScoreRequest validates input (FormRequest)
4. ScorePolicy::create() checks judge is assigned to event
5. ScoringService::submitScore() applies business rules
   - Validates score is within criteria max_score
   - Checks event is active
   - Checks judge hasn't already scored this contestant/criteria
6. ScoreRepository::save() persists to database
7. ScoreSubmitted event dispatched → Reverb broadcasts to channel
8. Controller returns Inertia redirect (or JSON for API)
```

## Real-Time Broadcasting

**Technology:** Laravel Reverb (WebSocket server)

**Channels:**
- `private-event.{eventId}.scores` — Score updates (admins + judges if scoring_mode=open)
- `presence-event.{eventId}.judges` — Active judge tracking
- `event.{eventId}.scoreboard` — Public scoreboard updates (viewers)

**Events broadcast:**
- `ScoreSubmitted` — When a judge submits/updates a score
- `EventStatusChanged` — When event status changes (draft→active→completed)

## Theme Configuration

The designer edits `resources/js/theme.js` to customize the entire app:

```js
export default {
  colors: {
    primary: '#3B82F6',
    secondary: '#6366F1',
    accent: '#F59E0B',
    success: '#10B981',
    danger: '#EF4444',
    warning: '#F59E0B',
    info: '#3B82F6',
    background: '#F9FAFB',
    surface: '#FFFFFF',
    text: '#111827',
    'text-muted': '#6B7280',
  },
  fonts: {
    sans: 'Inter, system-ui, sans-serif',
    heading: 'Inter, system-ui, sans-serif',
  },
  borderRadius: {
    sm: '0.25rem',
    md: '0.375rem',
    lg: '0.5rem',
  },
}
```

This is consumed by `tailwind.config.js` to generate utility classes.

## Frontend Developer Handoff

**What's ready for the frontend developer:**
1. All Vue pages exist as empty shells with documented Inertia props
2. Three layouts (AppLayout, GuestLayout, AuthLayout) with empty slots
3. `theme.js` for customizing colors/fonts
4. `useEcho.js` composable for subscribing to real-time channels
5. All backend endpoints are functional and return proper data

**What the frontend developer builds:**
1. UI design and component library
2. Page layouts and responsive design
3. Form components and interactions
4. Real-time UI updates using the composable
5. Charts/visualizations for reports

## Reports

| Report | Description | Data Source |
|--------|-------------|------------|
| Overall Ranking | Final contestant rankings with weighted scores | scores + criteria weights |
| Judge Scoresheet | Individual judge's complete scoring record | scores filtered by judge |
| Contestant Detail | All scores a contestant received | scores filtered by contestant |
| Criteria Breakdown | Score distribution per criteria | scores grouped by criteria |
| Score Comparison | Side-by-side judge scores for discrepancy analysis | scores pivoted by judge |
| Event Summary | High-level stats (completion %, averages) | aggregate queries |
| Audit Log | Score submission/edit history with timestamps | scores with timestamps |
