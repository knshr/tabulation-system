<script setup>
/**
 * Reports/ScoreComparison - Judge-to-judge comparison
 *
 * @prop {Object} event - Event
 * @prop {Object} report - { comparisons: [{ contestant, judge_scores, score_range }], judges, criteria }
 *
 * Designer: Build side-by-side judge comparison table with discrepancy highlighting.
 * Use daisyUI component classes. See: https://daisyui.com/components/
 *
 * Suggested daisyUI components:
 *   - table (https://daisyui.com/components/table/) - comparison matrix
 *   - card (https://daisyui.com/components/card/) - section wrapper
 *   - badge (https://daisyui.com/components/badge/) - discrepancy indicators
 *   - tooltip (https://daisyui.com/components/tooltip/) - score details on hover
 *   - select (https://daisyui.com/components/select/) - filter by criteria/contestant
 */
import AppLayout from '@/Layouts/AppLayout.vue';

defineOptions({ layout: AppLayout });

const props = defineProps({
    event: { type: Object, default: () => ({}) },
    report: { type: Object, default: () => ({}) },
});
</script>

<template>
    <div>
        <h1>Score Comparison - {{ event.name }}</h1>
        <p>Designer: Build side-by-side judge comparison table with discrepancy highlighting.</p>
    </div>
</template>
