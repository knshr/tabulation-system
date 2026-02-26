<script setup>
/**
 * Reports/EventSummary - High-level event statistics
 *
 * @prop {Object} event - Event
 * @prop {Object} report - { total_judges, total_contestants, total_criteria, expected_scores,
 *   actual_scores, completion_percentage, average_score, highest_score, lowest_score }
 *
 * Designer: Build summary dashboard with stat cards and progress indicators.
 * Use daisyUI component classes. See: https://daisyui.com/components/
 *
 * Suggested daisyUI components:
 *   - stat (https://daisyui.com/components/stat/) - key metrics (judges, contestants, scores)
 *   - card (https://daisyui.com/components/card/) - section wrappers
 *   - progress (https://daisyui.com/components/progress/) - completion percentage
 *   - radial-progress (https://daisyui.com/components/radial-progress/) - circular progress
 *   - badge (https://daisyui.com/components/badge/) - status indicators
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
        <h1>Event Summary - {{ event.name }}</h1>
        <p>Designer: Build summary dashboard with stat cards and progress indicators.</p>
    </div>
</template>
