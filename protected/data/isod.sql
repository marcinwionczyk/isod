-- phpMyAdmin SQL Dump
-- version 4.0.8
-- http://www.phpmyadmin.net
--
-- Host: z-db.pwr.wroc.pl:3306
-- Czas wygenerowania: 27 Sty 2014, 12:32
-- Wersja serwera: 5.6.12-log
-- Wersja PHP: 5.3.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Baza danych: `isod`
--
CREATE DATABASE IF NOT EXISTS `isod` DEFAULT CHARACTER SET utf8 COLLATE utf8_polish_ci;
USE `isod`;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `ParentId` int(11) DEFAULT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `lft` int(11) DEFAULT NULL,
  `rgt` int(11) DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `fk_Category_Category` (`ParentId`)
) ENGINE=InnoDB  AUTO_INCREMENT=49 ;

--
-- Zrzut danych tabeli `category`
--

INSERT INTO `category` (`Id`, `ParentId`, `Name`, `lft`, `rgt`) VALUES
(1, NULL, 'Wszystkie kategorie', 1, 96),
(2, 1, 'Mechanika', 2, 25),
(3, 1, 'Ciepło', 26, 41),
(4, 1, 'Drgania i fale', 42, 51),
(5, 1, 'Optyka', 52, 65),
(6, 1, 'Elektryczność i magnetyzm', 66, 79),
(7, 1, 'Fizyka Współczesna', 80, 95),
(8, 2, 'Kinematyka', 3, 4),
(9, 2, 'Dynamika', 5, 6),
(10, 2, 'Siły bezwładności', 7, 8),
(11, 2, 'Tarcie', 9, 10),
(12, 2, 'Praca i energia', 11, 12),
(13, 2, 'Statyka', 13, 14),
(14, 2, 'Ruch obrotowy ciała sztywnego', 15, 16),
(15, 2, 'Hydrostatyka', 17, 18),
(16, 2, 'Hydrodynamika', 19, 20),
(17, 2, 'Aerostatyka', 21, 22),
(18, 2, 'Aerodynamika', 23, 24),
(19, 3, 'Rozszerzalność termiczna ciał', 27, 28),
(20, 3, 'Kalorymetria', 29, 30),
(21, 3, 'Przenoszenie ciepła', 31, 32),
(22, 3, 'Zasady termodynamiki', 33, 34),
(23, 3, 'Zmiany stanów skupienia', 35, 36),
(24, 3, 'Ruchy cząstek', 37, 38),
(25, 3, 'Niskie temperatury', 39, 40),
(26, 4, 'Ruch drgający', 43, 44),
(27, 4, 'Drgania złożone', 45, 46),
(28, 4, 'Ruch falowy', 47, 48),
(29, 4, 'Akustyka', 49, 50),
(30, 5, 'Optyka geometryczna', 53, 54),
(31, 5, 'Fotometria', 55, 56),
(32, 5, 'Emisja i pochłanianie światła', 57, 58),
(33, 5, 'Interferencja \ni ugięcie światła', 59, 60),
(34, 5, 'Polaryzacja światła', 61, 62),
(35, 5, 'Dwójłomność w kryształach', 63, 64),
(36, 6, 'Elektrostatyka', 67, 68),
(37, 6, 'Prąd stały', 69, 70),
(38, 6, 'Pole magnetyczne', 71, 72),
(39, 6, 'Właściwości magnetyczne prądu elektrycznego', 73, 74),
(40, 6, 'Indukcja elektromagnetyczna', 75, 76),
(41, 6, 'Prąd zmienny', 77, 78),
(42, 7, 'Przewodnictwo elektryczne gazów', 81, 82),
(43, 7, 'Fizyka półprzewodników', 83, 84),
(44, 7, 'Drgania i fale elektromagnetyczne', 85, 86),
(45, 7, 'Zjawisko piezoelektryczne i elektrostrykcji', 87, 88),
(46, 7, 'Zjawisko fotoelektryczne', 89, 90),
(47, 7, 'Promieniowanie Röntgena', 91, 92),
(48, 7, 'Promieniotwórczość naturalna', 93, 94);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `dbsession`
--

