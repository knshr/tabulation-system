/**
 * useAuth - Composable for authentication actions
 *
 * Usage (for frontend developer):
 *   import { useAuth } from '@/Composables/useAuth';
 *   const { loginForm, login, logout } = useAuth();
 *
 *   // Bind loginForm fields to inputs:
 *   //   v-model="loginForm.email"
 *   //   v-model="loginForm.password"
 *   //   v-model="loginForm.remember"
 *
 *   // loginForm.errors.email - validation error for email
 *   // loginForm.processing - true while submitting
 *
 *   // Submit: @submit.prevent="login"
 *   // Logout: @click="logout"
 */
import { useForm } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';

export function useAuth() {
    const loginForm = useForm({
        email: '',
        password: '',
        remember: false,
    });

    function login() {
        loginForm.post(route('login'), {
            onFinish: () => loginForm.reset('password'),
        });
    }

    function logout() {
        router.post(route('logout'));
    }

    return { loginForm, login, logout };
}
