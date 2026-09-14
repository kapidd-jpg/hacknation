/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', 'sans-serif'],
            },
            colors: {
                navy: {
                    DEFAULT: '#1E3ABA',
                    950: '#0F172A',
                    900: '#16213B',
                    800: '#1E3ABA',
                    700: '#2742C9',
                    600: '#3A54D4',
                    400: '#7083E8',
                    300: '#A8B4F2',
                    100: '#DFE4FB',
                    50: '#EEF1FD',
                },
                brand: {
                    green: '#0D9488',
                    greenlight: '#5EEAD4',
                    greentext: '#0F766E',
                    teal: '#0D9488',
                },
                amber: {
                    DEFAULT: '#F59E0B',
                    50: '#FFFBEB',
                    100: '#FEF3C7',
                    200: '#FDE68A',
                    300: '#FCD34D',
                    400: '#FBBF24',
                    500: '#F59E0B',
                    600: '#D97706',
                    700: '#B45309',
                    800: '#92400E',
                    900: '#78350F',
                },
                ink: {
                    DEFAULT: '#0F172A',
                    soft: '#45464f',
                    muted: '#767680',
                },
            },
            backgroundImage: {
                'gradient-brand': 'linear-gradient(135deg, #1E3ABA 0%, #0D9488 140%)',
                'gradient-navy': 'linear-gradient(180deg, #0F172A 0%, #16213B 50%, #1E3ABA 150%)',
                'gradient-cta': 'linear-gradient(135deg, #0F172A 0%, #1E3ABA 140%)',
                'gradient-teal': 'linear-gradient(135deg, #0D9488 0%, #14B8A6 100%)',
            },
            boxShadow: {
                card: '0px 4px 20px -2px rgba(15,23,42,0.07)',
            },
        },
    },
    plugins: [],
};
