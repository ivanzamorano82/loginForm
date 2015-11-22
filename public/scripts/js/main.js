'use strict';

var authControllers = angular.module('authControllers', []);

//authApp.config(function($interpolateProvider){
//    $interpolateProvider.startSymbol('[[').endSymbol(']]');
//});

authControllers.controller('LoginController', ['$scope', '$http',
    function($scope, $http) {
        $http.get('/api/get.translates').success(function(data) {
            $scope.translates = data;
        });

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
        //console.log($scope.signUp.fio);

        //jQuery('#signUp').formValidate({
        //    'login': ['required', 'alphaNumeric(en)', 'length(100)'],
        //    'password': ['range(6,20)'],
        //    'type': ['required']
        //});

        //$http.get('/api/signup').success(function(data) {
        //    console.log(data);
        //});

        $scope.doSignUp = function() {
            console.log('submit');
        };

        $scope.xxx = 'xxxxxx';
    }
]);
