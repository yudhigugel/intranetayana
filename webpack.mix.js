const mix = require('laravel-mix');

mix.copy('node_modules/sweetalert2/dist/sweetalert2.min.js', 'public/js/vendor/sweetalert2.min.js')
.copy('node_modules/bootstrap/dist/css/bootstrap.min.css', 'public/css/vendor/bootstrap.min.css');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.webpackConfig({
    resolve: {
      extensions: ['.js', '.vue',],
      alias: {
        '@app': __dirname + '/resources/js/'
      },
    },
  })

//  mix.copy('node_modules/sweetalert2/dist/sweetalert2.min.js', 'public/js/vendor/sweetalert2.min.js')
// .copy('node_modules/sweetalert2/dist/sweetalert2.min.css', 'public/css/vendor/sweetalert2.min.css');

mix.js('resources/js/sites/side_menu/index.js', 'public/js/app/sites/side_menu')
.js('resources/js/app/sap/purchase_order/index.js', 'public/js/app/sap/purchase_order')
.js('resources/js/app/dashboards/warehouse/orders/index.js', 'public/js/app/dashboards/warehouse/orders')
.js('resources/js/app/warehouse/orders/index.js', 'public/js/app/warehouse/orders')
.js('resources/js/components/sap/index.js', 'public/js/app/sites/sap')
.js('resources/js/components/report_kms/cancellation/index.js', 'public/js/app/sites/report_kms/cancellation')
.js('resources/js/components/report_simphony/index.js', 'public/js/app/sites/report_simphony')
.js('resources/js/components/dashboards/index.js', 'public/js/app/sites/dashboards')
.sass('resources/sass/dashboards/warehouse/order.scss','public/css/app/dashboards/warehouse')
.sass('resources/sass/custom_layout.scss','public/css/app')
.sass('resources/sass/warehouse/order.scss','public/css/app/warehouse')
    .postCss('resources/css/app.css', 'public/css', [
        //
    ]);
