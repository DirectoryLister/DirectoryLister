import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite'

export default defineConfig(() => {
    return {
        base: './',
        build: {
            outDir: '.',
            emptyOutDir: false,
            assetsDir: 'app/assets',
            manifest: 'app/assets/manifest.json',
            rollupOptions: {
                input: [
                    'app/resources/css/app.css',
                    'app/resources/js/app.js',
                ]
            }
        },
        server: {
            host: true,
            port: 5173,
            strictPort: true,
            allowedHosts: true,
            cors: true,
        },
        plugins: [
            tailwindcss(),
        ],
        cacheDir: '.cache/vite',
    }
});
