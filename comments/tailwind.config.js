/** @type {import('tailwindcss').Config} */

import preset from './vendor/filament/support/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        "./resources/**/*.blade.php",
        "./resources/views/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        // "./vendor/lakm/laravel-comments/resources/views/**/*.blade.php"
        './vendor/filament/**/*.blade.php',
        "./vendor/lakm/laravel-comments-admin-panel/resources/views/**/*.blade.php"
    ],
    safelist: [
        'bg-gray-200',
        'bg-gray-500',
    ],
    theme: {
        extend: {},
    },
    plugins: [],
}

