/**
 * useConfirm - Promise-based confirm/alert modal composable
 *
 * Replaces native confirm() and alert() with a customizable daisyUI modal.
 * Uses module-level reactive state so it works anywhere (composables, pages, layouts).
 *
 * Usage (for frontend developer):
 *   import { useConfirm } from '@/Composables/useConfirm';
 *   const { confirm, alert } = useConfirm();
 *
 *   // Basic confirm (returns true/false):
 *   const ok = await confirm({ message: 'Delete this item?' });
 *   if (ok) { // proceed }
 *
 *   // Customized confirm:
 *   const ok = await confirm({
 *       title: 'Delete Event',
 *       message: 'This will permanently remove the event and all its data.',
 *       confirmText: 'Delete',
 *       cancelText: 'Keep it',
 *       variant: 'danger',  // 'danger' | 'warning' | 'info' | 'default'
 *   });
 *
 *   // Alert (single OK button, always resolves true):
 *   await alert({ title: 'Success', message: 'Event created!' });
 *
 *   // Alert with variant:
 *   await alert({ message: 'Something went wrong.', variant: 'danger' });
 */
import { reactive } from 'vue';

const VARIANT_CLASSES = {
    default: 'btn btn-primary',
    danger: 'btn btn-error',
    warning: 'btn btn-warning',
    info: 'btn btn-info',
};

const state = reactive({
    open: false,
    title: '',
    message: '',
    confirmText: 'Confirm',
    cancelText: 'Cancel',
    variant: 'default',
    mode: 'confirm',
    resolve: null,
});

function open(options, mode) {
    return new Promise((resolve) => {
        Object.assign(state, {
            open: true,
            title: options.title || (mode === 'alert' ? 'Notice' : 'Are you sure?'),
            message: options.message || '',
            confirmText: options.confirmText || (mode === 'alert' ? 'OK' : 'Confirm'),
            cancelText: options.cancelText || 'Cancel',
            variant: options.variant || 'default',
            mode,
            resolve,
        });
    });
}

function handleConfirm() {
    state.open = false;
    state.resolve?.(true);
}

function handleCancel() {
    state.open = false;
    state.resolve?.(false);
}

export function useConfirm() {
    function confirm(options = {}) {
        return open(options, 'confirm');
    }

    function alert(options = {}) {
        return open(options, 'alert');
    }

    return {
        state,
        confirm,
        alert,
        handleConfirm,
        handleCancel,
        VARIANT_CLASSES,
    };
}
