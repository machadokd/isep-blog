import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', 'Figtree', ...defaultTheme.fontFamily.sans],
                serif: ['"Source Serif 4"', ...defaultTheme.fontFamily.serif],
            },
            colors: {
                brand: {
                    50: '#eef3ff',
                    100: '#dfe8ff',
                    200: '#c7d6ff',
                    400: '#6f93ff',
                    600: '#2457f5',
                    700: '#1d47d1',
                    900: '#0f1d3d',
                },
            },
        },
    },

    plugins: [forms, typography],
};
