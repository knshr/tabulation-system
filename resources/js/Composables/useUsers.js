/**
 * useUsers - Composable for user CRUD operations
 *
 * Usage (for frontend developer):
 *   import { useUsers } from '@/Composables/useUsers';
 *   const { createForm, store, destroy } = useUsers();
 *
 *   // Bind: v-model="createForm.name", v-model="createForm.email", etc.
 *   // Role dropdown: v-model="createForm.role" with options: super_admin, admin, judge, viewer
 *   // Submit: @submit.prevent="store"
 *   // Delete: @click="destroy(userId)"
 *
 *   // Form state:
 *   //   createForm.errors.email - validation error
 *   //   createForm.processing - true while submitting
 */
import { useForm } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import { useConfirm } from './useConfirm';

export function useUsers() {
    const { confirm } = useConfirm();
    const createForm = useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        role: 'viewer',
        phone: '',
        is_active: true,
    });

    function store() {
        createForm.post(route('users.store'), {
            onSuccess: () => createForm.reset(),
        });
    }

    async function destroy(userId) {
        const ok = await confirm({
            title: 'Delete User',
            message: 'Are you sure you want to delete this user? This action cannot be undone.',
            confirmText: 'Delete',
            variant: 'danger',
        });
        if (!ok) return;
        router.delete(route('users.destroy', userId));
    }

    return { createForm, store, destroy };
}
