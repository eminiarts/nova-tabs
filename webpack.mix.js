const mix = require('laravel-mix');
const webpackConfig = require('./webpack.config');

mix.setPublicPath('dist')
    .js('resources/js/field.js', 'js')
    .vue({
        version: 2,
    })
    .sass('resources/sass/field.scss', 'css')
    .webpackConfig(webpackConfig);
