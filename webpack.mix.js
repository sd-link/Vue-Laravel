const { mix } = require('laravel-mix');
const path = require('path');
const webpack = require('webpack');

mix.autoload({
    'jquery': ['$', 'window.jQuery', 'jQuery'],
  })

mix.webpackConfig({
  resolve: {
    alias: {
      components: path.resolve(__dirname, './resources/assets/js/components'),
      utils: path.resolve(__dirname, './resources/assets/js/utils')
    }
  },
  plugins: [
    new webpack.NormalModuleReplacementPlugin(/element-ui[\/\\]lib[\/\\]locale[\/\\]lang[\/\\]zh-CN/, 'element-ui/lib/locale/lang/en'),
    new webpack.NormalModuleReplacementPlugin(/element-ui[\/\\]src[\/\\]locale[\/\\]lang[\/\\]zh-CN/, 'element-ui/src/locale/lang/en'),
    new webpack.NormalModuleReplacementPlugin(/element-ui[\/\\]packages[\/\\]scrollbar/, 'element-ui/lib/scrollbar')
  ]
});

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
mix.copy('app/Modules/**/Resources/Assets/js/*.js', 'public/js/modules/');
mix.copy('app/Modules/**/Resources/Assets/css/*.css', 'public/css/modules/');
mix.js('resources/assets/js/components/frontend/rendering/app.js', 'public/js/frontend/rendering')
    .js('resources/assets/js/settings.js', 'public/js')
    .js('resources/assets/js/adminvue.js', 'public/js')
    .js('resources/assets/js/content_index.js', 'public/js')
    .js('resources/assets/js/content_editor.js', 'public/js')
    .js('resources/assets/js/todos_editor.js', 'public/js')
    .js('resources/assets/js/taxonomy_editor.js', 'public/js')
    .js('resources/assets/js/gallery_index.js', 'public/js')
    .js('resources/assets/js/gallery_editor.js', 'public/js')
    .js('resources/assets/js/content_template_editor.js', 'public/js')
    .js('resources/assets/js/content_type_editor.js', 'public/js')
    .sass('resources/assets/sass/app.scss', 'public/css');
