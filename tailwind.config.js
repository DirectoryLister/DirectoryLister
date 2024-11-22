const defaultTheme = require('tailwindcss/defaultTheme');
const colors = require('tailwindcss/colors');

module.exports = {
    darkMode: 'class',
    content: [
        'app/resources/**/*.{js,scss}',
        'app/src/**/*.php',
        'app/views/**/*.twig',
    ],
    theme: {
        extend: {
            colors: {
                blue: colors.sky,
                gray: colors.slate,
                purple: colors.violet,
            },
            fontFamily: {
                merriweather: ['Merriweather', ...defaultTheme.fontFamily.serif],
                mono: ['Source Code Pro', ...defaultTheme.fontFamily.mono],
                sans: ['Work Sans', ...defaultTheme.fontFamily.sans],
            },
            textColor: {
                bluesky: '#0886FE',
                github: '#171515',
            }
        }
    }
};
