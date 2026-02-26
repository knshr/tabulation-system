<script setup>
/**
 * Events/Index - Event listing
 *
 * @prop {Array} events - All events
 *   Each: { id, name, description, venue, event_date, status, scoring_mode, creator }
 *
 * Composable: useEvents
 *   - destroy(eventId) - delete an event (with confirmation modal)
 *
 * Designer: Build event list/table with status badges and action buttons (view, edit, delete).
 * Use daisyUI component classes. See: https://daisyui.com/components/
 *
 * Suggested daisyUI components:
 *   - table (https://daisyui.com/components/table/) - event listing
 *   - badge (https://daisyui.com/components/badge/) - status & scoring mode
 *   - button (https://daisyui.com/components/button/) - actions
 *   - dropdown (https://daisyui.com/components/dropdown/) - action menu
 *   - card (https://daisyui.com/components/card/) - table wrapper
 */
import AppLayout from '@/Layouts/AppLayout.vue';
import { useEvents } from '@/Composables/useEvents';

defineOptions({ layout: AppLayout });

const props = defineProps({
    events: { type: Array, default: () => [] },
});

const { destroy } = useEvents();
</script>

<template>
    <div>
        <h1>Events</h1>
        <p>{{ events.length }} events. Designer: Build event list/table with status badges, actions.</p>
    </div>
</template>
