/* 
 * Controlador BrandController para la gestion de aplicaciones
 * Christian Sasig christiansasig@gmail.com
 */

app.controller('AlertController', function ($scope, NgMap, $uibModal, UtilFactory, UiGridService, toaster) {
    $scope.path = 'alert';

    $scope.checked = true;
    $scope.entitySelected = null;
    $scope.alerts = [];

    var columnDefs = [
        {name: '#', field: 'name', width: 50, enableFiltering: false, cellTemplate: '<div class="ui-grid-cell-contents">{{grid.renderContainers.body.visibleRowCache.indexOf(row) + 1}}</div>'},
        {field: 'id', visible: false},
        {field: 'created_at', displayName: 'Fecha', type: 'date', cellFilter: 'date:\'yyyy-MM-dd HH:mm:ss\'', enableFiltering: false},
        {field: 'tag', displayName: 'Etiqueta', enableFiltering: false},
        {field: 'device.name', displayName: 'Dispositivo', enableFiltering: false},
        {field: 'description', displayName: 'Descripción', enableFiltering: false}
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
                controller: 'AddEntityController',
                size: size,
                resolve: resolve
            });
        } else
        {
            modalInstance = $uibModal.open({
                template: template,
                controller: 'AddEntityController',
                size: size,
                resolve: resolve
            });
        }

        modalInstance.result.then(function (entity) {
            if (UiGridService.addData(entity)) {
                toaster.pop('success', 'Notificación', 'Registro guardado exitosamente');
            }
        }, function () {
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
                controller: 'EditEntityController',
                size: size,
                resolve: resolve
            });
        } else
        {
            modalInstance = $uibModal.open({
                template: template,
                controller: 'EditEntityController',
                size: size,
                resolve: resolve
            });
        }

        modalInstance.result.then(function (entity) {
            if (UiGridService.editData(entity)) {
                $scope.checked = true;
                toaster.pop('success', 'Notificación', 'Registro actualizado exitosamente');
            }
        }, function () {
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