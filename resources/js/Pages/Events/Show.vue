<script setup>
/**
 * Events/Show - Event detail view
 *
 * @prop {Object} event - Event with loaded relations
 *   { id, name, description, venue, event_date, status, scoring_mode, creator, contestants, judges, criteria }
 *
 * Designer: Build event detail view with tabs for contestants, criteria, judges, scores.
 * Use daisyUI component classes. See: https://daisyui.com/components/
 *
 * Suggested daisyUI components:
 *   - tab (https://daisyui.com/components/tab/) - section tabs (contestants, criteria, judges, scores)
 *   - card (https://daisyui.com/components/card/) - event info card
 *   - badge (https://daisyui.com/components/badge/) - status & scoring mode badges
 *   - table (https://daisyui.com/components/table/) - data tables per tab
 *   - stat (https://daisyui.com/components/stat/) - event statistics
 *   - button (https://daisyui.com/components/button/) - action buttons
 */
import AppLayout from '@/Layouts/AppLayout.vue';

defineOptions({ layout: AppLayout });

const props = defineProps({
    event: { type: Object, default: () => ({}) },
});
</script>

<template>
    <div>
        <h1>{{ event.name || 'Event Detail' }}</h1>
        <p>Designer: Build event detail view with tabs for contestants, criteria, judges, scores.</p>
    </div>
</template>
