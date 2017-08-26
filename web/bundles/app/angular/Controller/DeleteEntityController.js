/* 
 * Controlador DeleteEntityController para la gestion de registros
 * Christian Sasig christiansasig@gmail.com
 */


app.controller('DeleteEntityController', function ($scope, $http, $uibModalInstance, UtilFactory, path, entity, toaster) {
    $scope.ok = function () {

        $http({
            method: 'POST',
            url: Routing.generate(path + '_delete', {'id': entity.id}),
            data: $.param({_method: 'DELETE'}),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function successCallback(response) {
            if (response.data.status === 'ok')
            {
                $uibModalInstance.close(entity);
            }
            else
            {
                $uibModalInstance.dismiss('cancel');
            }
        }, function errorCallback(response) {
            $uibModalInstance.dismiss('cancel');
            toaster.pop('error', 'Notificación', 'Error en el servidor');

        });
    };

    $scope.cancel = function () {
        $uibModalInstance.dismiss('cancel');
    };
});