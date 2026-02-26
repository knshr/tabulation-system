<script setup>
/**
 * Contestants/Create - Add contestant to event
 *
 * @prop {Object} event - Parent event
 *
 * Composable: useContestants(event.id)
 *   - createForm.name, createForm.nickname, createForm.description,
 *     createForm.contestant_number (v-model bindings)
 *   - createForm.photo - set via: @change="createForm.photo = $event.target.files[0]"
 *   - createForm.errors.name, etc. (validation errors)
 *   - createForm.processing (boolean - true while submitting)
 *   - store() - submit handler
 *
 * Designer: Build contestant form (name, nickname, description, photo upload, number).
 * Use daisyUI component classes. See: https://daisyui.com/components/
 *
 * Suggested daisyUI components:
 *   - input (https://daisyui.com/components/input/) - text fields
 *   - textarea (https://daisyui.com/components/textarea/) - description
 *   - file-input (https://daisyui.com/components/file-input/) - photo upload
 *   - button (https://daisyui.com/components/button/) - submit
 *   - card (https://daisyui.com/components/card/) - form wrapper
 *   - loading (https://daisyui.com/components/loading/) - processing state
 */
import AppLayout from '@/Layouts/AppLayout.vue';
import { useContestants } from '@/Composables/useContestants';

defineOptions({ layout: AppLayout });

const props = defineProps({
    event: { type: Object, default: () => ({}) },
});

const { createForm, store } = useContestants(props.event.id);
</script>

<template>
    <div>
        <h1>Add Contestant - {{ event.name }}</h1>
        <p>Designer: Build contestant form (name, nickname, description, photo upload, number).</p>
    </div>
</template>
