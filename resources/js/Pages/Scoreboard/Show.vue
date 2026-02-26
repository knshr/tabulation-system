<script setup>
/**
 * Scoreboard/Show - Public live scoreboard
 *
 * @prop {Object} event - Event
 * @prop {Object} rankings - { rankings: [{ contestant, total_weighted_score, rank }], criteria }
 *
 * Composable: useEcho(event.id)
 *   - subscribeToScoreboard(callback) - listen for real-time rank updates
 *   - Auto-cleans up on component unmount
 *
 * Designer: Build animated live scoreboard with real-time rank updates.
 * Use daisyUI component classes. See: https://daisyui.com/components/
 *
 * Suggested daisyUI components:
 *   - table (https://daisyui.com/components/table/) - rankings table
 *   - card (https://daisyui.com/components/card/) - podium cards for top 3
 *   - badge (https://daisyui.com/components/badge/) - live indicator
 *   - loading (https://daisyui.com/components/loading/) - waiting state
 *   - stat (https://daisyui.com/components/stat/) - score display
 */
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { useEcho } from '@/Composables/useEcho';

defineOptions({ layout: GuestLayout });

const props = defineProps({
    event: { type: Object, default: () => ({}) },
    rankings: { type: Object, default: () => ({}) },
});

const { subscribeToScoreboard } = useEcho(props.event.id);
subscribeToScoreboard((data) => {
    // Real-time scoreboard update received - frontend developer can handle UI updates here
});
</script>

<template>
    <div>
        <h1>Live Scoreboard - {{ event.name }}</h1>
        <p>Designer: Build animated live scoreboard with real-time rank updates.</p>
    </div>
</template>
