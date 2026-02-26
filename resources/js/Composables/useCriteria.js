/**
 * useCriteria - Composable for criteria CRUD operations (scoped to event)
 *
 * Usage (for frontend developer):
 *   import { useCriteria } from '@/Composables/useCriteria';
 *   const { createForm, store, update, destroy } = useCriteria(event.id);
 *
 *   // Add new criteria:
 *   //   Bind: v-model="createForm.name", v-model="createForm.max_score", etc.
 *   //   Submit: @submit.prevent="store"
 *
 *   // Inline edit (call update with criterion id and data object):
 *   //   @submit.prevent="update(criterion.id, { name: ..., max_score: ... })"
 *
 *   // Delete:
 *   //   @click="destroy(criterion.id)"
 *
 *   // Form state:
 *   //   createForm.errors.name - validation error
 *   //   createForm.processing - true while submitting
 */
import { useForm } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import { useConfirm } from './useConfirm';

export function useCriteria(eventId) {
    const { confirm } = useConfirm();
    const createForm = useForm({
        name: '',
        description: '',
        max_score: 100,
        percentage_weight: 0,
        order: 0,
    });

    function store() {
        createForm.post(route('events.criteria.store', eventId), {
            preserveScroll: true,
            onSuccess: () => createForm.reset(),
        });
    }

    function update(criterionId, data) {
        router.put(
            route('events.criteria.update', [eventId, criterionId]),
            data,
            { preserveScroll: true }
        );
    }

    async function destroy(criterionId) {
        const ok = await confirm({
            title: 'Delete Criterion',
            message: 'Are you sure you want to delete this criterion? Existing scores for this criterion will also be removed.',
            confirmText: 'Delete',
            variant: 'danger',
        });
        if (!ok) return;
        router.delete(route('events.criteria.destroy', [eventId, criterionId]), {
            preserveScroll: true,
        });
    }

    return { createForm, store, update, destroy };
}
