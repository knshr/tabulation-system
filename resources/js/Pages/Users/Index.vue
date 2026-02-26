<script setup>
/**
 * Users/Index - User management
 *
 * @prop {Array} users - All users
 *   Each: { id, name, email, role, phone, is_active, created_at }
 *
 * Composable: useUsers
 *   - destroy(userId) - delete user (with confirmation modal)
 *
 * Designer: Build user management table with role badges and delete action.
 * Use daisyUI component classes. See: https://daisyui.com/components/
 *
 * Suggested daisyUI components:
 *   - table (https://daisyui.com/components/table/) - user listing
 *   - badge (https://daisyui.com/components/badge/) - role & status badges
 *   - button (https://daisyui.com/components/button/) - actions
 *   - dropdown (https://daisyui.com/components/dropdown/) - action menu
 *   - card (https://daisyui.com/components/card/) - table wrapper
 */
import AppLayout from '@/Layouts/AppLayout.vue';
import { useUsers } from '@/Composables/useUsers';

defineOptions({ layout: AppLayout });

const props = defineProps({
    users: { type: Array, default: () => [] },
});

const { destroy } = useUsers();
</script>

<template>
    <div>
        <h1>Users</h1>
        <p>{{ users.length }} users. Designer: Build user management table with role badges.</p>
    </div>
</template>
