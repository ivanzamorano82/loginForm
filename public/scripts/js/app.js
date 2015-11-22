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
            //when('/phones/:phoneId', {
            //    templateUrl: 'partials/phone-detail.html',
            //    controller: 'PhoneDetailCtrl'
            //}).
            otherwise({
                redirectTo: '/login'
            });
    }
]);