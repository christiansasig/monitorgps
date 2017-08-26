/* 
 * Archivo para la configuracion de las dependencias de angular
 * Christian Sasig christiansasig@gmail.com
 */


app.config(function ($interpolateProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
});

app.config(['cfpLoadingBarProvider', function (cfpLoadingBarProvider) {
        //cfpLoadingBarProvider.parentSelector = '#loading-bar-container';
        //cfpLoadingBarProvider.spinnerTemplate = '<div><span class="fa fa-spinner"> Cargando...</div>';
    }]);

app.config(['ivhTreeviewOptionsProvider', function (ivhTreeviewOptionsProvider) {
        ivhTreeviewOptionsProvider.set({
            twistieCollapsedTpl: '<span class="fa fa-plus"></span>',
            twistieExpandedTpl: '<span class="fa fa-minus"></span>',
            twistieLeafTpl: '&#9679;'
        });
    }]);
