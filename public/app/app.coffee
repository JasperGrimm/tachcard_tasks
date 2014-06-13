application = angular.module('com.tachcard.jasper.app', [])

application.directive 'tree', () ->
  tree_linker = (scope, element, attributes) ->
    scope.nodes = [
      {
        name: 'node0',
        nodes: [
          {
            name: 'node0.1'
          },
          {
            name: 'node0.2'
          }
        ]
      }
    ]
  return {
    restrict: 'AEC'
    link: tree_linker
    templateUrl: '/app/templates/tree.html'
  }


application.directive 'treeNode', () ->

  tree_node_linker = (scope, element, attributes) ->
    console.log scope.node
  return {
    restrict: 'AEC'
    templateUrl: '/app/templates/tree_node.html'
    scope:
      node: '='
    link: tree_node_linker
  }

