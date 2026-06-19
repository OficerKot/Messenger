-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Июн 19 2026 г., 06:36
-- Версия сервера: 8.0.30
-- Версия PHP: 8.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `Social`
--

-- --------------------------------------------------------

--
-- Структура таблицы `complains`
--

CREATE TABLE `complains` (
  `complain_id` int NOT NULL,
  `sender_id` int NOT NULL,
  `target_user_id` int NOT NULL,
  `complain_type_id` int NOT NULL,
  `additional_message` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `complain_types`
--

CREATE TABLE `complain_types` (
  `complain_type_id` int NOT NULL,
  `complain` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `direct_message`
--

CREATE TABLE `direct_message` (
  `message_id` int NOT NULL,
  `user1_ID` int NOT NULL,
  `user2_ID` int NOT NULL,
  `message` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `friends`
--

CREATE TABLE `friends` (
  `friendship_id` int NOT NULL,
  `user_id` int NOT NULL,
  `friend_id` int NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `friends`
--

INSERT INTO `friends` (`friendship_id`, `user_id`, `friend_id`, `status`, `date`) VALUES
(9, 8, 7, 'pending', '2026-06-16'),
(10, 7, 9, 'pending', '2026-06-16'),
(11, 8, 9, 'pending', '2026-06-16'),
(12, 10, 7, 'pending', '2026-06-16'),
(13, 8, 10, 'pending', '2026-06-18'),
(14, 1, 2, 'pending', '2026-06-18');

-- --------------------------------------------------------

--
-- Структура таблицы `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int NOT NULL,
  `receiver_id` int NOT NULL,
  `sender_id` int NOT NULL,
  `message` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `notifications`
--

INSERT INTO `notifications` (`notification_id`, `receiver_id`, `sender_id`, `message`, `type`, `is_read`, `created_at`) VALUES
(1, 9, 8, 'отправил(а) вам заявку в друзья', 'friend_request', 1, '2026-06-16 10:33:56'),
(2, 7, 10, 'отправил(а) вам заявку в друзья', 'friend_request', 1, '2026-06-16 10:44:18'),
(3, 10, 8, 'отправил(а) вам заявку в друзья', 'friend_request', 1, '2026-06-18 00:51:48'),
(4, 2, 1, 'отправил(а) вам заявку в друзья', 'friend_request', 1, '2026-06-18 09:17:35');

-- --------------------------------------------------------

--
-- Структура таблицы `posts`
--

CREATE TABLE `posts` (
  `post_id` int NOT NULL,
  `autor_id` int NOT NULL,
  `wall_owner_id` int NOT NULL,
  `message` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `posts`
--

INSERT INTO `posts` (`post_id`, `autor_id`, `wall_owner_id`, `message`, `image_path`, `date`) VALUES
(1, 2, 2, 'hello\r\n', NULL, '2026-06-18 09:42:51'),
(2, 2, 2, 'admin', NULL, '2026-06-18 10:52:14'),
(3, 2, 2, 'ю', NULL, '2026-06-18 10:54:48'),
(4, 2, 2, 'где', NULL, '2026-06-18 10:55:05'),
(5, 2, 2, '', NULL, '2026-06-18 10:59:09'),
(6, 2, 2, 'всем добрый день я опять сломал сайт', NULL, '2026-06-18 10:59:44'),
(7, 2, 1, 'Добрый день, удаляйте аккаунт', NULL, '2026-06-18 12:36:15'),
(8, 2, 1, '', '6a33bc2948a6f_maxwell.gif', '2026-06-18 12:36:41'),
(10, 1, 1, 'ДОБРЫЙ ВЕЧЕР!!!!!! ПОКА НЕ ПЛАНИРУЮ!! 😁😁😁👍👍👍👍🎶🎶🎶', NULL, '2026-06-18 15:13:41'),
(11, 1, 1, 'ЗДРАВСТВУЙТЕ?!?', NULL, '2026-06-18 15:28:13'),
(12, 1, 1, 'ПОДПИСЧИКИ ВСЕМ ПРИВЕТ!! ДОБРОГО СЧАСТЛИВОГО ВЕЧЕРА 🙌🙌🙌😉😉😉😉 Я СЕГОДНЯ НА УЖИН ЕЛА ЧЕЧЕВИЦУ И ОНА МЕНЯ ПОРАДОВАЛА 🤗🤗🤗 СВОЕЙ СТРУКТУРОЙ И ВКУСОМ 😛😛😛 А ПОТОМ Я ЕЙ ПОДАВИЛАСЬ И ЧУТЬ НЕ ЗАДОХНУЛАСЬ 🤣🤣🤣🤣😂😂😂 ВСЕМ ДОБРОГО ВЕЧЕРА ЗДОРОВЬЯ И СЧАСТЬЯ!!!!', '6a33ef1b3770f_dobryj-vecher.gif', '2026-06-18 15:31:31'),
(14, 12, 12, 'Добрый день.', NULL, '2026-06-18 16:06:19'),
(17, 1, 2, 'ДОБРОЕ УТРО!!! 🤗🤗🤣🤣😉😉', '6a34851880c99_dobroe-utro.gif', '2026-06-19 02:52:04'),
(18, 1, 1, 'ВСЕМ УТРА! УТРО НЕ ДОБРОЕ - ЗАКОНЧИЛОСЬ МОЛОКО. ЭТА ПЕЧАЛЬНАЯ НОВОСТЬ ПОВЕРГЛА МЕНЯ В ШОК, НО Я СПРАВЛЯЮСЬ ❤️❤️❤️\r\nЛЕНОЧКА ПРИВЕТ ДА ХРАНИТ ТЕБЯ БОГ', NULL, '2026-06-19 04:35:37');

-- --------------------------------------------------------

--
-- Структура таблицы `post_comments`
--

CREATE TABLE `post_comments` (
  `comment_id` int NOT NULL,
  `comment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_id` int NOT NULL,
  `author_id` int NOT NULL,
  `date` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `post_images`
--

CREATE TABLE `post_images` (
  `image_id` int NOT NULL,
  `post_id` int NOT NULL,
  `image_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `login` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_private` tinyint(1) NOT NULL,
  `birthday_date` date NOT NULL,
  `creation_date` date NOT NULL,
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`user_id`, `login`, `first_name`, `last_name`, `password`, `role`, `is_private`, `birthday_date`, `creation_date`, `avatar`) VALUES
(1, 'natali1936', 'Natali', 'Ivanovna', '$2y$10$47tInV7bU59Ak.m9dIGee.7cIl.GmNMMWnt/HdgZEch7mscLI1mmq', '0', 0, '1936-06-16', '2026-06-11', 'user_1_1781786590.webp'),
(2, 'admin', 'Admin', 'Administrator', '$2y$10$il.YtAUf6k8yXplXrQqsDuphCw4IVkAVxHKtJZsBmoJx6tE7m1xQO', '1', 1, '2006-08-02', '2026-06-11', 'baseimage.jpg'),
(3, 'd', 'd', 'd', '$2y$10$p51tWb7DXxHvY4ztHrgvSOojh3trLMzT9puow2ZQN5oLqyM6XUs2u', '0', 0, '2432-03-31', '2026-06-12', 'baseimage.jpg'),
(4, 'ds', 'd', 'd', '$2y$10$HNe1k08dfA5Gm0sAEKyPW.MICudA.7iqEWU8FG4ccMRlkl2NCMmva', '0', 0, '2432-03-31', '2026-06-12', 'baseimage.jpg'),
(6, 'логин', 'имя', 'фамилия', '$2y$10$4oSWq9AVamP2Jwj3W.lZZOq/rMbjHuahmxHDWlJSHj42seudVe6Lu', '0', 0, '0001-01-01', '2026-06-12', 'baseimage.jpg'),
(7, 'BEBE', 'BOBO', 'PEPE', '$2y$10$hsTSifcvbHYOMOTopunQ4eVBjePnv0XLANAKSGip4MgC45wUkjroy', '0', 0, '9999-09-23', '2026-06-13', 'user_7_1781338757.jpg'),
(8, 'KEKE', 'K', 'E', '$2y$10$BUFRuB6rH3t/fDMyXExyY.eKCoiQPXy0quoWt7PijdYOEGpzfh1F6', '0', 0, '0004-03-12', '2026-06-16', 'baseimage.jpg'),
(9, 'GEGE', 'G', 'E', '$2y$10$SwYuPrkfrTAPhgj4yK/GC.hc36CgyHeb19R0j1xbvoZpIF/PyQZjW', '0', 0, '0024-03-05', '2026-06-16', 'baseimage.jpg'),
(12, 'abramovCryptoAnalytic1986', 'Cryptograph', 'Abramov', '$2y$10$nzmHs9y1S8hs29nHkMOv..q4TQXoltdaD31h8wEmJWrdC5yN93P36', '0', 0, '1986-02-02', '2026-06-18', 'user_12_1781787526.webp');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `complains`
--
ALTER TABLE `complains`
  ADD PRIMARY KEY (`complain_id`);

--
-- Индексы таблицы `complain_types`
--
ALTER TABLE `complain_types`
  ADD PRIMARY KEY (`complain_type_id`);

--
-- Индексы таблицы `direct_message`
--
ALTER TABLE `direct_message`
  ADD PRIMARY KEY (`message_id`);

--
-- Индексы таблицы `friends`
--
ALTER TABLE `friends`
  ADD PRIMARY KEY (`friendship_id`);

--
-- Индексы таблицы `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`);

--
-- Индексы таблицы `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`);

--
-- Индексы таблицы `post_comments`
--
ALTER TABLE `post_comments`
  ADD PRIMARY KEY (`comment_id`);

--
-- Индексы таблицы `post_images`
--
ALTER TABLE `post_images`
  ADD PRIMARY KEY (`image_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `login` (`login`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `complains`
--
ALTER TABLE `complains`
  MODIFY `complain_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `complain_types`
--
ALTER TABLE `complain_types`
  MODIFY `complain_type_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `direct_message`
--
ALTER TABLE `direct_message`
  MODIFY `message_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `friends`
--
ALTER TABLE `friends`
  MODIFY `friendship_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT для таблицы `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT для таблицы `post_comments`
--
ALTER TABLE `post_comments`
  MODIFY `comment_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
