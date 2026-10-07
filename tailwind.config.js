import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
        './app/View/Components/**/*.php',
    ],

    theme: {
        extend: {
            colors: {
                // Design tokens - Architecture/Engineering palette
                stone: {
                    50: '#fafaf9',
                    100: '#f5f5f4',
                    200: '#e7e5e4',
                    300: '#d6d3d1',
                    400: '#a8a29e',
                    500: '#78716c',
                    600: '#57534e',
                    700: '#44403c',
                    800: '#292524',
                    900: '#1c1917',
                    950: '#0c0a09',
                },
                concrete: {
                    50: '#f8f8f8',
                    100: '#efefef',
                    200: '#dcdcdc',
                    300: '#c0c0c0',
                    400: '#a0a0a0',
                    500: '#888888',
                    600: '#6e6e6e',
                    700: '#595959',
                    800: '#4a4a4a',
                    900: '#3d3d3d',
                    950: '#212121',
                },
                accent: {
                    // Restrained architectural accent - warm terracotta/brick
                    50: '#fdf3f0',
                    100: '#fae3dc',
                    200: '#f5c9c0',
                    300: '#eda293',
                    400: '#e3745c',
                    500: '#d9573a',
                    600: '#c7432c',
                    700: '#a03224',
                    800: '#812c22',
                    900: '#692820',
                    950: '#381310',
                },
                // Semantic aliases
                primary: {
                    DEFAULT: 'var(--color-primary)',
                    foreground: 'var(--color-primary-foreground)',
                },
                secondary: {
                    DEFAULT: 'var(--color-secondary)',
                    foreground: 'var(--color-secondary-foreground)',
                },
                background: 'var(--color-background)',
                foreground: 'var(--color-foreground)',
                muted: {
                    DEFAULT: 'var(--color-muted)',
                    foreground: 'var(--color-muted-foreground)',
                },
                border: 'var(--color-border)',
                ring: 'var(--color-ring)',
            },

            fontFamily: {
                // Editorial display headings - serif for architectural feel
                display: ['Playfair Display', 'Georgia', 'Cambria', 'Times New Roman', 'serif'],
                // Highly readable body - sans-serif
                sans: ['Inter', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'sans-serif'],
                // Bengali support
                bengali: ['Noto Sans Bengali', 'Hind Siliguri', 'Inter', 'system-ui', 'sans-serif'],
                // Metadata/small text
                mono: ['JetBrains Mono', 'Fira Code', 'Monaco', 'Consolas', 'monospace'],
            },

            fontSize: {
                // Responsive type scale using clamp()
                'display-xl': ['clamp(3rem, 8vw, 6rem)', { lineHeight: '1.05', letterSpacing: '-0.03em', fontWeight: '400' }],
                'display-lg': ['clamp(2.5rem, 6vw, 4.5rem)', { lineHeight: '1.1', letterSpacing: '-0.02em', fontWeight: '400' }],
                'display-md': ['clamp(2rem, 4.5vw, 3.5rem)', { lineHeight: '1.15', letterSpacing: '-0.01em', fontWeight: '400' }],
                'display-sm': ['clamp(1.5rem, 3vw, 2.5rem)', { lineHeight: '1.2', letterSpacing: '0', fontWeight: '400' }],
                'heading-xl': ['clamp(1.75rem, 2.5vw, 2.25rem)', { lineHeight: '1.25', letterSpacing: '0', fontWeight: '500' }],
                'heading-lg': ['clamp(1.5rem, 2vw, 1.875rem)', { lineHeight: '1.3', letterSpacing: '0', fontWeight: '500' }],
                'heading-md': ['clamp(1.25rem, 1.5vw, 1.5rem)', { lineHeight: '1.35', letterSpacing: '0', fontWeight: '500' }],
                'heading-sm': ['clamp(1.125rem, 1.25vw, 1.25rem)', { lineHeight: '1.4', letterSpacing: '0', fontWeight: '500' }],
                'body-lg': ['clamp(1.125rem, 1.25vw, 1.25rem)', { lineHeight: '1.7', letterSpacing: '0', fontWeight: '400' }],
                'body': ['clamp(1rem, 1.1vw, 1.125rem)', { lineHeight: '1.7', letterSpacing: '0', fontWeight: '400' }],
                'body-sm': ['clamp(0.875rem, 1vw, 1rem)', { lineHeight: '1.6', letterSpacing: '0', fontWeight: '400' }],
                'caption': ['clamp(0.75rem, 0.9vw, 0.875rem)', { lineHeight: '1.5', letterSpacing: '0.01em', fontWeight: '400' }],
                'overline': ['clamp(0.75rem, 0.8vw, 0.875rem)', { lineHeight: '1.4', letterSpacing: '0.1em', fontWeight: '600', textTransform: 'uppercase' }],
            },

            spacing: {
                // Base spacing scale
                'space-px': '1px',
                'space-0': '0',
                'space-1': '0.25rem',   // 4px
                'space-2': '0.5rem',    // 8px
                'space-3': '0.75rem',   // 12px
                'space-4': '1rem',      // 16px
                'space-5': '1.25rem',   // 20px
                'space-6': '1.5rem',    // 24px
                'space-8': '2rem',      // 32px
                'space-10': '2.5rem',   // 40px
                'space-12': '3rem',     // 48px
                'space-16': '4rem',     // 64px
                'space-20': '5rem',     // 80px
                'space-24': '6rem',     // 96px
                'space-32': '8rem',     // 128px
            },

            maxWidth: {
                'prose': '65ch',
                'prose-lg': '75ch',
                'screen-sm': '640px',
                'screen-md': '768px',
                'screen-lg': '1024px',
                'screen-xl': '1280px',
                'screen-2xl': '1536px',
            },

            animation: {
                'fade-in': 'fadeIn 0.5s ease-out both',
                'fade-in-up': 'fadeInUp 0.6s ease-out both',
                'slide-up': 'slideUp 0.5s ease-out both',
                'slide-down': 'slideDown 0.3s ease-out both',
                'scale-in': 'scaleIn 0.2s ease-out both',
            },

            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                fadeInUp: {
                    '0%': { opacity: '0', transform: 'translateY(20px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                slideUp: {
                    '0%': { transform: 'translateY(100%)' },
                    '100%': { transform: 'translateY(0)' },
                },
                slideDown: {
                    '0%': { transform: 'translateY(-100%)' },
                    '100%': { transform: 'translateY(0)' },
                },
                scaleIn: {
                    '0%': { opacity: '0', transform: 'scale(0.95)' },
                    '100%': { opacity: '1', transform: 'scale(1)' },
                },
            },

            transitionDuration: {
                '0': '0ms',
                '75': '75ms',
                '150': '150ms',
                '200': '200ms',
                '300': '300ms',
                '500': '500ms',
                '700': '700ms',
                '1000': '1000ms',
            },

            transitionTimingFunction: {
                'ease-out-expo': 'cubic-bezier(0.19, 1, 0.22, 1)',
                'ease-in-expo': 'cubic-bezier(0.95, 0.05, 0.795, 0.035)',
                'ease-spring': 'cubic-bezier(0.34, 1.56, 0.64, 1)',
            },

            boxShadow: {
                'subtle': '0 1px 2px 0 rgb(0 0 0 / 0.03)',
                'soft': '0 2px 8px -2px rgb(0 0 0 / 0.06), 0 1px 2px -1px rgb(0 0 0 / 0.04)',
                'card': '0 4px 16px -4px rgb(0 0 0 / 0.08), 0 2px 4px -2px rgb(0 0 0 / 0.04)',
                'elevated': '0 12px 32px -8px rgb(0 0 0 / 0.1), 0 4px 8px -4px rgb(0 0 0 / 0.05)',
                'inner-subtle': 'inset 0 1px 1px 0 rgb(0 0 0 / 0.03)',
            },

            borderRadius: {
                'none': '0',
                'sm': '0.25rem',
                'DEFAULT': '0.375rem',
                'md': '0.5rem',
                'lg': '0.75rem',
                'xl': '1rem',
                '2xl': '1.5rem',
                'full': '9999px',
            },
        },
    },

    plugins: [forms, typography],
};