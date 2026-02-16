import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

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
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'pertamina': {
                    'red': '#CC0000',
                    'red-dark': '#990000',
                    'red-light': '#FF3333',
                    'blue': '#003366',
                    'blue-dark': '#002244',
                    'blue-light': '#004488',
                },
            },
        },
    },

    plugins: [forms],
};
