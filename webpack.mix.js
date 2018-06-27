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

mix.js('resources/assets/js/app.js', `${ASSETS_ROOT}/js`)
   .sass('resources/assets/sass/app.scss', `${ASSETS_ROOT}/css`);

mix.js('resources/assets/js/manage.js', `${ASSETS_ROOT}/js`);
mix.sass('resources/assets/sass/manage.scss', `${ASSETS_ROOT}/css`);

mix.webpackConfig({
  resolve: {
    extensions: ['.less'],
    alias: {
      app: path.resolve(__dirname, 'resources/assets/js/'),
    },
  },
  output: {
    publicPath: 'assets/js/',
    chunkFilename: 'assets/js/manage-[name]-[chunkhash].js',
  },
});
