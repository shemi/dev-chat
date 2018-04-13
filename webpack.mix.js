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

mix.extend(
    'audio',
    new class {
        register(val) {

        }

        dependencies() {
            return ['url-loader']
        }

        webpackRules() {
            return [
                {
                    test: /\.mp3$/,
                    loaders: [
                        {
                            loader: 'file-loader',
                            options: {
                                name: 'audio/[name].[ext]?[hash]',
                                publicPath: Config.resourceRoot
                            }
                        }
                    ],
                },
            ];
        }

        webpackPlugins() {}
    }()
);

mix.js('resources/assets/js/app.js', 'public/js')
    .sass('resources/assets/sass/app.scss', 'public/css')
    .audio()
    .browserSync({
        proxy: {
            target: 'http://dev-chat.test',
            ws: true
        }
    });
