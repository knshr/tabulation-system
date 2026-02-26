<script setup>
/**
 * Scoring/Index - Judge scoring interface
 *
 * @prop {Object} event - Event being scored
 * @prop {Array} contestants - Contestants to score
 * @prop {Array} criteria - Criteria to score against
 *   Each: { id, name, description, max_score, percentage_weight }
 * @prop {Array} existingScores - Judge's previously submitted scores
 *   Each: { id, contestant_id, criteria_id, score, remarks }
 *
 * Composable: useScoring(event.id, contestants, criteria, existingScores)
 *   - scoreForm.scores - flat array of { contestant_id, criteria_id, score, remarks }
 *   - scoreForm.processing (boolean - true while submitting)
 *   - scoreForm.errors (validation errors)
 *   - getScore(contestantId, criteriaId) - get a specific score entry
 *   - setScore(contestantId, criteriaId, value) - update a score value
 *   - setRemarks(contestantId, criteriaId, value) - update remarks
 *   - submit() - submit all scores
 *
 * Composable: useEcho(event.id)
 *   - subscribeToScores(callback) - listen for real-time score updates
 *
 * Designer: Build scoring grid (contestant x criteria matrix with score inputs).
 * Use daisyUI component classes. See: https://daisyui.com/components/
 *
 * Suggested daisyUI components:
 *   - table (https://daisyui.com/components/table/) - scoring matrix
 *   - input (https://daisyui.com/components/input/) - score inputs
 *   - button (https://daisyui.com/components/button/) - submit scores
 *   - badge (https://daisyui.com/components/badge/) - scoring mode indicator
 *   - alert (https://daisyui.com/components/alert/) - error messages
 *   - loading (https://daisyui.com/components/loading/) - processing state
 */
import AppLayout from '@/Layouts/AppLayout.vue';
import { useScoring } from '@/Composables/useScoring';
import { useEcho } from '@/Composables/useEcho';

defineOptions({ layout: AppLayout });

const props = defineProps({
    event: { type: Object, default: () => ({}) },
    contestants: { type: Array, default: () => [] },
    criteria: { type: Array, default: () => [] },
    existingScores: { type: Array, default: () => [] },
});

const { scoreForm, getScore, setScore, setRemarks, submit } = useScoring(
    props.event.id,
    props.contestants,
    props.criteria,
    props.existingScores,
);

const { subscribeToScores } = useEcho(props.event.id);
subscribeToScores((scoreData) => {
    // Real-time score update received - frontend developer can handle UI updates here
});
</script>

<template>
    <div>
        <h1>Scoring - {{ event.name }}</h1>
        <p>{{ contestants.length }} contestants, {{ criteria.length }} criteria.</p>
        <p>Designer: Build scoring grid (contestant x criteria matrix with score inputs).</p>
    </div>
</template>
