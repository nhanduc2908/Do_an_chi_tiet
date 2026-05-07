import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';
import aspectRatio from '@tailwindcss/aspect-ratio';
import containerQueries from '@tailwindcss/container-queries';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
        './resources/js/**/*.vue',
        './src/**/*.{js,jsx,ts,tsx,vue}',
        './index.html',
    ],
    
    safelist: [
        'bg-red-500',
        'bg-green-500',
        'bg-blue-500',
        'bg-yellow-500',
        'bg-purple-500',
        'text-red-500',
        'text-green-500',
        'text-blue-500',
        'text-yellow-500',
        'text-purple-500',
        'border-red-500',
        'border-green-500',
        'border-blue-500',
        {
            pattern: /bg-(red|green|blue|yellow|purple)-(100|200|300|400|500|600|700|800|900)/,
            variants: ['hover', 'focus', 'active'],
        },
        {
            pattern: /text-(red|green|blue|yellow|purple)-(100|200|300|400|500|600|700|800|900)/,
            variants: ['hover', 'focus'],
        },
    ],
    
    theme: {
        screens: {
            'xs': '375px',
            'sm': '480px',
            'md': '640px',
            'lg': '768px',
            'xl': '1024px',
            '2xl': '1280px',
            '3xl': '1440px',
            '4xl': '1920px',
            'mobile': '320px',
            'tablet': '768px',
            'desktop': '1024px',
            'large': '1440px',
        },
        
        colors: {
            transparent: 'transparent',
            current: 'currentColor',
            
            primary: {
                50: '#eff6ff',
                100: '#dbeafe',
                200: '#bfdbfe',
                300: '#93c5fd',
                400: '#60a5fa',
                500: '#3b82f6',
                600: '#2563eb',
                700: '#1d4ed8',
                800: '#1e40af',
                900: '#1e3a8a',
                950: '#172554',
                DEFAULT: '#3b82f6',
            },
            
            secondary: {
                50: '#f5f3ff',
                100: '#ede9fe',
                200: '#ddd6fe',
                300: '#c4b5fd',
                400: '#a78bfa',
                500: '#8b5cf6',
                600: '#7c3aed',
                700: '#6d28d9',
                800: '#5b21b6',
                900: '#4c1d95',
                950: '#2e1065',
                DEFAULT: '#8b5cf6',
            },
            
            success: {
                50: '#ecfdf5',
                100: '#d1fae5',
                200: '#a7f3d0',
                300: '#6ee7b7',
                400: '#34d399',
                500: '#10b981',
                600: '#059669',
                700: '#047857',
                800: '#065f46',
                900: '#064e3b',
                DEFAULT: '#10b981',
            },
            
            warning: {
                50: '#fffbeb',
                100: '#fef3c7',
                200: '#fde68a',
                300: '#fcd34d',
                400: '#fbbf24',
                500: '#f59e0b',
                600: '#d97706',
                700: '#b45309',
                800: '#92400e',
                900: '#78350f',
                DEFAULT: '#f59e0b',
            },
            
            danger: {
                50: '#fef2f2',
                100: '#fee2e2',
                200: '#fecaca',
                300: '#fca5a5',
                400: '#f87171',
                500: '#ef4444',
                600: '#dc2626',
                700: '#b91c1c',
                800: '#991b1b',
                900: '#7f1d1d',
                DEFAULT: '#ef4444',
            },
            
            info: {
                50: '#ecfeff',
                100: '#cffafe',
                200: '#a5f3fc',
                300: '#67e8f9',
                400: '#22d3ee',
                500: '#06b6d4',
                600: '#0891b2',
                700: '#0e7490',
                800: '#155e75',
                900: '#164e63',
                DEFAULT: '#06b6d4',
            },
            
            gray: {
                50: '#f9fafb',
                100: '#f3f4f6',
                200: '#e5e7eb',
                300: '#d1d5db',
                400: '#9ca3af',
                500: '#6b7280',
                600: '#4b5563',
                700: '#374151',
                800: '#1f2937',
                900: '#111827',
                950: '#030712',
                DEFAULT: '#6b7280',
            },
            
            white: '#ffffff',
            black: '#000000',
        },
        
        fontFamily: {
            sans: [
                'Inter',
                'system-ui',
                '-apple-system',
                'BlinkMacSystemFont',
                'Segoe UI',
                'Roboto',
                'Helvetica Neue',
                'Arial',
                'sans-serif',
            ],
            serif: ['Georgia', 'Cambria', 'Times New Roman', 'serif'],
            mono: [
                'SF Mono',
                'Monaco',
                'Inconsolata',
                'Fira Code',
                'Courier New',
                'monospace',
            ],
        },
        
        fontSize: {
            xs: ['0.75rem', { lineHeight: '1rem' }],
            sm: ['0.875rem', { lineHeight: '1.25rem' }],
            base: ['1rem', { lineHeight: '1.5rem' }],
            lg: ['1.125rem', { lineHeight: '1.75rem' }],
            xl: ['1.25rem', { lineHeight: '1.75rem' }],
            '2xl': ['1.5rem', { lineHeight: '2rem' }],
            '3xl': ['1.875rem', { lineHeight: '2.25rem' }],
            '4xl': ['2.25rem', { lineHeight: '2.5rem' }],
            '5xl': ['3rem', { lineHeight: '1' }],
            '6xl': ['3.75rem', { lineHeight: '1' }],
            '7xl': ['4.5rem', { lineHeight: '1' }],
            '8xl': ['6rem', { lineHeight: '1' }],
            '9xl': ['8rem', { lineHeight: '1' }],
        },
        
        spacing: {
            px: '1px',
            0: '0px',
            0.5: '0.125rem',
            1: '0.25rem',
            1.5: '0.375rem',
            2: '0.5rem',
            2.5: '0.625rem',
            3: '0.75rem',
            3.5: '0.875rem',
            4: '1rem',
            5: '1.25rem',
            6: '1.5rem',
            7: '1.75rem',
            8: '2rem',
            9: '2.25rem',
            10: '2.5rem',
            11: '2.75rem',
            12: '3rem',
            14: '3.5rem',
            16: '4rem',
            20: '5rem',
            24: '6rem',
            28: '7rem',
            32: '8rem',
            36: '9rem',
            40: '10rem',
            44: '11rem',
            48: '12rem',
            52: '13rem',
            56: '14rem',
            60: '15rem',
            64: '16rem',
            72: '18rem',
            80: '20rem',
            96: '24rem',
        },
        
        borderRadius: {
            none: '0px',
            sm: '0.125rem',
            DEFAULT: '0.25rem',
            md: '0.375rem',
            lg: '0.5rem',
            xl: '0.75rem',
            '2xl': '1rem',
            '3xl': '1.5rem',
            full: '9999px',
        },
        
        boxShadow: {
            sm: '0 1px 2px 0 rgb(0 0 0 / 0.05)',
            DEFAULT: '0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1)',
            md: '0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1)',
            lg: '0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1)',
            xl: '0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1)',
            '2xl': '0 25px 50px -12px rgb(0 0 0 / 0.25)',
            inner: 'inset 0 2px 4px 0 rgb(0 0 0 / 0.05)',
            none: 'none',
        },
        
        animation: {
            'fade-in': 'fadeIn 0.3s ease-in-out',
            'fade-out': 'fadeOut 0.3s ease-in-out',
            'slide-in': 'slideIn 0.3s ease-in-out',
            'slide-out': 'slideOut 0.3s ease-in-out',
            'slide-up': 'slideUp 0.3s ease-in-out',
            'slide-down': 'slideDown 0.3s ease-in-out',
            'spin-slow': 'spin 3s linear infinite',
            'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
            'bounce-slow': 'bounce 2s infinite',
            'shake': 'shake 0.5s ease-in-out',
            'glow': 'glow 2s ease-in-out infinite',
            'ping-slow': 'ping 2s cubic-bezier(0, 0, 0.2, 1) infinite',
        },
        
        extend: {
            backdropBlur: {
                xs: '2px',
                sm: '4px',
                md: '8px',
                lg: '12px',
                xl: '16px',
                '2xl': '24px',
            },
            transitionDuration: {
                '2000': '2000ms',
                '3000': '3000ms',
            },
            zIndex: {
                '-1': '-1',
                0: '0',
                10: '10',
                20: '20',
                30: '30',
                40: '40',
                50: '50',
                auto: 'auto',
                dropdown: '1000',
                sticky: '1020',
                fixed: '1030',
                modalBackdrop: '1040',
                modal: '1050',
                popover: '1060',
                tooltip: '1070',
                toast: '1080',
            },
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                fadeOut: {
                    '0%': { opacity: '1' },
                    '100%': { opacity: '0' },
                },
                slideIn: {
                    '0%': { transform: 'translateX(-100%)' },
                    '100%': { transform: 'translateX(0)' },
                },
                slideOut: {
                    '0%': { transform: 'translateX(0)' },
                    '100%': { transform: 'translateX(100%)' },
                },
                slideUp: {
                    '0%': { transform: 'translateY(100%)' },
                    '100%': { transform: 'translateY(0)' },
                },
                slideDown: {
                    '0%': { transform: 'translateY(-100%)' },
                    '100%': { transform: 'translateY(0)' },
                },
                shake: {
                    '0%, 100%': { transform: 'translateX(0)' },
                    '25%': { transform: 'translateX(-5px)' },
                    '75%': { transform: 'translateX(5px)' },
                },
                glow: {
                    '0%, 100%': { boxShadow: '0 0 5px rgba(59, 130, 246, 0.5)' },
                    '50%': { boxShadow: '0 0 20px rgba(59, 130, 246, 0.8)' },
                },
            },
        },
    },
    
    plugins: [
        forms,
        typography,
        aspectRatio,
        containerQueries,
        
        function({ addUtilities, theme, addComponents, addBase }) {
            const newUtilities = {
                '.text-responsive': {
                    fontSize: theme('fontSize.base'),
                    '@screen sm': { fontSize: theme('fontSize.lg') },
                    '@screen md': { fontSize: theme('fontSize.xl') },
                    '@screen lg': { fontSize: theme('fontSize.2xl') },
                },
                '.hide-scrollbar': {
                    scrollbarWidth: 'none',
                    msOverflowStyle: 'none',
                    '&::-webkit-scrollbar': { display: 'none' },
                },
                '.custom-scrollbar': {
                    scrollbarWidth: 'thin',
                    '&::-webkit-scrollbar': { width: '8px', height: '8px' },
                    '&::-webkit-scrollbar-track': { background: theme('colors.gray.100'), borderRadius: '8px' },
                    '&::-webkit-scrollbar-thumb': { background: theme('colors.gray.400'), borderRadius: '8px', '&:hover': { background: theme('colors.gray.500') } },
                },
                '.safe-area-top': {
                    paddingTop: 'env(safe-area-inset-top)',
                },
                '.safe-area-bottom': {
                    paddingBottom: 'env(safe-area-inset-bottom)',
                },
            };
            addUtilities(newUtilities, ['responsive', 'hover']);
            
            addComponents({
                '.btn': {
                    display: 'inline-flex',
                    alignItems: 'center',
                    justifyContent: 'center',
                    gap: '0.5rem',
                    padding: '0.5rem 1rem',
                    fontWeight: '500',
                    borderRadius: theme('borderRadius.lg'),
                    transition: 'all 0.2s ease',
                    cursor: 'pointer',
                    '&:disabled': {
                        opacity: '0.5',
                        cursor: 'not-allowed',
                    },
                },
                '.btn-primary': {
                    backgroundColor: theme('colors.primary.600'),
                    color: 'white',
                    '&:hover': { backgroundColor: theme('colors.primary.700') },
                },
                '.card': {
                    backgroundColor: 'white',
                    borderRadius: theme('borderRadius.xl'),
                    boxShadow: theme('boxShadow.md'),
                    overflow: 'hidden',
                },
            });
            
            addBase({
                'html': {
                    scrollBehavior: 'smooth',
                },
                'body': {
                    backgroundColor: theme('colors.gray.50'),
                    color: theme('colors.gray.900'),
                },
            });
        },
    ],
};