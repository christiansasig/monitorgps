/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
app.factory('UtilFactory', function () {

    var factory = {};

    factory.addAlert = function (scope, type, msg) {
        scope.alerts = [];
        scope.alerts.push({type: type, msg: msg});
    };

    factory.closeAlert = function (scope, index) {
        scope.alerts.splice(index, 1);
    };

    factory.sortRecursive = function (array, propertyName) {

        array.forEach(function (item) {
            var keys = _.keys(item);
            keys.forEach(function (key) {
                if (_.isArray(item[key])) {
                    item[key] = factory.sortRecursive(item[key], propertyName);
                }
            });
        });

        return _.sortBy(array, propertyName);
    };

    factory.formatArrayToPrentChild = function (data) {

        var dataMap = data.reduce(function (map, node) {
            map[node.id] = node;
            return map;
        }, {});

        // create the tree array
        var tree = [];
        data.forEach(function (node) {
            // add to parent
            node["label"] = node.name;
            var parent;

            if (node.parent_menu !== null)
            {
                parent = dataMap[node.parent_menu.id];
            }
            if (parent) {
                // create child array if it doesn't exist
                (parent.children || (parent.children = []))
                        // add node to child array
                        .push(node);
            } else {
                // parent is null or missing
                tree.push(node);
            }
        });
        return tree;
    };
    
    factory.addPropertyToArray = function (array, property, value) {

        array.forEach(function (node) {
            node[property] = value;
            
        });
        return array;
    };

    factory.findItemFilter = function (items, id) {
        var filtered = [];
        var recursiveFilter = function (items, id) {
            angular.forEach(items, function (item) {
                if (item.id === id) {
                    filtered.push(item);
                }
                if (angular.isArray(item.children) && item.children.length > 0) {
                    recursiveFilter(item.children, id);
                }
            });
        };
        recursiveFilter(items, id);
        return filtered;
    };
    
    factory.findItemById = function (items, id) {
        var node = null;
            angular.forEach(items, function (item, index) {
                if (item.id === id) {
                    node = item;
                }
            });
      
        return node;
    };
    
    factory.findPositionById = function (items, id) {
        var pos = -1;
            angular.forEach(items, function (item, index) {
                if (item.id === id) {
                    pos = index;
                }
            });
      
        return pos;
    };

    return factory;
});

