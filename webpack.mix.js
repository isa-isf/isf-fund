let mix = require('laravel-mix');

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

const ASSETS_ROOT = 'public/assets';

mix.js('resources/assets/js/app.js', `${ASSETS_ROOT}/js`)
   .sass('resources/assets/sass/app.scss', `${ASSETS_ROOT}/css`);
