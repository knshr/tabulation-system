<script setup>
/**
 * Contestants/Index - Manage contestants for an event
 *
 * @prop {Object} event - Parent event
 * @prop {Array} contestants - Contestants in this event
 *   Each: { id, name, nickname, description, photo, contestant_number }
 *
 * Composable: useContestants(event.id)
 *   - destroy(contestantId) - remove contestant (with confirmation modal)
 *
 * Designer: Build contestant list/table with photo, details, and remove button.
 * Use daisyUI component classes. See: https://daisyui.com/components/
 *
 * Suggested daisyUI components:
 *   - table (https://daisyui.com/components/table/) - contestant listing
 *   - avatar (https://daisyui.com/components/avatar/) - contestant photo
 *   - button (https://daisyui.com/components/button/) - remove action
 *   - card (https://daisyui.com/components/card/) - table wrapper
 */
import AppLayout from '@/Layouts/AppLayout.vue';
import { useContestants } from '@/Composables/useContestants';

defineOptions({ layout: AppLayout });

const props = defineProps({
    event: { type: Object, default: () => ({}) },
    contestants: { type: Array, default: () => [] },
});

const { destroy } = useContestants(props.event.id);
</script>

<template>
    <div>
        <h1>Contestants - {{ event.name }}</h1>
        <p>{{ contestants.length }} contestants. Designer: Build contestant list with add/remove actions.</p>
    </div>
</template>
