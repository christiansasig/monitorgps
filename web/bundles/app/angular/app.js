/* 
 * Archivo para la la inyeccion de dependencias para angular
 * Christian Sasig christiansasig@gmail.com
 */


var app = angular
        .module('FrontendBundleApp', [
            'ngRoute',
            'ngResource',
            'ngSanitize',
            'ui.select',
            'ngTouch',
            'ngAnimate',
            'ui.grid',
            'ui.grid.pagination',
            'ui.grid.selection',
            'ui.bootstrap',
            'angular-loading-bar',
            'angularBootstrapNavTree',
            'ui.validate',
            'ivh.treeview',
            'toaster',
            'ngCookies',
            'ngMap'
        ]);
