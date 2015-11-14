'use strict';

/**
 * Global object App.options
 * @type {{options: {translates: {}}}}
 */
window.App = {
    options: {
        maxUploadSizeFile: 1,
        loadingPicture: '/images/loading.gif'
    },
    translates: {}
};

App.changeImage = function (j, image) {
    if (image == undefined) {
        image = App.options.loadingPicture;
    }
    j.removeClass('hover');
    j.removeClass('error').find('.error-message').remove();
    var tag_img = j.find('img');
    if (tag_img.length > 0) {
        tag_img.attr('src', image);
    }
    else {
        j.append('<img src="' + image + '" class="vertical-center">');
    }
};

App.getAllTranslates = function() {
    $.ajax({
        url : '/api/get.translates',
        dataType : 'json',
        type : 'get',
        success : function(data) {
            App.translates = data;
        }
    });
};

App.getTranslate = function(word) {
    return this.translates[word] != undefined ? this.translates[word] : '';
};

/**
 * Describes class that manipulates the output of error notification for
 * specified form's element.
 *
 * @param {Object} element   jQuery object in DOM that has error message.
 * @param {string} classOfErrorBlock   Class of error block.
 *
 * @constructor   Creates an instance of ErrorMessage.
 */
App.ErrorMessage = function(element, classOfErrorBlock) {
    if (element != undefined) {
        /**
         * Parent element of specified field.
         *
         * @type {Object}
         *
         * @access private
         */
        var $parentOfField = element.parent();

        /**
         * Html block with class "error-block" in DOM that is inside of
         * closest "form-group" block.
         *
         * @type {Object}
         *
         * @access private
         */
        var $errorBlock = $parentOfField.find('.' + classOfErrorBlock);
    }
    /**
     * Html template of error block.
     *
     * @type {string}
     * @access public
     */
    this.errorHtmlBlock = '';

    /**
     * Generates html block with error messages for inserting into DOM.
     *
     * @param {string[]} errorMessages   List of error messages.
     *
     * @access public
     */
    this.setMessage = function(errorMessages) {
        this.errorHtmlBlock = '' +
        '<div class="'+classOfErrorBlock+'">'+
        errorMessages.join('. ')+'.'+
        '</div>';
    };

    /**
     * Removes current html element with error messages from the DOM
     * if it exists.
     *
     * @access public
     */
    this.remove = function() {
        if ($errorBlock.length) {
            $errorBlock.remove();
            $parentOfField.removeClass('error');
        }
    };

    /**
     * Inserts html block with passed error messages into DOM.
     * And removes old block with error messages before inserting
     * if it exists.
     *
     * @param {string[]} errorMessages   List of error messages for one
     *                                   form field.
     *
     * @access public
     */
    this.show = function(errorMessages) {
        this.setMessage(errorMessages);
        this.remove();
        $parentOfField.append(this.errorHtmlBlock).addClass('error');
    };

    /**
     * Inserts html block with passed common error messages into DOM and
     * displays on the top of window.
     * Removes old block with error messages before inserting.
     *
     * @param {{string[]}} commonErrors   List of common error messages for
     *                                    hidden or nonexistent fields.
     *
     * @access public
     */
    this.showOnTop = function(commonErrors) {
        var $errorBlock = '<div class="error-ontop error-block"><span>';
        $.each(commonErrors, function (field, errors) {
            $errorBlock += field+': '+errors.join('. ')+'<br>';
        });
        $errorBlock += '</span></div>';
        $('.error-ontop').remove();
        $('body').append($errorBlock);
    }
};
