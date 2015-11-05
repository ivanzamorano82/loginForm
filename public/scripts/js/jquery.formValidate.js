(function($) {

    /**
     * The class name of the element surrounding the form element.
     *
     * @type {string}
     */
    var classOfElementWrapper = 'element-wrapper';

    /**
     * The class name of error's block.
     * @type {string}
     */
    var classOfErrorBlock = 'error-block';

    /**
     * Performs Ajax post request with data from specified form
     * and processes response.
     *
     * @param {jQuery} $form  Specified form element.
     */
    var runSubmit = function ($form) {
        var $button = $form
            .find('button[type="submit"] i');
        $.ajax({
            url : $form.attr('action'),
            data : $form.serialize(),
            dataType : 'json',
            type : 'post',
            beforeSend: function() {
                $button
                    .addClass('loading')
                    .removeClass('save');
            },
            success : function(res) {
                if (res.data.status == 'success') {
                    $button
                        .removeClass('loading')
                        .addClass('saved');
                    setTimeout(function() {
                        $button
                            .removeClass('saved')
                            .addClass('save');
                    }, 2000);
                    return;
                }
                $button
                    .removeClass('loading')
                    .addClass('save');

                var commonErrors = {};
                $.each(res.data.errors, function (field, errors) {
                    var errorMessages = [];
                    var $element = $('[name^="'+field+'"]');
                    $.each(errors, function (error, message) {
                        errorMessages.push(message);
                    });
                    if ($element.length
                        && $element.attr('type') != 'hidden'
                    ) {
                        var EM = new ErrorMessage($form, field);
                        EM.show(errorMessages);
                    } else {
                        commonErrors[field] = errorMessages;
                    }
                });

                if (!$.isEmptyObject(commonErrors)) {
                    var EM = new ErrorMessage($form);
                    EM.showOnTop(commonErrors);
                }
            },
            error: function(xhr) {
                if (xhr.status == 301) {
                    location.href = xhr.responseJSON.redirectUrl;
                } else {
                    var errorMessage = 'Error: '+
                        xhr.status+' => '+
                        xhr.statusText;
                    alert(errorMessage);
                    $button
                        .removeClass('loading')
                        .addClass('save');
                }
            }
        });
    };

    /**
     * Makes a string's first character uppercase.
     *
     * @param str   The input string.
     *
     * @returns {string}   Returns a string with the capitalized first character
     *                     if it is alphabetic.
     */
    var ucfirst = function ucfirst(str) {
        var f = str.charAt(0).toUpperCase();
        return f + str.substr(1, str.length-1);
    };


    /**
     * Describes class that manipulates the output of error notification for
     * specified form's element.
     *
     * @param form        Form that contain required elements.
     * @param fieldName   Name of element that has error message.
     *
     * @constructor   Creates an instance of ErrorMessage.
     */
    var ErrorMessage = function(form, fieldName) {
        /**
         * Parent element of specified field.
         *
         * @type {Object}
         *
         * @access private
         */
        var $parentOfField = form.
            find('[name^="'+fieldName+'"]')
            .closest('div');

        /**
         * Closest html block with class "form-group" in DOM
         * for specified field.
         *
         * @type {Object}
         *
         * @access private
         */
        var $closestFormGroup = form
            .find('[name^="'+fieldName+'"]')
            .closest('.'+classOfElementWrapper);

        /**
         * Html block with class "error-block" in DOM that is inside of
         * closest "form-group" block.
         *
         * @type {Object}
         *
         * @access private
         */
        var $errorBlock = $closestFormGroup.find('.'+classOfErrorBlock);

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
                $closestFormGroup.removeClass('error');
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
            $parentOfField
                .append(this.errorHtmlBlock);
            $closestFormGroup.addClass('error');
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
            var $errorBlock = '<div class="error-ontop">';
            $.each(commonErrors, function (field, errors) {
                $errorBlock += field+': '+errors.join('. ')+'<br>';
            });
            $errorBlock += '</div>';
            $('.error-ontop').remove();
            $('body').append($errorBlock);
        }
    };

    /**
     * Describes class that validates one field of form.
     *
     * @constructor   Creates an instance of FormValidator.
     */
    function FormValidator() {
        /**
         * List of errors occurred during validation.
         *
         * @type {Array}
         *
         * @access private
         */
        var errors = [];

        /**
         * Error messages mapping in format:  error code => error message.
         * Message can contain placeholders in format "{([^{]*)('+i+')([^}]*)}"
         * for replacing to some value,
         * where "i" - number of replaceable placeholder.
         *
         * @type {{string}}
         *
         * @access private
         */
        var errorMessages = {  // TODO: must be moved in next task
            required: 'Обязательное поле для заполнения',
            alphabet: 'Поле должно содержать только {"0"} буквы',
            alphaNumeric: 'Поле должно содержать только {"0"} буквы либо цифры',
            numeric: 'Поле должно содержать только числа',
            matchWith: 'Не совпадает с полем {"0"}',
            length: 'Не более {0} символов',
            range: 'Диапазон от {0} до {1} символов',
            phone: 'Телефон имеет некорректный формат',
            email: 'Email иммеет некорректный формат',
            enum: 'Недопустимый набор элементов',
            match: 'Не соответствует заданному паттерну {0}'
        };

        /**
         * Adds error message into list of current errors.
         *
         * @param error     Code of error.
         * @param args      Arguments for placeholders replacement in message.
         * @param message   Optional error message.
         *
         * @access public
         */
        this.setErrorMessage = function(error, args, message) {
            if (message != undefined) {
                errors.push(message);
            } else if (errorMessages[error] != undefined) {
                errors.push(sprintf(errorMessages[error], args));
            }  else {
                errors.push('Неизвестная ошибка');
            }
        };

        /**
         * Returns formatted string.
         *
         * @param {string} str      String that must be formatted.
         * @param {string[]} args   Arguments for replacing of placeholders.
         *
         * @access private
         *
         * @returns {string}   Formatted string filled with passed arguments.
         */
        var sprintf = function(str, args) {
            if (args.length) {
                var regExp;
                for (var i = 0; i < args.length; i++) {
                    regExp = new RegExp('{([^{]*)('+i+')([^}]*)}');
                    str = str.replace(regExp, '$1'+args[i]+'$3');
                }
            } else {
                str = str.replace(/{[^{]*\d+[^}]*}/, '');
            }
            return str;
        };

        /**
         * Returns full list of errors occurred during validation.
         *
         * @access public
         *
         * @returns {Array}   List of errors.
         */
        this.getErrors = function() {
            return errors;
        };

        /**
         * Checks that current value of form element is not empty.
         * Or checks that at least one element of the set is marked
         * in case when current element is "checkbox" or "radio".
         *
         * @returns {boolean}   Result of validation.
         */
        this.isRequired = function() {
            if (this.length > 1) {
                return $(this).is(':checked');
            } else {
                return (this.val().length) ? true : false;
            }
        };

        /**
         * Checks that current value (string) contains only alphabetical symbols
         * or spaces or underscores given the language of string (russian - "ru"
         * or english - "en").
         *
         * @param {string} lang   Allowed language if it is defined. Otherwise
         *                        russian and english language are allowed.
         *
         * @returns {boolean}   Result of validation.
         */
        this.isAlphabet = function(lang) {
            var regExp;
            if (lang === 'ru') {
                regExp = /^[а-яА-Я_\s]*$/i;
            } else if (lang === 'en') {
                regExp = /^[a-zA-Z_\s]*$/i;
            } else {
                regExp = /^[a-zA-Zа-яА-Я_\s]*$/i;
            }
            return regExp.test(this.val());
        };

        /**
         * Checks that current value contains only alphabetical or numeric
         * symbols or spaces or underscores given the language of string
         * (russian - "ru" or english - "en").
         *
         * @param {string} lang   Allowed language if it is defined. Otherwise
         *                        russian and english language are allowed.
         *
         * @returns {boolean}   Result of validation.
         */
        this.isAlphaNumeric = function(lang) {
            var regExp;
            if (lang === 'ru') {
                regExp = /^[а-яА-Я_\s\d]*$/i;
            } else if (lang === 'en') {
                regExp = /^[a-zA-Z_\s\d]*$/i;
            } else {
                regExp = /^[a-zA-Zа-яА-Я_\s\d]*$/i;
            }
            return regExp.test(this.val());
        };

        /**
         * Checks that current value contains only numeric symbols
         * or spaces or points.
         *
         * @returns {boolean}   Result of validation.
         */
        this.isNumeric = function() {
            return /^[\d\s\.]*$/.test(this.val());
        };

        /**
         * Checks that length of current value isn't longer of allowed length.
         *
         * @param {Number} length   Allowed length of string.
         */
        this.isLength = function(length) {
            if (length == undefined) {
                return false;
            }
            return (this.val().length <= length);
        };

        /**
         * Checks that length of current value is within of allowed range.
         *
         * @param {Number} from   Start of allowed range.
         * @param {Number} to     End of allowed range.
         *
         * @returns {boolean}  Result of validation.
         */
        this.isRange = function(from, to) {
            if (from == undefined || to == undefined) {
                return false;
            }
            return (this.val() === ''
                || (this.val().length >= from && this.val().length <= to)
            );
        };

        /**
         * Checks that current value matches with value of another form's field.
         *
         * @param {string} name   Name of another form's field.
         *
         * @returns {boolean}  Result of validation.
         */
        this.isMatchWith = function(name) {
            var match_value = $(this).closest('form')
                .find('[name="'+name+'"]').val();
            return (match_value === this.val());
        };

        /**
         * Checks that current value matches to the specified regular
         * expression.
         *
         * Comment: passed backslashes (\) in regular expression must be
         * escaped otherwise JS will remove its from the string.
         *
         * @param {string} pattern   String with regular expression and flags.
         *
         * @returns {boolean}   Result of validation.
         */
        this.isMatch = function(pattern) {
            // Split pattern at regular expression and flags.
            var matches = pattern.match(/^\/(.*)\/(\w*)$/);
            var flag = (matches[2] != undefined) ? matches[2] : '';
            if (matches[1] != undefined) {
                pattern = matches[1];
            }
            var regExp = new RegExp(pattern, flag);
            return regExp.test(this.val());
        };

        /**
         * Checks for valid email.
         *
         * @returns {boolean}  Result of validation.
         */
        this.isEmail = function() {
            if (this.val() === '') {
                return true;
            }
            var regExp = /^[a-z0-9_\.-]{1,40}@[a-z0-9\.-]{1,30}\.[a-z]{2,4}$/i;
            return regExp.test(this.val());
        };
    }

    /**
     * jQuery Plugin for validation of form's element.
     * Main handler of plugin.
     *
     * @external "jQuery.fn"
     * @see {@link http://learn.jquery.com/plugins/|jQuery Plugins}
     *
     * @returns {*}
     */
    $.fn.formValidate = function(options) {
        options = (options != undefined) ? options : {};

        /**
         * Add event handlers for all matched forms in the DOM.
         */
        var make = function() {
            /**
             * Current form.
             *
             * @type {Object}
             */
            var $form = $(this);

            /**
             * The event handler for submit of form.
             * Runs validation process.
             */
            $form.submit(function() {
                var isAllRight = true;

                $.each(options, function (fieldName, rules) {
                    var $field =  $form.find('[name^="'+fieldName+'"]');
                    var Validator = new FormValidator();

                    $.each(rules, function( index, rule ) {
                        var match = rule.match(/^([\w\d]+)(\((.+)\))?$/);
                        var errorCode = match[1];
                        var method = 'is'+ucfirst(match[1]);
                        var args = [];
                        if (match[3] != undefined) {
                            if (/^re:/.test(match[3])) {
                                args = [match[3].replace('re:', '')];
                            } else {
                                args = match[3].split(',');
                            }
                        }
                        if (typeof Validator[method] == 'function') {
                            if (!Validator[method].apply($field, args)) {
                                Validator.setErrorMessage(errorCode, args);
                            }
                        } else {
                            Validator.setErrorMessage(
                                errorCode, args, 'Неверный валидатор'
                            );
                        }
                    });

                    if (Validator.getErrors().length) {
                        var EM = new ErrorMessage($form, fieldName);
                        EM.show(Validator.getErrors());
                        isAllRight = false;
                    }
                });

                if (isAllRight) {
                    runSubmit($form);
                }
                return false;
            });

            /**
             * The event handler for focus in the form's input.
             */
            $form.find('input').focus(function() {
                var $form = $(this).closest('form');
                var $field = $(this).attr('name');
                var EM = new ErrorMessage($form, $field);
                EM.remove();
            });

            /**
             * The event handler for hover on the error blocks.
             */
            $(document).on('mouseenter', '.'+classOfErrorBlock, function() {
                var $form = $(this).closest('form');
                var $field = $(this)
                    .closest('.'+classOfElementWrapper)
                    .find('[name]')
                    .attr('name');
                var EM = new ErrorMessage($form, $field);
                EM.remove();
            });
        };

        return this.each(make);
    };

})(jQuery);
