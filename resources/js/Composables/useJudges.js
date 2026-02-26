/**
 * useJudges - Composable for judge assignment operations (scoped to event)
 *
 * Usage (for frontend developer):
 *   import { useJudges } from '@/Composables/useJudges';
 *   const { assignForm, assign, remove } = useJudges(event.id);
 *
 *   // Assign judge:
 *   //   Bind: v-model="assignForm.judge_id" (select from availableJudges)
 *   //   Submit: @submit.prevent="assign"
 *
 *   // Remove judge:
 *   //   @click="remove(judgeId)"
 *
 *   // Form state:
 *   //   assignForm.errors.judge_id - validation error
 *   //   assignForm.processing - true while submitting
 */
import { useForm } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import { useConfirm } from './useConfirm';

export function useJudges(eventId) {
    const { confirm } = useConfirm();
    const assignForm = useForm({
        judge_id: '',
    });

    function assign() {
        assignForm.post(route('events.judges.assign', eventId), {
            preserveScroll: true,
            onSuccess: () => assignForm.reset(),
        });
    }

    async function remove(judgeId) {
        const ok = await confirm({
            title: 'Remove Judge',
            message: 'Are you sure you want to remove this judge from the event?',
            confirmText: 'Remove',
            variant: 'warning',
        });
        if (!ok) return;
        router.delete(route('events.judges.remove', [eventId, judgeId]), {
            preserveScroll: true,
        });
    }

    return { assignForm, assign, remove };
}
