/**
 * useFlash - Auto-shows Inertia flash messages as toast notifications
 *
 * Wire this into AppLayout so flash messages from the backend
 * automatically appear as toasts on every page.
 *
 * Usage (for frontend developer):
 *   import { useFlash } from '@/Composables/useFlash';
 *   useFlash(); // Call once in AppLayout
 *
 * Backend flash messages are set via:
 *   return redirect()->back()->with('success', 'Event created!');
 *   return redirect()->back()->with('error', 'Something went wrong.');
 */
import { watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { useToast } from './useToast';

export function useFlash() {
    const page = usePage();
    const { success, error } = useToast();

    watch(
        () => page.props.flash,
        (flash) => {
            if (flash?.success) success(flash.success);
            if (flash?.error) error(flash.error);
        },
        { immediate: true }
    );
}
