<script setup>
/**
 * Users/Create - Create new user
 *
 * Props: none
 *
 * Composable: useUsers
 *   - createForm.name, createForm.email, createForm.password,
 *     createForm.password_confirmation, createForm.role, createForm.phone,
 *     createForm.is_active (v-model bindings)
 *   - createForm.errors.email, etc. (validation errors)
 *   - createForm.processing (boolean - true while submitting)
 *   - store() - submit handler
 *
 * Roles: super_admin, admin, judge, viewer
 * Designer: Build user creation form with role dropdown.
 * Use daisyUI component classes. See: https://daisyui.com/components/
 *
 * Suggested daisyUI components:
 *   - input (https://daisyui.com/components/input/) - text fields
 *   - select (https://daisyui.com/components/select/) - role dropdown
 *   - toggle (https://daisyui.com/components/toggle/) - is_active toggle
 *   - button (https://daisyui.com/components/button/) - submit
 *   - card (https://daisyui.com/components/card/) - form wrapper
 *   - loading (https://daisyui.com/components/loading/) - processing state
 */
import AppLayout from '@/Layouts/AppLayout.vue';
import { useUsers } from '@/Composables/useUsers';

defineOptions({ layout: AppLayout });

const { createForm, store } = useUsers();
</script>

<template>
    <div>
        <h1>Create User</h1>
        <p>Designer: Build user creation form with role dropdown.</p>
    </div>
</template>
