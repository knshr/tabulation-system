# Tabulation System Implementation Plan

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Build a real-time competition tabulation platform with Laravel 11 + Vue 3 + Inertia + Passport + Reverb + Tailwind CSS 4.

**Architecture:** Monolith with Inertia. SOLID layered architecture: Controllers (HTTP) → FormRequests (validation) → Policies (auth) → Services (logic) → Repositories (data) → Models. Backend fully functional, Vue pages are empty shells for frontend developer.

**Tech Stack:** PHP 8.2, Laravel 11, Vue 3 (Composition API), InertiaJS 2.x, Laravel Passport, Laravel Reverb, Tailwind CSS 4, Vite, MySQL 8

**Note:** User handles all git commits. Do NOT run git commit commands.

---

## Task 1: Scaffold Laravel Project with Vue + Inertia + Tailwind

**Files:**
- Create: `tabulation-system/` (Laravel project root)

**Step 1: Create Laravel project**

Run:
```bash
cd "D:/Documents/Programming/WebCodes/VueJs" && composer create-project laravel/laravel tabulation-system
```

**Step 2: Install PHP dependencies**

Run:
```bash
cd "D:/Documents/Programming/WebCodes/VueJs/tabulation-system" && composer require inertiajs/inertia-laravel laravel/passport laravel/reverb tightenco/ziggy
```

**Step 3: Install NPM dependencies**

Run:
```bash
cd "D:/Documents/Programming/WebCodes/VueJs/tabulation-system" && npm install vue @vitejs/plugin-vue @inertiajs/vue3 tailwindcss @tailwindcss/vite laravel-echo pusher-js
```

**Step 4: Configure Vite**

Modify: `vite.config.js`

```js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        tailwindcss(),
    ],
});
```

**Step 5: Configure Inertia server-side middleware**

Run:
```bash
cd "D:/Documents/Programming/WebCodes/VueJs/tabulation-system" && php artisan inertia:middleware
```

Then register `HandleInertiaRequests` middleware in `bootstrap/app.php`:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->web(append: [
        \App\Http\Middleware\HandleInertiaRequests::class,
    ]);
})
```

**Step 6: Create Inertia root blade template**

Modify: `resources/views/app.blade.php`

```blade
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Tabulation System') }}</title>
    @routes
    @vite(['resources/js/app.js'])
    @inertiaHead
</head>
<body>
    @inertia
</body>
</html>
```

**Step 7: Create Vue app entry point**

Create: `resources/js/app.js`

```js
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import '../css/app.css';

createInertiaApp({
    title: (title) => title ? `${title} - Tabulation System` : 'Tabulation System',
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#3B82F6',
    },
});
```

**Step 8: Add Tailwind import to CSS**

Modify: `resources/css/app.css`

```css
@import "tailwindcss";
```

**Step 9: Verify scaffold works**

Run:
```bash
cd "D:/Documents/Programming/WebCodes/VueJs/tabulation-system" && npm run build
```
Expected: Build completes without errors.

---

## Task 2: Theme Config + Layouts

**Files:**
- Create: `resources/js/theme.js`
- Create: `resources/js/Layouts/AppLayout.vue`
- Create: `resources/js/Layouts/GuestLayout.vue`
- Create: `resources/js/Layouts/AuthLayout.vue`

**Step 1: Create theme config file**

Create: `resources/js/theme.js`

```js
/**
 * Theme Configuration
 * -------------------
 * Designer: Edit this file to customize the app's look and feel.
 * These values are used by Tailwind CSS via tailwind.config.js.
 *
 * After editing, run `npm run build` (or `npm run dev`) to apply changes.
 */
export default {
    colors: {
        primary: '#3B82F6',
        'primary-hover': '#2563EB',
        'primary-light': '#DBEAFE',
        secondary: '#6366F1',
        'secondary-hover': '#4F46E5',
        'secondary-light': '#E0E7FF',
        accent: '#F59E0B',
        'accent-hover': '#D97706',
        success: '#10B981',
        'success-light': '#D1FAE5',
        danger: '#EF4444',
        'danger-light': '#FEE2E2',
        warning: '#F59E0B',
        'warning-light': '#FEF3C7',
        info: '#3B82F6',
        'info-light': '#DBEAFE',
        background: '#F9FAFB',
        surface: '#FFFFFF',
        'surface-alt': '#F3F4F6',
        border: '#E5E7EB',
        text: '#111827',
        'text-secondary': '#4B5563',
        'text-muted': '#6B7280',
        'text-inverse': '#FFFFFF',
    },
    fonts: {
        sans: 'Inter, system-ui, -apple-system, sans-serif',
        heading: 'Inter, system-ui, -apple-system, sans-serif',
        mono: 'ui-monospace, SFMono-Regular, monospace',
    },
    borderRadius: {
        sm: '0.25rem',
        DEFAULT: '0.375rem',
        md: '0.5rem',
        lg: '0.75rem',
        xl: '1rem',
    },
};
```

**Step 2: Create AppLayout (authenticated shell)**

Create: `resources/js/Layouts/AppLayout.vue`

```vue
<script setup>
/**
 * AppLayout - Authenticated application shell
 *
 * Props available from Inertia shared data:
 * - auth.user (current user object with id, name, email, role)
 *
 * Designer: Build sidebar navigation, top bar, user menu here.
 * Use theme.js colors via Tailwind utility classes.
 */
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const user = page.props.auth?.user;
</script>

