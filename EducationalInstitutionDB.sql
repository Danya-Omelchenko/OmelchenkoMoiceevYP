-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Май 15 2025 г., 07:17
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
-- База данных: `EducationalInstitutionDB`
--

-- --------------------------------------------------------

--
-- Структура таблицы `Departments`
--

CREATE TABLE `Departments` (
  `DepartmentID` int NOT NULL,
  `DepartmentName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `DisabledStudents`
--

CREATE TABLE `DisabledStudents` (
  `DisabledStudentID` int NOT NULL,
  `StudentID` int DEFAULT NULL,
  `StatusOrder` varchar(50) DEFAULT NULL,
  `StatusStart` date NOT NULL,
  `StatusEnd` date NOT NULL,
  `DisabilityType` varchar(50) DEFAULT NULL,
  `Notes` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `Dormitories`
--

CREATE TABLE `Dormitories` (
  `DormitoryID` int NOT NULL,
  `StudentID` int DEFAULT NULL,
  `RoomNumber` int NOT NULL,
  `CheckInDate` date NOT NULL,
  `CheckOutDate` date DEFAULT NULL,
  `Notes` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `Errors`
--

CREATE TABLE `Errors` (
  `ErrorID` int NOT NULL,
  `ErrorMessage` text NOT NULL,
  `ErrorDate` datetime NOT NULL,
  `ErrorDetails` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `Files`
--

CREATE TABLE `Files` (
  `FileID` int NOT NULL,
  `StudentID` int DEFAULT NULL,
  `FilePath` varchar(255) NOT NULL,
  `FileType` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `Orphans`
--

CREATE TABLE `Orphans` (
  `OrphanID` int NOT NULL,
  `StudentID` int DEFAULT NULL,
  `StatusOrder` varchar(50) DEFAULT NULL,
  `StatusStart` date NOT NULL,
  `StatusEnd` date NOT NULL,
  `Notes` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `RiskGroups`
--

CREATE TABLE `RiskGroups` (
  `RiskGroupID` int NOT NULL,
  `StudentID` int DEFAULT NULL,
  `Type` varchar(20) NOT NULL,
  `RegistrationDate` date NOT NULL,
  `RegistrationReason` text,
  `RemovalDate` date DEFAULT NULL,
  `RemovalReason` text,
  `Notes` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `Rooms`
--

CREATE TABLE `Rooms` (
  `RoomID` int NOT NULL,
  `RoomNumber` int NOT NULL,
  `Capacity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `SocialScholarships`
--

CREATE TABLE `SocialScholarships` (
  `ScholarshipID` int NOT NULL,
  `StudentID` int DEFAULT NULL,
  `DocumentBasis` varchar(50) DEFAULT NULL,
  `PaymentStart` date NOT NULL,
  `PaymentEnd` date NOT NULL,
  `Notes` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `SpecialNeedsStudents`
--

CREATE TABLE `SpecialNeedsStudents` (
  `SpecialNeedsStudentID` int NOT NULL,
  `StudentID` int DEFAULT NULL,
  `StatusOrder` varchar(50) DEFAULT NULL,
  `StatusStart` date NOT NULL,
  `StatusEnd` date NOT NULL,
  `Notes` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `SPPPMeetings`
--

CREATE TABLE `SPPPMeetings` (
  `MeetingID` int NOT NULL,
  `StudentID` int DEFAULT NULL,
  `MeetingDate` date NOT NULL,
  `CallReason` text,
  `PresentEmployees` text,
  `PresentRepresentatives` text,
  `CallCause` text,
  `Decision` text,
  `Notes` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `Students`
--

CREATE TABLE `Students` (
  `StudentID` int NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `MiddleName` varchar(50) DEFAULT NULL,
  `BirthDate` date NOT NULL,
  `Gender` varchar(10) NOT NULL,
  `ContactNumber` varchar(15) NOT NULL,
  `EducationLevel` varchar(10) NOT NULL,
  `Department` varchar(10) NOT NULL,
  `GroupName` varchar(10) NOT NULL,
  `FundingType` varchar(20) NOT NULL,
  `AdmissionYear` int NOT NULL,
  `GraduationYear` int DEFAULT NULL,
  `DismissalInfo` text,
  `DismissalDate` date DEFAULT NULL,
  `Notes` text,
  `ParentsInfo` text,
  `Penalties` text,
  `IsActive` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `SVOStatus`
--

CREATE TABLE `SVOStatus` (
  `SVOStatusID` int NOT NULL,
  `StudentID` int DEFAULT NULL,
  `DocumentBasis` varchar(50) DEFAULT NULL,
  `StatusStart` date NOT NULL,
  `StatusEnd` date NOT NULL,
  `Notes` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `Departments`
--
ALTER TABLE `Departments`
  ADD PRIMARY KEY (`DepartmentID`);

--
-- Индексы таблицы `DisabledStudents`
--
ALTER TABLE `DisabledStudents`
  ADD PRIMARY KEY (`DisabledStudentID`),
  ADD KEY `StudentID` (`StudentID`);

--
-- Индексы таблицы `Dormitories`
--
ALTER TABLE `Dormitories`
  ADD PRIMARY KEY (`DormitoryID`),
  ADD KEY `StudentID` (`StudentID`);

--
-- Индексы таблицы `Errors`
--
ALTER TABLE `Errors`
  ADD PRIMARY KEY (`ErrorID`);

--
-- Индексы таблицы `Files`
--
ALTER TABLE `Files`
  ADD PRIMARY KEY (`FileID`),
  ADD KEY `StudentID` (`StudentID`);

--
-- Индексы таблицы `Orphans`
--
ALTER TABLE `Orphans`
  ADD PRIMARY KEY (`OrphanID`),
  ADD KEY `StudentID` (`StudentID`);

--
-- Индексы таблицы `RiskGroups`
--
ALTER TABLE `RiskGroups`
  ADD PRIMARY KEY (`RiskGroupID`),
  ADD KEY `StudentID` (`StudentID`);

--
-- Индексы таблицы `Rooms`
--
ALTER TABLE `Rooms`
  ADD PRIMARY KEY (`RoomID`);

--
-- Индексы таблицы `SocialScholarships`
--
ALTER TABLE `SocialScholarships`
  ADD PRIMARY KEY (`ScholarshipID`),
  ADD KEY `StudentID` (`StudentID`);

--
-- Индексы таблицы `SpecialNeedsStudents`
--
ALTER TABLE `SpecialNeedsStudents`
  ADD PRIMARY KEY (`SpecialNeedsStudentID`),
  ADD KEY `StudentID` (`StudentID`);

--
-- Индексы таблицы `SPPPMeetings`
--
ALTER TABLE `SPPPMeetings`
  ADD PRIMARY KEY (`MeetingID`),
  ADD KEY `StudentID` (`StudentID`);

--
-- Индексы таблицы `Students`
--
ALTER TABLE `Students`
  ADD PRIMARY KEY (`StudentID`);

--
-- Индексы таблицы `SVOStatus`
--
ALTER TABLE `SVOStatus`
  ADD PRIMARY KEY (`SVOStatusID`),
  ADD KEY `StudentID` (`StudentID`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `Departments`
--
ALTER TABLE `Departments`
  MODIFY `DepartmentID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `DisabledStudents`
--
ALTER TABLE `DisabledStudents`
  MODIFY `DisabledStudentID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `Dormitories`
--
ALTER TABLE `Dormitories`
  MODIFY `DormitoryID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `Errors`
--
ALTER TABLE `Errors`
  MODIFY `ErrorID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `Files`
--
ALTER TABLE `Files`
  MODIFY `FileID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `Orphans`
--
ALTER TABLE `Orphans`
  MODIFY `OrphanID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `RiskGroups`
--
ALTER TABLE `RiskGroups`
  MODIFY `RiskGroupID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `Rooms`
--
ALTER TABLE `Rooms`
  MODIFY `RoomID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `SocialScholarships`
--
ALTER TABLE `SocialScholarships`
  MODIFY `ScholarshipID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `SpecialNeedsStudents`
--
ALTER TABLE `SpecialNeedsStudents`
  MODIFY `SpecialNeedsStudentID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `SPPPMeetings`
--
ALTER TABLE `SPPPMeetings`
  MODIFY `MeetingID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `Students`
--
ALTER TABLE `Students`
  MODIFY `StudentID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `SVOStatus`
--
ALTER TABLE `SVOStatus`
  MODIFY `SVOStatusID` int NOT NULL AUTO_INCREMENT;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `DisabledStudents`
--
ALTER TABLE `DisabledStudents`
  ADD CONSTRAINT `disabledstudents_ibfk_1` FOREIGN KEY (`StudentID`) REFERENCES `Students` (`StudentID`);

--
-- Ограничения внешнего ключа таблицы `Dormitories`
--
ALTER TABLE `Dormitories`
  ADD CONSTRAINT `dormitories_ibfk_1` FOREIGN KEY (`StudentID`) REFERENCES `Students` (`StudentID`);

--
-- Ограничения внешнего ключа таблицы `Files`
--
ALTER TABLE `Files`
  ADD CONSTRAINT `files_ibfk_1` FOREIGN KEY (`StudentID`) REFERENCES `Students` (`StudentID`);

--
-- Ограничения внешнего ключа таблицы `Orphans`
--
ALTER TABLE `Orphans`
  ADD CONSTRAINT `orphans_ibfk_1` FOREIGN KEY (`StudentID`) REFERENCES `Students` (`StudentID`);

--
-- Ограничения внешнего ключа таблицы `RiskGroups`
--
ALTER TABLE `RiskGroups`
  ADD CONSTRAINT `riskgroups_ibfk_1` FOREIGN KEY (`StudentID`) REFERENCES `Students` (`StudentID`);

--
-- Ограничения внешнего ключа таблицы `SocialScholarships`
--
ALTER TABLE `SocialScholarships`
  ADD CONSTRAINT `socialscholarships_ibfk_1` FOREIGN KEY (`StudentID`) REFERENCES `Students` (`StudentID`);

--
-- Ограничения внешнего ключа таблицы `SpecialNeedsStudents`
--
ALTER TABLE `SpecialNeedsStudents`
  ADD CONSTRAINT `specialneedsstudents_ibfk_1` FOREIGN KEY (`StudentID`) REFERENCES `Students` (`StudentID`);

--
-- Ограничения внешнего ключа таблицы `SPPPMeetings`
--
ALTER TABLE `SPPPMeetings`
  ADD CONSTRAINT `spppmeetings_ibfk_1` FOREIGN KEY (`StudentID`) REFERENCES `Students` (`StudentID`);

--
-- Ограничения внешнего ключа таблицы `SVOStatus`
--
ALTER TABLE `SVOStatus`
  ADD CONSTRAINT `svostatus_ibfk_1` FOREIGN KEY (`StudentID`) REFERENCES `Students` (`StudentID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
