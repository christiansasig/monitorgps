/* 
 * Controlador BrandController para la gestion de aplicaciones
 * Christian Sasig christiansasig@gmail.com
 */

app.controller('PolygonController', function ($scope, NgMap, $uibModal, UtilFactory, UiGridService, toaster) {
    $scope.path = 'polygon';

    $scope.checked = true;
    $scope.entitySelected = null;
    $scope.alerts = [];

    var columnDefs = [
        {name: '#', field: 'name', width: 50, enableFiltering: false, cellTemplate: '<div class="ui-grid-cell-contents">{{grid.renderContainers.body.visibleRowCache.indexOf(row) + 1}}</div>'},
        {field: 'id', visible: false},
        {field: 'name', displayName: 'name', enableFiltering: false},
        {field: 'status', displayName: 'Estado', cellClass: 'grid-align', cellTemplate: "<i class=\"fa fa-{{row.entity.status ? 'check' : 'close'}}\"></i>", enableFiltering: false}
    ];

    $scope.gridOptions = {
        enableFiltering: true,
        enableRowSelection: true,
        enableRowHeaderSelection: false,
        multiSelect: false,
        modifierKeysToMultiSelect: false,
        noUnselect: true,
        paginationPageSizes: [10, 20, 30],
        paginationPageSize: 10,
        columnDefs: columnDefs
    };

    UiGridService.config($scope, $scope.gridOptions);
    UiGridService.loadData(Routing.generate($scope.path + '_find_all'));
    $scope.rowSelectionChanged = function (row) {
        $scope.checked = false;
        $scope.entitySelected = row.entity;
    };

    $scope.closeAlert = function (index) {
        UtilFactory.closeAlert($scope, index);
    };

    $scope.addEntity = function (size, template) {
        var modalInstance;
        var resolve = {
            addEntity: function () {
                return $scope.addEntity;
            },
            path: function () {
                return $scope.path;
            }
        };

        if (angular.isUndefined(template))
        {
            modalInstance = $uibModal.open({
                templateUrl: Routing.generate($scope.path + '_new'),
                controller: 'AddPolygonController',
                size: size,
                resolve: resolve
            });
        } else
        {
            modalInstance = $uibModal.open({
                template: template,
                controller: 'AddPolygonController',
                size: size,
                resolve: resolve
            });
        }

        modalInstance.opened.then(function () {
            NgMap.getMap("map_polygon").then(function (map) {
                google.maps.event.trigger(map, 'resize');
                //map.mapDrawingManager[0].setOptions({drawingControl:false});
            }).catch(function (error) {
                console.log(error);
            });
        });

        modalInstance.result.catch(function () {
        });

        modalInstance.result.then(function (entity) {
            if (UiGridService.addData(entity)) {
                toaster.pop('success', 'Notificación', 'Registro guardado exitosamente');
            }
        }, function () {// se ejecuta en el dismiss y cuando se da clic fuera de la ventna modal
            if ($scope.overlay)
            {
                $scope.overlay.setMap(null);
            }
        });
    };


    $scope.editEntity = function (size, template) {
        var modalInstance;
        var _entity = $scope.entitySelected;
        var resolve = {
            editEntity: function () {
                return $scope.editEntity;
            },
            entity: function () {
                return _entity;
            },
            path: function () {
                return $scope.path;
            }
        };

        if (angular.isUndefined(template))
        {
            modalInstance = $uibModal.open({
                templateUrl: Routing.generate($scope.path + '_edit', {'id': _entity.id}) + '?time=' + new Date().getTime(),
                controller: 'EditPolygonController',
                size: size,
                resolve: resolve
            });
        } else
        {
            modalInstance = $uibModal.open({
                template: template,
                controller: 'EditPolygonController',
                size: size,
                resolve: resolve
            });
        }

        modalInstance.opened.then(function () {
            NgMap.getMap("map_edit_polygon").then(function (map) {
                google.maps.event.trigger(map, 'resize');
                //map.mapDrawingManager[0].setOptions({drawingControl:false});
            }).catch(function (error) {
                console.log(error);
            });
        });

        modalInstance.result.then(function (entity) {
            if (UiGridService.editData(entity)) {
                $scope.checked = true;
                toaster.pop('success', 'Notificación', 'Registro actualizado exitosamente');
            }
        }, function () {
            if ($scope.overlay)
            {
                $scope.overlay.setMap(null);
            }
        });
    };

    $scope.showEntity = function (size) {
        var _entity = $scope.entitySelected;
        var modalInstance = $uibModal.open({
            templateUrl: Routing.generate($scope.path + '_show', {'id': _entity.id}),
            controller: 'ShowMapController',
            size: size
        });

        modalInstance.opened.then(function () {
            NgMap.getMap("map").then(function (map) {
                google.maps.event.trigger(map, 'resize');
            }).catch(function (error) {
                console.log(error);
            });
        });
    };


    $scope.deleteEntity = function (size) {
        var _entity = $scope.entitySelected;
        var modalInstance = $uibModal.open({
            templateUrl: 'modalDelete.html',
            controller: 'DeleteEntityController',
            size: size,
            resolve: {
                entity: function () {
                    return _entity;
                },
                path: function () {
                    return $scope.path;
                }
            }
        });

        modalInstance.result.then(function (entity) {
            if (UiGridService.deleteData(entity)) {
                toaster.pop('success', 'Notificación', 'Registro eliminado exitosamente');

            }
        }, function () {
        });
    };
});

