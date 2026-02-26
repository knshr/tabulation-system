<script setup>
/**
 * Auth/Login - Login page
 *
 * Props: none (form submits to POST /login)
 *
 * Composable: useAuth
 *   - loginForm.email, loginForm.password, loginForm.remember (v-model bindings)
 *   - loginForm.errors.email, loginForm.errors.password (validation errors)
 *   - loginForm.processing (boolean - true while submitting)
 *   - login() - submit handler
 *
 * Designer: Build login form (email input, password input, remember checkbox, submit button).
 * Use daisyUI component classes.
 * See: https://daisyui.com/components/
 *
 * Suggested daisyUI components:
 *   - input (https://daisyui.com/components/input/) - email & password fields
 *   - checkbox (https://daisyui.com/components/checkbox/) - remember me
 *   - button (https://daisyui.com/components/button/) - submit button
 *   - loading (https://daisyui.com/components/loading/) - spinner during processing
 */
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { useAuth } from '@/Composables/useAuth';

defineOptions({ layout: AuthLayout });

const { loginForm, login } = useAuth();
</script>

<template>
    <div>
        <h1>Login</h1>
        <p>Designer: Build login form here (email, password, remember me, submit button)</p>
    </div>
</template>
