'use strict';

var authControllers = angular.module('authControllers', []);

authControllers.controller('LoginController', ['$scope', '$http',
    function($scope, $http) {
        $scope.serverErrors = {login:'', pass:''};

        $scope.doLogin = function() {
            console.log($scope.loginForm);
            if (!$scope.loginForm.$invalid) {
                $http.post(
                    '/api/post.login',
                    {login: $scope.login, pass: $scope.pass}
                ).success(function(data) {
                        console.log(data);
                        if (data.status == 'success') {
                            return;
                        }
                        var commonErrors;
                        angular.forEach(data.errors, function(errors, field) {
                            commonErrors = [];
                            angular.forEach(errors, function(error, rule) {
                                commonErrors.push(error);
                            });
                            $scope.serverErrors[field] = commonErrors.join('. ');
                        });
                        console.log($scope.serverErrors);
                        //$scope.serverErrors = data.errors.join();
                    });
            }
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

authControllers.controller('TranslatesEditController',
    ['$scope', '$http', '$filter', 'translates', '$timeout',
    function($scope, $http, $filter, translates, $timeout) {
        $http.get('/api/get.allTranslates').success(function(data) {
            $scope.words = data;
            angular.forEach($scope.words, function(value, index) {
                angular.forEach(['key', 'val'], function(field) {
                    $scope.$watch('words['+index+'].'+field,
                        function(newVal, oldVal) {
                            if (newVal != oldVal) {
                                value.action = 'save'+ $filter('ucfirst')(field);
                                value.dirty = true;
                                $http
                                    .put('/api/put.setTranslate', value)
                                    .success(function(data) {
                                        translates.refreshTranslates($scope.words);
                                        $timeout(function() {
                                            value.dirty = false;
                                            $timeout(function() {
                                                value.dirty = true;
                                                $timeout(function() {
                                                    value.dirty = false;
                                                    value.saved = true;
                                                    $timeout(function() {
                                                        value.saved = false;
                                                    }, 2000);
                                                }, 500);
                                            }, 200);
                                        }, 200);
                                    })
                            }
                        }
                    );
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
    }]
);

authControllers.controller('CommonCtrl', ['$scope', '$http', 'translates',
    function($scope, $http, translates) {
        $scope.$watch(function () {
            return translates.getTranslates();
        }, function (newValue, oldValue) {
            if (newValue != oldValue) {
                $scope.translates = newValue;
            }
        }, true);
    }
]);

authControllers.factory('translates', ['$http', function($http) {
    var translates = {};
    var refreshCash = true;
    return {
        getTranslates: function () {
            if (!refreshCash) {
                return translates;
            } else {
                $http.get('/api/get.currentTranslates').success(function(data) {
                    translates = data;
                });
                refreshCash = false;
                return translates;
            }
        },
        refreshTranslates: function () {
            refreshCash = true;
        }
    };
}]);