<template>
    <div>
        <!-- Designer: Add sidebar, navigation, top bar here -->
        <main>
            <slot />
        </main>
    </div>
</template>
```

**Step 3: Create GuestLayout (public pages)**

Create: `resources/js/Layouts/GuestLayout.vue`

```vue
<script setup>
/**
 * GuestLayout - Public pages (scoreboard, public views)
 *
 * No authentication required.
 * Designer: Build a minimal public-facing layout here.
 */
</script>

<template>
    <div>
        <!-- Designer: Add public header/footer here -->
        <main>
            <slot />
        </main>
    </div>
</template>
```

**Step 4: Create AuthLayout (login/register)**

Create: `resources/js/Layouts/AuthLayout.vue`

```vue
<script setup>
/**
 * AuthLayout - Authentication pages (login, register, forgot password)
 *
 * Designer: Build a centered card layout for auth forms here.
 */
</script>

<template>
    <div>
        <!-- Designer: Add centered auth card layout here -->
        <main>
            <slot />
        </main>
    </div>
</template>
```

**Step 5: Verify build**

Run:
```bash
cd "D:/Documents/Programming/WebCodes/VueJs/tabulation-system" && npm run build
```
Expected: Build completes without errors.

---

## Task 3: Enums + Database Migrations

**Files:**
- Create: `app/Enums/UserRole.php`
- Create: `app/Enums/EventStatus.php`
- Create: `app/Enums/ScoringMode.php`
- Modify: `database/migrations/0001_01_01_000000_create_users_table.php`
- Create: migrations for events, contestants, event_contestant, criteria, scores

**Step 1: Create UserRole enum**

Create: `app/Enums/UserRole.php`

```php
<?php

namespace App\Enums;

enum UserRole: string
{
    case SuperAdmin = 'super_admin';
    case Admin = 'admin';
    case Judge = 'judge';
    case Viewer = 'viewer';

    public function label(): string
    {
        return match ($this) {
            self::SuperAdmin => 'Super Admin',
            self::Admin => 'Admin',
            self::Judge => 'Judge',
            self::Viewer => 'Viewer',
        };
    }
}
```

**Step 2: Create EventStatus enum**

Create: `app/Enums/EventStatus.php`

```php
<?php

namespace App\Enums;

enum EventStatus: string
{
    case Draft = 'draft';
    case Active = 'active';
    case Completed = 'completed';
    case Archived = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Active => 'Active',
            self::Completed => 'Completed',
            self::Archived => 'Archived',
        };
    }
}
```

**Step 3: Create ScoringMode enum**

Create: `app/Enums/ScoringMode.php`

```php
<?php

namespace App\Enums;

enum ScoringMode: string
{
    case Blind = 'blind';
    case Open = 'open';

    public function label(): string
    {
        return match ($this) {
            self::Blind => 'Blind (scores hidden from other judges)',
            self::Open => 'Open (all scores visible)',
        };
    }
}
```

**Step 4: Modify users migration**

Modify the existing users migration to add role, avatar, phone, is_active columns:

```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->timestamp('email_verified_at')->nullable();
    $table->string('password');
    $table->string('role')->default('viewer');
    $table->string('avatar')->nullable();
    $table->string('phone')->nullable();
    $table->boolean('is_active')->default(true);
    $table->rememberToken();
    $table->timestamps();
});
```

**Step 5: Create events migration**

Run:
```bash
php artisan make:migration create_events_table
```

```php
Schema::create('events', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->text('description')->nullable();
    $table->string('venue')->nullable();
    $table->dateTime('event_date');
    $table->string('status')->default('draft');
    $table->string('scoring_mode')->default('blind');
    $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
    $table->timestamps();
});
```

**Step 6: Create contestants migration**

Run:
```bash
php artisan make:migration create_contestants_table
```

```php
Schema::create('contestants', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('nickname')->nullable();
    $table->text('description')->nullable();
    $table->string('photo')->nullable();
    $table->integer('contestant_number');
    $table->timestamps();
});
```

**Step 7: Create event_contestant pivot migration**

Run:
```bash
php artisan make:migration create_event_contestant_table
```

```php
Schema::create('event_contestant', function (Blueprint $table) {
    $table->id();
    $table->foreignId('event_id')->constrained()->cascadeOnDelete();
    $table->foreignId('contestant_id')->constrained()->cascadeOnDelete();
    $table->integer('order')->default(0);
    $table->timestamps();
    $table->unique(['event_id', 'contestant_id']);
});
```

**Step 8: Create criteria migration**

Run:
```bash
php artisan make:migration create_criteria_table
```

```php
Schema::create('criteria', function (Blueprint $table) {
    $table->id();
    $table->foreignId('event_id')->constrained()->cascadeOnDelete();
    $table->string('name');
    $table->text('description')->nullable();
    $table->decimal('max_score', 8, 2);
    $table->decimal('percentage_weight', 5, 2);
    $table->integer('order')->default(0);
    $table->timestamps();
});
```

**Step 9: Create event_judge pivot migration**

Run:
```bash
php artisan make:migration create_event_judge_table
```

```php
Schema::create('event_judge', function (Blueprint $table) {
    $table->id();
    $table->foreignId('event_id')->constrained()->cascadeOnDelete();
    $table->foreignId('judge_id')->constrained('users')->cascadeOnDelete();
    $table->boolean('is_active')->default(true);
    $table->timestamps();
    $table->unique(['event_id', 'judge_id']);
});
```

**Step 10: Create scores migration**

Run:
```bash
php artisan make:migration create_scores_table
```

```php
Schema::create('scores', function (Blueprint $table) {
    $table->id();
    $table->foreignId('event_id')->constrained()->cascadeOnDelete();
    $table->foreignId('judge_id')->constrained('users')->cascadeOnDelete();
    $table->foreignId('contestant_id')->constrained()->cascadeOnDelete();
    $table->foreignId('criteria_id')->constrained()->cascadeOnDelete();
    $table->decimal('score', 8, 2);
    $table->text('remarks')->nullable();
    $table->timestamps();
    $table->unique(['event_id', 'judge_id', 'contestant_id', 'criteria_id'], 'scores_unique_entry');
});
```

**Step 11: Configure .env and run migrations**

Update `.env` with MySQL credentials, then:
```bash
php artisan migrate
```
Expected: All migrations run successfully.

---

## Task 4: Eloquent Models

**Files:**
- Modify: `app/Models/User.php`
- Create: `app/Models/Event.php`
- Create: `app/Models/Contestant.php`
- Create: `app/Models/Criteria.php`
- Create: `app/Models/Score.php`

**Step 1: Update User model**

```php
<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'avatar', 'phone', 'is_active',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
            'is_active' => 'boolean',
        ];
    }

    public function createdEvents(): HasMany
    {
        return $this->hasMany(Event::class, 'created_by');
    }

    public function judgingEvents(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_judge', 'judge_id')
            ->withPivot('is_active')
            ->withTimestamps();
    }

    public function scores(): HasMany
    {
        return $this->hasMany(Score::class, 'judge_id');
    }

    public function hasRole(UserRole ...$roles): bool
    {
        return in_array($this->role, $roles);
    }
}
```

**Step 2: Create Event model**

```php
<?php

