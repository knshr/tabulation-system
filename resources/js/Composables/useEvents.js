/**
 * useEvents - Composable for event CRUD operations
 *
 * Usage (for frontend developer):
 *   import { useEvents } from '@/Composables/useEvents';
 *
 *   // For create page:
 *   const { createForm, store } = useEvents();
 *   // Bind: v-model="createForm.name", v-model="createForm.description", etc.
 *   // Submit: @submit.prevent="store"
 *
 *   // For edit page:
 *   const { editForm, update } = useEvents(existingEvent);
 *   // Bind: v-model="editForm.name", etc. (pre-populated)
 *   // Submit: @submit.prevent="update"
 *
 *   // For index page (delete):
 *   const { destroy } = useEvents();
 *   // Delete: @click="destroy(eventId)"
 *
 *   // Form state:
 *   //   createForm.errors.name - validation error
 *   //   createForm.processing - true while submitting
 */
import { useForm } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import { useConfirm } from './useConfirm';

export function useEvents(event = null) {
    const { confirm } = useConfirm();

    const createForm = useForm({
        name: '',
        description: '',
        venue: '',
        event_date: '',
        scoring_mode: 'blind',
    });

    const editForm = event
        ? useForm({
              name: event.name || '',
              description: event.description || '',
              venue: event.venue || '',
              event_date: event.event_date || '',
              scoring_mode: event.scoring_mode || 'blind',
          })
        : null;

    function store() {
        createForm.post(route('events.store'));
    }

    function update() {
        if (!editForm || !event) return;
        editForm.put(route('events.update', event.id));
    }

    async function destroy(eventId) {
        const ok = await confirm({
            title: 'Delete Event',
            message: 'This will permanently remove the event and all its data. This action cannot be undone.',
            confirmText: 'Delete',
            variant: 'danger',
        });
        if (!ok) return;
        router.delete(route('events.destroy', eventId));
    }

    return { createForm, editForm, store, update, destroy };
}
