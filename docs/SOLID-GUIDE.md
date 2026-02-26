# SOLID Developer Guide - Tabulation System

This guide explains how SOLID principles are applied in this codebase and how to follow them when adding new features.

## Architecture Overview

```
Request → Controller → FormRequest (validation) → Policy (auth) → Service (logic) → Repository (data) → Model
                                                                       ↓
                                                                  Event → Listener → Broadcast
```

**Rule:** Data flows in one direction. Never call a controller from a service, never call a service from a repository.

---

## S - Single Responsibility Principle

> Each class has one reason to change.

### Controllers — HTTP only

Controllers receive HTTP requests and return responses. They contain NO business logic.

```php
// GOOD
class EventController extends Controller
{
    public function store(StoreEventRequest $request, EventService $service)
    {
        $event = $service->createEvent($request->validated());
        return redirect()->route('events.show', $event);
    }
}

// BAD - business logic in controller
class EventController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([...]); // validation belongs in FormRequest
        if (auth()->user()->role !== 'admin') { abort(403); } // auth belongs in Policy
        $event = Event::create($validated); // data access belongs in Repository
        broadcast(new EventCreated($event)); // side effects belong in Listener
        return redirect()->route('events.show', $event);
    }
}
```

### Services — Business logic only

Services orchestrate operations and enforce business rules. They don't know about HTTP, requests, or responses.

```php
// GOOD
class ScoringService
{
    public function __construct(
        private ScoreRepositoryInterface $scoreRepo,
        private CriteriaRepositoryInterface $criteriaRepo,
    ) {}

    public function submitScore(int $eventId, int $judgeId, int $contestantId, int $criteriaId, float $score): Score
    {
        $criteria = $this->criteriaRepo->findOrFail($criteriaId);

        if ($score > $criteria->max_score) {
            throw new InvalidScoreException("Score exceeds maximum of {$criteria->max_score}");
        }

        return $this->scoreRepo->save([
            'event_id' => $eventId,
            'judge_id' => $judgeId,
            'contestant_id' => $contestantId,
            'criteria_id' => $criteriaId,
            'score' => $score,
        ]);
    }
}
```

### Repositories — Data access only

Repositories handle database queries and persistence. They don't contain business rules.

```php
// GOOD
class EloquentScoreRepository implements ScoreRepositoryInterface
{
    public function save(array $data): Score
    {
        return Score::updateOrCreate(
            Arr::only($data, ['event_id', 'judge_id', 'contestant_id', 'criteria_id']),
            Arr::only($data, ['score', 'remarks']),
        );
    }

    public function findByEvent(int $eventId): Collection
    {
        return Score::where('event_id', $eventId)->with(['judge', 'contestant', 'criteria'])->get();
    }
}
```

### Form Requests — Validation only

```php
class StoreScoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Let Policy handle authorization
    }

    public function rules(): array
    {
        return [
            'contestant_id' => ['required', 'exists:contestants,id'],
            'criteria_id' => ['required', 'exists:criteria,id'],
            'score' => ['required', 'numeric', 'min:0'],
            'remarks' => ['nullable', 'string', 'max:500'],
        ];
    }
}
```

### Policies — Authorization only

```php
class ScorePolicy
{
    public function create(User $user, Event $event): bool
    {
        return $user->role === UserRole::Judge
            && $event->judges()->where('judge_id', $user->id)->exists()
            && $event->status === EventStatus::Active;
    }
}
```

---

## O - Open/Closed Principle

> Open for extension, closed for modification.

### Adding a new report type

1. Create a new class implementing `ReportGeneratorInterface`
2. Register it in the report service
3. Do NOT modify existing report classes

```php
// Interface
interface ReportGeneratorInterface
{
    public function generate(Event $event, array $options = []): array;
    public function getName(): string;
}

// Existing report (don't modify this)
class OverallRankingReport implements ReportGeneratorInterface
{
    public function generate(Event $event, array $options = []): array { /* ... */ }
    public function getName(): string { return 'overall-ranking'; }
}

// NEW report - just add a new class
class TopPerformerReport implements ReportGeneratorInterface
{
    public function generate(Event $event, array $options = []): array { /* ... */ }
    public function getName(): string { return 'top-performer'; }
}
```

