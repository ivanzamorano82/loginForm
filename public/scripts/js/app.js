'use strict';

/* App module */

var authApp = angular.module('authApp', [
    'ngRoute',
    'authControllers'
]);

//authApp.config(function($interpolateProvider){
//    $interpolateProvider.startSymbol('[[').endSymbol(']]');
//});

authApp.config(['$routeProvider',
    function($routeProvider) {
        $routeProvider.
            when('/login', {
                templateUrl: 'partials/login.html',
                controller: 'LoginController'
            }).
            when('/signUp', {
                templateUrl: 'partials/signUp.html',
                controller: 'SignUpController'
            }).
            when('/translatesEdit', {
                templateUrl: 'partials/translatesEdit.html',
                controller: 'TranslatesEditController'
            }).
            otherwise({
                redirectTo: '/login'
            });
    }
]);