namespace App\Models;

use App\Enums\EventStatus;
use App\Enums\ScoringMode;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends \Illuminate\Database\Eloquent\Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'venue', 'event_date', 'status', 'scoring_mode', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'datetime',
            'status' => EventStatus::class,
            'scoring_mode' => ScoringMode::class,
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function contestants(): BelongsToMany
    {
        return $this->belongsToMany(Contestant::class, 'event_contestant')
            ->withPivot('order')
            ->withTimestamps()
            ->orderByPivot('order');
    }

    public function judges(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'event_judge', 'event_id', 'judge_id')
            ->withPivot('is_active')
            ->withTimestamps();
    }

    public function criteria(): HasMany
    {
        return $this->hasMany(Criteria::class)->orderBy('order');
    }

    public function scores(): HasMany
    {
        return $this->hasMany(Score::class);
    }
}
```

**Step 3: Create Contestant model**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contestant extends \Illuminate\Database\Eloquent\Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'nickname', 'description', 'photo', 'contestant_number',
    ];

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_contestant')
            ->withPivot('order')
            ->withTimestamps();
    }

    public function scores(): HasMany
    {
        return $this->hasMany(Score::class);
    }
}
```

**Step 4: Create Criteria model**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Criteria extends \Illuminate\Database\Eloquent\Model
{
    use HasFactory;

    protected $table = 'criteria';

    protected $fillable = [
        'event_id', 'name', 'description', 'max_score', 'percentage_weight', 'order',
    ];

    protected function casts(): array
    {
        return [
            'max_score' => 'decimal:2',
            'percentage_weight' => 'decimal:2',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function scores(): HasMany
    {
        return $this->hasMany(Score::class);
    }
}
```

**Step 5: Create Score model**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Score extends \Illuminate\Database\Eloquent\Model
{
    use HasFactory;

    protected $fillable = [
        'event_id', 'judge_id', 'contestant_id', 'criteria_id', 'score', 'remarks',
    ];

    protected function casts(): array
    {
        return [
            'score' => 'decimal:2',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function judge(): BelongsTo
    {
        return $this->belongsTo(User::class, 'judge_id');
    }

    public function contestant(): BelongsTo
    {
        return $this->belongsTo(Contestant::class);
    }

    public function criteria(): BelongsTo
    {
        return $this->belongsTo(Criteria::class);
    }
}
```

---

## Task 5: Repository Interfaces + Implementations

**Files:**
- Create: `app/Repositories/Contracts/EventRepositoryInterface.php`
- Create: `app/Repositories/Contracts/ContestantRepositoryInterface.php`
- Create: `app/Repositories/Contracts/CriteriaRepositoryInterface.php`
- Create: `app/Repositories/Contracts/ScoreRepositoryInterface.php`
- Create: `app/Repositories/Contracts/ScoreQueryInterface.php`
- Create: `app/Repositories/Contracts/UserRepositoryInterface.php`
- Create: `app/Repositories/EloquentEventRepository.php`
- Create: `app/Repositories/EloquentContestantRepository.php`
- Create: `app/Repositories/EloquentCriteriaRepository.php`
- Create: `app/Repositories/EloquentScoreRepository.php`
- Create: `app/Repositories/EloquentUserRepository.php`

**Step 1: EventRepositoryInterface**

```php
<?php

namespace App\Repositories\Contracts;

use App\Models\Event;
use Illuminate\Database\Eloquent\Collection;

interface EventRepositoryInterface
{
    public function all(): Collection;
    public function findOrFail(int $id): Event;
    public function create(array $data): Event;
    public function update(int $id, array $data): Event;
    public function delete(int $id): bool;
    public function findByStatus(string $status): Collection;
}
```

**Step 2: ContestantRepositoryInterface**

```php
<?php

namespace App\Repositories\Contracts;

use App\Models\Contestant;
use Illuminate\Database\Eloquent\Collection;

interface ContestantRepositoryInterface
{
    public function all(): Collection;
    public function findOrFail(int $id): Contestant;
    public function create(array $data): Contestant;
    public function update(int $id, array $data): Contestant;
    public function delete(int $id): bool;
    public function findByEvent(int $eventId): Collection;
}
```

**Step 3: CriteriaRepositoryInterface**

```php
<?php

namespace App\Repositories\Contracts;

use App\Models\Criteria;
use Illuminate\Database\Eloquent\Collection;

interface CriteriaRepositoryInterface
{
    public function findOrFail(int $id): Criteria;
    public function create(array $data): Criteria;
    public function update(int $id, array $data): Criteria;
    public function delete(int $id): bool;
    public function findByEvent(int $eventId): Collection;
}
```

**Step 4: ScoreRepositoryInterface (write)**

```php
<?php

namespace App\Repositories\Contracts;

use App\Models\Score;

interface ScoreRepositoryInterface
{
    public function save(array $data): Score;
    public function update(int $id, array $data): Score;
    public function delete(int $id): bool;
}
```

**Step 5: ScoreQueryInterface (read)**

```php
<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface ScoreQueryInterface
{
    public function findByEvent(int $eventId): Collection;
    public function findByJudge(int $eventId, int $judgeId): Collection;
    public function findByContestant(int $eventId, int $contestantId): Collection;
    public function findByCriteria(int $eventId, int $criteriaId): Collection;
    public function getAggregateByContestant(int $eventId): Collection;
    public function getScoreMatrix(int $eventId): array;
}
```

**Step 6: UserRepositoryInterface**

```php
<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface
{
    public function all(): Collection;
    public function findOrFail(int $id): User;
    public function create(array $data): User;
    public function update(int $id, array $data): User;
    public function delete(int $id): bool;
    public function findByRole(string $role): Collection;
}
```

**Step 7: Implement EloquentEventRepository**

```php
<?php

namespace App\Repositories;

use App\Models\Event;
use App\Repositories\Contracts\EventRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EloquentEventRepository implements EventRepositoryInterface
{
    public function all(): Collection
    {
        return Event::with('creator')->latest('event_date')->get();
    }

    public function findOrFail(int $id): Event
    {
        return Event::with(['creator', 'contestants', 'judges', 'criteria'])->findOrFail($id);
    }

    public function create(array $data): Event
    {
        return Event::create($data);
    }

    public function update(int $id, array $data): Event
    {
        $event = Event::findOrFail($id);
        $event->update($data);
        return $event->fresh();
    }

    public function delete(int $id): bool
    {
        return Event::findOrFail($id)->delete();
    }

    public function findByStatus(string $status): Collection
    {
        return Event::where('status', $status)->with('creator')->latest('event_date')->get();
    }
}
```

**Step 8: Implement EloquentContestantRepository**

```php
<?php

namespace App\Repositories;

use App\Models\Contestant;
use App\Repositories\Contracts\ContestantRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EloquentContestantRepository implements ContestantRepositoryInterface
{
    public function all(): Collection
    {
        return Contestant::orderBy('contestant_number')->get();
    }

    public function findOrFail(int $id): Contestant
    {
        return Contestant::findOrFail($id);
    }

    public function create(array $data): Contestant
    {
        return Contestant::create($data);
    }

    public function update(int $id, array $data): Contestant
    {
        $contestant = Contestant::findOrFail($id);
        $contestant->update($data);
        return $contestant->fresh();
    }

    public function delete(int $id): bool
    {
        return Contestant::findOrFail($id)->delete();
    }

    public function findByEvent(int $eventId): Collection
    {
        return Contestant::whereHas('events', fn ($q) => $q->where('event_id', $eventId))
            ->orderBy('contestant_number')
            ->get();
    }
}
```

**Step 9: Implement EloquentCriteriaRepository**

```php
<?php

namespace App\Repositories;

use App\Models\Criteria;
use App\Repositories\Contracts\CriteriaRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EloquentCriteriaRepository implements CriteriaRepositoryInterface
{
    public function findOrFail(int $id): Criteria
    {
        return Criteria::findOrFail($id);
    }

    public function create(array $data): Criteria
    {
        return Criteria::create($data);
    }

    public function update(int $id, array $data): Criteria
    {
        $criteria = Criteria::findOrFail($id);
        $criteria->update($data);
        return $criteria->fresh();
    }

    public function delete(int $id): bool
    {
        return Criteria::findOrFail($id)->delete();
    }

    public function findByEvent(int $eventId): Collection
    {
        return Criteria::where('event_id', $eventId)->orderBy('order')->get();
    }
}
```

**Step 10: Implement EloquentScoreRepository (both interfaces)**

```php
<?php

namespace App\Repositories;

use App\Models\Score;
use App\Repositories\Contracts\ScoreQueryInterface;
use App\Repositories\Contracts\ScoreRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;

class EloquentScoreRepository implements ScoreRepositoryInterface, ScoreQueryInterface
{
    public function save(array $data): Score
    {
        return Score::updateOrCreate(
            Arr::only($data, ['event_id', 'judge_id', 'contestant_id', 'criteria_id']),
            Arr::only($data, ['score', 'remarks']),
        );
    }

    public function update(int $id, array $data): Score
    {
        $score = Score::findOrFail($id);
        $score->update($data);
        return $score->fresh();
    }

    public function delete(int $id): bool
    {
        return Score::findOrFail($id)->delete();
    }

    public function findByEvent(int $eventId): Collection
    {
        return Score::where('event_id', $eventId)
            ->with(['judge', 'contestant', 'criteria'])
            ->get();
    }

    public function findByJudge(int $eventId, int $judgeId): Collection
    {
        return Score::where('event_id', $eventId)
            ->where('judge_id', $judgeId)
            ->with(['contestant', 'criteria'])
            ->get();
    }

    public function findByContestant(int $eventId, int $contestantId): Collection
    {
        return Score::where('event_id', $eventId)
            ->where('contestant_id', $contestantId)
            ->with(['judge', 'criteria'])
            ->get();
    }

    public function findByCriteria(int $eventId, int $criteriaId): Collection
    {
        return Score::where('event_id', $eventId)
            ->where('criteria_id', $criteriaId)
            ->with(['judge', 'contestant'])
            ->get();
    }

    public function getAggregateByContestant(int $eventId): Collection
    {
        return Score::where('event_id', $eventId)
            ->with(['contestant', 'criteria'])
            ->get()
            ->groupBy('contestant_id');
    }

    public function getScoreMatrix(int $eventId): array
    {
        $scores = Score::where('event_id', $eventId)
            ->with(['judge', 'contestant', 'criteria'])
            ->get();

        $matrix = [];
        foreach ($scores as $score) {
            $matrix[$score->contestant_id][$score->judge_id][$score->criteria_id] = [
                'score' => $score->score,
                'remarks' => $score->remarks,
            ];
        }

        return $matrix;
    }
}
```

**Step 11: Implement EloquentUserRepository**

```php
<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function all(): Collection
    {
        return User::orderBy('name')->get();
    }

    public function findOrFail(int $id): User
    {
        return User::findOrFail($id);
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(int $id, array $data): User
    {
        $user = User::findOrFail($id);
        $user->update($data);
        return $user->fresh();
    }

    public function delete(int $id): bool
    {
        return User::findOrFail($id)->delete();
    }

    public function findByRole(string $role): Collection
    {
        return User::where('role', $role)->orderBy('name')->get();
    }
}
```

**Step 12: Register bindings in AppServiceProvider**

Modify: `app/Providers/AppServiceProvider.php`

```php
use App\Repositories\Contracts\EventRepositoryInterface;
use App\Repositories\Contracts\ContestantRepositoryInterface;
use App\Repositories\Contracts\CriteriaRepositoryInterface;
use App\Repositories\Contracts\ScoreRepositoryInterface;
use App\Repositories\Contracts\ScoreQueryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\EloquentEventRepository;
use App\Repositories\EloquentContestantRepository;
use App\Repositories\EloquentCriteriaRepository;
use App\Repositories\EloquentScoreRepository;
use App\Repositories\EloquentUserRepository;

public function register(): void
{
    $this->app->bind(EventRepositoryInterface::class, EloquentEventRepository::class);
    $this->app->bind(ContestantRepositoryInterface::class, EloquentContestantRepository::class);
    $this->app->bind(CriteriaRepositoryInterface::class, EloquentCriteriaRepository::class);
    $this->app->bind(ScoreRepositoryInterface::class, EloquentScoreRepository::class);
    $this->app->bind(ScoreQueryInterface::class, EloquentScoreRepository::class);
    $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
}
```

---

## Task 6: Services Layer

**Files:**
- Create: `app/Services/EventService.php`
- Create: `app/Services/ContestantService.php`
- Create: `app/Services/CriteriaService.php`
- Create: `app/Services/ScoringService.php`
- Create: `app/Services/ReportService.php`
- Create: `app/Services/UserService.php`
- Create: `app/Exceptions/InvalidScoreException.php`
- Create: `app/Reports/Contracts/ReportGeneratorInterface.php`
- Create: `app/Reports/OverallRankingReport.php`
- Create: `app/Reports/JudgeScoresheetReport.php`
- Create: `app/Reports/ContestantDetailReport.php`
- Create: `app/Reports/CriteriaBreakdownReport.php`
- Create: `app/Reports/ScoreComparisonReport.php`
- Create: `app/Reports/EventSummaryReport.php`
- Create: `app/Reports/AuditLogReport.php`

**Step 1: Create InvalidScoreException**

```php
<?php

namespace App\Exceptions;

use RuntimeException;

class InvalidScoreException extends RuntimeException {}
```

**Step 2: Create EventService**

```php
<?php

namespace App\Services;

use App\Models\Event;
use App\Repositories\Contracts\EventRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EventService
{
    public function __construct(
        private EventRepositoryInterface $eventRepo,
    ) {}

    public function getAllEvents(): Collection
    {
        return $this->eventRepo->all();
    }

    public function getEvent(int $id): Event
    {
        return $this->eventRepo->findOrFail($id);
    }

    public function createEvent(array $data): Event
    {
        return $this->eventRepo->create($data);
    }

    public function updateEvent(int $id, array $data): Event
    {
        return $this->eventRepo->update($id, $data);
    }

    public function deleteEvent(int $id): bool
    {
        return $this->eventRepo->delete($id);
    }
}
```

**Step 3: Create ContestantService**

```php
<?php

namespace App\Services;

use App\Models\Contestant;
use App\Models\Event;
use App\Repositories\Contracts\ContestantRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ContestantService
{
    public function __construct(
        private ContestantRepositoryInterface $contestantRepo,
    ) {}

    public function getContestantsForEvent(int $eventId): Collection
    {
        return $this->contestantRepo->findByEvent($eventId);
    }

    public function createContestant(array $data): Contestant
    {
        return $this->contestantRepo->create($data);
    }

    public function attachToEvent(Event $event, Contestant $contestant, int $order = 0): void
    {
        $event->contestants()->syncWithoutDetaching([
            $contestant->id => ['order' => $order],
        ]);
    }

    public function detachFromEvent(Event $event, int $contestantId): void
    {
        $event->contestants()->detach($contestantId);
    }

    public function deleteContestant(int $id): bool
    {
        return $this->contestantRepo->delete($id);
    }
}
```

**Step 4: Create CriteriaService**

```php
<?php

namespace App\Services;

use App\Models\Criteria;
use App\Repositories\Contracts\CriteriaRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CriteriaService
{
    public function __construct(
        private CriteriaRepositoryInterface $criteriaRepo,
    ) {}

    public function getCriteriaForEvent(int $eventId): Collection
    {
        return $this->criteriaRepo->findByEvent($eventId);
    }

    public function createCriteria(array $data): Criteria
    {
        return $this->criteriaRepo->create($data);
    }

    public function updateCriteria(int $id, array $data): Criteria
    {
        return $this->criteriaRepo->update($id, $data);
    }

    public function deleteCriteria(int $id): bool
    {
        return $this->criteriaRepo->delete($id);
    }
}
```

**Step 5: Create ScoringService**

```php
<?php

namespace App\Services;

use App\Enums\EventStatus;
use App\Events\ScoreSubmitted;
use App\Exceptions\InvalidScoreException;
use App\Models\Score;
use App\Repositories\Contracts\CriteriaRepositoryInterface;
use App\Repositories\Contracts\EventRepositoryInterface;
use App\Repositories\Contracts\ScoreRepositoryInterface;

class ScoringService
{
    public function __construct(
        private ScoreRepositoryInterface $scoreRepo,
        private CriteriaRepositoryInterface $criteriaRepo,
        private EventRepositoryInterface $eventRepo,
    ) {}

    public function submitScore(int $eventId, int $judgeId, int $contestantId, int $criteriaId, float $score, ?string $remarks = null): Score
    {
        $event = $this->eventRepo->findOrFail($eventId);

        if ($event->status !== EventStatus::Active) {
            throw new InvalidScoreException('Scores can only be submitted for active events.');
        }

        $criteria = $this->criteriaRepo->findOrFail($criteriaId);

        if ($score < 0 || $score > (float) $criteria->max_score) {
            throw new InvalidScoreException("Score must be between 0 and {$criteria->max_score}.");
        }

        $savedScore = $this->scoreRepo->save([
            'event_id' => $eventId,
            'judge_id' => $judgeId,
            'contestant_id' => $contestantId,
            'criteria_id' => $criteriaId,
            'score' => $score,
            'remarks' => $remarks,
        ]);

        event(new ScoreSubmitted($savedScore));

        return $savedScore;
    }
}
```

**Step 6: Create ReportGeneratorInterface**

```php
<?php

namespace App\Reports\Contracts;

use App\Models\Event;

interface ReportGeneratorInterface
{
    public function generate(Event $event, array $options = []): array;
    public function getName(): string;
}
```

**Step 7: Create OverallRankingReport**

```php
<?php

namespace App\Reports;

use App\Models\Event;
use App\Reports\Contracts\ReportGeneratorInterface;
use App\Repositories\Contracts\ScoreQueryInterface;

class OverallRankingReport implements ReportGeneratorInterface
{
    public function __construct(private ScoreQueryInterface $scoreQuery) {}

    public function generate(Event $event, array $options = []): array
    {
        $grouped = $this->scoreQuery->getAggregateByContestant($event->id);
        $criteria = $event->criteria;
        $rankings = [];

        foreach ($grouped as $contestantId => $scores) {
            $contestant = $scores->first()->contestant;
            $totalWeighted = 0;

            foreach ($criteria as $c) {
                $criteriaScores = $scores->where('criteria_id', $c->id);
                $avgScore = $criteriaScores->avg('score') ?? 0;
                $weightedScore = ($avgScore / (float) $c->max_score) * (float) $c->percentage_weight;
                $totalWeighted += $weightedScore;
            }

            $rankings[] = [
                'contestant' => $contestant,
                'total_weighted_score' => round($totalWeighted, 4),
            ];
        }

        usort($rankings, fn ($a, $b) => $b['total_weighted_score'] <=> $a['total_weighted_score']);

        foreach ($rankings as $i => &$r) {
            $r['rank'] = $i + 1;
        }

        return ['rankings' => $rankings, 'criteria' => $criteria];
    }

    public function getName(): string
    {
        return 'overall-ranking';
    }
}
```

**Step 8: Create remaining report classes**

Create `JudgeScoresheetReport`, `ContestantDetailReport`, `CriteriaBreakdownReport`, `ScoreComparisonReport`, `EventSummaryReport`, `AuditLogReport` — each implementing `ReportGeneratorInterface` with their specific query logic. (Full code for each will be implemented during execution.)

**Step 9: Create ReportService**

```php
<?php

namespace App\Services;

use App\Models\Event;
use App\Reports\Contracts\ReportGeneratorInterface;

class ReportService
{
    /** @var array<string, ReportGeneratorInterface> */
    private array $generators = [];

    public function registerGenerator(ReportGeneratorInterface $generator): void
    {
        $this->generators[$generator->getName()] = $generator;
    }

    public function generate(string $reportName, Event $event, array $options = []): array
    {
        if (!isset($this->generators[$reportName])) {
            throw new \InvalidArgumentException("Unknown report type: {$reportName}");
        }

        return $this->generators[$reportName]->generate($event, $options);
    }

    public function getAvailableReports(): array
    {
        return array_keys($this->generators);
    }
}
```

**Step 10: Create UserService**

```php
<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(
        private UserRepositoryInterface $userRepo,
    ) {}

    public function getAllUsers(): Collection
    {
        return $this->userRepo->all();
    }

    public function getUser(int $id): User
    {
        return $this->userRepo->findOrFail($id);
    }

    public function createUser(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        return $this->userRepo->create($data);
    }

    public function updateUser(int $id, array $data): User
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        return $this->userRepo->update($id, $data);
    }

    public function deleteUser(int $id): bool
    {
        return $this->userRepo->delete($id);
    }

    public function getJudges(): Collection
    {
        return $this->userRepo->findByRole('judge');
    }
}
```

**Step 11: Register ReportService with generators in AppServiceProvider**

Add to `AppServiceProvider::register()`:

```php
$this->app->singleton(ReportService::class, function ($app) {
    $service = new ReportService();
    $service->registerGenerator($app->make(\App\Reports\OverallRankingReport::class));
    $service->registerGenerator($app->make(\App\Reports\JudgeScoresheetReport::class));
    $service->registerGenerator($app->make(\App\Reports\ContestantDetailReport::class));
    $service->registerGenerator($app->make(\App\Reports\CriteriaBreakdownReport::class));
    $service->registerGenerator($app->make(\App\Reports\ScoreComparisonReport::class));
    $service->registerGenerator($app->make(\App\Reports\EventSummaryReport::class));
    $service->registerGenerator($app->make(\App\Reports\AuditLogReport::class));
    return $service;
});
```

---

## Task 7: Policies + Middleware + Form Requests

**Files:**
- Create: `app/Http/Middleware/RoleMiddleware.php`
- Create: `app/Policies/EventPolicy.php`
- Create: `app/Policies/ScorePolicy.php`
- Create: `app/Policies/UserPolicy.php`
- Create: `app/Http/Requests/StoreEventRequest.php`
- Create: `app/Http/Requests/UpdateEventRequest.php`
- Create: `app/Http/Requests/StoreContestantRequest.php`
- Create: `app/Http/Requests/StoreCriteriaRequest.php`
- Create: `app/Http/Requests/UpdateCriteriaRequest.php`
- Create: `app/Http/Requests/StoreScoreRequest.php`
- Create: `app/Http/Requests/StoreUserRequest.php`
- Create: `app/Http/Requests/UpdateUserRequest.php`
- Create: `app/Http/Requests/AssignJudgeRequest.php`

Each policy, middleware, and form request will be created with the validation rules and authorization logic from the design doc. Full code provided during execution.

Register `RoleMiddleware` in `bootstrap/app.php`:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->web(append: [
        \App\Http\Middleware\HandleInertiaRequests::class,
    ]);
    $middleware->alias([
        'role' => \App\Http\Middleware\RoleMiddleware::class,
    ]);
})
```

---

## Task 8: Broadcasting Events + Reverb Setup

**Files:**
- Create: `app/Events/ScoreSubmitted.php`
- Create: `app/Events/EventStatusChanged.php`
- Modify: `config/broadcasting.php` (verify reverb config)
- Create: `resources/js/Composables/useEcho.js`

**Step 1: Install and configure Reverb**

Run:
```bash
php artisan install:broadcasting
```

**Step 2: Create ScoreSubmitted event**

```php
<?php

