var elixir = require('laravel-elixir');

elixir(function(mix) {
    var dtPluginsDir = 'vendor/bower_components/datatables-plugins/';

    // Copy jquery, bootstrap, flat-ui and font awesome
    mix.copy(
            'vendor/bower_components/jquery/dist/jquery.js',
            'resources/assets/js/jquery.js'
        ).copy(
            'vendor/bower_components/bootstrap/less',
            'resources/assets/less/bootstrap'
        ).copy(
            'vendor/bower_components/bootstrap/dist/js/bootstrap.js',
            'resources/assets/js/bootstrap.js'
        ).copy(
            'vendor/bower_components/bootstrap/dist/fonts',
            'public/assets/fonts'
        ).copy(
            'vendor/bower_components/fontawesome/less',
            'resources/assets/less/fontawesome'
        ).copy(
            'vendor/bower_components/fontawesome/fonts',
            'public/assets/fonts'
        ).copy(
            'vendor/bower_components/flat-ui/less',
            'resources/assets/less/flat-ui'
        ).copy(
            'vendor/bower_components/flat-ui/dist/js/flat-ui.min.js',
            'resources/assets/js/flat-ui.min.js'
        ).copy(
            'vendor/bower_components/flat-ui/dist/fonts/glyphicons',
            'public/assets/font/glyphicons'
        ).copy(
            'vendor/bower_components/flat-ui/dist/fonts/lato',
            'public/assets/fonts/lato'
        );

    // Copy datatables
    mix.copy(
            'vendor/bower_components/datatables/media/js/jquery.dataTables.js',
            'resources/assets/js/dataTables.js'
        ).copy(
            dtPluginsDir + 'integration/bootstrap/3/dataTables.bootstrap.css',
            'resources/assets/less/dataTables.less'
        ).copy(
            dtPluginsDir + 'integration/bootstrap/3/dataTables.bootstrap.js',
            'resources/assets/js/dataTables.bootstrap.js'
        );


    // Combine scripts
    mix.scripts([
        'js/jquery.js',
        'js/bootstrap.js',
        'js/dataTables.js',
        'js/dataTables.bootstrap.js'

    ], 'public/assets/js/app.js', 'resources/assets');

    // Compile Less
    mix.less('app.less', 'public/assets/css');

});