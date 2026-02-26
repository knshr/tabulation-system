# Designer Guide - Tabulation System

This guide covers everything you need to design the frontend of the Tabulation System. The backend is fully functional, composables handle all data operations, and pages are empty shells waiting for your design.

---

## Tech Stack

| Technology | Version | Purpose |
|---|---|---|
| [Vue 3](https://vuejs.org/guide/introduction.html) | 3.5+ | UI framework (Composition API, `<script setup>`) |
| [InertiaJS](https://inertiajs.com/) | 2.x | SPA routing without API (server-driven) |
| [Tailwind CSS](https://tailwindcss.com/docs) | 4.x | Utility-first CSS |
| [daisyUI](https://daisyui.com/components/) | 5.x | Tailwind component library |
| [vue-sonner](https://vue-sonner.vercel.app/) | 2.x | Toast notifications (already wired) |
| [Laravel Echo](https://laravel.com/docs/reverb) | 2.x | Real-time WebSocket events |

---

## Project Structure

```
resources/js/
├── app.js                          # App entry (Toaster + ConfirmModal mounted globally)
├── bootstrap.js                    # Axios + Echo setup
├── theme.js                        # Theme colors, fonts, border-radius config
├── Components/
│   └── ConfirmModal.vue            # Global confirm/alert modal (auto-mounted)
├── Composables/                    # All data logic lives here
│   ├── useAuth.js                  # Login/logout
│   ├── useConfirm.js               # Confirm/alert modals
│   ├── useContestants.js           # Contestant CRUD
│   ├── useCriteria.js              # Criteria CRUD
│   ├── useEcho.js                  # Real-time subscriptions
│   ├── useEvents.js                # Event CRUD
│   ├── useFlash.js                 # Auto flash→toast (wired in AppLayout)
│   ├── useJudges.js                # Judge assignment
│   ├── useScoring.js               # Judge score submission
│   ├── useToast.js                 # Manual toast notifications
│   └── useUsers.js                 # User CRUD
├── Layouts/
│   ├── AppLayout.vue               # Authenticated shell (sidebar, nav, user menu)
│   ├── AuthLayout.vue              # Login/auth pages (centered card)
│   └── GuestLayout.vue             # Public pages (scoreboard)
└── Pages/                          # All pages - your design canvas
    ├── Auth/Login.vue
    ├── Dashboard/Index.vue
    ├── Events/{Index,Create,Edit,Show}.vue
    ├── Contestants/{Index,Create}.vue
    ├── Criteria/Index.vue
    ├── Judges/Index.vue
    ├── Users/{Index,Create}.vue
    ├── Scoring/Index.vue
    ├── Scores/Index.vue
    ├── Scoreboard/Show.vue
    └── Reports/{OverallRanking,JudgeScoresheet,ContestantDetail,
                  CriteriaBreakdown,ScoreComparison,EventSummary,AuditLog}.vue
```

---

## Getting Started

### Dev server

```bash
npm run dev         # Vite dev server with HMR
npm run build       # Production build
```

### How pages work

Every `.vue` file in `Pages/` maps to a route. InertiaJS handles navigation — **no Vue Router needed**. The backend passes props directly to each page component.

Each page has a `<script setup>` block with:
- `defineProps()` — the data the backend sends to this page
- `defineOptions({ layout: AppLayout })` — which layout wraps the page
- Composable imports — pre-wired data logic (forms, CRUD, real-time)

**Your job:** Design the `<template>` section using the props and composable methods already available.

---

## daisyUI

daisyUI is already installed and configured. Use its component classes directly in your templates.

**Component reference:** https://daisyui.com/components/

Each page has JSDoc comments suggesting which daisyUI components to use. For example:

```
* Suggested daisyUI components:
*   - table (https://daisyui.com/components/table/) - scores listing
*   - card (https://daisyui.com/components/card/) - table wrapper
```

### Common patterns

```html
<!-- Table -->
<div class="overflow-x-auto">
  <table class="table">
    <thead><tr><th>Name</th><th>Score</th></tr></thead>
    <tbody>
      <tr v-for="item in items" :key="item.id">
        <td>{{ item.name }}</td>
        <td>{{ item.score }}</td>
      </tr>
    </tbody>
  </table>
</div>

<!-- Card wrapper -->
<div class="card bg-base-100 shadow-xl">
  <div class="card-body">
    <h2 class="card-title">Title</h2>
    <p>Content here</p>
  </div>
</div>

<!-- Form input with error -->
<fieldset class="fieldset">
  <legend class="fieldset-legend">Email</legend>
  <input type="email" class="input" v-model="form.email" />
  <p v-if="form.errors.email" class="fieldset-label text-error">{{ form.errors.email }}</p>
</fieldset>

<!-- Button with loading state -->
<button class="btn btn-primary" :disabled="form.processing" @click="submit">
  <span v-if="form.processing" class="loading loading-spinner loading-sm"></span>
  Save
</button>

<!-- Badge -->
<span class="badge badge-success">Active</span>
<span class="badge badge-error">Inactive</span>

<!-- Stat cards -->
<div class="stats shadow">
  <div class="stat">
    <div class="stat-title">Total Events</div>
    <div class="stat-value">12</div>
  </div>
</div>
```

---

## Navigation with InertiaJS

InertiaJS provides the `<Link>` component for SPA navigation. **Do not use `<a href>` for internal links.**

```vue
<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
  <!-- Named routes via Ziggy's route() helper -->
  <Link :href="route('dashboard')" class="btn btn-primary">Dashboard</Link>
  <Link :href="route('events.index')" class="link">Events</Link>
  <Link :href="route('events.show', event.id)">{{ event.name }}</Link>
  <Link :href="route('events.edit', event.id)" class="btn btn-sm">Edit</Link>
  <Link :href="route('events.contestants.index', event.id)">Contestants</Link>
  <Link :href="route('scoreboard.show', event.id)" class="btn btn-ghost">Live Scoreboard</Link>
</template>
```

**Full InertiaJS Links docs:** https://inertiajs.com/links

### Available route names

| Route Name | URL | Page |
|---|---|---|
| `login` | `/login` | Auth/Login |
| `logout` | POST `/logout` | — |
| `dashboard` | `/` | Dashboard/Index |
| `events.index` | `/events` | Events/Index |
| `events.create` | `/events/create` | Events/Create |
| `events.show` | `/events/{event}` | Events/Show |
| `events.edit` | `/events/{event}/edit` | Events/Edit |
| `events.contestants.index` | `/events/{event}/contestants` | Contestants/Index |
| `events.contestants.create` | `/events/{event}/contestants/create` | Contestants/Create |
| `events.criteria.index` | `/events/{event}/criteria` | Criteria/Index |
| `events.judges.index` | `/events/{event}/judges` | Judges/Index |
| `events.scores.index` | `/events/{event}/scores` | Scores/Index |
| `events.scoring.index` | `/events/{event}/scoring` | Scoring/Index |
| `scoreboard.show` | `/scoreboard/{event}` | Scoreboard/Show |
| `users.index` | `/users` | Users/Index |
| `users.create` | `/users/create` | Users/Create |
| `reports.overall-ranking` | `/reports/overall-ranking/{event}` | Reports/OverallRanking |
| `reports.judge-scoresheet` | `/reports/judge-scoresheet/{event}` | Reports/JudgeScoresheet |
| `reports.contestant-detail` | `/reports/contestant-detail/{event}` | Reports/ContestantDetail |
| `reports.criteria-breakdown` | `/reports/criteria-breakdown/{event}` | Reports/CriteriaBreakdown |
| `reports.score-comparison` | `/reports/score-comparison/{event}` | Reports/ScoreComparison |
| `reports.event-summary` | `/reports/event-summary/{event}` | Reports/EventSummary |
| `reports.audit-log` | `/reports/audit-log/{event}` | Reports/AuditLog |

### Accessing the current page URL

```vue
<script setup>
import { usePage } from '@inertiajs/vue3';
const page = usePage();
// page.url — current URL
// page.props — all props from the backend
</script>
```

**Full InertiaJS docs:** https://inertiajs.com/

---

## Composable Reference

All composables are in `resources/js/Composables/`. Each has JSDoc comments with detailed usage instructions. Below is a quick reference.

### useAuth — Login & Logout

```vue
<script setup>
import { useAuth } from '@/Composables/useAuth';
const { loginForm, login, logout } = useAuth();
</script>

<template>
  <form @submit.prevent="login">
    <input v-model="loginForm.email" type="email" />
    <p v-if="loginForm.errors.email" class="text-error">{{ loginForm.errors.email }}</p>

    <input v-model="loginForm.password" type="password" />
    <p v-if="loginForm.errors.password" class="text-error">{{ loginForm.errors.password }}</p>

    <label><input type="checkbox" v-model="loginForm.remember" /> Remember me</label>

    <button type="submit" :disabled="loginForm.processing">Login</button>
  </form>

  <!-- Logout (use anywhere in authenticated layout) -->
  <button @click="logout">Logout</button>
</template>
```

### useEvents — Event CRUD

```vue
<script setup>
import { useEvents } from '@/Composables/useEvents';

// Create page:
const { createForm, store } = useEvents();

// Edit page (pass existing event):
const { editForm, update } = useEvents(props.event);

// Index page (delete):
const { destroy } = useEvents();
</script>

<template>
  <!-- Create form -->
  <form @submit.prevent="store">
    <input v-model="createForm.name" />             <!-- string -->
    <textarea v-model="createForm.description" />    <!-- string -->
    <input v-model="createForm.venue" />             <!-- string -->
    <input v-model="createForm.event_date" type="date" />  <!-- date -->
    <select v-model="createForm.scoring_mode">       <!-- 'blind' | 'open' -->
      <option value="blind">Blind</option>
      <option value="open">Open</option>
    </select>
    <p v-if="createForm.errors.name" class="text-error">{{ createForm.errors.name }}</p>
    <button type="submit" :disabled="createForm.processing">Create Event</button>
  </form>

  <!-- Delete button -->
  <button @click="destroy(event.id)">Delete</button>
</template>
```

### useContestants — Contestant CRUD (scoped to event)

```vue
<script setup>
import { useContestants } from '@/Composables/useContestants';
const { createForm, store, destroy } = useContestants(props.event.id);
</script>

<template>
  <form @submit.prevent="store">
    <input v-model="createForm.name" />                  <!-- string -->
    <input v-model="createForm.nickname" />               <!-- string -->
    <textarea v-model="createForm.description" />         <!-- string -->
    <input v-model="createForm.contestant_number" />      <!-- string/number -->
    <input type="file" @change="createForm.photo = $event.target.files[0]" />  <!-- file -->
    <button type="submit" :disabled="createForm.processing">Add Contestant</button>
  </form>

  <button @click="destroy(contestant.id)">Remove</button>
</template>
```

### useCriteria — Criteria CRUD (scoped to event)

```vue
<script setup>
import { useCriteria } from '@/Composables/useCriteria';
const { createForm, store, update, destroy } = useCriteria(props.event.id);
</script>

<template>
  <form @submit.prevent="store">
    <input v-model="createForm.name" />                     <!-- string -->
    <textarea v-model="createForm.description" />            <!-- string -->
    <input v-model.number="createForm.max_score" type="number" />        <!-- number (default: 100) -->
    <input v-model.number="createForm.percentage_weight" type="number" /> <!-- number (0-100) -->
    <input v-model.number="createForm.order" type="number" />            <!-- number -->
    <button type="submit" :disabled="createForm.processing">Add Criteria</button>
  </form>

  <!-- Inline edit -->
  <button @click="update(criterion.id, { name: 'New Name', max_score: 50 })">Save</button>

  <!-- Delete -->
  <button @click="destroy(criterion.id)">Delete</button>
</template>
```

### useJudges — Judge Assignment (scoped to event)

```vue
<script setup>
import { useJudges } from '@/Composables/useJudges';
const { assignForm, assign, remove } = useJudges(props.event.id);
</script>

<template>
  <!-- Assign judge from available judges list -->
  <form @submit.prevent="assign">
    <select v-model="assignForm.judge_id">
      <option v-for="judge in availableJudges" :key="judge.id" :value="judge.id">
        {{ judge.name }}
      </option>
    </select>
    <button type="submit" :disabled="assignForm.processing">Assign</button>
  </form>

  <!-- Remove judge -->
  <button @click="remove(judge.id)">Remove</button>
</template>
```

### useUsers — User Management

```vue
<script setup>
import { useUsers } from '@/Composables/useUsers';
const { createForm, store, destroy } = useUsers();
</script>

<template>
  <form @submit.prevent="store">
    <input v-model="createForm.name" />                   <!-- string -->
    <input v-model="createForm.email" type="email" />     <!-- string -->
    <input v-model="createForm.password" type="password" /> <!-- string -->
    <input v-model="createForm.password_confirmation" type="password" />
    <input v-model="createForm.phone" />                  <!-- string -->
    <select v-model="createForm.role">                    <!-- role dropdown -->
      <option value="super_admin">Super Admin</option>
      <option value="admin">Admin</option>
      <option value="judge">Judge</option>
      <option value="viewer">Viewer</option>
    </select>
    <label>
      <input type="checkbox" v-model="createForm.is_active" /> Active
    </label>
    <button type="submit" :disabled="createForm.processing">Create User</button>
  </form>

  <button @click="destroy(user.id)">Delete</button>
</template>
```

### useScoring — Judge Score Submission

```vue
<script setup>
import { useScoring } from '@/Composables/useScoring';

const { scoreForm, getScore, setScore, setRemarks, submit } = useScoring(
  props.event.id,
  props.contestants,
  props.criteria,
  props.existingScores,
);
</script>

<template>
  <!-- Scoring grid: contestants (rows) x criteria (columns) -->
  <form @submit.prevent="submit">
    <table class="table">
      <thead>
        <tr>
          <th>Contestant</th>
          <th v-for="c in criteria" :key="c.id">{{ c.name }} (max: {{ c.max_score }})</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="contestant in contestants" :key="contestant.id">
          <td>{{ contestant.name }}</td>
          <td v-for="c in criteria" :key="c.id">
            <input
              type="number"
              :value="getScore(contestant.id, c.id)?.score"
              :max="c.max_score"
              min="0"
              @input="setScore(contestant.id, c.id, $event.target.value)"
            />
          </td>
        </tr>
      </tbody>
    </table>

    <button type="submit" :disabled="scoreForm.processing">
      <span v-if="scoreForm.processing" class="loading loading-spinner loading-sm"></span>
      Submit Scores
    </button>
  </form>
</template>
```

### useEcho — Real-Time Updates

```vue
<script setup>
import { ref } from 'vue';
import { useEcho } from '@/Composables/useEcho';

const { subscribeToScores, subscribeToScoreboard } = useEcho(props.event.id);

// For Scoring/Index — listen for other judges' score updates:
subscribeToScores((scoreData) => {
  // scoreData contains the new score — update UI as needed
});

// For Scoreboard/Show — listen for rank changes:
const rankings = ref(props.rankings);
subscribeToScoreboard((data) => {
  // data contains updated rankings — refresh the display
  // Example: rankings.value = data.rankings;
});
</script>
```

### useToast — Manual Toast Notifications

```vue
<script setup>
import { useToast } from '@/Composables/useToast';
const { success, error, info, warning } = useToast();

function doSomething() {
  success('Action completed!');
  error('Something went wrong.');
  info('FYI: Event is still in draft.');
  warning('Scores are incomplete.');
}
</script>
```

> **Note:** Backend flash messages (`redirect()->with('success', '...')`) are automatically shown as toasts via `useFlash()` in AppLayout. You don't need to handle those manually.

### useConfirm — Confirm & Alert Modals

The confirm modal is globally mounted. Just import and use:

```vue
<script setup>
import { useConfirm } from '@/Composables/useConfirm';
const { confirm, alert } = useConfirm();

async function deleteItem() {
  const ok = await confirm({
    title: 'Delete Item',
    message: 'This action cannot be undone.',
    confirmText: 'Delete',
    variant: 'danger',       // 'default' | 'danger' | 'warning' | 'info'
  });
  if (!ok) return;
  // proceed with delete...
}

async function showNotice() {
  await alert({
    title: 'Notice',
    message: 'Your scores have been saved.',
    variant: 'info',
  });
}
</script>
```

> **Note:** All destructive actions in composables (delete event, remove contestant, etc.) already use `useConfirm`. You don't need to add confirmation logic for those — it's built in.

---

## Layouts

### AppLayout — Authenticated Pages

Used by all pages behind login. **Design this first** — it wraps every authenticated page.

What to build:
- Sidebar navigation (drawer) with links to Dashboard, Events, Users
- Top navbar with app title and user dropdown (name, role, logout)
- Role-aware nav items (hide admin-only links from judges/viewers)

Available data:
- `user` — `page.props.auth.user` (has `id`, `name`, `email`, `role`)
- `logout()` — from `useAuth()` composable
- Flash messages are auto-handled by `useFlash()`

### AuthLayout — Login Page

Centered card layout for authentication forms. Keep it simple.

### GuestLayout — Public Scoreboard

Minimal layout for the public scoreboard page (no auth required).

---

## User Roles

The system has 4 roles. Use these for role-based UI decisions:

| Role | Value | Can access |
|---|---|---|
| Super Admin | `super_admin` | Everything + Users + Audit Log |
| Admin | `admin` | Events, Contestants, Criteria, Judges, Scores, Reports |
| Judge | `judge` | Dashboard, Scoring interface |
| Viewer | `viewer` | Dashboard (read-only) |

Access the current user's role:

```vue
<script setup>
import { usePage } from '@inertiajs/vue3';
const user = usePage().props.auth?.user;
// user.role === 'super_admin' | 'admin' | 'judge' | 'viewer'
</script>

<template>
  <Link v-if="user.role === 'super_admin'" :href="route('users.index')">Users</Link>
</template>
```

---

## Theme Configuration

Edit `resources/js/theme.js` to customize colors, fonts, and border radii. daisyUI also supports theme switching via its built-in themes: https://daisyui.com/docs/themes/

---

## Page-by-Page Reference

Each page's `<script setup>` has a JSDoc block listing:
1. **Props** — what data the backend sends
2. **Composables** — what methods/forms are available
3. **Suggested daisyUI components** — with direct links

Open any page file and read the comment block at the top for full details.

### Quick overview

| Page | Props | Composables | Key Design Elements |
|---|---|---|---|
| Auth/Login | — | useAuth | Login form with email/password |
| Dashboard/Index | user, events | — | Stat cards, events table, quick actions |
| Events/Index | events | useEvents | Events table with status badges, CRUD buttons |
| Events/Create | — | useEvents | Create event form |
| Events/Edit | event | useEvents | Edit event form (pre-populated) |
| Events/Show | event (with relations) | — | Tabbed detail view (contestants, criteria, judges) |
| Contestants/Index | event, contestants | useContestants | Contestants table, add form |
| Contestants/Create | event | useContestants | Create contestant form with photo upload |
| Criteria/Index | event, criteria | useCriteria | Criteria table, inline edit, add form |
| Judges/Index | event, judges, availableJudges | useJudges | Assigned judges list, assign dropdown |
| Users/Index | users | useUsers | Users table with role badges |
| Users/Create | — | useUsers | User creation form with role dropdown |
| Scoring/Index | event, contestants, criteria, existingScores | useScoring, useEcho | Score input grid (contestant x criteria matrix) |
| Scores/Index | event, scores | — | All scores data table with filters |
| Scoreboard/Show | event, rankings | useEcho | Live animated scoreboard with real-time updates |
| Reports/* | event, report | — | Data visualizations, tables, charts |

---

## Tips

- **Always use `class="btn btn-primary"` not `class="bg-blue-500 ..."`** — let daisyUI handle consistent theming
- **Use `form.processing`** to disable buttons and show loading spinners during submission
- **Use `form.errors.fieldName`** to show validation errors inline
- **Use `<Link>` from InertiaJS** for all internal navigation, never `<a href>`
- **Use `route('name', params)`** (Ziggy helper) for URLs, never hardcode paths
- **Toast notifications are automatic** — backend flash messages appear as toasts without any extra code
- **Confirm modals are built-in** — destructive actions already trigger confirm dialogs
- **Check the JSDoc comments** in each page file for specific design guidance