namespace App\Events;

use App\Models\Score;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ScoreSubmitted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Score $score) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("event.{$this->score->event_id}.scores"),
            new Channel("event.{$this->score->event_id}.scoreboard"),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'score' => $this->score->load(['judge', 'contestant', 'criteria'])->toArray(),
        ];
    }
}
```

**Step 3: Create EventStatusChanged event**

```php
<?php

namespace App\Events;

use App\Models\Event;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EventStatusChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Event $event) {}

    public function broadcastOn(): array
    {
        return [
            new Channel("event.{$this->event->id}.scoreboard"),
        ];
    }
}
```

**Step 4: Create useEcho composable**

```js
/**
 * useEcho - Composable for real-time channel subscriptions
 *
 * Usage (for frontend developer):
 *   import { useEcho } from '@/Composables/useEcho';
 *   const { subscribeToScores, subscribeToScoreboard, leave } = useEcho(eventId);
 *   subscribeToScores((scoreData) => { /* handle new score */ });
 *   onUnmounted(() => leave());
 */
import { onUnmounted } from 'vue';

export function useEcho(eventId) {
    const channels = [];

    function subscribeToScores(callback) {
        const channel = window.Echo.private(`event.${eventId}.scores`)
            .listen('ScoreSubmitted', (e) => callback(e.score));
        channels.push(channel);
        return channel;
    }

    function subscribeToScoreboard(callback) {
        const channel = window.Echo.channel(`event.${eventId}.scoreboard`)
            .listen('ScoreSubmitted', (e) => callback(e.score))
            .listen('EventStatusChanged', (e) => callback(e));
        channels.push(channel);
        return channel;
    }

    function leave() {
        window.Echo.leave(`event.${eventId}.scores`);
        window.Echo.leave(`event.${eventId}.scoreboard`);
    }

    onUnmounted(() => leave());

    return { subscribeToScores, subscribeToScoreboard, leave };
}
```

---

## Task 9: Passport Setup + API Auth

**Files:**
- Modify: `app/Models/User.php` (HasApiTokens already added in Task 4)
- Create: `app/Http/Controllers/Api/V1/AuthController.php`

**Step 1: Install Passport**

Run:
```bash
php artisan passport:install
```

**Step 2: Create API AuthController**

```php
<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials.'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('api-token')->accessToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->token()->revoke();
        return response()->json(['message' => 'Logged out.']);
    }
}
```

---

## Task 10: Web Controllers (Inertia)

**Files:**
- Create: `app/Http/Controllers/Web/Auth/LoginController.php`
- Create: `app/Http/Controllers/Web/DashboardController.php`
- Create: `app/Http/Controllers/Web/EventController.php`
- Create: `app/Http/Controllers/Web/ContestantController.php`
- Create: `app/Http/Controllers/Web/CriteriaController.php`
- Create: `app/Http/Controllers/Web/JudgeController.php`
- Create: `app/Http/Controllers/Web/ScoringController.php`
- Create: `app/Http/Controllers/Web/ScoreController.php`
- Create: `app/Http/Controllers/Web/ReportController.php`
- Create: `app/Http/Controllers/Web/ScoreboardController.php`
- Create: `app/Http/Controllers/Web/UserController.php`

Each controller is thin — receives request, calls service, returns `Inertia::render()` with all props the frontend developer needs. Full code for each controller during execution.

Example pattern (EventController):

```php
public function index(EventService $service)
{
    return Inertia::render('Events/Index', [
        'events' => $service->getAllEvents(),
    ]);
}

