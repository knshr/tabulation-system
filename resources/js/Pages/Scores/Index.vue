<script setup>
/**
 * Scores/Index - View all scores for an event (admin view)
 *
 * @prop {Object} event - Event
 * @prop {Array} scores - All scores for this event
 *   Each: { id, judge, contestant, criteria, score, remarks, created_at }
 *
 * Designer: Build score data table with filters.
 * Use daisyUI component classes. See: https://daisyui.com/components/
 *
 * Suggested daisyUI components:
 *   - table (https://daisyui.com/components/table/) - scores listing
 *   - card (https://daisyui.com/components/card/) - table wrapper
 *   - input (https://daisyui.com/components/input/) - search/filter
 */
import AppLayout from '@/Layouts/AppLayout.vue';

defineOptions({ layout: AppLayout });

const props = defineProps({
    event: { type: Object, default: () => ({}) },
    scores: { type: Array, default: () => [] },
});
</script>

<template>
    <div>
        <h1>Scores - {{ event.name }}</h1>
        <p>{{ scores.length }} scores submitted. Designer: Build score data table with filters.</p>
    </div>
</template>