CREATE TABLE IF NOT EXISTS `dbsession` (
  `id` char(32) NOT NULL,
  `expire` int(11) DEFAULT NULL,
  `data` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

--
-- Struktura tabeli dla tabeli `demonstration`
--

CREATE TABLE IF NOT EXISTS `demonstration` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) DEFAULT NULL,
  `Description` varchar(1000) DEFAULT NULL,
  `Category_Id` int(11) NOT NULL,
  `create_time` datetime NOT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `fk_Demonstration_Category1` (`Category_Id`)
) ENGINE=InnoDBAUTO_INCREMENT=4;

--
-- Zrzut danych tabeli `demonstration`
--

INSERT INTO `demonstration` (`Id`, `Name`, `Description`, `Category_Id`, `create_time`, `update_time`) VALUES
(1, 'Zasada niezależności ruchów', '<p>\r\n	Pokaz polega na upuszczeniu dwóch jednakowych kulek. Uderzenie młoteczka\r\n uruchamia "całą machinę": kulki - jedna spadająca swobodnie a druga \r\nwykonująca rzut poziomy - upadają jednocześnie na podłogę. Słuchacze \r\nmają wsłuchiwać się w pierwsze uderzenie kulek o podłogę i odpowiedzieć \r\nna pytanie czy słyszą jedno czy dwa uderzenia.\r\n</p>\r\n<img src="/attached/image/20130321/20130321123451_81520.jpg" alt="" /><br />', 8, '2013-02-03 23:25:26', '2013-03-21 12:34:54'),
(2, 'Swobodny spadek w próżni', '<p>\r\n	W doświadczeniu pokazuje się wpływ sił oporu powietrza na poruszające się przedmioty. Z rury Newtona odpompowuje się powietrze, przez co ciała znajdujące się rurze (piórko, kulka z kredy tablicowej, kulka z tworzywa sztucznego) spadają w jednakowym tempie. Po zapełnieniu rury powietrzem obserwuje się, że siła oporu działająca na piórko jest większa, niż siła działająca na kulki.\r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<img src="/attached/image/20130321/20130321123112_57809.jpg" alt="" />\r\n</p>', 8, '2013-02-03 23:28:47', '2013-03-21 12:31:18'),
(3, 'Ruch kulki po równi pochyłej', '<p>\r\n	Doświadczenie polega na toczeniu się kulki po równi pochyłej. Metronom \r\njest używany do mierzenia odstępów czasowych. Jeśli tarcie nie zakłóca \r\nprzebiegu ruchu, wówczas uderzenia metronomu będą pokrywać się z \r\nchwilami, w których chorągiewki będą potrącane przez staczającą się \r\nkulkę. Pojawiające się nieznaczne odstępstwa czasu od tej prawidłowości \r\nmożemy wyjaśnić wpływem tarcia oraz ruchu obrotowego kulki.\r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p align="center">\r\n	<img src="/attached/image/20130315/20130315143854_65255.jpg" alt="" />\r\n</p>', 8, '2013-02-03 23:31:17', '2013-03-15 14:38:58'),

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `demonstration_has_device`
--

CREATE TABLE IF NOT EXISTS `demonstration_has_device` (
  `Demonstration_Id` int(11) NOT NULL,
  `Device_Id` int(11) NOT NULL,
  PRIMARY KEY (`Demonstration_Id`,`Device_Id`),
  KEY `fk_Demonstration_has_Device_Device1` (`Device_Id`),
  KEY `fk_Demonstration_has_Device_Demonstration1` (`Demonstration_Id`)
) ENGINE=InnoDB ;

--
-- Zrzut danych tabeli `demonstration_has_device`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `device`
--

