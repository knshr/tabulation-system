<script setup>
/**
 * Reports/ContestantDetail - Per-contestant score details
 *
 * @prop {Object} event - Event
 * @prop {Object} report - { details: [{ contestant, scores_by_criteria, total_average }] }
 *
 * Designer: Build contestant score detail cards with criteria breakdowns.
 * Use daisyUI component classes. See: https://daisyui.com/components/
 *
 * Suggested daisyUI components:
 *   - card (https://daisyui.com/components/card/) - contestant detail cards
 *   - table (https://daisyui.com/components/table/) - criteria score breakdown
 *   - stat (https://daisyui.com/components/stat/) - total/average scores
 *   - progress (https://daisyui.com/components/progress/) - score visualizer
 *   - collapse (https://daisyui.com/components/collapse/) - expandable details
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
        <h1>Contestant Detail - {{ event.name }}</h1>
        <p>Designer: Build contestant score detail cards with criteria breakdowns.</p>
    </div>
</template>
