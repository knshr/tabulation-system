<script setup>
/**
 * Events/Create - Create new event form
 *
 * Props: none
 *
 * Composable: useEvents
 *   - createForm.name, createForm.description, createForm.venue,
 *     createForm.event_date, createForm.scoring_mode (v-model bindings)
 *   - createForm.errors.name, etc. (validation errors)
 *   - createForm.processing (boolean - true while submitting)
 *   - store() - submit handler
 *
 * Designer: Build event creation form (name, description, venue, date, scoring mode dropdown).
 * Use daisyUI component classes. See: https://daisyui.com/components/
 *
 * Suggested daisyUI components:
 *   - input (https://daisyui.com/components/input/) - text fields
 *   - textarea (https://daisyui.com/components/textarea/) - description
 *   - select (https://daisyui.com/components/select/) - scoring mode
 *   - button (https://daisyui.com/components/button/) - submit
 *   - card (https://daisyui.com/components/card/) - form wrapper
 *   - loading (https://daisyui.com/components/loading/) - processing state
 */
import AppLayout from '@/Layouts/AppLayout.vue';
import { useEvents } from '@/Composables/useEvents';

defineOptions({ layout: AppLayout });

const { createForm, store } = useEvents();
</script>

<template>
    <div>
        <h1>Create Event</h1>
        <p>Designer: Build event creation form (name, description, venue, date, scoring mode).</p>
    </div>
</template>