public function store(StoreEventRequest $request, EventService $service)
{
    $event = $service->createEvent([
        ...$request->validated(),
        'created_by' => $request->user()->id,
    ]);

    return redirect()->route('events.show', $event);
}
```

---

## Task 11: API Controllers (Passport)

**Files:**
- Create: `app/Http/Controllers/Api/V1/EventController.php`
- Create: `app/Http/Controllers/Api/V1/ScoreController.php`
- Create: `app/Http/Resources/EventResource.php`
- Create: `app/Http/Resources/ScoreResource.php`

API controllers return JSON via API Resources. Same services as web controllers.

---

## Task 12: Routes

**Files:**
- Modify: `routes/web.php`
- Modify: `routes/api.php`
- Create: `routes/channels.php` (broadcast channel auth)

Wire all routes from the design doc route table. Web routes use Inertia controllers with role middleware. API routes use Passport auth.

---

## Task 13: Vue Pages (Empty Shells)

**Files:**
- Create all `.vue` files listed in the frontend structure

Each page is an empty shell that:
1. Declares its layout
2. Documents available Inertia props with JSDoc comments
3. Renders a placeholder showing the page name and available props

Example:

```vue
<script setup>
/**
 * Events/Index - Event listing page
 *
 * Available props (from EventController@index):
 * @prop {Array} events - List of events with: id, name, description, venue, event_date, status, scoring_mode, creator
 */
