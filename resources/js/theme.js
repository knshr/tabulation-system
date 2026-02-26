/**
 * Theme Configuration
 * -------------------
 * Designer: Edit this file to customize the app's look and feel.
 * These values are used by Tailwind CSS via tailwind.config.js.
 *
 * After editing, run `npm run build` (or `npm run dev`) to apply changes.
 */
export default {
    colors: {
        primary: '#3B82F6',
        'primary-hover': '#2563EB',
        'primary-light': '#DBEAFE',
        secondary: '#6366F1',
        'secondary-hover': '#4F46E5',
        'secondary-light': '#E0E7FF',
        accent: '#F59E0B',
        'accent-hover': '#D97706',
        success: '#10B981',
        'success-light': '#D1FAE5',
        danger: '#EF4444',
        'danger-light': '#FEE2E2',
        warning: '#F59E0B',
        'warning-light': '#FEF3C7',
        info: '#3B82F6',
        'info-light': '#DBEAFE',
        background: '#F9FAFB',
        surface: '#FFFFFF',
        'surface-alt': '#F3F4F6',
        border: '#E5E7EB',
        text: '#111827',
        'text-secondary': '#4B5563',
        'text-muted': '#6B7280',
        'text-inverse': '#FFFFFF',
    },
    fonts: {
        sans: 'Inter, system-ui, -apple-system, sans-serif',
        heading: 'Inter, system-ui, -apple-system, sans-serif',
        mono: 'ui-monospace, SFMono-Regular, monospace',
    },
    borderRadius: {
        sm: '0.25rem',
        DEFAULT: '0.375rem',
        md: '0.5rem',
        lg: '0.75rem',
        xl: '1rem',
    },
};
