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

mix.js('resources/js/app.js', 'public/assets/js')
mix.postCss('resources/css/app.css', 'public/assets/css', [require('tailwindcss')])

mix.copy('node_modules/open-iconic/font/css/open-iconic.min.css', 'public/assets/vendor/open-iconic/css/open-iconic.min.css');
mix.copy('node_modules/open-iconic/font/fonts', 'public/assets/vendor/open-iconic/fonts/');

mix.version();

