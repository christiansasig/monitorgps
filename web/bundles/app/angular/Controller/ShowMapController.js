/* 
 * Controlador ShowEntityController para la gestion de registros
 * Christian Sasig christiansasig@gmail.com
 */

app.controller('ShowMapController', function ($scope, NgMap, $uibModalInstance) {
    $scope.cancel = function () {
        $uibModalInstance.dismiss('cancel');
    };

    //html  n-click="toggleBounce()"
    $scope.toggleBounce = function () {
        if (this.getAnimation() != null) {
            this.setAnimation(null);
        } else {
            this.setAnimation(google.maps.Animation.BOUNCE);
        }
    }

    NgMap.getMap("map").then(function (map) {
        $scope.map = map;
    });
    
    $scope.latlng = [-2.901895761873221,-79.01779174804688];
    $scope.getpos = function(event){
     $scope.latlng = [event.latLng.lat(), event.latLng.lng()];
  };

});