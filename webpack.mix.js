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

mix.js('resources/js/admin/main.js', 'public/js');
    // .js('resources/js/argon/assets/js/argon.js', 'public/js')
    // .js('resources/js/app', 'public/js')
    // .sass('resources/js/argon/assets/scss/argon.scss', 'public/css')
