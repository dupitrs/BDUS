import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
        colors: {
            accentaRed: '#C71616',
            accentaOrange: '#F66B1A',
            primaryGreen: '#08A398',
            secondaryBlue: '#B3EFEB',
            secondaryGreen: '#ADE9A1',
        },
        fontFamily: {
            heading: ['"Josefin Sans"', 'sans-serif'],
            comic: ['"ABYS"', 'cursive'],
        }
        }

    },

    plugins: [forms],
};
