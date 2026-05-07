/**
 * Laravel Mix Configuration
 * Cấu hình build assets, minify, versioning
 * 
 * @type {import('laravel-mix')}
 */

const mix = require('laravel-mix');
const path = require('path');

// -------------------- Mix Configuration --------------------
mix.setPublicPath('public');
mix.setResourceRoot('/');
mix.disableSuccessNotifications();
mix.options({
    terser: {
        extractComments: false,
        terserOptions: {
            compress: {
                drop_console: mix.inProduction(),
                drop_debugger: mix.inProduction(),
            },
        },
    },
    cssNano: {
        preset: ['default', {
            discardComments: { removeAll: true },
            normalizeWhitespace: true,
        }],
    },
    postCss: [
        require('tailwindcss'),
        require('autoprefixer'),
        require('cssnano')({
            preset: 'default',
        }),
    ],
    purgeCss: mix.inProduction(),
    uglify: mix.inProduction(),
    manifest: true,
    processCssUrls: false,
});

// -------------------- Environment Variables --------------------
mix.env('NODE_ENV', mix.inProduction() ? 'production' : 'development');
mix.env('MIX_APP_URL', process.env.MIX_APP_URL || 'http://localhost:8000');
mix.env('MIX_APP_ENV', process.env.MIX_APP_ENV || 'local');

// -------------------- Versioning --------------------
if (mix.inProduction()) {
    mix.version();
} else {
    mix.sourceMaps();
    mix.browserSync({
        proxy: process.env.MIX_APP_URL || 'http://localhost:8000',
        files: [
            'resources/views/**/*.blade.php',
            'resources/js/**/*.js',
            'resources/css/**/*.css',
            'resources/sass/**/*.scss',
            'app/**/*.php',
        ],
        open: false,
        notify: false,
    });
}

// -------------------- Styles Compilation --------------------
// Main CSS
mix.css('resources/css/app.css', 'public/build/css')
   .options({
        postCss: [
            require('tailwindcss'),
            require('autoprefixer'),
        ],
   });

// SASS Compilation
mix.sass('resources/sass/responsive/_mixins.scss', 'public/build/css/responsive')
   .sass('resources/sass/responsive/_breakpoints.scss', 'public/build/css/responsive')
   .sass('resources/sass/responsive/_mobile.scss', 'public/build/css/responsive')
   .sass('resources/sass/responsive/_tablet.scss', 'public/build/css/responsive')
   .sass('resources/sass/responsive/_desktop.scss', 'public/build/css/responsive')
   .sass('resources/sass/responsive/_large.scss', 'public/build/css/responsive');

// Combine CSS files
mix.combine([
    'public/build/css/app.css',
    'public/build/css/responsive/_mixins.css',
    'public/build/css/responsive/_breakpoints.css',
    'public/build/css/responsive/_mobile.css',
], 'public/build/css/app.min.css');

// -------------------- JavaScript Compilation --------------------
// Main JS
mix.js('resources/js/app.js', 'public/build/js')
   .js('resources/js/modules/screenDetector.js', 'public/build/js/modules')
   .js('resources/js/modules/responsiveEngine.js', 'public/build/js/modules')
   .js('resources/js/modules/deviceConnector.js', 'public/build/js/modules')
   .js('resources/js/modules/websocketClient.js', 'public/build/js/modules')
   .js('resources/js/modules/screenMirror.js', 'public/build/js/modules');

// Vendor extraction
if (mix.inProduction()) {
    mix.extract(['alpinejs', 'jquery', 'chart.js', 'moment'], 'public/build/js/vendor.js');
}

// -------------------- Copy Assets --------------------
// Copy images
mix.copyDirectory('resources/images', 'public/build/images');

// Copy fonts
mix.copyDirectory('resources/fonts', 'public/build/fonts');

// Copy vendor files
mix.copy('node_modules/tailwindcss/dist/tailwind.min.css', 'public/vendor/css/tailwind.min.css');
mix.copy('node_modules/@fortawesome/fontawesome-free/css/all.min.css', 'public/vendor/css/fontawesome.min.css');
mix.copy('node_modules/jquery/dist/jquery.min.js', 'public/vendor/js/jquery.min.js');
mix.copy('node_modules/alpinejs/dist/cdn.min.js', 'public/vendor/js/alpine.min.js');
mix.copy('node_modules/chart.js/dist/chart.umd.js', 'public/vendor/js/chart.min.js');
mix.copy('node_modules/moment/min/moment.min.js', 'public/vendor/js/moment.min.js');

// Copy webfonts
mix.copyDirectory('node_modules/@fortawesome/fontawesome-free/webfonts', 'public/vendor/webfonts');

// -------------------- Additional Options --------------------
mix.webpackConfig({
    resolve: {
        extensions: ['.js', '.vue', '.json'],
        alias: {
            '@': path.resolve(__dirname, 'resources/js'),
            '@css': path.resolve(__dirname, 'resources/css'),
            '@sass': path.resolve(__dirname, 'resources/sass'),
            '@components': path.resolve(__dirname, 'resources/js/components'),
            '@utils': path.resolve(__dirname, 'resources/js/utils'),
            '@modules': path.resolve(__dirname, 'resources/js/modules'),
        },
        fallback: {
            crypto: false,
            path: false,
            fs: false,
        },
    },
    optimization: {
        splitChunks: {
            chunks: 'all',
            cacheGroups: {
                vendor: {
                    test: /[\\/]node_modules[\\/]/,
                    name: 'vendors',
                    chunks: 'all',
                },
                common: {
                    name: 'common',
                    minChunks: 2,
                    priority: -10,
                    reuseExistingChunk: true,
                },
            },
        },
        minimize: mix.inProduction(),
    },
    output: {
        chunkFilename: 'js/chunks/[name].[chunkhash].js',
        publicPath: '/',
    },
    stats: {
        children: false,
        modules: false,
    },
});

// -------------------- Bundle Analyzer --------------------
if (process.env.MIX_BUNDLE_ANALYZER === 'true') {
    const BundleAnalyzerPlugin = require('webpack-bundle-analyzer').BundleAnalyzerPlugin;
    mix.webpackConfig({
        plugins: [
            new BundleAnalyzerPlugin(),
        ],
    });
}

// -------------------- Service Worker --------------------
if (mix.inProduction()) {
    mix.copy('public/sw.js', 'public/build/sw.js');
}

// -------------------- Manifest --------------------
mix.generateManifest();

// -------------------- Clean Build --------------------
mix.before(() => {
    const fs = require('fs-extra');
    const buildPath = path.resolve(__dirname, 'public/build');
    
    if (fs.existsSync(buildPath)) {
        fs.emptyDirSync(buildPath);
    }
});

// -------------------- Complete Callback --------------------
mix.after(() => {
    console.log('✓ Build completed successfully!');
    console.log('✓ Assets are ready for deployment');
    
    if (mix.inProduction()) {
        console.log('✓ Production mode: Minified and optimized');
    } else {
        console.log('✓ Development mode: Source maps enabled');
    }
});

// -------------------- Export --------------------
module.exports = mix;