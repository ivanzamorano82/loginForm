'use strict';

var authControllers = angular.module('authControllers', []);

//authApp.config(function($interpolateProvider){
//    $interpolateProvider.startSymbol('[[').endSymbol(']]');
//});

authControllers.controller('LoginController', ['$scope', '$http',
    function($scope, $http) {
        console.log('lc controller...');
        //$http.get('/api/get.translates').success(function(data) {
        //    $scope.translates = data;
        //});

        //$scope.$watch("login_", function (newValue) {
        //    var login = $scope.loginForm.login,
        //        errors = [];
        //
        //    if (login.$error.required) {
        //        errors.push('Обязательное поле для заполнения.');
        //        $scope.errorMessage = 'Обязательное поле для заполнения';
        //    }
        //
        //    if (login.$error.pattern) {
        //        errors.push('Поле должно содержать только буквы [a-z]');
        //    }
        //    $scope.errorMessage = errors.join('. ')
        //});

        $scope.errorMessage = 'Обязательное поле для заполнения.';

        $scope.fio = '44444';

        $scope.doLogin = function() {
            console.log('login');
        };
    }
]);

authControllers.controller('SignUpController', ['$scope', '$http',
    function($scope, $http) {
        console.log('su controller...');
        $scope.doSignUp = function() {
            console.log('submit');
        };
    }
]);

authControllers.controller('TranslatesEditController', ['$scope', '$http',
    function($scope, $http) {
        console.log('te controller...');
        $http.get('/api/get.allTranslates').success(function(data) {
            $scope.words = data;
        });
        $http.get('/api/get.languages').success(function(data) {
            $scope.languages = data;
        });
        $scope.curLang = {code:'ru'};
    }
]);

authControllers.controller('CommonCtrl', ['$scope', '$http',
    function($scope, $http) {
        $http.get('/api/get.currentTranslates').success(function(data) {
            $scope.translates = data;
        });
    }
]);