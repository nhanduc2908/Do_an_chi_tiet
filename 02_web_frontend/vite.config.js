import { defineConfig, loadEnv } from 'vite';
import laravel from 'vite-plugin-laravel';
import vue from '@vitejs/plugin-vue';
import { resolve } from 'path';

export default defineConfig(({ command, mode }) => {
    const env = loadEnv(mode, process.cwd(), '');
    
    return {
        plugins: [
            laravel({
                input: [
                    'resources/css/app.css',
                    'resources/js/app.js',
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
                '@': resolve(__dirname, 'resources/js'),
                '@css': resolve(__dirname, 'resources/css'),
                '@views': resolve(__dirname, 'resources/views'),
                '@components': resolve(__dirname, 'resources/js/components'),
                '@utils': resolve(__dirname, 'resources/js/utils'),
                '@store': resolve(__dirname, 'resources/js/store'),
                '@api': resolve(__dirname, 'resources/js/api'),
            },
            extensions: ['.js', '.vue', '.json', '.jsx', '.ts', '.tsx'],
        },
        
        server: {
            host: '0.0.0.0',
            port: 5173,
            strictPort: false,
            hmr: {
                host: 'localhost',
            },
            watch: {
                usePolling: true,
                interval: 1000,
            },
            proxy: {
                '/api': {
                    target: env.VITE_API_URL || 'http://localhost:8000',
                    changeOrigin: true,
                    rewrite: (path) => path.replace(/^\/api/, ''),
                },
                '/storage': {
                    target: env.VITE_API_URL || 'http://localhost:8000',
                    changeOrigin: true,
                },
            },
        },
        
        build: {
            outDir: 'public/build',
            assetsDir: 'assets',
            manifest: true,
            rollupOptions: {
                input: {
                    app: 'resources/js/app.js',
                    css: 'resources/css/app.css',
                },
                output: {
                    chunkFileNames: 'assets/js/[name]-[hash].js',
                    entryFileNames: 'assets/js/[name]-[hash].js',
                    assetFileNames: 'assets/[ext]/[name]-[hash].[ext]',
                    manualChunks: {
                        vendor: ['alpinejs', 'axios', 'lodash', 'moment'],
                        ui: ['chart.js', 'swiper', 'tippy.js'],
                    },
                },
            },
            minify: mode === 'production' ? 'esbuild' : false,
            sourcemap: mode !== 'production',
            target: 'es2020',
            cssCodeSplit: true,
            assetsInlineLimit: 4096,
        },
        
        css: {
            preprocessorOptions: {
                scss: {
                    additionalData: `@import "@/styles/variables.scss";`,
                },
            },
            devSourcemap: true,
            modules: {
                localsConvention: 'camelCase',
            },
        },
        
        optimizeDeps: {
            include: [
                'alpinejs',
                'axios',
                'lodash',
                'moment',
                'chart.js',
                'swiper',
                'tippy.js',
                'dompurify',
                'js-cookie',
                'qs',
                'uuid',
            ],
            exclude: ['@vite/client', '@vite/env'],
        },
        
        esbuild: {
            drop: mode === 'production' ? ['console', 'debugger'] : [],
            legalComments: 'none',
        },
        
        define: {
            __APP_ENV__: JSON.stringify(mode),
            __APP_VERSION__: JSON.stringify(process.env.npm_package_version),
            __BUILD_TIME__: JSON.stringify(new Date().toISOString()),
        },
    };
});