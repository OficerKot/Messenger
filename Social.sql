-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Июн 02 2026 г., 13:35
-- Версия сервера: 5.6.51
-- Версия PHP: 7.2.34

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
  `complain_id` int(255) NOT NULL,
  `sender_id` int(255) NOT NULL,
  `target_user_id` int(255) NOT NULL,
  `complain_type_id` int(255) NOT NULL,
  `additional_message` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `complain_types`
--

CREATE TABLE `complain_types` (
  `complain_type_id` int(255) NOT NULL,
  `complain` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `direct_message`
--

CREATE TABLE `direct_message` (
  `message_id` int(255) NOT NULL,
  `user1_ID` int(255) NOT NULL,
  `user2_ID` int(255) NOT NULL,
  `message` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `friends`
--

CREATE TABLE `friends` (
  `friendship_id` int(255) NOT NULL,
  `user_id` int(255) NOT NULL,
  `friend_id` int(255) NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(255) NOT NULL,
  `message` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `posts`
--

CREATE TABLE `posts` (
  `post_id` int(255) NOT NULL,
  `autor_id` int(255) NOT NULL,
  `wall_owner_id` int(255) NOT NULL,
  `message` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `post_comments`
--

CREATE TABLE `post_comments` (
  `comment_id` int(255) NOT NULL,
  `comment` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_id` int(255) NOT NULL,
  `user_id` int(255) NOT NULL,
  `date` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `post_images`
--

CREATE TABLE `post_images` (
  `image_id` int(11) NOT NULL,
  `post_id` int(255) NOT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `user_id` int(255) NOT NULL,
  `login` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fio` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_private` tinyint(1) NOT NULL,
  `creation_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  MODIFY `complain_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `complain_types`
--
ALTER TABLE `complain_types`
  MODIFY `complain_type_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `direct_message`
--
ALTER TABLE `direct_message`
  MODIFY `message_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `friends`
--
ALTER TABLE `friends`
  MODIFY `friendship_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `post_comments`
--
ALTER TABLE `post_comments`
  MODIFY `comment_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(255) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
