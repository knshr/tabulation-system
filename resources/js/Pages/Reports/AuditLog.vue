<script setup>
/**
 * Reports/AuditLog - Score submission history
 *
 * @prop {Object} event - Event
 * @prop {Object} report - { entries: [{ id, judge, contestant, criteria, score, remarks,
 *   created_at, updated_at, was_edited }] }
 *
 * Designer: Build chronological score log with edit indicators and timestamps.
 * Use daisyUI component classes. See: https://daisyui.com/components/
 *
 * Suggested daisyUI components:
 *   - table (https://daisyui.com/components/table/) - log entries table
 *   - card (https://daisyui.com/components/card/) - table wrapper
 *   - badge (https://daisyui.com/components/badge/) - edit/new indicators
 *   - input (https://daisyui.com/components/input/) - search/filter
 *   - timeline (https://daisyui.com/components/timeline/) - chronological view option
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
        <h1>Audit Log - {{ event.name }}</h1>
        <p>Designer: Build chronological score log with edit indicators and timestamps.</p>
    </div>
</template>
