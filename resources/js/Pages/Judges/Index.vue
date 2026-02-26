<script setup>
/**
 * Judges/Index - Assign judges to an event
 *
 * @prop {Object} event - Parent event
 * @prop {Array} assignedJudges - Currently assigned judges
 *   Each: { id, name, email }
 * @prop {Array} availableJudges - All users with judge role
 *   Each: { id, name, email }
 *
 * Composable: useJudges(event.id)
 *   - assignForm.judge_id (v-model binding - select from availableJudges)
 *   - assignForm.errors.judge_id (validation error)
 *   - assignForm.processing (boolean - true while submitting)
 *   - assign() - assign selected judge
 *   - remove(judgeId) - unassign judge (with confirmation modal)
 *
 * Designer: Build judge assignment UI (dropdown to assign, list with remove buttons).
 * Use daisyUI component classes. See: https://daisyui.com/components/
 *
 * Suggested daisyUI components:
 *   - select (https://daisyui.com/components/select/) - judge picker
 *   - button (https://daisyui.com/components/button/) - assign/remove
 *   - avatar (https://daisyui.com/components/avatar/) - judge avatars
 *   - card (https://daisyui.com/components/card/) - panel wrappers
 *   - badge (https://daisyui.com/components/badge/) - count indicator
 */
import AppLayout from '@/Layouts/AppLayout.vue';
import { useJudges } from '@/Composables/useJudges';

defineOptions({ layout: AppLayout });

const props = defineProps({
    event: { type: Object, default: () => ({}) },
    assignedJudges: { type: Array, default: () => [] },
    availableJudges: { type: Array, default: () => [] },
});

const { assignForm, assign, remove } = useJudges(props.event.id);
</script>

<template>
    <div>
        <h1>Judges - {{ event.name }}</h1>
        <p>{{ assignedJudges.length }} assigned. Designer: Build judge assignment UI (assign/remove).</p>
    </div>
</template>
