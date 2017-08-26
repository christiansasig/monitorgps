/* 
 * Archivo para la configuracion de rutas
 * Christian Sasig christiansasig@gmail.com
 */

app.config(['$routeProvider', function ($routeProvider) {
        $routeProvider
                .when('/position', {
                    templateUrl: Routing.generate('position_index'),
                    controller: 'PositionController'
                })
                .when('/polygon', {
                    templateUrl: Routing.generate('polygon_index'),
                    controller: 'PolygonController'
                })
                .when('/device', {
                    templateUrl: Routing.generate('device_index'),
                    controller: 'DeviceController'
                })
                
                 .when('/role', {
                    templateUrl: Routing.generate('role_index'),
                    controller: 'RoleController'
                })
                
                 .when('/user', {
                    templateUrl: Routing.generate('users_index'),
                    controller: 'UserController'
                })
                .when('/alert', {
                    templateUrl: Routing.generate('alert_index'),
                    controller: 'AlertController'
                })
               

    }]);