### Adding a new scoring strategy

```php
interface ScoringStrategyInterface
{
    public function calculate(Collection $scores, Collection $criteria): float;
}

class WeightedAverageStrategy implements ScoringStrategyInterface { /* ... */ }
class RankBasedStrategy implements ScoringStrategyInterface { /* ... */ }
// Add new strategies without touching existing ones
```

---

## L - Liskov Substitution Principle

> Subtypes must be substitutable for their base types.

Any class implementing a repository interface must fulfill the full contract. If `EloquentScoreRepository` works, a `CacheDecoratedScoreRepository` wrapping it must also work identically from the consumer's perspective.

```php
// This decorator can replace EloquentScoreRepository anywhere
class CacheDecoratedScoreRepository implements ScoreRepositoryInterface
{
    public function __construct(
        private EloquentScoreRepository $inner,
        private CacheManager $cache,
    ) {}

    public function findByEvent(int $eventId): Collection
    {
        return $this->cache->remember("scores.event.{$eventId}", 60, function () use ($eventId) {
            return $this->inner->findByEvent($eventId);
        });
    }

    public function save(array $data): Score
    {
        $this->cache->forget("scores.event.{$data['event_id']}");
        return $this->inner->save($data);
    }
}
```

---

## I - Interface Segregation Principle

> Clients should not depend on interfaces they don't use.

Split fat interfaces into focused ones:

```php
// GOOD - separate read and write interfaces
interface ScoreRepositoryInterface
{
    public function save(array $data): Score;
    public function update(int $id, array $data): Score;
    public function delete(int $id): bool;
}

interface ScoreQueryInterface
{
    public function findByEvent(int $eventId): Collection;
    public function findByJudge(int $eventId, int $judgeId): Collection;
    public function getAggregateByContestant(int $eventId): Collection;
}

// BAD - one fat interface forces report-only consumers to depend on write methods
interface ScoreRepositoryInterface
{
    public function save(array $data): Score;
    public function delete(int $id): bool;
    public function findByEvent(int $eventId): Collection;
    public function findByJudge(int $eventId, int $judgeId): Collection;
    public function getAggregateByContestant(int $eventId): Collection;
}
```

A `ReportService` that only reads scores depends on `ScoreQueryInterface`, not the full repository.

---

## D - Dependency Inversion Principle

> Depend on abstractions, not concretions.

### Service Provider bindings

All interface-to-implementation bindings are registered in `AppServiceProvider`:

```php
class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(EventRepositoryInterface::class, EloquentEventRepository::class);
        $this->app->bind(ContestantRepositoryInterface::class, EloquentContestantRepository::class);
        $this->app->bind(CriteriaRepositoryInterface::class, EloquentCriteriaRepository::class);
        $this->app->bind(ScoreRepositoryInterface::class, EloquentScoreRepository::class);
        $this->app->bind(ScoreQueryInterface::class, EloquentScoreRepository::class);
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
    }
}
```

### Constructor injection

Always type-hint interfaces, never concrete classes:

```php
// GOOD
class ScoringService
{
    public function __construct(
        private ScoreRepositoryInterface $scoreRepo,  // interface
    ) {}
}

// BAD
class ScoringService
{
    public function __construct(
        private EloquentScoreRepository $scoreRepo,  // concrete class
    ) {}
}
```

---

## Checklist for Adding New Features

1. **Need a new page?** → Create a thin controller method, a Vue page, and wire the route
2. **Need validation?** → Create a FormRequest class
3. **Need authorization?** → Add a method to the relevant Policy
4. **Need business logic?** → Add a method to the relevant Service (or create a new one)
5. **Need database access?** → Add a method to the relevant Repository interface and implementation
6. **Need a new entity?** → Create Model, Migration, Repository interface + implementation, Service, Policy, Controller
7. **Need a side effect?** → Create an Event and Listener
8. **Need a new report?** → Create a class implementing ReportGeneratorInterface

**Never put business logic in controllers, models, or repositories.**
