$(function() {
    /**
     * Switches tabs for sign up and login.
     */
    $('.tabs a').click(function() {
        if (!$(this).is('.active_tab')) {
            $(this).addClass('active-tab').siblings().removeClass('active-tab');
            var rel = $(this).attr('rel');
            $(rel).show().parent().siblings('form').find('fieldset').hide();
        }
    });

    /**
     * Switches between authorization and restore tabs.
     */
    $('#fp_button').click(function() {
        $('.authorization').hide();
        $('.restore').show();
    });

    /**
     * Switches between restore and authorization tabs.
     */
    $('#back_login').click(function() {
        $('.restore').hide();
        $('.authorization').show();
    });

    /**
     * Initiates rules of validations for registration form.
     * And adds additional field "photo" in post.
     */
    $('#signUpForm').formValidate({
        fio:   ['required', 'alphabet', 'length(30)'],
        login: ['required', 'alphaNumeric(en)', 'length(20)'],
        email: ['required', 'email', 'length(100)'],
        pass: ['required', 'range(6,15)'],
        repeat_pass: ['required', 'matchWith(pass)']
    }, {
        photo: function() {
            var $dz = $('#drop-zone img');
            if (!$dz.length) {
                return '';
            }
            return encodeURIComponent($dz.attr('src'));
        }
    });

    /**
     * Initiates rules of validations for login form.
     */
    $('#loginForm').formValidate({
        login: ['required'],
        pass: ['required']
    });

    /**
     * Initiates rules of validations for restore password form.
     */
    $('#restoreForm').formValidate({
        email:['required', 'email']
    });

    /**
     * Initiates mask for phone input.
     */
    $('input[name="phone"]').maskInput('+__(___)___-__-__');

    /**
     * Prevents D&D in browser.
     */
    $(document).on('drop dragover', function (evt) {
        evt.stopPropagation();
        evt.preventDefault();
    });

    /**
     * Enters in D&D zone.
     */
    $(document).on('dragenter', '.photo-wrapp', function() {
        $(this).addClass('hover');
    });

    /**
     * Leaves in D&D zone.
     */
    $(document).on('dragleave', '.photo-wrapp', function() {
        $(this).removeClass('hover');
    });

    /**
     * Object of D&D.
     */
    var dropZone = document.getElementById('drop-zone');

    /**
     * Handler of D&D for dropping files.
     */
    dropZone.addEventListener('drop', function(evt) {
        var j = $(this);
        evt.stopPropagation();
        evt.preventDefault();
        var files = evt.dataTransfer.files;

        var file = files[0];

        var reader = new FileReader();

        /**
         * Successful downloading of file.
         * @param event
         */
        reader.onload = function(event) {

            var image = event.target.result;

            setTimeout(function() {
                App.changeImage(j, image);
            }, 500)

        };

        /**
         * Start of file downloading.
         */
        reader.onloadstart = function() {
            App.changeImage(j);
        };

        /**
         * Checks type and available size of downloading file.
         */
        var EM;
        if (file.type.split('/')[0] != 'image') {
            EM = new App.ErrorMessage($('#d_d'), 'error-block');
            EM.show(['Загружаемый файл не является изображением']);
            return false;
        }
        else if (false && file.size > App.options.maxUploadSizeFile * 1024 * 1024) {
            var mb = +App.options.maxUploadSizeFile;
            EM = new App.ErrorMessage($('#d_d'), 'error-block');
            EM.show(['Загружаемый файл превышает 2 MB']);
            return false;
        }
        else{
            reader.readAsDataURL(file);
        }

    }, false);

    App.getAllTranslates();
});
