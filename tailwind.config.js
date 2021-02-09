const defaultTheme = require('tailwindcss/defaultTheme');
const colors = require('tailwindcss/colors');

module.exports = {
    darkMode: 'class',
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
            safelist: ['html', 'body', 'main', 'fab', 'fas', /^hljs/],
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
                merriweather: ['Merriweather', ...defaultTheme.fontFamily.serif],
                mono: ['Source Code Pro', ...defaultTheme.fontFamily.mono],
                sans: ['Work Sans', ...defaultTheme.fontFamily.sans],
            },
            textColor: {
                github: '#171515',
                twitter: '#1DA1F2'
            }
        }
    },
    variants: {
        backgroundOpacity: ['dark'],
        visibility: ['responsive', 'hover', 'group-hover']
    }
};
