const defaultTheme = require('tailwindcss/defaultTheme');
const colors = require('tailwindcss/colors');

module.exports = {
    plugins: [],
    purge: {
        mode: 'all',
        content: [
            'app/**/*.js',
            'app/**/*.php',
            'app/**/*.scss',
            'app/**/*.twig',
            'app/**/*.vue',
        ],
        options: {
            safelist: ['html', 'body', 'main', 'fab', 'fas'],
            safelistPatterns: [/^fa\-/, /^hljs/]
        }
    },
    theme: {
        extend: {
            colors: {
                blue: colors.lightBlue,
                gray: colors.blueGray,
                purple: colors.violet,
            },
            fontFamily: {
                mono: ['Source Code Pro', ...defaultTheme.fontFamily.mono],
                sans: ['Work Sans', ...defaultTheme.fontFamily.sans]
            },
            textColor: {
                github: '#171515',
                spectrum: '#7B16FF',
                twitter: '#1DA1F2'
            }
        }
    },
    variants: {
        visibility: ['responsive', 'hover', 'group-hover']
    }
};
