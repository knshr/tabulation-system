<script setup>
/**
 * Events/Edit - Edit event form
 *
 * @prop {Object} event - Current event data
 *   { id, name, description, venue, event_date, status, scoring_mode }
 *
 * Composable: useEvents(event)
 *   - editForm.name, editForm.description, editForm.venue,
 *     editForm.event_date, editForm.scoring_mode (v-model bindings, pre-populated)
 *   - editForm.errors.name, etc. (validation errors)
 *   - editForm.processing (boolean - true while submitting)
 *   - update() - submit handler
 *
 * Designer: Build event edit form (pre-populated with event data).
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

const props = defineProps({
    event: { type: Object, default: () => ({}) },
});

const { editForm, update } = useEvents(props.event);
</script>

<template>
    <div>
        <h1>Edit Event: {{ event.name }}</h1>
        <p>Designer: Build event edit form (pre-populated with event data).</p>
    </div>
</template>
