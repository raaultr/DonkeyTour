import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [react()],
    publicDir: false,
    server: {
        origin: 'http://localhost:5173',
        cors: true,
    },
    build: {
        manifest: true,
        outDir: 'public/build',
        emptyOutDir: true,
        rollupOptions: {
            input: './assets/react/main.jsx',
        },
    },
});
