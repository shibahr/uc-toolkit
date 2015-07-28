var elixir = require('laravel-elixir');

elixir(function(mix) {
    var bowerLoc = '/vendor/bower_components/';

    // Copy jquery, bootstrap, flat-ui and font awesome
    mix.copy(
            bowerLoc + 'jquery/dist/jquery.js',
            'resources/assets/js/jquery.js'
        ).copy(
            bowerLoc + 'bootstrap/less',
            'resources/assets/less/bootstrap'
        ).copy(
            bowerLoc + 'bootstrap/dist/js/bootstrap.js',
            'resources/assets/js/bootstrap.js'
        ).copy(
            bowerLoc + 'bootstrap/dist/fonts',
            'public/assets/fonts'
        ).copy(
            bowerLoc + 'fontawesome/less',
            'resources/assets/less/fontawesome'
        ).copy(
            bowerLoc + 'fontawesome/fonts',
            'public/assets/fonts'
        ).copy(
            bowerLoc + 'flat-ui/less',
            'resources/assets/less/flat-ui'
        ).copy(
            bowerLoc + 'flat-ui/dist/js/flat-ui.min.js',
            'resources/assets/js/flat-ui.min.js'
        ).copy(
            bowerLoc + 'flat-ui/dist/fonts/glyphicons',
            'public/assets/font/glyphicons'
        ).copy(
            bowerLoc + 'flat-ui/dist/fonts/lato',
            'public/assets/fonts/lato'
        );

    // Copy datatables
    mix.copy(
            bowerLoc + 'datatables/media/js/jquery.dataTables.js',
            'resources/assets/js/dataTables.js'
        ).copy(
            bowerLoc + 'datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css',
            'resources/assets/less/dataTables.less'
        ).copy(
            bowerLoc + 'datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.js',
            'resources/assets/js/dataTables.bootstrap.js'
        ).copy(
            bowerLoc + 'datatables-tabletools/css/dataTables.tableTools.css',
            'resources/assets/less/dataTables.tableTools.less'
        ).copy(
            bowerLoc + 'datatables-tabletools/js/dataTables.tableTools.js',
            'resources/assets/js/dataTables.tableTools.js'
    );


    // Combine scripts
    mix.scripts([
        'js/jquery.js',
        'js/bootstrap.js',
        'js/dataTables.js',
        'js/dataTables.bootstrap.js',
        'js/dataTables.tableTools.js'

    ], 'public/assets/js/app.js', 'resources/assets');

    // Compile Less
    mix.less([
        'app.less',
        'dataTables.less',
        'dataTables.tableTools.less'

    ], 'public/assets/css');

});