/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
app.service('UiGridService', function ($http, UtilFactory, i18nService) {
    var gridOptions;
    var gridApi;
    var entitySelected;
    var scope;

    this.config = function (_scope, _gridOptions) {
        scope = _scope;
        gridOptions = _gridOptions;
        i18nService.setCurrentLang('es');
       
        gridOptions.onRegisterApi = function (_gridApi) {
            gridApi = _gridApi;
            _gridApi.selection.on.rowSelectionChanged(scope, function (row) {
                entitySelected = row.entity;
                scope.rowSelectionChanged(row);
            });
            
            _gridApi.selection.on.rowSelectionChangedBatch(scope, function (rows) {
            });
        };
    };


    this.loadData = function (url) {
        $http.get(url).then(
                function successCallback(response) {
                    gridOptions.data = response.data.entities;
                },
                function errorCallback(response) {
                    UtilFactory.addAlert(scope, 'danger', 'Error en el servidor');
                }
        );
    };

    this.addData = function (entity) {
        var status = false;
        if (!angular.isUndefined(entity) && entity !== null)
        {
            gridOptions.data.push(entity);
            status = true;
        }
        return status;
    };

    this.editData = function (entity) {
        var status = false;
        if (!angular.isUndefined(entity) && entity !== null)
        {
            gridOptions.data[gridOptions.data.indexOf(entitySelected)] = entity;
            gridApi.core.refresh();
            status = true;
        }
        return status;
    };

    this.deleteData = function (entity) {
        var status = false;
        if (!angular.isUndefined(entity) && entity !== null)
        {
            gridOptions.data.splice(gridOptions.data.indexOf(entity), 1);
            status = true;
        }
        return status;
    };

});

