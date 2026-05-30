import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));

/**
 * ASSET_URL (ruta pública de assets). Si instalas en subdirectorio, define en .env p.ej.:
 *   APP_URL=https://dominio.com/miapp/public
 *   ASSET_URL=https://dominio.com/miapp/public
 * El pathname se usa como base de Vite (debe terminar en /).
 */
function viteBaseFromEnv(env) {
    const raw = env.ASSET_URL || env.APP_URL;
    if (!raw) {
        return '/';
    }
    try {
        const u = new URL(raw);
        let p = u.pathname;
        if (p === '' || p === '/') {
            return '/';
        }
        return p.endsWith('/') ? p : `${p}/`;
    } catch {
        return '/';
    }
}

export default defineConfig(({ mode }) => {
    const env = loadEnv(mode, __dirname, '');
    const base = viteBaseFromEnv(env);

    return {
        base,
        plugins: [
            laravel({
                input: ['resources/css/app.css', 'resources/js/app.js'],
                refresh: true,
            }),
            tailwindcss(),
        ],
        server: {
            watch: {
                ignored: ['**/storage/framework/views/**'],
            },
        },
    };
});
