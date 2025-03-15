import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite'

export default defineConfig(({ command }) => {
    return {
        build: {
            emptyOutDir: false,
            manifest: 'manifest.json',
            outDir: 'app',
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
            cors: {
                origin: '*'
            },
            hmr: command === 'serve' ? {
                host: 'directory-lister.local'
            } : {},
        },
        plugins: [
            tailwindcss(),
        ],
    }
});
