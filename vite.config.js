import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
      resolve: {

        alias: {
            '$': 'jQuery'
        },

    },
    server: {
        host: true,
        hmr: {
            host: '192.168.100.115'
        }
    }
});


