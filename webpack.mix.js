const path = require('path');
const glob = require('glob-all');
const PurgecssPlugin = require('purgecss-webpack-plugin');
const mix = require('laravel-mix');
require('laravel-mix-purgecss');

const PurgeCSSPlugin = require('purgecss-webpack-plugin')

mix.config.webpackConfig.output = {
    chunkFilename: 'js/[name].[contenthash].bundle.js',
    publicPath: '/',
};

mix.babelConfig({
    plugins: ['@babel/plugin-syntax-dynamic-import'],
});

mix.webpackConfig({
    resolve: {
        alias: {
            ziggy: path.resolve('vendor/tightenco/ziggy/dist/js/route.js'),
        },
    },
    module: {
        rules: [
            {
                test: /\.js?$/,
                use: [{
                    loader: 'babel-loader',
                    options: mix.config.babel()
                }]
            }
        ]
    },
    plugins: [
        new PurgecssPlugin({
            paths: glob.sync([
                path.join(__dirname, "resources/views/**/*.blade.php"),
                path.join(__dirname, "resources/views/front/*.blade.php"),
                path.join(__dirname, "resources/views/front/**/*.blade.php"),
                path.join(__dirname, "resources/views/layouts/*.blade.php"),
                path.join(__dirname, "resources/views/layouts/**/*.blade.php"),
                path.join(__dirname, "resources/views/vendor/**/*.blade.php"),
                path.join(__dirname, "resources/js/components/**/*.vue"),
                path.join(__dirname, "resources/js/*.js"),
            ]),
            safelist: {
                standard: [/is-affixed/, /modal-backdrop/,   /modal-open/,  /fade/,  /show/, /modal/, /modal-*/, /pl-*/, /select2-*/, /jp-card-*/, /tt-*/, /twitter-*/, /fas/, /far/],
                deep: [/is-affixed/, /modal-backdrop/,   /modal-open/,  /fade/,  /show/, /modal/, /modal-*/, /pl-*/, /select2-*/, /jp-card-*/, /tt-*/, /twitter-*/, /fas/, /far/],
                greedy: [/is-affixed/, /modal-backdrop/,   /modal-open/,  /fade/,  /show/, /modal/, /modal-*/, /pl-*/, /select2-*/, /jp-card-*/, /tt-*/, /twitter-*/, /fas/, /far/]
            },
        })
    ]
})

mix.options({
    cssNano: {
        discardComments: {
            removeAll: true
        },
        discardDuplicates: true,
        discardEmpty: true,
    }
})

// // compile this file first
// mix.js('resources/js/card.js', 'public/js')

mix.autoload({
    jquery: ['$', 'window.jQuery', 'jQuery'],
    vue: ['Vue','window.Vue']
})

mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/import-typeahead.js', 'public/js')
   	.sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/partials/_bootstrap.scss','public/css')
    .version()
