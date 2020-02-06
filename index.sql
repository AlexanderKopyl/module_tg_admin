-- phpMyAdmin SQL Dump
-- version 4.6.6deb4
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Фев 06 2020 г., 10:50
-- Версия сервера: 10.3.22-MariaDB-0+deb10u1
-- Версия PHP: 7.2.27-1+0~20200123.34+debian10~1.gbp63c0bc

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `b0zoo`
--

-- --------------------------------------------------------

--
-- Структура таблицы `oc_tg_users`
--

CREATE TABLE `oc_tg_users` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(32) NOT NULL,
  `last_name` varchar(32) NOT NULL,
  `username` varchar(32) NOT NULL,
  `date_add` date NOT NULL,
  `telephone` varchar(32) NOT NULL,
  `chat_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `oc_tg_users`
--

INSERT INTO `oc_tg_users` (`user_id`, `first_name`, `last_name`, `username`, `date_add`, `telephone`, `chat_id`) VALUES
(14, 'Александр', 'Копыл', 'Kopul', '2020-02-04', '+380630674453', 205993908),
(15, 'Bohdan', 'D.', 'pheodot', '2020-02-04', '+380636989858', 251182842),
(16, 'Ростислав', '', 'FckThaSystem', '2020-02-04', '', 316978491);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `oc_tg_users`
--
ALTER TABLE `oc_tg_users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `oc_tg_users`
--
ALTER TABLE `oc_tg_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
