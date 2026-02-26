/**
 * useContestants - Composable for contestant CRUD operations (scoped to event)
 *
 * Usage (for frontend developer):
 *   import { useContestants } from '@/Composables/useContestants';
 *   const { createForm, store, destroy } = useContestants(event.id);
 *
 *   // Bind: v-model="createForm.name", v-model="createForm.nickname", etc.
 *   // Photo upload: @change="createForm.photo = $event.target.files[0]"
 *   // Submit: @submit.prevent="store"
 *   // Delete: @click="destroy(contestantId)"
 *
 *   // Form state:
 *   //   createForm.errors.name - validation error
 *   //   createForm.processing - true while submitting
 */
import { useForm } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import { useConfirm } from './useConfirm';

export function useContestants(eventId) {
    const { confirm } = useConfirm();
    const createForm = useForm({
        name: '',
        nickname: '',
        description: '',
        photo: null,
        contestant_number: '',
    });

    function store() {
        createForm.post(route('events.contestants.store', eventId), {
            forceFormData: true,
        });
    }

    async function destroy(contestantId) {
        const ok = await confirm({
            title: 'Remove Contestant',
            message: 'Are you sure you want to remove this contestant from the event?',
            confirmText: 'Remove',
            variant: 'danger',
        });
        if (!ok) return;
        router.delete(route('events.contestants.destroy', [eventId, contestantId]));
    }

    return { createForm, store, destroy };
}