import AppLayout from '@/Layouts/AppLayout.vue';
import { usePage } from '@inertiajs/vue3';

defineOptions({ layout: AppLayout });

const props = defineProps({
    events: { type: Array, default: () => [] },
});
</script>

<template>
    <div>
        <h1>Events</h1>
        <p>{{ events.length }} events available. (Designer: build event list UI here)</p>
        <!-- Props: events[] { id, name, description, venue, event_date, status, scoring_mode, creator } -->
    </div>
</template>
```

---

## Task 14: Inertia Shared Data + HandleInertiaRequests

**Files:**
- Modify: `app/Http/Middleware/HandleInertiaRequests.php`

Share auth user data with all pages:

```php
public function share(Request $request): array
{
    return [
        ...parent::share($request),
        'auth' => [
            'user' => $request->user() ? [
                'id' => $request->user()->id,
                'name' => $request->user()->name,
                'email' => $request->user()->email,
                'role' => $request->user()->role,
            ] : null,
        ],
        'flash' => [
            'success' => fn () => $request->session()->get('success'),
            'error' => fn () => $request->session()->get('error'),
        ],
    ];
}
```

---

## Task 15: Database Seeder

**Files:**
- Modify: `database/seeders/DatabaseSeeder.php`
- Create: model factories for each model

Create seeders that generate:
- 1 SuperAdmin user (admin@tabulation.test / password)
- 2 Admin users
- 5 Judge users
- 2 Viewer users
- 3 sample events with contestants, criteria, and some scores

---

## Task 16: Final Verification

**Step 1: Run migrations fresh with seed**
```bash
php artisan migrate:fresh --seed
```

**Step 2: Build frontend**
```bash
npm run build
```

**Step 3: Start dev server**
```bash
php artisan serve
```

**Step 4: Verify key pages load**
- Login at `/login`
- Dashboard at `/`
- Events list at `/events`

**Step 5: Verify API**
```bash
php artisan passport:client --personal
```
Test login endpoint with curl/Postman.
