-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Окт 31 2023 г., 08:45
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
-- Структура таблицы `admin`
--

CREATE TABLE `admin` (
  `ID` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `surname` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL,
  `personCode` char(12) NOT NULL,
  `phoneNumber` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `admin`
--

INSERT INTO `admin` (`ID`, `name`, `surname`, `email`, `password`, `personCode`, `phoneNumber`) VALUES
(1, 'Kirill', 'Ivanovs', 'kirill@gmial.com', '$2y$10$ed3fwB/Ven4dwG.5cjJVJ.qIsrr1LnutV0yBTT9HcbNQuvX/.PBna', '200303-20102', 2147483647),
(4, 'name', 'surname', 'asd@asd.zxc', '$2y$10$LNOuFzeHII.vq/fRjHdMq.kLPzrdJGIEl/rrZKoT2JogeRbPUZsuy', '989878-98043', 25856312),
(5, 'asd', 'asd', '123@gmail.com', '$2y$10$2XzcAchdAeNYZarmYiRYCuPmFYtko03NRLO/jpCNvskOZKazdD4Om', '132123-12316', 123123123),
(7, 'Edgars', 'Pavlovs', 'asd@asd.asd', '$2y$10$Yj/xmkcsINg4.7C6ifz2TO53vbtA/dlhlm.JAAoifJg6dGN0Hg5nG', '684965-39865', 596874657),
(8, 'dfgh', 'dfgh', 'ponchik@gmail.com', '$2y$10$uc1Gqg6b5UgnN/CuzAwJyeSkmyYLwSwNRLR8Qh.kYxyqhvA1aD8Qe', '200303-20134', 2147483647),
(9, 'admin', 'admin', 'email@gmail.com', '$2y$10$dvaqkAxxtw2aTQQcZNBlo.XzWacJCGqi1wdZ9dMeuUSZBWpz4mikS', '123543-34576', 7654567),
(10, 'edgar', 'kozlovs', 'kozlovs@gmai.com', '$2y$10$sW.x31UEwdZdA/3gi/rsyOYBUG.paZXUpSu2BvaaxlnIMuEulpJD6', '765098-56784', 848356),
(19, 'testt', 'testt', 'test@gmial.com', '$2y$10$tclNnvztlv4.2P3xKLtBKe400LysdOTLHSwHqhjFkfq9Gr/sHnf6y', '909090-09909', 9090999);

-- --------------------------------------------------------

--
-- Структура таблицы `comment`
--

CREATE TABLE `comment` (
  `ID` int(11) NOT NULL,
  `comment` text NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `comment`
--

INSERT INTO `comment` (`ID`, `comment`, `user_id`, `admin_id`) VALUES
(7, 'Man ļoti patika, bija ļoti forši! Super forši', 10, NULL),
(9, 'Iesaku visiem!', NULL, 1),
(16, 'nu tā', 13, NULL),
(21, 'asd', NULL, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `quests`
--

CREATE TABLE `quests` (
  `ID` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `adress` varchar(128) NOT NULL,
  `discount` int(2) NOT NULL,
  `peopleAmount` varchar(5) NOT NULL,
  `ageLimit` int(2) NOT NULL,
  `description` text NOT NULL,
  `category` varchar(128) NOT NULL,
  `photoPath` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `quests`
--

INSERT INTO `quests` (`ID`, `name`, `adress`, `discount`, `peopleAmount`, `ageLimit`, `description`, `category`, `photoPath`) VALUES
(3, 'Slaughterhouse', 'Adrese iela 2', 99, '2-10', 16, 'You woke up in the slaughterhouse maniac and you realize that it has become prey. He fastened the handcuffs you around solid blood, and heard the cries of the victims in the distance. After a while, after hitting an ax, shouting disappear ... Do you realize that you do not want that would you all so over and you at any cost necessary to vybratsya. Think of how to do it, otherwise your fate will be the same.', 'Horror', '../images/e026b59f-b96f-48e2-b4f3-f21d38a7f8a1.jpg.1200x500_q85_crop-smart_upscale.jpg'),
(4, 'Abandoned house', 'Аdrese iela 5 ', 0, '2-4', 12, 'The horror hidden in the ancient house breaks out, enveloping them with invisible bonds and dragging them into the labyrinths of an endless nightmare.', 'Horror', '../images/99a6cf68-1823-4a48-83cb-b18f188b77d8-spooky-homes-around-the-world-moulthrop-house.jpg'),
(5, 'Party', 'Adres iela 10', 15, '2-6', 5, 'Yesterday was great, but not today. You woke up after office corporate party in  underwear. Trying to build a head last night and found the annual report, which now need to pass. The chief had already left to you and will be there within 60 minutes.', 'For beginers', '../images/загруженное.jpg'),
(6, 'The Captain\'s Room', 'Elizabetes iela 47', 0, '2-5', 16, 'The adventure begins in a room of the old \"sea wolf\", who has disappeared many years ago. Although at first it seems that it is a usual room, you will soon realize that the captain had hidden something. The room is cursed, and things are moving as if they where alive. You need agility, quick mind and teamwork to get out of the room. They say that it is not possible...', 'Children', '../images/dsc_7689_rt9vkTL.jpg.1200x500_q85_crop-smart_upscale.jpg'),
(7, 'The Bank Job', 'Tērbatas iela 83B', 5, '2-8', 6, 'Unique and priceless collection of diamonds has been arrived in one of the biggest banks on Baker street. Your team of professional, skillful burglars know this fact, and that is why your made genius plan of robbery of the century. Now everything that is left to do is to realize it!', 'Detective', '../images/bank_4_ffrqrZu.jpg.1200x500_q85_crop-smart_upscale.jpg'),
(8, 'Behind the Glass', 'Kr.Barona 92', 10, '2-8', 13, 'Quest has no genre, no legenzhy and objectives, with the exception of the usual - get out in 60 minutes. This room floor difficult engineering solution with a variety of tasks that will make test your ability to work in a team. Enjoy exciting, atmospheric and modern game', 'not standart', '../images/01_loZH3ci.jpg.1200x500_q85_crop-smart_upscale.jpg'),
(9, 'PSYLAB', 'Kr.Barona 92', 0, '2-5', 9, 'Half-ruined basement, darkness, scent of danger is in the air, no sense to wait for help… this room doesn\'t like fuss, noise and hollow talks. There is only 60 minutes left. Concentrate, join forces, and find the code!', 'Horror', '../images/1.jpg.1200x500_q85_crop-smart_upscale.jpg'),
(10, 'Sherlock Holmes and royal treasures', 'Zigfrīda Annas Meierovica bulvāris 14', 15, '2-5', 12, 'From the royal Treasury regularly lost jewellery. Group of villains within 60 minutes get into Treasury and disappear without a trace. All in despair. They decided to call for help Sherlock Holmes.\r\n\r\n ', 'By Movie', '../images/karaliskie_dargumi3.jpg.1200x500_q85_crop-smart_upscale.jpg'),
(11, 'Titanic', 'Kalku iela 11a', 0, '2-6', 5, '“Titanic”-yes, the same old Titanic! – collided with an iceberg and sinks to the bottom. It means that after putting on your life vest, it is time to take a seat at a lifeboat. But... You got fastened with the handcuffs to the pipe in a radio cabin at a lower deck and you are given only 60 minutes to avoid your inevitable death!...', 'By Movie', '../images/04939435-ca42-43fa-b678-bba0fa0153db.jpg.1200x500_q85_crop-smart_upscale.jpg'),
(13, 'Lottery Robbery', 'Puskina iela 1a', 0, '2-6', 3, 'Forget everything you have seen before. Quest takes escape-room games to a whole new level. Now, the usual opening of locks and searching of safe codes will not be enough – you’ll be surprised with how many incredible wow-effects do engineering riddles hold. And of course you simply cannot do without a core set of each questman – logic, intelligence and quick wit.', 'Detective', '../images/kwest_laupisana_pLDC92e.jpg.1200x500_q85_crop-smart_upscale.jpg'),
(14, 'House 12', 'Adrese iela 2', 10, '2-9', 12, 'You woke up in the slaughterhouse maniac and you realize that it has become prey. He fastened the handcuffs you around solid blood, and heard the cries of the victims in the distance. After a while, after hitting an ax, shouting disappear ... Do you realize that you do not want that would you all so over and you at any cost necessary to vybratsya. Think of how to do it, otherwise your fate will be the same.', 'Horror', '../images/99a6cf68-1823-4a48-83cb-b18f188b77d8-spooky-homes-around-the-world-moulthrop-house.jpg');

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

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `ID` int(11) NOT NULL,
  `nickname` varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `name` varchar(128) NOT NULL,
  `surname` varchar(128) NOT NULL,
  `phoneNumber` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`ID`, `nickname`, `password`, `email`, `name`, `surname`, `phoneNumber`) VALUES
(10, 'poncik', '$2y$10$oY9d85TITLqeqkck1732MOS2zBtpt1FjAM7iK.4DY7RhpDPnWAG7a', 'poncik@gmail.com', 'Kirils', 'Ivanovs', 222222222),
(12, 'Misha35', '$2y$10$0mXpwvSZuNcq1L3hG1PL/OIymwDjQ8b2XB3qOCWaB.phOwtm/mM3C', 'misha@gmail.com', 'Misha', 'Pavlov', 20202123),
(13, 'Bublik', '$2y$10$ZCevqM1GSgcUWWS1kcjuW.073wakifnZZ2NpZxcH0Wqy50YEUSLcy', 'pavlov@gmail.com', 'Anton', 'Pavlov', 20405735),
(15, 'QuestBoy32', '$2y$10$bklLw/PMMytVbpuY6fkJpOdWvSif0dkrSKjx8y4elCxFGUxK0oBYm', 'ponchhik@gmail.com', 'asdfasdf', 'asdfasdf', 34563456),
(16, 'Dmitry Cool', '$2y$10$H1063UOLUaeUki.9Y0zIseynHfeC9CvTj26YEq6LVans1YOpWSKui', 'dmitry.smirnov@gmail.com', 'Dmitry', 'Smirnov', 689678967),
(17, 'Batep', '$2y$10$g/NJB64Ln2qYM5w1Il5zjOdjQzvGvFYC0oMsSaRLBDvTPEVgqw39K', 'pochta@gmail.com', 'Andrej', 'Ivanov', 23457456),
(18, 'Deniss25', '$2y$10$6tMoGf3BD6OG/rNvMnS0oO.9vtoOzSvadD03OSn8zclIENb1OOj5O', 'deniss25@gmail.com', 'Deniss', 'Kozlovs', 20950694),
(19, 'Kirils12', '$2y$10$haUkLFA0PyPDRf7hSJcAeut10R2nKF3JUZ4ijeZ1SZ1PLKeQ4HRTi', 'kirils12@gmail.com', 'Kirils', 'Ivanovs', 204050645),
(21, 'asd', '$2y$10$fu2x33dWOFzvGNeLQye6ZuWH9Ga9nRFprpvqIxlDYvzJZhqD9MnPC', 'asd@gmail.com', 'asd', 'asd', 2147483647),
(23, 'asdasd', '$2y$10$xETlf4BVvjWZ9QWbOfA4UOGCKfjxtqYgIhFzEWo7KKUJQ8B190gQ.', 'asdasd@gmail.com', 'asdasd', 'asdasd', 2147483647);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`ID`);

--
-- Индексы таблицы `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `AdminID` (`admin_id`),
  ADD KEY `Test` (`user_id`);

--
-- Индексы таблицы `quests`
--
ALTER TABLE `quests`
  ADD PRIMARY KEY (`ID`);

--
-- Индексы таблицы `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ClientID` (`client_id`),
  ADD KEY `RoomID` (`room_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `admin`
--
ALTER TABLE `admin`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT для таблицы `comment`
--
ALTER TABLE `comment`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT для таблицы `quests`
--
ALTER TABLE `quests`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT для таблицы `reservation`
--
ALTER TABLE `reservation`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `AdminID` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Test` FOREIGN KEY (`user_id`) REFERENCES `users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

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
