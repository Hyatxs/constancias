/** @type {import('tailwindcss').Config} */
const defaultTheme = require('tailwindcss/defaultTheme')

module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        './resources/**/*.vue',

    ],
    darkMode: 'class',
    theme: {
        extend: {
            fontFamily: {
                'sans': ['Inter', ...defaultTheme.fontFamily.sans],
                'body': ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    "50": "#eff6ff",
                    "100": "#dbeafe",
                    "200": "#bfdbfe",
                    "300": "#93c5fd",
                    "400": "#60a5fa",
                    "500": "#3b82f6",
                    "600": "#2563eb",
                    "700": "#1d4ed8",
                    "800": "#1e40af",
                    "900": "#1e3a8a",
                    "950": "#172554"
                }
            },
            boxShadow: {
                'outline-blue': '0 0 0 3px rgba(59, 130, 246, 0.5)',
            },
            extend: {
                fontSize: {
                    '2xs': '.625rem', // 10px
                },
                borderWidth: {
                    '1.5': '1.5px',
                },
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
        require('@tailwindcss/aspect-ratio'),
        // Otros plugins que puedas necesitar
    ],
}