app.controller('AddPolygonController', function ($scope, NgMap, $http, $uibModalInstance, UtilFactory, path, addEntity, toaster) {

    $scope.shapePath = '';
    $scope.overlay = '';
    $scope.devices = [];
    $scope.position = {};

    NgMap.getMap("map_polygon").then(function (map) {
        $scope.map = map;

    });

    $scope.source = {
        deviceSelected: '',
    };

    $http.get(Routing.generate('device_find_all')).then(
            function successCallback(response) {
                $scope.devices = response.data.entities;
            },
            function errorCallback(response) {
                UtilFactory.addAlert($scope, 'danger', 'Error en el servidor');
            }
    );




    $scope.searchPosition = function () {


        var marker = new google.maps.Marker({
            title: $scope.source.deviceSelected.name,
            label: $scope.source.deviceSelected.name
        })
        var dataObj = {
            ip: $scope.source.deviceSelected.ip
        };
        $http({
            method: 'POST',
            url: Routing.generate('position_current'),
            data: dataObj,
            cache: false,
            contentType: false,
            processData: false,
            headers: {'Content-Type': 'application/json'}
        }).then(function successCallback(response) {
            if (response.data.position)
            {
                $scope.position = response.data.position;
                //var center = new google.maps.LatLng($scope.position.latitude, $scope.position.longitude);
                var latlng = new google.maps.LatLng($scope.position.latitude, $scope.position.longitude);
                $scope.map.panTo(latlng);
                marker.setPosition(latlng);
                marker.setMap($scope.map);
            } else
            {
                toaster.pop('info', 'Notificación', 'No se pudo localizar al dispositivo');
            }
        }, function errorCallback(response) {
            toaster.pop('error', 'Notificación', 'No se pudo localizar al dispositivo');
        });
    }



    $scope.onMapOverlayCompleted = function (e) {
        $scope.shapePath = e.overlay.getPath().getArray();  //This returns an array of drawn polygon cordinates
        $scope.overlay = e.overlay;
        $scope.$parent.overlay = $scope.overlay;
    };

    $scope.cancel = function () {
        if ($scope.overlay)
        {
            $scope.overlay.setMap(null);
        }
        $uibModalInstance.dismiss('cancel');
    };


    $scope.processForm = function () {
        $("#path").val(JSON.stringify($scope.shapePath));
        var datastring = $("#adminForm").serialize();
        $http({
            method: 'POST',
            url: Routing.generate(path + '_new'),
            data: datastring,
            cache: false,
            contentType: false,
            processData: false,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function successCallback(response) {
            if (response.data.status === 'ok')
            {
                $scope.overlay.setMap(null);
                $uibModalInstance.close(response.data.entity);
            } else
            {
                $uibModalInstance.close();
                addEntity('', response.data);
            }
        }, function errorCallback(response) {
            $uibModalInstance.dismiss('cancel');
            toaster.pop('error', 'Notificación', 'Error en el servidor');
        });
    };
});

app.controller('EditPolygonController', function ($scope, $http, $uibModalInstance, UtilFactory, path, entity, editEntity, toaster) {

    $scope.shapePath = '';
    $scope.overlay = '';

    $scope.cancel = function () {
        if ($scope.overlay)
        {
            $scope.overlay.setMap(null);
        }
        $uibModalInstance.dismiss('cancel');
    };

    $scope.onMapOverlayCompleted = function (e) {
        $scope.shapePath = e.overlay.getPath().getArray();  //This returns an array of drawn polygon cordinates
        $scope.overlay = e.overlay;
        $scope.$parent.overlay = $scope.overlay;
    };

    $scope.processForm = function () {
        if ($scope.shapePath)
        {
            $("#path").val(JSON.stringify($scope.shapePath));
        } else
        {
            $("#path").val(entity.path);
        }

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
                if ($scope.overlay)
                {
                    $scope.overlay.setMap(null);
                }

                $uibModalInstance.close(response.data.entity);
            } else
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
