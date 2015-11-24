'use strict';

/* Filters */

angular.module('authFilter',[]).filter('ucfirst', function() {
    return function(input) {
        return input.replace(/(?:^|\s)\S/g, function(a) { return a.toUpperCase(); });
    };
});