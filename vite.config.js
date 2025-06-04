import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app.js', // Ensure this points to your main.js
                'resources/sass/app.scss',
            ],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            vue: 'vue/dist/vue.esm-bundler.js',
        },
    },
    server: {
        host: '127.0.0.1', // Ensure Vite runs on localhost
        port: 5173, // Default Vite port, adjust if needed
        hmr: {
            host: '127.0.0.1',
            port: 5173,
        },
    },
});
