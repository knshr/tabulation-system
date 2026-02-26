<script setup>
/**
 * Reports/JudgeScoresheet - Individual judge scoring records
 *
 * @prop {Object} event - Event
 * @prop {Object} report - { sheets: [{ judge, scores: [{ contestant, criteria_scores }] }] }
 *
 * Designer: Build per-judge score tables with print support.
 * Use daisyUI component classes. See: https://daisyui.com/components/
 *
 * Suggested daisyUI components:
 *   - table (https://daisyui.com/components/table/) - score tables per judge
 *   - card (https://daisyui.com/components/card/) - judge section wrapper
 *   - select (https://daisyui.com/components/select/) - judge filter/selector
 *   - divider (https://daisyui.com/components/divider/) - section separator
 *   - button (https://daisyui.com/components/button/) - print button
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
        <h1>Judge Scoresheet - {{ event.name }}</h1>
        <p>Designer: Build per-judge score tables with print support.</p>
    </div>
</template>
