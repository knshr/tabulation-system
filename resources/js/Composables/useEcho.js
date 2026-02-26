/**
 * useEcho - Composable for real-time channel subscriptions
 *
 * Usage (for frontend developer):
 *   import { useEcho } from '@/Composables/useEcho';
 *   const { subscribeToScores, subscribeToScoreboard, leave } = useEcho(eventId);
 *   subscribeToScores((scoreData) => { // handle new score });
 *   onUnmounted(() => leave());
 */
import { onUnmounted } from 'vue';

export function useEcho(eventId) {
    const channels = [];

    function subscribeToScores(callback) {
        const channel = window.Echo.private(`event.${eventId}.scores`)
            .listen('ScoreSubmitted', (e) => callback(e.score));
        channels.push(channel);
        return channel;
    }

    function subscribeToScoreboard(callback) {
        const channel = window.Echo.channel(`event.${eventId}.scoreboard`)
            .listen('ScoreSubmitted', (e) => callback(e.score))
            .listen('EventStatusChanged', (e) => callback(e));
        channels.push(channel);
        return channel;
    }

    function leave() {
        window.Echo.leave(`event.${eventId}.scores`);
        window.Echo.leave(`event.${eventId}.scoreboard`);
    }

    onUnmounted(() => leave());

    return { subscribeToScores, subscribeToScoreboard, leave };
}
