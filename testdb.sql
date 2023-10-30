-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Окт 30 2023 г., 21:54
-- Версия сервера: 10.4.27-MariaDB
-- Версия PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `testdb`
--

-- --------------------------------------------------------

--
-- Структура таблицы `reservation`
--

CREATE TABLE `reservation` (
  `ID` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` varchar(5) NOT NULL,
  `cost` double(4,2) NOT NULL,
  `payment` varchar(128) NOT NULL,
  `room_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `reservation`
--

INSERT INTO `reservation` (`ID`, `date`, `time`, `cost`, `payment`, `room_id`, `client_id`) VALUES
(55, '2023-04-27', '13:30', 51.00, 'card', 5, 13),
(56, '2023-04-27', '13:30', 60.00, 'cash', 6, 13),
(57, '2023-05-19', '10:00', 60.00, 'cash', 6, 13),
(58, '2023-04-27', '11:30', 51.00, 'cash', 5, 10),
(62, '2023-05-27', '14:30', 0.60, 'cash', 3, 18),
(66, '2023-05-31', '19:00', 0.60, 'cash', 3, 19),
(69, '2023-11-02', '', 0.00, 'cash', 5, 23),
(71, '2023-11-03', '', 0.00, 'cash', 5, 23),
(74, '2023-11-17', '', 0.00, 'cash', 5, 23),
(81, '2023-11-02', '14:30', 51.00, 'cash', 5, 23),
(83, '2023-11-30', '22:00', 68.00, 'cash', 5, 23),
(85, '2023-11-30', '22:00', 72.00, 'card', 14, 23);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ClientID` (`client_id`),
  ADD KEY `RoomID` (`room_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `reservation`
--
ALTER TABLE `reservation`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `ClientID` FOREIGN KEY (`client_id`) REFERENCES `users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `RoomID` FOREIGN KEY (`room_id`) REFERENCES `quests` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