CREATE TABLE IF NOT EXISTS `device` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `EvidenceId` varchar(100) DEFAULT NULL COMMENT 'Numer ewidencyjny instytutu',
  `Name` varchar(100) NOT NULL,
  `Description` varchar(1000) DEFAULT NULL,
  `Instruction` varchar(1000) DEFAULT NULL,
  `Place` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB;

--
-- Zrzut danych tabeli `device`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `order`
--

CREATE TABLE IF NOT EXISTS `order` (
  `Id` bigint(20) NOT NULL AUTO_INCREMENT,
  `DateFrom` datetime DEFAULT NULL,
  `DateTo` datetime DEFAULT NULL,
  `Demonstration_Id` int(11) NOT NULL,
  `Room_Id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`Id`),
  KEY `fk_Order_Demonstration1` (`Demonstration_Id`),
  KEY `fk_Order_Room1` (`Room_Id`),
  KEY `fk_order_user1_idx` (`user_id`)
) ENGINE=InnoDB;


-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `role`
--

CREATE TABLE IF NOT EXISTS `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Zrzut danych tabeli `role`
--

INSERT INTO `role` (`id`, `name`) VALUES
(1, 'admin'),
(2, 'lecturer'),
(3, 'repository'),
(4, 'editor');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `room`
--

CREATE TABLE IF NOT EXISTS `room` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Building` varchar(45) COLLATE utf8_polish_ci DEFAULT NULL,
  `Number` varchar(45) COLLATE utf8_polish_ci DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=5;

--
-- Zrzut danych tabeli `room`
--

INSERT INTO `room` (`Id`, `Building`, `Number`) VALUES
(1, 'A1', '314'),
(2, 'A1', '320a'),
(3, 'A1', '321'),
(4, 'A1', '322');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(128) NOT NULL,
  `username_alt` varchar(128) DEFAULT NULL,
  `name` varchar(128) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_user_role1_idx` (`role_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Zrzut danych tabeli `user`
--

INSERT INTO `user` (`id`, `username`, `username_alt`, `name`, `role_id`) VALUES
(1, 'admin', NULL, 'mgr inż. Marcin Wionczyk', 1),
(2, 'edytor', NULL, 'mgr Michał Nowakowski', 4),
(3, 'marcin.wionczyk', NULL, 'mgr inż. Marcin Wionczyk', 3),
(4, 'wykładowca', NULL, 'profesor doktor inżynier lekarz medycyny niekonwencjonalnej', 2);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `yii_log`
--

CREATE TABLE IF NOT EXISTS `yii_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `level` varchar(128) DEFAULT NULL,
  `category` varchar(128) DEFAULT NULL,
  `logtime` int(11) DEFAULT NULL,
  `message` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

--
-- Zrzut danych tabeli `yii_log`
--

INSERT INTO `yii_log` (`id`, `level`, `category`, `logtime`, `message`) VALUES

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `category`
--
ALTER TABLE `category`
  ADD CONSTRAINT `fk_Category_Category` FOREIGN KEY (`ParentId`) REFERENCES `category` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `demonstration`
--
ALTER TABLE `demonstration`
  ADD CONSTRAINT `fk_Demonstration_Category1` FOREIGN KEY (`Category_Id`) REFERENCES `category` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `demonstration_has_device`
--
ALTER TABLE `demonstration_has_device`
  ADD CONSTRAINT `fk_Demonstration_has_Device_Demonstration1` FOREIGN KEY (`Demonstration_Id`) REFERENCES `demonstration` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Demonstration_has_Device_Device1` FOREIGN KEY (`Device_Id`) REFERENCES `device` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `fk_Order_Demonstration1` FOREIGN KEY (`Demonstration_Id`) REFERENCES `demonstration` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Order_Room1` FOREIGN KEY (`Room_Id`) REFERENCES `room` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_order_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `fk_user_role1` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
