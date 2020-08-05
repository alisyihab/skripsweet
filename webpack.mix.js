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

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/assets/sass/theme.scss', 'public/css/style.css');


mix.scripts([
    'resources/assets/vendor/jquery/jquery.min.js',
    'resources/assets/vendor/popper.js/umd/popper.min.js',
    'resources/assets/vendor/bootstrap/js/bootstrap.min.js',
    'resources/assets/vendor/stacked-menu/js/stacked-menu.min.js',
    'resources/assets/vendor/perfect-scrollbar/perfect-scrollbar.min.js',
    'resources/assets/js/theme.min.js',
], 'public/js/all.js');