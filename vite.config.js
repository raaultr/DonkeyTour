import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [react()],
    server: {
        // Esto permite que el servidor de Vite sea accesible desde tu PHP
        origin: 'http://localhost:5173',
        cors: true,
    },
    build: {
        // Genera un manifiesto para que PHP sepa qué archivos cargar en producción
        manifest: true,
        outDir: 'dist',
        rollupOptions: {
            // El punto de entrada de tu App de React
            input: './resources/js/main.jsx',
        },
    },
});
