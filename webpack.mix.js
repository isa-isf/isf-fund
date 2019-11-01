const mix = require('laravel-mix');
const path = require('path');

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

mix.js('resources/js/app.js', `${ASSETS_ROOT}/js`)
   .sass('resources/sass/app.scss', `${ASSETS_ROOT}/css`);

mix.js('resources/js/manage.js', `${ASSETS_ROOT}/js`);
mix.sass('resources/sass/manage.scss', `${ASSETS_ROOT}/css`);

mix.webpackConfig({
  resolve: {
    extensions: ['.less'],
    alias: {
      app: path.resolve(__dirname, 'resources/js/'),
    },
  },
  output: {
    publicPath: '/',
    chunkFilename: 'assets/js/manage-[name]-[chunkhash].js',
  },
});
