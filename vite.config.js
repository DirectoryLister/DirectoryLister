import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite'

export default defineConfig(({ command }) => {
    return {
        build: {
            outDir: 'app',
            emptyOutDir: false,
            manifest: 'manifest.json',
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
