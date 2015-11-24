'use strict';

var authControllers = angular.module('authControllers', []);

//authApp.config(function($interpolateProvider){
//    $interpolateProvider.startSymbol('[[').endSymbol(']]');
//});

authControllers.controller('LoginController', ['$scope', '$http',
    function($scope, $http) {
        $scope.doLogin = function() {
            console.log('login');
        };
    }
]);

authControllers.controller('SignUpController', ['$scope', '$http',
    function($scope, $http) {
        $scope.doSignUp = function() {
            console.log('submit');
        };
    }
]);

authControllers.controller('TranslatesEditController', ['$scope', '$http',
    function($scope, $http) {
        $http.get('/api/get.allTranslates').success(function(data) {
            $scope.words = data;
            angular.forEach($scope.words, function(value, index) {
                $scope.$watch('words['+index+'].key', function(newVal, oldVal) {
                    if (newVal != oldVal) {
                        console.log(newVal, oldVal, value, index);
                        value.oldKey = oldVal;
                        $http.put('/api/put.setTranslate', value)
                            .success(function(data) {
                                console.log(data)
                            })
                    }
                });
            });
        });
        $http.get('/api/get.languages').success(function(data) {
            $scope.languages = data;
        });
        $scope.curLang = {langCode:'ru'};
        $scope.doEditTranslates = function() {
            console.log($scope.filteredWords);
        };



    }
]);

authControllers.controller('CommonCtrl', ['$scope', '$http',
    function($scope, $http) {
        $http.get('/api/get.currentTranslates').success(function(data) {
            $scope.translates = data;
        });
    }
]);