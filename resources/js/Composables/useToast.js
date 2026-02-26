/**
 * useToast - Wrapper around vue-sonner for consistent toast notifications
 *
 * Usage (for frontend developer):
 *   import { useToast } from '@/Composables/useToast';
 *   const { success, error, info, warning } = useToast();
 *   success('Event created successfully');
 *   error('Failed to save score');
 */
import { toast } from 'vue-sonner';

export function useToast() {
    return {
        success: (message) => toast.success(message),
        error: (message) => toast.error(message),
        info: (message) => toast.info(message),
        warning: (message) => toast.warning(message),
    };
}
