'use strict';

var authApp = angular.module('authApp', []);

authApp.config(function($interpolateProvider){
    $interpolateProvider.startSymbol('[[').endSymbol(']]');
});

authApp.controller('SignUpController', ['$scope', '$http',
    function($scope, $http) {
        console.log('init signUp controller');

        $scope.$watch("value", function (newValue) {
            console.log($scope.signUp);
        });

        $scope.fio = '44444';
        //console.log($scope.signUp.fio);

        //jQuery('#signUp').formValidate({
        //    'login': ['required', 'alphaNumeric(en)', 'length(100)'],
        //    'password': ['range(6,20)'],
        //    'type': ['required']
        //});

        $http.get('/api/signup').success(function(data) {
            console.log(data);
        });

        $scope.doSignUp = function() {
            console.log('submit');
            return false;
        };

        $scope.xxx = 'xxxxxx';
    }
]);


var test = angular.module('testModule', []);

//test.
