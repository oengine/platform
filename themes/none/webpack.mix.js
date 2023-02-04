const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */
 mix.webpackConfig({
    stats: {
        children: true,
    },});
mix.js('resources/assets/js/app.js', 'public/js/oengine-none.js')
    .sass('resources/assets/sass/app.scss', 'public/css/oengine-none.css');