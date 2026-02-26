<script setup>
/**
 * Dashboard/Index - Main dashboard
 *
 * @prop {Object} user - Current user { id, name, email, role }
 * @prop {Array} events - Events list (filtered by role)
 *   Each event: { id, name, description, venue, event_date, status, scoring_mode, creator }
 *
 * Designer: Build dashboard cards, event summaries, quick actions here.
 * Use daisyUI component classes. See: https://daisyui.com/components/
 *
 * Suggested daisyUI components:
 *   - stat (https://daisyui.com/components/stat/) - stat cards (total events, active, etc.)
 *   - table (https://daisyui.com/components/table/) - recent events table
 *   - card (https://daisyui.com/components/card/) - content wrappers
 *   - badge (https://daisyui.com/components/badge/) - status & role badges
 *   - button (https://daisyui.com/components/button/) - quick actions
 */
import AppLayout from '@/Layouts/AppLayout.vue';

defineOptions({ layout: AppLayout });

const props = defineProps({
    user: { type: Object, default: () => ({}) },
    events: { type: Array, default: () => [] },
});
</script>

<template>
    <div>
        <h1>Dashboard</h1>
        <p>Welcome, {{ user.name }}. {{ events.length }} events available.</p>
        <p>Designer: Build dashboard cards, event summaries, quick actions here.</p>
    </div>
</template>
