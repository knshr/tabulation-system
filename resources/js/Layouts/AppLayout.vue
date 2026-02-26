<script setup>
/**
 * AppLayout - Authenticated application shell
 *
 * Props available from Inertia shared data:
 * - auth.user (current user object with id, name, email, role)
 *
 * Composable: useFlash - auto-shows backend flash messages as toasts
 *   Flash messages set via redirect()->with('success', '...') or with('error', '...')
 *   are automatically displayed as toast notifications.
 *
 * Composable: useAuth
 *   - logout() - for user menu logout button
 *
 * Designer: Build sidebar navigation, top bar, user menu here.
 * Use daisyUI component classes (btn, card, drawer, navbar, menu, etc.).
 * See: https://daisyui.com/components/
 *
 * Suggested daisyUI components:
 *   - drawer (https://daisyui.com/components/drawer/) - sidebar layout
 *   - navbar (https://daisyui.com/components/navbar/) - top bar
 *   - menu (https://daisyui.com/components/menu/) - sidebar navigation
 *   - dropdown (https://daisyui.com/components/dropdown/) - user menu
 *   - avatar (https://daisyui.com/components/avatar/) - user avatar
 *   - badge (https://daisyui.com/components/badge/) - role badge
 */
import { usePage } from '@inertiajs/vue3';
import { useFlash } from '@/Composables/useFlash';
import { useAuth } from '@/Composables/useAuth';

const page = usePage();
const user = page.props.auth?.user;

useFlash();
const { logout } = useAuth();
</script>

<template>
    <div>
        <!-- Designer: Add sidebar, navigation, top bar here -->
        <main>
            <slot />
        </main>
    </div>
</template>
