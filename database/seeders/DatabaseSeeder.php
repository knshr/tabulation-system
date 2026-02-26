<?php

namespace Database\Seeders;

use App\Enums\EventStatus;
use App\Enums\ScoringMode;
use App\Enums\UserRole;
use App\Models\Contestant;
use App\Models\Criteria;
use App\Models\Event;
use App\Models\Score;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create SuperAdmin
        $superAdmin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@tabulation.test',
            'password' => bcrypt('password'),
            'role' => UserRole::SuperAdmin,
        ]);

        // Create Admins
        $admin1 = User::factory()->create([
            'name' => 'Admin One',
            'email' => 'admin1@tabulation.test',
            'password' => bcrypt('password'),
            'role' => UserRole::Admin,
        ]);

        $admin2 = User::factory()->create([
            'name' => 'Admin Two',
            'email' => 'admin2@tabulation.test',
            'password' => bcrypt('password'),
            'role' => UserRole::Admin,
        ]);

        // Create Judges
        $judges = collect();
        for ($i = 1; $i <= 5; $i++) {
            $judges->push(User::factory()->create([
                'name' => "Judge {$i}",
                'email' => "judge{$i}@tabulation.test",
                'password' => bcrypt('password'),
                'role' => UserRole::Judge,
            ]));
        }

        // Create Viewers
        User::factory()->create([
            'name' => 'Viewer One',
            'email' => 'viewer1@tabulation.test',
            'password' => bcrypt('password'),
            'role' => UserRole::Viewer,
        ]);

        User::factory()->create([
            'name' => 'Viewer Two',
            'email' => 'viewer2@tabulation.test',
            'password' => bcrypt('password'),
            'role' => UserRole::Viewer,
        ]);

        // Create Events
        $event1 = Event::factory()->create([
            'name' => 'Miss Universe 2026',
            'description' => 'Annual beauty pageant competition',
            'venue' => 'Grand Convention Center',
            'event_date' => now()->addDays(7),
            'status' => EventStatus::Active,
            'scoring_mode' => ScoringMode::Blind,
            'created_by' => $admin1->id,
        ]);

        $event2 = Event::factory()->create([
            'name' => 'Talent Showcase 2026',
            'description' => 'Multi-category talent competition',
            'venue' => 'City Auditorium',
            'event_date' => now()->addDays(14),
            'status' => EventStatus::Draft,
            'scoring_mode' => ScoringMode::Open,
            'created_by' => $admin2->id,
        ]);

        $event3 = Event::factory()->create([
            'name' => 'Hackathon Spring 2026',
            'description' => 'Annual coding competition',
            'venue' => 'Tech Hub',
            'event_date' => now()->addDays(30),
            'status' => EventStatus::Completed,
            'scoring_mode' => ScoringMode::Blind,
            'created_by' => $superAdmin->id,
        ]);

        // Create Contestants
        $contestants = Contestant::factory(10)->create();

        // Attach contestants to events
        foreach ([$event1, $event2, $event3] as $event) {
            $eventContestants = $contestants->random(5);
            foreach ($eventContestants->values() as $index => $contestant) {
                $event->contestants()->attach($contestant->id, ['order' => $index + 1]);
            }
        }

        // Create Criteria for each event
        $criteriaNames = [
            ['name' => 'Beauty', 'weight' => 25],
            ['name' => 'Talent', 'weight' => 30],
            ['name' => 'Intelligence', 'weight' => 25],
            ['name' => 'Poise & Grace', 'weight' => 20],
        ];

        foreach ([$event1, $event2, $event3] as $event) {
            foreach ($criteriaNames as $i => $c) {
                Criteria::factory()->create([
                    'event_id' => $event->id,
                    'name' => $c['name'],
                    'max_score' => 100,
                    'percentage_weight' => $c['weight'],
                    'order' => $i + 1,
                ]);
            }
        }

        // Assign judges to events
        $event1->judges()->attach($judges->take(3)->pluck('id'));
        $event2->judges()->attach($judges->take(4)->pluck('id'));
        $event3->judges()->attach($judges->pluck('id'));

        // Create some scores for the completed event (event3)
        $event3Contestants = $event3->contestants;
        $event3Criteria = $event3->criteria;
        $event3Judges = $event3->judges;

        foreach ($event3Judges as $judge) {
            foreach ($event3Contestants as $contestant) {
                foreach ($event3Criteria as $criteria) {
                    Score::factory()->create([
                        'event_id' => $event3->id,
                        'judge_id' => $judge->id,
                        'contestant_id' => $contestant->id,
                        'criteria_id' => $criteria->id,
                        'score' => fake()->randomFloat(2, 60, 100),
                    ]);
                }
            }
        }

        // Create a few scores for the active event (event1) - partial scoring
        $event1Contestants = $event1->contestants->take(2);
        $event1Criteria = $event1->criteria;
        $event1Judge = $judges->first();

        foreach ($event1Contestants as $contestant) {
            foreach ($event1Criteria as $criteria) {
                Score::factory()->create([
                    'event_id' => $event1->id,
                    'judge_id' => $event1Judge->id,
                    'contestant_id' => $contestant->id,
                    'criteria_id' => $criteria->id,
                    'score' => fake()->randomFloat(2, 70, 95),
                ]);
            }
        }
    }
}
