$(function() {
    /**
     * Переключение закладок регистрации и авторизации
     */
    $('.tabs a').click(function() {
        if (!$(this).is('.active_tab')) {
            $(this).addClass('active-tab').siblings().removeClass('active-tab');
            var rel = $(this).attr('rel');
            $(rel).show().parent().siblings('form').find('fieldset').hide();
        }
    });

    /**
     * Initiates rules of validations for registration form.
     */
    $('#signUpForm').formValidate({
        //fio:   ['required', 'alphabet', 'length(10)'],
        //login: ['required', 'alphaNumeric(en)', 'length(10)'],
        //email: ['required', 'email', 'length(10)'],
        //pass: ['required', 'range(6,15)'],
        //repeat_pass: ['required', 'matchWith(pass)']
    }, {
        photo: function() {
            if (!$('#drop-zone img').length) {
                return '';
            }
            return encodeURIComponent($('#drop-zone img').attr('src'));
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
     * Initiates mask for phone input.
     */
    //$('input[name="phone"]').maskInput('+__(___)___-__-__');

    /**
     * запрещаем драг & дроп в браузере
     */
    $(document).on('drop dragover', function (evt) {
        evt.stopPropagation();
        evt.preventDefault();
    });

    /**
     * Вход в зону D&D
     */
    $(document).on('dragenter', '.photo-wrapp', function() {
        $(this).addClass('hover');
    });

    /**
     * Выход из зоны D&D
     */
    $(document).on('dragleave', '.photo-wrapp', function() {
        $(this).removeClass('hover');
    });

    /**
     * Объект дропзоны
     */
    var dropZone = document.getElementById('drop-zone');

    /**
     * Обработчик дропзоны изображения
     */
    dropZone.addEventListener('drop', function(evt) {
        var j = $(this);
        evt.stopPropagation();
        evt.preventDefault();
        var files = evt.dataTransfer.files;

        var file = files[0];

        var reader = new FileReader();

        /**
         * успешная загрузка изображения
         * @param event
         */
        reader.onload = function(event) {

            var image = event.target.result;

            setTimeout(function() {
                App.changeImage(j, image);
            }, 500)

        };

        /**
         * Начало загрузки изображения
         */
        reader.onloadstart = function() {
            App.changeImage(j);
        };

        /**
         * Проверка типа загружаемого файла и допустимого размера
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
