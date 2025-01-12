import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        // Allow external connections for tunneling services like LocalTunnel
        host: '0.0.0.0',  // Listen on all network interfaces
        port: 3000,  // Ensure Vite is running on the expected port
        https: false,  // LocalTunnel will handle the SSL termination
        hmr: {
            protocol: 'wss',  // Use secure WebSocket (since LocalTunnel uses HTTPS)
            host: 'early-bats-love.loca.ltt',  // Replace with your LocalTunnel subdomain
        },
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
