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
                display: ['"Baloo 2"', 'cursive'],
                hand: ['"Patrick Hand"', 'cursive'],
            },
            colors: {
                neo: {
                    yellow: '#FFE600',
                    orange: '#F7941D',
                    lime: '#B8FF00',
                    purple: '#8A2BE2',
                    blue: '#1E88E5',
                    cream: '#FFFDF5',
                    paper: '#F4F0E6',
                    sky: '#9ED8FF',
                    sand: '#FFD27A',
                    blush: '#FF8A80',
                },
            },
        },
    },

    plugins: [forms],
};
