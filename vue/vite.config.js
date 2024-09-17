import { defineConfig } from "vite";
import vue from "@vitejs/plugin-vue";

// https://vitejs.dev/config/
export default defineConfig({
    plugins: [vue()],
    build: {
        outDir: "../public/vue",
        rollupOptions: {
            input: {
                main: "./src/main.js",
            },
        },
    },
});
