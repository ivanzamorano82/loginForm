/**
 * Created by ivanzamorano
 */

(function($) {

    /**
     * Расширяем класс String, для возможности замены символа в строке по его позиции
     * @param index
     * @param character
     * @returns {string}
     */
    String.prototype.replaceAt=function(index, character) {
        return this.substr(0, index) + character + this.substr(index+character.length);
    };

    /**
     * позиции пустых мест, которые будут заполнятся
     * @type {Array}
     */
    var maska_positions = [];

    /**
     * Поиск следующего ближайшего пустого места относительно указанной позиции
     * @param pos
     * @returns {*}
     */
    var findNearNext = function(pos) {
        for(var i=0; i < maska_positions.length; i++) {
            if (maska_positions[i] >= pos) return maska_positions[i];
        }
        return false;
    };

    /**
     * Поиск предыдущего ближайшего пустого места относительно указанной позиции
     * @param pos
     * @returns {*}
     */
    var findNearPrev = function(pos) {
        for(var i=maska_positions.length; i >= 0; i--) {
            if (maska_positions[i] < pos) return maska_positions[i];
        }
        return false;
    };

    /**
     * Основной обработчик
     * @param maska
     * @returns {*}
     */
    $.fn.maskInput = function(maska) {

        for(var i = 0; i < maska.length; i++) {
            if (maska[i] == '_') {
                maska_positions.push(i);
            }
        }

        var make = function() {
            // событие фокусировки инпута
            $(this).focusin(function() {
                var obj = $(this);

                var regExp = /_/;
                if (regExp.test(obj.val()) || obj.val()=='') {
                    // вписываем текст в инпут соответствующий маске
                    obj.val(maska);
                    obj[0].setSelectionRange(maska_positions[0], maska_positions[0]+1);
                }
                return false;
            });

            // событие потери фокуса
            $(this).focusout(function() {
                var obj = $(this);

                var regExp = /_/;
                // очищаем текст в инпуте, если не все свободные места маски заполнены
                if (regExp.test(obj.val())) {
                    obj.val('');
                }
            });

            $(this).keydown(function(e) {
                //console.log(e.which);
                var obj = $(this);
                var slovo  = obj.val();
                var position = obj[0].selectionStart; //стартовая позиция выделения
                var position2 = obj[0].selectionEnd; //конечная позиция выделения
                var next_char = slovo[position];
                var fn;

                // нажатие на цифровые клавиши основные либо боковые
                if ( (e.which >= 48 && e.which <= 57) || (e.which >= 96 && e.which <= 105) ) {
                    fn = findNearNext(position);

                    // если боковые клавиши отнимаем -48 чтобы получить коды основных
                    var key =String.fromCharCode((e.which >= 96 && e.which <= 105)? e.which-48 : e.which);

                    // если есть следующее пустое место заменяем на введенную цифру
                    if (fn) {
                        obj.val(slovo.replaceAt(fn, key));
                        obj[0].setSelectionRange(fn+1, fn+1);
                    }

                    return false;

                }
                else if (e.which == 8) { // нажатие бекспейса
                    // если выделен весь текст в инпуте - удаляем все заполненные места
                    if (position == 0 && position2 == slovo.length) {
                        obj.val(maska);
                        obj[0].setSelectionRange(maska_positions[0], maska_positions[0]);
                        return false;
                    }

                    // находим ближайшее место для удаления (замену на символ "_")
                    fn = findNearPrev(position);
                    if (fn) {
                        obj.val(slovo.replaceAt(fn, '_'));
                        obj[0].setSelectionRange(fn, fn);
                    }
                    return false;
                }
                else if (e.which == 37 || e.which == 39 || e.which == 9) {
                    // ничего не делаем при нажатии на стрелки влево-вправо либо таб
                }
                else{
                    return false; // нажатие остольных клавиш блокируем
                }
            });

        };

        return this.each(make);
    };

})(jQuery);
