/**
 * useScoring - Composable for judge score submission
 *
 * Usage (for frontend developer):
 *   import { useScoring } from '@/Composables/useScoring';
 *   const { scoreForm, initScores, submit } = useScoring(event.id, contestants, criteria, existingScores);
 *
 *   // scoreForm.scores is a flat array of { contestant_id, criteria_id, score, remarks }
 *   // Use getScore(contestantId, criteriaId) to get a specific score entry
 *   // Use setScore(contestantId, criteriaId, value) to update a score
 *
 *   // Submit all scores: @submit.prevent="submit"
 *
 *   // Form state:
 *   //   scoreForm.processing - true while submitting
 *   //   scoreForm.errors - validation errors
 */
import { useForm } from '@inertiajs/vue3';

export function useScoring(eventId, contestants = [], criteria = [], existingScores = []) {
    const scores = buildScoreMatrix(contestants, criteria, existingScores);

    const scoreForm = useForm({ scores });

    function buildScoreMatrix(contestants, criteria, existing) {
        const matrix = [];
        for (const contestant of contestants) {
            for (const criterion of criteria) {
                const existing_score = existing.find(
                    (s) => s.contestant_id === contestant.id && s.criteria_id === criterion.id
                );
                matrix.push({
                    contestant_id: contestant.id,
                    criteria_id: criterion.id,
                    score: existing_score?.score ?? '',
                    remarks: existing_score?.remarks ?? '',
                });
            }
        }
        return matrix;
    }

    function getScore(contestantId, criteriaId) {
        return scoreForm.scores.find(
            (s) => s.contestant_id === contestantId && s.criteria_id === criteriaId
        );
    }

    function setScore(contestantId, criteriaId, value) {
        const entry = getScore(contestantId, criteriaId);
        if (entry) entry.score = value;
    }

    function setRemarks(contestantId, criteriaId, value) {
        const entry = getScore(contestantId, criteriaId);
        if (entry) entry.remarks = value;
    }

    function submit() {
        scoreForm.post(route('events.scoring.store', eventId), {
            preserveScroll: true,
        });
    }

    return { scoreForm, getScore, setScore, setRemarks, submit };
}
