export default {
    plugins: {
        'postcss-import': {},
        'postcss-nesting': {},
        'tailwindcss/nesting': {},
        tailwindcss: {},
        autoprefixer: {},
        ...(process.env.NODE_ENV === 'production' ? {
            cssnano: {
                preset: ['default', {
                    discardComments: { removeAll: true },
                    normalizeWhitespace: true,
                }],
            },
        } : {}),
    },
};