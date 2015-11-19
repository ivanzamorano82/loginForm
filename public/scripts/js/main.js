'use strict';

var authApp = angular.module('authApp', []);

authApp.config(function($interpolateProvider){
    $interpolateProvider.startSymbol('[[').endSymbol(']]');
});

authApp.controller('LoginController', ['$scope', '$http',
    function($scope, $http) {
        console.log('init login controller');

        $scope.$watch("login_", function (newValue) {
            var login = $scope.loginForm.login,
                errors = [];

            if (login.$error.required) {
                errors.push('Обязательное поле для заполнения.');
                $scope.errorMessage = 'Обязательное поле для заполнения';
            }

            if (login.$error.pattern) {
                errors.push('Поле должно содержать только буквы [a-z]');
            }
            $scope.errorMessage = errors.join('. ')
        });

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

authApp.controller('Ctrl', ['$scope', function($scope) {
    console.log('asdf');
    $scope.list = [];
    $scope.text = 'hello';
    $scope.submit = function() {
        if (this.text) {
            this.list.push(this.text);
            this.text = '';
        }
    };
}]);

var test = angular.module('testModule', []);

//test.
