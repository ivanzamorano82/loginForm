-- phpMyAdmin SQL Dump
-- version 4.2.8
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Ноя 16 2015 г., 12:15
-- Версия сервера: 5.6.24
-- Версия PHP: 5.6.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `login_form`
--

-- --------------------------------------------------------

--
-- Структура таблицы `langs`
--

CREATE TABLE IF NOT EXISTS `langs` (
`id` int(10) unsigned NOT NULL,
  `title` varchar(45) NOT NULL,
  `code` varchar(2) NOT NULL,
  `is_main` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `langs`
--

INSERT INTO `langs` (`id`, `title`, `code`, `is_main`) VALUES
(1, 'Українська', 'ua', 0),
(2, 'Русский', 'ru', 1),
(3, 'English', 'en', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `options`
--

CREATE TABLE IF NOT EXISTS `options` (
`id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `value` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `options`
--

INSERT INTO `options` (`id`, `name`, `value`) VALUES
(1, 'maxUploadSizeFile', '2');

-- --------------------------------------------------------

--
-- Структура таблицы `translates`
--

CREATE TABLE IF NOT EXISTS `translates` (
  `lang_id` int(10) unsigned NOT NULL,
  `word_id` int(10) unsigned NOT NULL,
  `value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `translates`
--

INSERT INTO `translates` (`lang_id`, `word_id`, `value`) VALUES
(1, 2, 'Вхід'),
(1, 3, 'ФІО'),
(1, 4, 'Телефон'),
(1, 5, 'Email'),
(1, 6, 'Пароль'),
(1, 8, 'Повторити пароль'),
(1, 9, 'Зареєструватися'),
(1, 10, 'Логін'),
(1, 11, 'Мова інтерфейсу'),
(1, 12, 'Увійти'),
(1, 13, 'Ви ввели невірні дані'),
(1, 14, 'Фото'),
(1, 15, 'Профіль користувача'),
(1, 16, 'Вийти'),
(1, 17, 'Перетягніть в цю зону зображення для завантаження'),
(1, 18, 'Сторінка авторизації та реєстрації'),
(1, 19, 'Сторінка профілю користувача'),
(1, 20, 'Реєстрація'),
(2, 2, 'Вход'),
(2, 3, 'ФИО'),
(2, 4, 'Телефон'),
(2, 5, 'Email'),
(2, 6, 'Пароль'),
(2, 8, 'Повторить пароль'),
(2, 9, 'Зарегестрироваться'),
(2, 10, 'Логин'),
(2, 11, 'Язык интерфейса'),
(2, 12, 'Войти'),
(2, 13, 'Вы ввели неверные данные'),
(2, 14, 'Фото (перетащить)'),
(2, 15, 'Профиль пользователя'),
(2, 16, 'Выйти'),
(2, 17, 'Перетащите в эту зон изображение для загрузки'),
(2, 18, 'Страница авторизации и регистрации'),
(2, 19, 'Страница профиля пользователя'),
(2, 20, 'Регистрация'),
(2, 21, 'Обязательное поле для заполнения++'),
(2, 22, 'Поле должно содержать только {"0"} буквы ++'),
(2, 23, 'Недопустимое поле++'),
(2, 24, 'Неизвестный валидатор++'),
(2, 25, 'Введенный логин уже существует++'),
(2, 26, 'Введенный email уже существует ++'),
(2, 27, '← Вернуться'),
(2, 28, 'Для востановления пароля введите свой email!'),
(2, 29, 'Восстановить пароль'),
(2, 30, 'Вы ввели несуществующий логин'),
(2, 35, 'Неверный пароль'),
(2, 36, 'Вы ввели несуществующий email'),
(3, 2, 'Login'),
(3, 3, 'Name'),
(3, 4, 'Phone'),
(3, 5, 'Email'),
(3, 6, 'Password'),
(3, 8, 'Repeat password'),
(3, 9, 'Sign Up'),
(3, 10, 'Login'),
(3, 11, 'Languages'),
(3, 12, 'Login'),
(3, 13, 'You entered the wrong data'),
(3, 14, 'Drag&Drop'),
(3, 15, 'User profile of'),
(3, 16, 'Logout'),
(3, 17, 'Drag and drop image into this zone for download'),
(3, 18, 'Registration and Authorization pages'),
(3, 19, 'The User profile page'),
(3, 20, 'Signup');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`id` int(10) unsigned NOT NULL,
  `fio` varchar(255) NOT NULL,
  `login` varchar(255) NOT NULL,
  `loginHash` char(32) NOT NULL,
  `phone` varchar(255) DEFAULT '',
  `email` varchar(255) NOT NULL,
  `emailHash` char(32) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `photo` varchar(20) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `fio`, `login`, `loginHash`, `phone`, `email`, `emailHash`, `pass`, `photo`) VALUES
(1, 'XP7NuVxgRyGqrahJvb/v+HszFgDnv8DpERwc7fqwoNbaU08GbSQ=', '+ANuf70=', '016f2006ebe2f49da9bef6c9b61424f7', 'p1cvI73iprW4TwS0OlxyHfA=', '+ANuf72Q+/q4U0fw', '453840716f98bb76130b93b55e08617d', '$2y$12$X1nNUTIxiJC.YXSsW05w6ezrH0p83Wa1GD05U4TbM76ZmFL0FLpP6', '5649853006ab8.png'),
(2, '4JuksmXJyQCVqY8wW2o=', 'RGwHVoY=', '7b867e6a00148d2069330f4047e0360a', '', 'RGwHVoYIdd53PCjh', '888df76e0279b617383be0cfd98c045a', '$2y$12$14BpKIDNhasogPG76DGzP.aEfraC6SGektAlxij8dUeWZ.qEJPIJC', '5649b564a5943.png');

-- --------------------------------------------------------

--
-- Структура таблицы `words`
--

CREATE TABLE IF NOT EXISTS `words` (
`id` int(10) unsigned NOT NULL,
  `key` varchar(45) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `words`
--

INSERT INTO `words` (`id`, `key`) VALUES
(12, 'button_login'),
(9, 'button_registration'),
(29, 'button_restore'),
(14, 'drag_drop'),
(22, 'error_alphabet'),
(26, 'error_availableEmail'),
(25, 'error_availableLogin'),
(23, 'error_common'),
(35, 'error_correctPassword'),
(36, 'error_existingEmail'),
(30, 'error_existingLogin'),
(13, 'error_incorrect_data'),
(21, 'error_required'),
(24, 'error_unknownvalidator'),
(17, 'form_dz_title'),
(5, 'form_email'),
(10, 'form_login'),
(3, 'form_name'),
(6, 'form_pass'),
(4, 'form_phone'),
(8, 'form_repeat_pass'),
(27, 'link_back'),
(18, 'meta_title_index'),
(19, 'meta_title_profile'),
(16, 'profile_logout'),
(15, 'profile_title'),
(28, 'text_restore'),
(11, 'title_lang_of_interface'),
(2, 'title_login'),
(20, 'title_registration');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `langs`
--
ALTER TABLE `langs`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `code_UNIQUE` (`code`);

--
-- Индексы таблицы `options`
--
ALTER TABLE `options`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `name` (`name`);

--
-- Индексы таблицы `translates`
--
ALTER TABLE `translates`
 ADD PRIMARY KEY (`lang_id`,`word_id`), ADD KEY `fk_translate_words_idx` (`word_id`), ADD KEY `fk_words_langs_idx` (`lang_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `login_UNIQUE` (`login`), ADD UNIQUE KEY `email_UNIQUE` (`email`);

--
-- Индексы таблицы `words`
--
ALTER TABLE `words`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `key_UNIQUE` (`key`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `langs`
--
ALTER TABLE `langs`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `options`
--
ALTER TABLE `options`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `words`
--
ALTER TABLE `words`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=37;
--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `translates`
--
ALTER TABLE `translates`
ADD CONSTRAINT `fk_translate_words` FOREIGN KEY (`word_id`) REFERENCES `words` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `fk_translates_langs` FOREIGN KEY (`lang_id`) REFERENCES `langs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
