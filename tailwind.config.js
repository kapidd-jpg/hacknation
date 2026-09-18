/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', 'system-ui', 'sans-serif'],
                display: ['Manrope', 'Inter', 'sans-serif'],
                grotesk: ['Manrope', 'Inter', 'sans-serif'],
                code: ['Manrope', 'ui-monospace', 'monospace'],
            },
            colors: {
                /* Lembar & latar — kertas hangat khas StudyServer */
                paper: {
                    DEFAULT: '#F6F7F3',
                    100: '#EDEEEA',
                },
                whitewarm: '#FFFFFF',
                /* Tinta utama — navy ledger */
                ink: {
                    DEFAULT: '#101A2E',
                    soft: '#45474D',
                    muted: '#76777D',
                },
                navy: {
                    DEFAULT: '#101A2E',
                    800: '#17233D',
                    900: '#0B1322',
                    100: '#E2E8F0',
                    50: '#F0F3FA',
                },
                /* Cetakan bata → dipetakan ke sistem navy/gold */
                brick: {
                    800: '#0B1322',
                    DEFAULT: '#101A2E',
                    600: '#17233D',
                    100: '#E2E8F0',
                    50: '#F0F3FA',
                },
                /* Aksen emas ledger */
                gold: {
                    DEFAULT: '#E3A23B',
                    600: '#C8891F',
                    100: '#F9E6C9',
                    50: '#FDF3E3',
                },
                /* Teal standar mutu */
                teal: {
                    DEFAULT: '#1B5E56',
                    light: '#A7CAB6',
                    100: '#C9E1DD',
                    50: '#E4F0EE',
                },
                sukses: '#1B5E56',
                danger: '#BA1A1A',
                /* Legasi runtime (JS quiz landing) */
                brand: {
                    green: '#1B5E56',
                    greenlight: '#A7CAB6',
                    greentext: '#25583F',
                },
            },
            borderRadius: {
                DEFAULT: '0.125rem',
                lg: '0.375rem',
                xl: '0.625rem',
                '2xl': '0.875rem',
                '3xl': '1rem',
            },
            boxShadow: {
                'ledger': '2px 2px 0 0 #101A2E',
                'ledger-sm': '1px 1px 0 0 #101A2E',
                'ledger-gold': '2px 2px 0 0 #E3A23B',
                'lift': '0px 28px 60px -34px rgba(16,26,46,0.45)',
                'line': '0px 1px 0px 0px rgba(16,26,46,0.08)',
            },
            maxWidth: {
                shell: '1280px',
            },
            fontSize: {
                'code-figure': ['13px', { lineHeight: '16px', letterSpacing: '0.02em', fontWeight: '500' }],
                'label-sm': ['11px', { lineHeight: '14px', letterSpacing: '0.08em', fontWeight: '700' }],
                'label-md': ['13px', { lineHeight: '16px', fontWeight: '600' }],
            },
        },
    },
    plugins: [],
};