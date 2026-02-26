<script setup>
/**
 * Reports/CriteriaBreakdown - Scores grouped by criteria
 *
 * @prop {Object} event - Event
 * @prop {Object} report - { breakdowns: [{ criteria, scores_by_contestant, overall_average }] }
 *
 * Designer: Build criteria analysis charts (bar/radar) with stats tables.
 * Use daisyUI component classes. See: https://daisyui.com/components/
 *
 * Suggested daisyUI components:
 *   - card (https://daisyui.com/components/card/) - criteria section cards
 *   - table (https://daisyui.com/components/table/) - scores per criteria
 *   - stat (https://daisyui.com/components/stat/) - averages, min, max
 *   - progress (https://daisyui.com/components/progress/) - score bars
 *   - radial-progress (https://daisyui.com/components/radial-progress/) - percentage display
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
        <h1>Criteria Breakdown - {{ event.name }}</h1>
        <p>Designer: Build criteria analysis charts (bar/radar) with stats tables.</p>
    </div>
</template>
