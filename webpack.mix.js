
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

const mix = require("laravel-mix");
require('laravel-mix-purgecss');
const webpack = require("webpack");

const fs = require("fs"); // i dont know what is it, but mqpacker without this - dont work. Maybe FileSystem?
const mqpacker = require("css-mqpacker"); // combaine all media queries by a groups
const sortCSSmq = require("sort-css-media-queries"); //custom sorting for mqpacker

mix.options({
    processCssUrls: false, // dont copy files by links from css
    postCss: [
        require("css-mqpacker")({
            sort: sortCSSmq
        }),
        require("autoprefixer")({
            grid: "autoplace",
            remove: true
        })
    ]
});

mix.webpackConfig({
    resolve: {
        extensions: [".js"],
        modules: ["node_modules"],

        /**
         * if you have a problem with compiling
         *  - GSAP
         *  - ScrollMagic
         *  - and...
         * Save and do not let God, some jQuery libs
         *
         * remove the relevant comment
         *
         * If you have any other libraries that cause the error, add them in alias`es
         *  or provide in plugins
         */
        alias: {
            /** GSAP */
            'TweenLite': 'gsap/src/minified/TweenLite.min.js',
            'TweenMax': 'gsap/src/minified/TweenMax.min.js',
            // 'TimelineLite': 'gsap/src/minified/TimelineLite.min.js',
            'TimelineMax': 'gsap/src/minified/TimelineMax.min.js',


            /** ScrollMagic and bridge GSAP */
            'ScrollMagic': 'scrollmagic/scrollmagic/uncompressed/ScrollMagic.js',
            'animation.gsap': 'scrollmagic/scrollmagic/uncompressed/plugins/animation.gsap.js',

            /** and jQuery */
            // 'jQuery': "jquery/dist/jquery.js"
        }
    },
    plugins: [
        /** and here jQuery */
        new webpack.ProvidePlugin({
            $: "jquery",
            jQuery: "jquery"
        })
    ]
});


if (mix.inProduction()) {

    mix.options({
        // drop all consoles
        terser: {
            terserOptions: {
                mangle: true,
                compress: {
                    "drop_console": true
                }
            }
        }
    });

    // combine all imported modules and libs to single ECMAScript 2015+ file.
    mix.js(["resources/js/app.js"], "public/js/vanilla.js");


    // convert single file into a backwards compatible
    // version of JavaScript in current and older browsers.
    mix.babel(["public/js/vanilla.js"], "public/js/app.js");
    mix.copyDirectory("resources/image", "public/image");


} else {
    //section for development, will not work IE11<, safari 9.1.3<
    mix.js(["resources/js/app.js"], "public/js/app.js");
    mix.js(["resources/js/ajax-module.js"], "public/js/ajax-module.js");
    mix.js(["resources/js/Helper.js"], "public/js/Helper.js");
    //mix.js(["resources/js/tableGenerate.js"], "public/js/tableGenerate.js");
    mix.copyDirectory("resources/image", "public/image");
}

// fonts, images, temporary diresctories

mix.copyDirectory("resources/fonts", "public/fonts");
mix.copyDirectory("resources/image", "public/image");

// mix.copy("resources/index.html", "public/index.html");

mix.sass("resources/sass/app.sass", "public/css/app.css");

// mix.purgeCss({
//     // folders: ['resources', 'resources/views']
// });
mix.version(); // Ensable versioning.
mix.extract([
    'jquery',
    // 'axios',
    // 'ScrollMagic',
    // 'gsap',
    // 'jquery-ui',
    // 'jquery-ui-touch-punch',
    // 'jquery.maskedinput',
    // 'lodash.debounce',
    // 'lozad',
    // '@fancyapps/fancybox',
    // 'slick-carousel'
]);

// mix.sourceMaps(); // Enable sourcemaps
// mix.disableNotifications();

// mix.browserSync({
//     proxy: false,
//     notify: false,
//     server: {
//         baseDir: "public",
//         index: "index.html"
//     }
// });
mix.then(function () {

    let filesToClear = ["public/js/app-vanilla.js"];

    for (let i = 0; i < filesToClear.length; i++) {
        fs.stat(filesToClear[i], function(err, stats) {
            if (stats && stats.isFile()) {
                fs.unlink(filesToClear[i], (err) => {
                    if (err) throw err;
                    console.log(filesToClear[i] + ' - was deleted');
                });
            }
        });
    }
});
