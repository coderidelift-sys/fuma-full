import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',

                'resources/js/console/users/script.js',
                'resources/js/console/users/edit_script.js',
                'resources/js/console/users/show_script.js',

                'resources/css/match_detail.css',
                'resources/js/match_detail_script.js',
                'resources/js/auth/script.js',
                'resources/js/main.js',
                'resources/js/app-logistics-dashboard.js',
            ],
            refresh: true,
        }),
    ],
});
