let mix = require('laravel-mix');
let tailwindcss = require('tailwindcss');
require('laravel-mix-purgecss');

mix.setPublicPath('app/assets');

mix.webpackConfig({
    watchOptions: { ignored: ['node_modules', 'app/vendor'] }
});

mix.sass('app/resources/sass/app.scss', 'app/assets/app.css').options({
    processCssUrls: false,
    postCss: [tailwindcss('tailwind.config.js')]
});

mix.js('app/resources/js/app.js', 'app/assets/app.js');

mix.copyDirectory('app/resources/images', 'app/assets/images');

mix.copyDirectory(
    'node_modules/@fortawesome/fontawesome-free/webfonts',
    'app/assets/webfonts'
);

mix.purgeCss({
    extensions: ['html', 'js', 'php', 'scss', 'twig', 'vue'],
    folders: ['app'],
    whitelist: ['html', 'body', 'main', 'fab', 'far', 'fas'],
    whitelistPatterns: [/^fa\-/, /^hljs/]
});

if (mix.inProduction()) {
    mix.version();
}
