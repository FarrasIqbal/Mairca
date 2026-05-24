import defaultTheme from 'tailwindcss/defaultTheme';

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
                // Warna Primary khusus untuk tombol dan aksen UI
                primary: {
                    50: '#eff6ff',
                    100: '#dbeafe',
                    500: '#3b82f6', // Warna utama (Biru SaaS)
                    600: '#2563eb', // Hover state
                    700: '#1d4ed8',
                    900: '#1e3a8a',
                }
            }
        },
    },
    plugins: [require('@tailwindcss/forms')],
};