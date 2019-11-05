let mix = require('laravel-mix');
let tailwindcss = require('tailwindcss');
require('laravel-mix-purgecss');

mix.sass('src/sass/app.scss', 'dist').options({
    processCssUrls: false,
    postCss: [tailwindcss('tailwind.config.js')]
});

mix.js('src/js/app.js', 'dist');

mix.purgeCss({
    extensions: ['html', 'scss', 'twig'],
    globs: ['*.twig'],
    folders: ['src'],
    whitelist: ['html', 'body', 'main']
});
