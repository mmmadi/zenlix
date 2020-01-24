var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */


elixir(function(mix) {

    this.config.assetsPath = 'public/';
    //this.config.css.sass.folder = 'public/';
    //mix.stylesIn('public/');


    /*
    Styles that is in all templates
     */

    mix.styles([
        'bootstrap/css/bootstrap.min.css',
        'plugins/font-awesome/css/font-awesome.min.css',
        'plugins/ionicons/css/ionicons.min.css',
        'plugins/select2/select2.min.css',
        'plugins/toastr/toastr.min.css',
        'plugins/datatables/media/css/dataTables.bootstrap.min.css',
        'plugins/trumbowyg/ui/trumbowyg.css',
        'dist/css/AdminLTE.min.css',
        'dist/css/skins/_all-skins.min.css',
        'plugins/select2/select2-bootstrap.css',
        'plugins/pace/pace.min.css',
    ], 'public/dist/css/gulpStyles.css', 'public/');

    /*
    Scripts that is in all templates

     */

    mix.scripts([
        'plugins/jQuery/jQuery-2.1.4.min.js',
        'dist/js/jquery-ui.min.js',
        'bootstrap/js/bootstrap.min.js',
        'plugins/pace/pace.min.js',
        'plugins/select2/select2.full.min.js',
        'dist/js/app.min.js',
        'plugins/trumbowyg/trumbowyg.min.js',
        'plugins/datatables/media/js/jquery.dataTables.min.js',
        'plugins/datatables/media/js/dataTables.bootstrap.min.js',
        'plugins/autosize/autosize.min.js',
        'plugins/socket.io/socket.io-1.4.3.js',
        'plugins/toastr/toastr.min.js',
        'plugins/ionsound/ion.sound.min.js',
    ], 'public/dist/js/gulpJs.js', 'public/');

});
