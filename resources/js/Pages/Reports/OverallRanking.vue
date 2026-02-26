<script setup>
/**
 * Reports/OverallRanking - Final contestant rankings
 *
 * @prop {Object} event - Event
 * @prop {Object} report - { rankings: [{ contestant, total_weighted_score, rank }], criteria }
 *
 * Designer: Build ranking table with positions, scores, crown icons.
 * Use daisyUI component classes. See: https://daisyui.com/components/
 *
 * Suggested daisyUI components:
 *   - table (https://daisyui.com/components/table/) - rankings table
 *   - card (https://daisyui.com/components/card/) - podium cards for top 3
 *   - badge (https://daisyui.com/components/badge/) - rank badges
 *   - stat (https://daisyui.com/components/stat/) - score display
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
        <h1>Overall Ranking - {{ event.name }}</h1>
        <p>Designer: Build ranking table with positions, scores, crown icons.</p>
    </div>
</template>
