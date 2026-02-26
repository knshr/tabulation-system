<script setup>
/**
 * Criteria/Index - Manage criteria for an event
 *
 * @prop {Object} event - Parent event
 * @prop {Array} criteria - Criteria for this event
 *   Each: { id, name, description, max_score, percentage_weight, order }
 *
 * Composable: useCriteria(event.id)
 *   - createForm.name, createForm.description, createForm.max_score,
 *     createForm.percentage_weight, createForm.order (v-model bindings)
 *   - createForm.errors.name, etc. (validation errors)
 *   - createForm.processing (boolean - true while submitting)
 *   - store() - add new criterion
 *   - update(criterionId, { name, max_score, ... }) - inline edit
 *   - destroy(criterionId) - delete criterion (with confirmation modal)
 *
 * Designer: Build criteria list with inline add/edit/delete.
 * Use daisyUI component classes. See: https://daisyui.com/components/
 *
 * Suggested daisyUI components:
 *   - table (https://daisyui.com/components/table/) - criteria listing
 *   - input (https://daisyui.com/components/input/) - inline edit fields
 *   - collapse (https://daisyui.com/components/collapse/) - add form section
 *   - button (https://daisyui.com/components/button/) - actions
 *   - badge (https://daisyui.com/components/badge/) - weight percentages
 *   - card (https://daisyui.com/components/card/) - wrapper
 */
import AppLayout from '@/Layouts/AppLayout.vue';
import { useCriteria } from '@/Composables/useCriteria';

defineOptions({ layout: AppLayout });

const props = defineProps({
    event: { type: Object, default: () => ({}) },
    criteria: { type: Array, default: () => [] },
});

const { createForm, store, update, destroy } = useCriteria(props.event.id);
</script>

<template>
    <div>
        <h1>Criteria - {{ event.name }}</h1>
        <p>{{ criteria.length }} criteria. Designer: Build criteria list with inline add/edit/delete.</p>
    </div>
</template>
