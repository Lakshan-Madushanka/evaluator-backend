import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            // hotFile: 'vendor/lakm/laravel-comments-admin-panel/laravel-comments-admin-panel.hot',
            // buildDirectory: 'vendor/lakm/laravel-comments-admin-panel/build',
            input: ['resources/admin/app.css', 'resources/css/app.css'],
            // input: ['resources/css/app.css'],
            refresh: false,
        }),
    ],
});
