-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Июн 02 2024 г., 22:33
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
(4, 'nameasd', 'surname', 'asd@asd.zxc', '$2y$10$LNOuFzeHII.vq/fRjHdMq.kLPzrdJGIEl/rrZKoT2JogeRbPUZsuy', '989878-98043', 25856312),
(5, 'asd', 'asd', '123@gmail.com', '$2y$10$2XzcAchdAeNYZarmYiRYCuPmFYtko03NRLO/jpCNvskOZKazdD4Om', '132123-12316', 123123123),
(7, 'Edgars', 'Pavlovs', 'asd@asd.asd', '$2y$10$Yj/xmkcsINg4.7C6ifz2TO53vbtA/dlhlm.JAAoifJg6dGN0Hg5nG', '684965-39865', 596874657),
(8, 'dfgh', 'dfgh', 'ponchik@gmail.com', '$2y$10$uc1Gqg6b5UgnN/CuzAwJyeSkmyYLwSwNRLR8Qh.kYxyqhvA1aD8Qe', '200303-20134', 2147483647),
(9, 'admin', 'admin', 'email@gmail.com', '$2y$10$dvaqkAxxtw2aTQQcZNBlo.XzWacJCGqi1wdZ9dMeuUSZBWpz4mikS', '123543-34576', 7654567),
(10, 'edgar', 'kozlovs', 'kozlovs@gmai.com', '$2y$10$sW.x31UEwdZdA/3gi/rsyOYBUG.paZXUpSu2BvaaxlnIMuEulpJD6', '765098-56784', 848356),
(19, 'testtasdasdssss', 'testtsss', 'test@gmial.com', '$2y$10$VxLA8DT.uH6dl7r./hplS.x8TxXtd7RtSAgvVpi5W1KVaL/O5eCui', '909090-09909', 9090999);

-- --------------------------------------------------------

--
-- Структура таблицы `adress`
--

