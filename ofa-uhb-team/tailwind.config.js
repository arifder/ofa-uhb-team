import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                navy: {
                    50:  '#f0f4ff',
                    100: '#e0eaff',
                    200: '#c7d7fc',
                    300: '#a5bcf8',
                    400: '#8097f3',
                    500: '#5c72eb',
                    600: '#3f51e0',
                    700: '#3240c7',
                    800: '#2a35a0',
                    900: '#1e3a5f',
                    950: '#0f172a',
                },
            },
            borderRadius: {
                '2xl': '1rem',
                '3xl': '1.5rem',
            },
            boxShadow: {
                'glass': '0 8px 32px rgba(0,0,0,0.12)',
                'card': '0 4px 24px rgba(0,0,0,0.06)',
            },
        },
    },

    plugins: [forms, typography],
};
