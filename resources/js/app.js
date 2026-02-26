import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { Toaster } from 'vue-sonner';
import ConfirmModal from './Components/ConfirmModal.vue';
import '../css/app.css';

createInertiaApp({
    title: (title) => title ? `${title} - Tabulation System` : 'Tabulation System',
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({
            render: () => [
                h(App, props),
                h(Toaster, { richColors: true, position: 'top-right' }),
                h(ConfirmModal),
            ],
        })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#3B82F6',
    },
});