CREATE TABLE `adress` (
  `ID` int(11) NOT NULL,
  `buildingAdress` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `adress`
--

INSERT INTO `adress` (`ID`, `buildingAdress`) VALUES
(1, 'test'),
(2, 'vavava'),
(3, 'Elizabetes iela 42'),
(4, 'Viršu iela 2'),
(5, 'Matisa iela 11'),
(6, 'Kanla iela 32'),
(7, 'Kalupes iela 12'),
(8, 'Dzirnavu iela 2'),
(9, 'Hanzas iela 3'),
(10, 'Vidus iela 15'),
(11, 'Eksporta iela 31'),
(12, 'Stabu iela 32'),
(13, 'Krasta iela 32');

-- --------------------------------------------------------

--
-- Структура таблицы `card`
--

CREATE TABLE `card` (
  `ID` int(11) NOT NULL,
  `cardDate` varchar(5) NOT NULL,
  `cardNumber` int(16) NOT NULL,
  `cardName` varchar(128) NOT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `card`
--

INSERT INTO `card` (`ID`, `cardDate`, `cardNumber`, `cardName`, `user_id`) VALUES
(1, 'date1', 2147483647, 'name1', NULL),
(2, 'date2', 2147483647, 'name2', NULL),
(3, 'date3', 2147483647, 'name3', NULL),
(4, 'date4', 123123123, 'asdasdasd', NULL),
(5, 'asd', 0, 'asdasd', NULL),
(6, 'asd', 0, 'asdasd', 23),
(7, '04/25', 2147483647, 'Test Test', 31);

-- --------------------------------------------------------

--
-- Структура таблицы `comment`
--

CREATE TABLE `comment` (
  `ID` int(11) NOT NULL,
  `comment` text NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `quest_id` int(11) DEFAULT NULL,
  `creation_date` date NOT NULL DEFAULT current_timestamp(),
  `reply_to` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `comment`
--

INSERT INTO `comment` (`ID`, `comment`, `user_id`, `admin_id`, `quest_id`, `creation_date`, `reply_to`) VALUES
(7, 'Man ļoti patika, bija ļoti forši! Super forši', 10, NULL, NULL, '2024-03-03', NULL),
(9, 'Iesaku visiem!', NULL, 1, NULL, '2024-03-03', NULL),
(16, 'nu tā', 13, NULL, NULL, '2024-03-03', NULL),
(21, 'asd', NULL, 1, NULL, '2024-03-03', NULL),
(26, 'test', NULL, NULL, 22, '2024-03-07', NULL),
(27, 'test', NULL, NULL, 22, '2024-03-07', NULL),
(28, '123', NULL, NULL, 22, '2024-03-07', NULL),
(29, '123', NULL, NULL, 22, '2024-03-07', NULL),
(30, '123', NULL, NULL, 22, '2024-03-07', NULL),
(31, '123', NULL, NULL, 22, '2024-03-07', NULL),
(32, 'test', NULL, NULL, 13, '2024-03-07', NULL),
(33, 'test', NULL, NULL, 11, '2024-03-07', NULL),
(34, 'test', NULL, NULL, 10, '2024-03-07', NULL),
(35, 'test', NULL, NULL, 10, '2024-03-07', NULL),
(36, 'test', NULL, NULL, 10, '2024-03-07', NULL),
(37, 'test', NULL, NULL, 14, '2024-03-07', NULL),
(38, 'asd', NULL, NULL, 14, '2024-03-07', NULL),
(39, 'asd', NULL, NULL, 3, '2024-04-21', NULL),
(40, 'asd', NULL, NULL, 3, '2024-04-21', NULL),
(41, 'asd', NULL, NULL, 3, '2024-04-21', NULL),
(42, 'das', NULL, NULL, 3, '2024-04-21', NULL),
(43, 'asdasd', 23, NULL, 3, '2024-04-21', NULL),
(44, 'asdads', 23, NULL, 3, '2024-04-21', 43),
(45, 'фыв', 23, NULL, 3, '2024-04-21', 0),
(46, 'авыаывафяяяяя', 23, NULL, 3, '2024-04-21', 0),
(48, 'test', 23, NULL, 3, '2024-04-21', 0),
(49, 'test2', 23, NULL, 3, '2024-04-21', 0),
(50, 'фыв', 23, NULL, 3, '2024-04-21', 0),
(51, 'фыв', 23, NULL, 3, '2024-04-21', 0),
(52, '123123123321', 23, NULL, 3, '2024-04-21', 0),
(53, 'еуыеtesttesttest222', 23, NULL, 3, '2024-04-21', 0),
(54, 'comentcomentcoment\r\n', 23, NULL, 3, '2024-04-21', 53),
(55, 'TTTTTTTTTTTTTTTTTTTTTTTT', 23, NULL, 3, '2024-04-21', 0),
(56, 'SSSSSSSSSSSSSSSSSS', 23, NULL, 3, '2024-04-21', 55),
(57, 'ads', 23, NULL, 3, '2024-04-22', 55),
(58, 'testtest222', 23, NULL, 3, '2024-04-22', 0),
(59, '22testtest', 23, NULL, 3, '2024-04-22', 58),
(60, 'ok', 23, NULL, 13, '2024-04-25', 32),
(61, '?', 23, NULL, 13, '2024-04-25', 0),
(64, 'reply on reply\r\n', 23, NULL, 21, '2024-04-25', 63),
(66, 'reply to reply 2', 23, NULL, 21, '2024-04-25', 65),
(67, 'reply to reply reply 2', 23, NULL, 21, '2024-04-25', 66),
(68, '', 31, NULL, 3, '2024-05-19', 0),
(70, 'asd', 31, NULL, 3, '2024-05-20', 0),
(71, 'asdasdasd', 31, NULL, 3, '2024-05-20', 0),
(74, 'asd', 31, NULL, 14, '2024-05-22', 0),
(78, 'asd', 31, NULL, 14, '2024-05-22', 0),
(83, 'test', 31, NULL, 14, '2024-05-22', 0),
(84, 'reply', 31, NULL, 14, '2024-05-22', 0),
(85, 'replyto', 31, NULL, 14, '2024-05-22', 74),
(86, 'coment 1\r\n', 31, NULL, 11, '2024-05-22', 0),
(87, 'reply 1\r\n', 31, NULL, 11, '2024-05-22', 86),
(88, 'comment 2', 31, NULL, 11, '2024-05-22', 0),
(89, 'reply 2', 31, NULL, 11, '2024-05-22', 88),
(90, 'reply 11', 31, NULL, 11, '2024-05-22', 87),
(91, 'ку ётп', 31, NULL, 11, '2024-05-22', 87),
(92, 'testponch22', 31, NULL, 3, '2024-05-22', 0),
(93, 'test', 31, NULL, 5, '2024-05-22', 0),
(94, 'reply', 31, NULL, 5, '2024-05-22', 93),
(95, 'test', 31, NULL, 5, '2024-05-22', 93),
(96, 'asdasd', 31, NULL, 5, '2024-05-22', 0),
(97, '1', 31, NULL, 5, '2024-05-22', 0),
(98, '2', 31, NULL, 5, '2024-05-22', 0),
(99, '11', 31, NULL, 5, '2024-05-22', 97),
(100, '22', 31, NULL, 5, '2024-05-22', 98),
(101, 'dsada', 31, NULL, 5, '2024-05-22', 0),
(102, 'newest comment', 31, NULL, 5, '2024-05-22', 0),
(103, 'new reply', 31, NULL, 5, '2024-05-22', 102),
(104, 'ку ёпт228', 31, NULL, 3, '2024-05-22', 92),
(105, 'фывфыв', 31, NULL, 22, '2024-05-22', 31),
(106, 'дффд\r\n', 31, NULL, 22, '2024-05-22', 0),
(107, 'фывфыв', 31, NULL, 22, '2024-05-22', 106),
(108, 'рпр', 31, NULL, 3, '2024-05-22', 57),
(114, 'test', NULL, 1, 3, '2024-05-23', 0),
(115, 'nu ok', NULL, 1, 3, '2024-05-23', 104),
(116, 'Ļoti patika vai Jūs varat ieteikt tāda paša žanra kvestu istabu?\r\n', 31, NULL, 21, '2024-05-25', 0),
(117, 'iesaku the bank job ', 31, NULL, 21, '2024-05-25', 116);

-- --------------------------------------------------------

--
-- Структура таблицы `prices`
--

CREATE TABLE `prices` (
  `ID` int(11) NOT NULL,
  `timePeriod` varchar(128) NOT NULL,
  `cost` double(4,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `prices`
--

INSERT INTO `prices` (`ID`, `timePeriod`, `cost`) VALUES
(1, '10:00', 60.00),
(2, '11:30', 60.00),
(3, '13:30', 60.00),
(4, '14:30', 60.00),
(5, '16:00', 60.00),
(6, '17:30', 60.00),
(7, '19:00', 60.00),
(8, '20:30', 80.00),
(9, '22:00', 80.00);

-- --------------------------------------------------------

--
-- Структура таблицы `questcategory`
--

CREATE TABLE `questcategory` (
  `ID` int(11) NOT NULL,
  `ageLimit` int(2) NOT NULL,
  `categoryName` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `questcategory`
--

INSERT INTO `questcategory` (`ID`, `ageLimit`, `categoryName`) VALUES
(1, 6, 'Party'),
(2, 12, 'vavavavA'),
(3, 16, 'Horror'),
(4, 18, 'Detective'),
(5, 12, 'For beginners'),
(6, 6, 'Party'),
(7, 6, 'For beginners'),
(8, 3, 'Party'),
(9, 3, 'Detective'),
(10, 16, 'Party'),
(11, 12, 'Horror'),
(12, 6, 'Horror'),
(13, 12, 'Detective');

-- --------------------------------------------------------

--
-- Структура таблицы `quests`
--

CREATE TABLE `quests` (
  `ID` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `peopleAmount` varchar(5) NOT NULL,
  `description` text NOT NULL,
  `photoPath` varchar(128) NOT NULL,
  `adress_id` int(11) DEFAULT NULL,
  `questCategory_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `quests`
--

INSERT INTO `quests` (`ID`, `name`, `peopleAmount`, `description`, `photoPath`, `adress_id`, `questCategory_id`) VALUES
(3, 'Slaughterhouse', '2-9', 'You woke up in the slaughterhouse maniac and you realize that it has become prey. He fastened the handcuffs you around solid blood, and heard the cries of the victims in the distance. After a while, after hitting an ax, shouting disappear ... Do you realize that you do not want that would you all so over and you at any cost necessary to vybratsya. Think of how to do it, otherwise your fate will be the same.', '../images/e026b59f-b96f-48e2-b4f3-f21d38a7f8a1.jpg.1200x500_q85_crop-smart_upscale.jpg', 3, 3),
(4, 'Abandoned house', '2-4', 'The horror hidden in the ancient house breaks out, enveloping them with invisible bonds and dragging them into the labyrinths of an endless nightmare.', '../images/99a6cf68-1823-4a48-83cb-b18f188b77d8-spooky-homes-around-the-world-moulthrop-house.jpg', 4, 4),
(5, 'Party11', '2-6', 'Yesterday was great, but not today. You woke up after office corporate party in  underwear. Trying to build a head last night and found the annual report, which now need to pass. The chief had already left to you and will be there within 60 minutes.', '../images/6652340c78f7b.jpg', 5, 5),
(6, 'The Captain\'s Room', '2-5', 'The adventure begins in a room of the old \"sea wolf\", who has disappeared many years ago. Although at first it seems that it is a usual room, you will soon realize that the captain had hidden something. The room is cursed, and things are moving as if they where alive. You need agility, quick mind and teamwork to get out of the room. They say that it is not possible...', '../images/dsc_7689_rt9vkTL.jpg.1200x500_q85_crop-smart_upscale.jpg', 6, 6),
(7, 'The Bank Job', '2-8', 'Unique and priceless collection of diamonds has been arrived in one of the biggest banks on Baker street. Your team of professional, skillful burglars know this fact, and that is why your made genius plan of robbery of the century. Now everything that is left to do is to realize it!', '../images/bank_4_ffrqrZu.jpg.1200x500_q85_crop-smart_upscale.jpg', 7, 7),
(8, 'Behind the Glass', '2-8', 'Quest has no genre, no legenzhy and objectives, with the exception of the usual - get out in 60 minutes. This room floor difficult engineering solution with a variety of tasks that will make test your ability to work in a team. Enjoy exciting, atmospheric and modern game', '../images/01_loZH3ci.jpg.1200x500_q85_crop-smart_upscale.jpg', 8, 8),
(9, 'PSYLAB', '2-5', 'Half-ruined basement, darkness, scent of danger is in the air, no sense to wait for help… this room doesn\'t like fuss, noise and hollow talks. There is only 60 minutes left. Concentrate, join forces, and find the code!', '../images/1.jpg.1200x500_q85_crop-smart_upscale.jpg', 9, 9),
(10, 'Sherlock Holmes and royal treasures', '2-5', 'From the royal Treasury regularly lost jewellery. Group of villains within 60 minutes get into Treasury and disappear without a trace. All in despair. They decided to call for help Sherlock Holmes.\r\n\r\n ', '../images/karaliskie_dargumi3.jpg.1200x500_q85_crop-smart_upscale.jpg', 10, 10),
(11, 'Titanic', '2-6', '“Titanic”-yes, the same old Titanic! – collided with an iceberg and sinks to the bottom. It means that after putting on your life vest, it is time to take a seat at a lifeboat. But... You got fastened with the handcuffs to the pipe in a radio cabin at a lower deck and you are given only 60 minutes to avoid your inevitable death!...', '../images/04939435-ca42-43fa-b678-bba0fa0153db.jpg.1200x500_q85_crop-smart_upscale.jpg', 11, 11),
(13, 'Lottery Robbery', '2-6', 'Forget everything you have seen before. Quest takes escape-room games to a whole new level. Now, the usual opening of locks and searching of safe codes will not be enough – you’ll be surprised with how many incredible wow-effects do engineering riddles hold. And of course you simply cannot do without a core set of each questman – logic, intelligence and quick wit.', '../images/kwest_laupisana_pLDC92e.jpg.1200x500_q85_crop-smart_upscale.jpg', 12, 12),
(14, 'House 12', '2-9', 'You woke up in the slaughterhouse maniac and you realize that it has become prey. He fastened the handcuffs you around solid blood, and heard the cries of the victims in the distance. After a while, after hitting an ax, shouting disappear ... Do you realize that you do not want that would you all so over and you at any cost necessary to vybratsya. Think of how to do it, otherwise your fate will be the same.', '../images/99a6cf68-1823-4a48-83cb-b18f188b77d8-spooky-homes-around-the-world-moulthrop-house.jpg', 13, 13),
(21, 'test', '2-5', 'testtesttest', '../images/room_image750295605', 1, 1),
(22, 'vavavavA', '2-4', 'vavavavav', '../images/664f1e5d24ac8.jpg', 2, 2);

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
  `client_id` int(11) NOT NULL,
  `creation_date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `reservation`
--

INSERT INTO `reservation` (`ID`, `date`, `time`, `cost`, `payment`, `room_id`, `client_id`, `creation_date`) VALUES
(55, '2023-04-27', '13:30', 51.00, 'card', 5, 13, '2023-09-06'),
(56, '2023-04-27', '13:30', 60.00, 'cash', 6, 13, '2023-09-10'),
(57, '2023-05-19', '10:00', 60.00, 'cash', 6, 13, '2023-09-15'),
(58, '2024-05-30', '20:30', 80.00, 'cash', 5, 10, '2023-09-15'),
(62, '2023-05-27', '14:30', 0.60, 'cash', 3, 18, '2023-10-06'),
(66, '2023-05-31', '19:00', 0.60, 'cash', 3, 19, '2023-10-10'),
(69, '2023-11-02', '', 0.00, 'cash', 5, 23, '2023-10-11'),
(71, '2023-11-03', '', 0.00, 'cash', 5, 23, '2023-10-12'),
(74, '2023-11-17', '', 0.00, 'cash', 5, 23, '2023-10-12'),
(81, '2023-11-02', '14:30', 51.00, 'cash', 5, 23, '2023-10-12'),
(83, '2023-11-30', '22:00', 68.00, 'cash', 5, 23, '2023-11-06'),
(85, '2023-11-30', '22:00', 72.00, 'card', 14, 23, '2023-11-06'),
(87, '2023-11-09', '16:00', 0.60, 'cash', 3, 24, '2023-11-06'),
(90, '2023-11-26', '22:00', 80.00, 'cash', 4, 24, '2023-11-06'),
(92, '2023-11-30', '19:00', 60.00, 'cash', 9, 24, '2023-11-06'),
(93, '2024-03-07', '', 0.00, 'cash', 22, 23, '2024-03-06'),
(94, '2024-03-28', '', 0.00, 'cash', 22, 23, '2024-03-07'),
(95, '2024-03-30', '', 0.00, 'cash', 22, 23, '2024-03-07'),
(96, '2024-03-16', '20:30', 80.00, 'cash', 22, 23, '2024-03-07'),
(97, '2024-03-16', '19:00', 60.00, 'cash', 22, 23, '2024-03-07'),
(98, '2024-03-16', '22:00', 80.00, 'cash', 22, 23, '2024-03-07'),
(99, '2024-03-27', '11:30', 60.00, 'cash', 22, 23, '2024-03-07'),
(100, '2024-03-31', '16:00', 60.00, 'cash', 22, 23, '2024-03-07'),
(101, '2024-03-16', '', 0.00, 'cash', 22, 23, '2024-03-07'),
(103, '2024-03-26', '16:00', 60.00, 'cash', 22, 23, '2024-03-07'),
(110, '2024-03-28', '22:00', 80.00, 'cash', 22, 23, '2024-03-07'),
(112, '2024-03-19', '11:30', 60.00, 'cash', 22, 23, '2024-03-07'),
(114, '2024-04-05', '17:30', 60.00, 'cash', 22, 23, '2024-03-07'),
(117, '2024-04-01', '10:00', 60.00, 'cash', 22, 23, '2024-03-07'),
(121, '2024-04-24', '10:00', 60.00, 'card', 8, 23, '2024-04-22'),
(123, '2024-04-30', '14:30', 60.00, 'card', 14, 23, '2024-04-25'),
(125, '2024-04-26', '16:00', 60.00, 'card', 14, 23, '2024-04-25'),
(127, '2024-05-03', '14:30', 60.00, 'cash', 3, 31, '2024-05-20'),
(128, '2024-05-02', '20:30', 80.00, 'card', 3, 31, '2024-05-20'),
(129, '2024-05-10', '16:00', 60.00, 'card', 3, 31, '2024-05-20'),
(130, '2024-05-11', '17:30', 60.00, 'cash', 3, 31, '2024-05-20'),
(131, '2024-05-23', '', 0.00, 'cash', 3, 31, '2024-05-22'),
(132, '2024-05-23', '10:00', 60.00, 'cash', 3, 31, '2024-05-22'),
(133, '0000-00-00', '', 0.00, 'cash', 3, 31, '2024-05-22'),
(134, '2024-05-24', '22:00', 80.00, 'cash', 3, 31, '2024-05-22'),
(136, '2024-05-31', '22:00', 80.00, 'cash', 4, 31, '2024-05-25'),
(137, '2024-05-31', '19:00', 60.00, 'cash', 4, 31, '2024-05-25'),
(138, '2024-05-31', '22:00', 80.00, 'cash', 5, 31, '2024-05-25'),
(139, '2024-05-31', '16:00', 60.00, 'card', 4, 31, '2024-05-28'),
(140, '2024-05-30', '19:00', 60.00, 'cash', 7, 31, '2024-05-29'),
(141, '2024-05-30', '22:00', 80.00, 'cash', 7, 31, '2024-05-29'),
(142, '2024-05-30', '22:00', 80.00, 'cash', 4, 31, '2024-05-29'),
(143, '2024-06-09', '22:00', 80.00, 'cash', 3, 31, '2024-05-31'),
(144, '2024-06-03', '11:30', 60.00, 'card', 3, 31, '2024-06-02'),
(145, '2024-06-03', '16:00', 60.00, 'cash', 3, 31, '2024-06-02'),
(146, '2024-06-23', '22:00', 80.00, 'cash', 3, 31, '2024-06-02'),
(147, '2024-06-14', '13:30', 60.00, 'cash', 3, 31, '2024-06-02'),
(148, '2024-06-26', '22:00', 80.00, 'cash', 3, 31, '2024-06-02'),
(149, '2024-06-03', '22:00', 80.00, 'cash', 3, 31, '2024-06-02');

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
(23, 'asdasd', '$2y$10$xETlf4BVvjWZ9QWbOfA4UOGCKfjxtqYgIhFzEWo7KKUJQ8B190gQ.', 'asdasd@gmail.com', 'asdasd', 'asdasd', 2147483647),
(24, 'user', '$2y$10$Frb03P90nsLjmcqaJOr0.e8O15jaOoj6y3ykR/BLTqlfXzCXNw.di', 'useruser@user.com', 'user', 'user', 1231233213),
(25, 'reply', '$2y$10$dB1SMi3OKznSyk5xKY9IGu2tw/oR7Xzp0xHDK0eUamw5RWbK1swou', 'manapiw843@dxice.com', 'reply', 'reply', 23423424),
(31, 'testpochta', '$2y$10$tw3l4Eie3PVz7do45ZqK/.p90wGFJajb9rS.mP7wLZSRMgd8wWoa.', 'ekspunser@gmail.com', 'testpochta', 'testpochta', 20563754),
(32, 'DJ', '$2y$10$2s6qvpjCP9ISdvzExh815euva/hEQQdO8VQ1UZTOfjPU22.Z.HQmO', 'mnogoopal@gmail.com', 'Caban', 'Kuplinov', 22423423);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`ID`);

--
-- Индексы таблицы `adress`
--
ALTER TABLE `adress`
  ADD PRIMARY KEY (`ID`);

--
-- Индексы таблицы `card`
--
ALTER TABLE `card`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `CardID` (`user_id`);

--
-- Индексы таблицы `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `AdminID` (`admin_id`),
  ADD KEY `Test` (`user_id`),
  ADD KEY `QuestID` (`quest_id`);

--
-- Индексы таблицы `prices`
--
ALTER TABLE `prices`
  ADD PRIMARY KEY (`ID`);

--
-- Индексы таблицы `questcategory`
--
ALTER TABLE `questcategory`
  ADD PRIMARY KEY (`ID`);

--
-- Индексы таблицы `quests`
--
ALTER TABLE `quests`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `AdressID` (`adress_id`),
  ADD KEY `QuestCategoryID` (`questCategory_id`);

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
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT для таблицы `adress`
--
ALTER TABLE `adress`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT для таблицы `card`
--
ALTER TABLE `card`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `comment`
--
ALTER TABLE `comment`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;

--
-- AUTO_INCREMENT для таблицы `prices`
--
ALTER TABLE `prices`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `questcategory`
--
ALTER TABLE `questcategory`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT для таблицы `quests`
--
ALTER TABLE `quests`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT для таблицы `reservation`
--
ALTER TABLE `reservation`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=150;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `card`
--
ALTER TABLE `card`
  ADD CONSTRAINT `CardID` FOREIGN KEY (`user_id`) REFERENCES `users` (`ID`);

--
-- Ограничения внешнего ключа таблицы `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `AdminID` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `QuestID` FOREIGN KEY (`quest_id`) REFERENCES `quests` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Test` FOREIGN KEY (`user_id`) REFERENCES `users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `quests`
--
ALTER TABLE `quests`
  ADD CONSTRAINT `AdressID` FOREIGN KEY (`adress_id`) REFERENCES `adress` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `QuestCategoryID` FOREIGN KEY (`questCategory_id`) REFERENCES `questcategory` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

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
