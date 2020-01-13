let mix = require('laravel-mix');
let tailwindcss = require('tailwindcss');
require('laravel-mix-purgecss');

mix.setPublicPath('.');

mix.sass('app/resources/sass/app.scss', 'app/dist/app.css').options({
    processCssUrls: false,
    postCss: [tailwindcss('tailwind.config.js')]
});

mix.js('app/resources/js/app.js', 'app/dist/app.js');

mix.copyDirectory(
    'node_modules/@fortawesome/fontawesome-free/webfonts',
    'app/dist/webfonts'
);

mix.purgeCss({
    extensions: ['html', 'js', 'php', 'scss', 'twig', 'vue'],
    folders: ['app'],
    whitelist: ['html', 'body', 'main', 'fab', 'fad', 'fal', 'far', 'fas'],
    whitelistPatterns: [/^fa\-/]
});

if (mix.inProduction()) {
    mix.version();
}
