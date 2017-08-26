/* 
 * Controlador ShowEntityController para la gestion de registros
 * Christian Sasig christiansasig@gmail.com
 */

app.controller('ShowEntityController', function ($scope, $uibModalInstance) {
    $scope.cancel = function () {
        $uibModalInstance.dismiss('cancel');
    };
});