/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
app.service('TreeService', function ($http) {
    var menuTree;

    this.config = function (_menuTree) {
        menuTree = _menuTree;
    };
    
    this.addBranch = function (entity, parent) {
        var status = false;
        if (!angular.isUndefined(entity) && entity !== null)
        {
            menuTree.add_branch(parent, {
                id: entity.id,
                label: entity.name,
                name: entity.name,
                parent_menu: entity.parent_menu,
                position: entity.position
            });
            status = true;
        }
        return status;
    };

    this.editBranch = function (entity) {
        var status = false;
        if (!angular.isUndefined(entity) && entity !== null)
        {
            var branch = menuTree.get_selected_branch();
            branch.label = entity.name;
            status = true;
        }
        return status;
    };

    this.deleteBranch = function (menuData, entity) {
        var status = false;
        if (!angular.isUndefined(entity) && entity !== null)
        {
            var branch = menuTree.get_selected_branch();
            var parent = menuTree.select_parent_branch();
            if (parent) {
                parent.children.splice(parent.children.indexOf(branch), 1);
            } else {
                //Es un menu padre
                menuData.splice(menuData.indexOf(branch), 1);
                menuTree.select_branch(null);
            }
            status = true;
        }
        return status;
    };

});

