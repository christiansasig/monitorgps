/* 
 * Controlador EditEntityController  para la gestion de registros
 * Christian Sasig christiansasig@gmail.com
 */

app.controller('EditEntityController', function ($scope, $http, $uibModalInstance, UtilFactory, path, entity, editEntity, toaster) {
    $scope.ok = function () {
    };

    $scope.cancel = function () {
        $uibModalInstance.dismiss('cancel');
    };

    $scope.processForm = function () {
        var datastring = $("#adminForm").serialize();
        $http({
            method: 'POST',
            url: Routing.generate(path + '_edit', {'id': entity.id}),
            data: datastring,
            cache: false,
            contentType: false,
            processData: false,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function successCallback(response) {
            if (response.data.status === 'ok')
            {
                $uibModalInstance.close(response.data.entity);
            }
            else
            {
                $uibModalInstance.close();
                editEntity('', response.data);
            }
        }, function errorCallback(response) {
            $uibModalInstance.dismiss('cancel');
            toaster.pop('error', 'Notificación', 'Error en el servidor');

        });
    };
});
