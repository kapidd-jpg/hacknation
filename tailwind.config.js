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
                    DEFAULT: '#111c4e',
                    950: '#00052c',
                    900: '#0b1c30',
                    800: '#111c4e',
                    600: '#515b90',
                    400: '#7b85bd',
                    300: '#b9c3ff',
                    100: '#dee1ff',
                    50: '#eff4ff',
                },
                brand: {
                    green: '#006d30',
                    greenlight: '#7efc9a',
                    greentext: '#007433',
                },
                ink: {
                    DEFAULT: '#0b1c30',
                    soft: '#45464f',
                    muted: '#767680',
                },
            },
            boxShadow: {
                card: '0px 4px 20px -2px rgba(15,27,76,0.06)',
            },
        },
    },
    plugins: [],
};