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
                sans: ['DM Sans', ...defaultTheme.fontFamily.sans],
                display: ['Playfair Display', 'Georgia', 'serif'],
            },
            colors: {
                event: {
                    ink: '#241321',
                    plum: '#4a1d37',
                    wine: '#5d2350',
                    gold: '#b98a2e',
                    champagne: '#c9a35f',
                    cream: '#fffaf7',
                    line: '#f0dce4',
                    pink: '#ed3d81',
                    orange: '#ff6b35',
                },
            },
        },
    },

    plugins: [forms],
};
