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

const ASSETS_ROOT = 'public/assets';

mix.js('resources/js/app.js', 'public/assets/js')
mix.postCss('resources/css/app.css', 'public/assets/css', [require('tailwindcss')])

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
