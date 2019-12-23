let mix = require('laravel-mix');
let tailwindcss = require('tailwindcss');
require('laravel-mix-purgecss');

mix.sass('app/resources/sass/app.scss', 'app/dist').options({
    processCssUrls: false,
    postCss: [tailwindcss('tailwind.config.js')]
});

mix.js('app/resources/js/app.js', 'app/dist');

mix.copyDirectory(
    'node_modules/@fortawesome/fontawesome-free/webfonts',
    'app/dist/webfonts'
);

mix.purgeCss({
    extensions: ["html", "js", "php", "scss", "twig", "vue"],
    globs: ["**/*.php", "**/*.scss", "**/*.twig"],
    folders: ["src"],
    whitelist: ["html", "body", "main", "fab", "far", "fas", "fal", "fad"],
    whitelistPatterns: [/^fa\-/]
});
