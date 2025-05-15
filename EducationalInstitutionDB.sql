-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Май 15 2025 г., 11:23
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

--
-- Дамп данных таблицы `Departments`
--

INSERT INTO `Departments` (`DepartmentID`, `DepartmentName`) VALUES
(1, 'ВТ'),
(2, 'АД'),
(3, 'МП'),
(4, 'ЛП'),
(5, 'УК');

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

--
-- Дамп данных таблицы `DisabledStudents`
--

INSERT INTO `DisabledStudents` (`DisabledStudentID`, `StudentID`, `StatusOrder`, `StatusStart`, `StatusEnd`, `DisabilityType`, `Notes`) VALUES
(2, 2, 'Приказ №2', '2025-10-01', '2027-04-12', 'Сенсорная', 'Инвалид'),
(3, 3, 'Приказ №3', '2020-09-01', '2024-11-30', 'Умственная', 'Инвалид'),
(4, 4, 'Приказ №4', '2023-01-12', '2027-01-30', 'Физическая', 'Инвалид'),
(5, 7, 'Приказ №5', '2023-01-12', '2027-01-30', 'Сенсорная', 'Инвалид');

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

--
-- Дамп данных таблицы `Dormitories`
--

INSERT INTO `Dormitories` (`DormitoryID`, `StudentID`, `RoomNumber`, `CheckInDate`, `CheckOutDate`, `Notes`) VALUES
(1, 2, 102, '2022-09-01', '2023-06-30', 'Проживает'),
(2, 3, 103, '2022-09-01', '2021-06-30', 'Проживает'),
(3, 4, 104, '2022-09-01', '2024-06-30', 'Проживает');

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

--
-- Дамп данных таблицы `Files`
--

INSERT INTO `Files` (`FileID`, `StudentID`, `FilePath`, `FileType`) VALUES
(1, 1, '/files/student1.pdf', 'PDF'),
(2, 2, '/files/student2.pdf', 'PDF'),
(3, 3, '/files/student3.pdf', 'PDF'),
(4, 4, '/files/student4.pdf', 'PDF'),
(5, 5, '/files/student3.pdf', 'PDF'),
(6, 6, '/files/student4.pdf', 'PDF'),
(7, 7, '/files/student5.pdf', 'PDF');

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

--
-- Дамп данных таблицы `Orphans`
--

INSERT INTO `Orphans` (`OrphanID`, `StudentID`, `StatusOrder`, `StatusStart`, `StatusEnd`, `Notes`) VALUES
(2, 2, 'Приказ №2', '2025-10-01', '2027-04-12', 'Сирота'),
(3, 3, 'Приказ №3', '2023-01-12', '2027-01-30', 'Сирота'),
(4, 4, 'Приказ №4', '2024-04-01', '2028-06-03', 'Сирота');

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

--
-- Дамп данных таблицы `RiskGroups`
--

INSERT INTO `RiskGroups` (`RiskGroupID`, `StudentID`, `Type`, `RegistrationDate`, `RegistrationReason`, `RemovalDate`, `RemovalReason`, `Notes`) VALUES
(1, 2, 'СОП', '2019-09-01', 'Проблемы с поведением', '2023-06-30', 'Улучшение поведения', 'СОП'),
(2, 3, 'Группа риска', '2017-09-01', 'Проблемы с учебой', '2021-06-30', 'Улучшение успеваемости', 'Группа риска');

-- --------------------------------------------------------

--
-- Структура таблицы `Rooms`
--

CREATE TABLE `Rooms` (
  `RoomID` int NOT NULL,
  `RoomNumber` int NOT NULL,
  `Capacity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `Rooms`
--

INSERT INTO `Rooms` (`RoomID`, `RoomNumber`, `Capacity`) VALUES
(1, 101, 2),
(2, 102, 2),
(3, 103, 2),
(4, 104, 2),
(5, 105, 2);

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

--
-- Дамп данных таблицы `SocialScholarships`
--

INSERT INTO `SocialScholarships` (`ScholarshipID`, `StudentID`, `DocumentBasis`, `PaymentStart`, `PaymentEnd`, `Notes`) VALUES
(6, 1, 'Приказ №1', '2018-09-01', '2022-12-01', 'Социальная стипендия'),
(7, 2, 'Приказ №2', '2019-09-01', '2023-06-30', 'Социальная стипендия'),
(8, 3, 'Приказ №3', '2022-09-01', '2024-08-11', 'Социальная стипендия'),
(9, 6, 'Приказ №4', '2020-09-01', '2024-06-30', 'Социальная стипендия'),
(10, 7, 'Приказ №5', '2019-09-01', '2023-09-03', 'Социальная стипендия');

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

--
-- Дамп данных таблицы `SpecialNeedsStudents`
--

INSERT INTO `SpecialNeedsStudents` (`SpecialNeedsStudentID`, `StudentID`, `StatusOrder`, `StatusStart`, `StatusEnd`, `Notes`) VALUES
(1, 1, 'Приказ №1', '2025-10-01', '2026-04-12', 'ОВЗ'),
(2, 2, 'Приказ №2', '2023-01-12', '2027-01-30', 'ОВЗ'),
(3, 3, 'Приказ №3', '2023-01-12', '2025-01-30', 'ОВЗ'),
(4, 4, 'Приказ №4', '2025-10-01', '2027-04-12', 'ОВЗ'),
(5, 5, 'Приказ №5', '2023-01-12', '2027-01-30', 'ОВЗ');

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

--
-- Дамп данных таблицы `SPPPMeetings`
--

INSERT INTO `SPPPMeetings` (`MeetingID`, `StudentID`, `MeetingDate`, `CallReason`, `PresentEmployees`, `PresentRepresentatives`, `CallCause`, `Decision`, `Notes`) VALUES
(3, 1, '2024-10-01', 'Проблемы с успеваемостью', 'Плешков', 'Родители', 'Низкая успеваемость', 'Наблюдение', 'СППП'),
(4, 2, '2023-10-01', 'Проблемы с поведением', 'Петрова', 'Родители', 'Нарушение дисциплины', 'Наблюдение', 'СППП');

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

--
-- Дамп данных таблицы `Students`
--

INSERT INTO `Students` (`StudentID`, `LastName`, `FirstName`, `MiddleName`, `BirthDate`, `Gender`, `ContactNumber`, `EducationLevel`, `Department`, `GroupName`, `FundingType`, `AdmissionYear`, `GraduationYear`, `DismissalInfo`, `DismissalDate`, `Notes`, `ParentsInfo`, `Penalties`, `IsActive`) VALUES
(1, 'Омельченко', 'Данил', 'Андреевич', '2006-03-28', 'Мужской', '+79123456789', '9 кл.', 'ВТ', 'ИСВ-22-1', 'Бюджет', 2022, 2026, NULL, NULL, 'Хорошист', 'Мать: Омельченко Светлана', 'Нет', 1),
(2, 'Елышева', 'Анна', 'Сергеевна', '2006-08-22', 'Женский', '+79221234567', '9 кл.', 'ВТ', 'ИСВ-22-1', 'Бюджет', 2019, 2023, NULL, NULL, 'Хорошист', 'Отец: Елтышев Сергей', 'Нет', 1),
(3, 'Сидоров', 'Алексей', 'Петрович', '2003-11-30', 'Мужской', '+79324567890', '10 кл.', 'МП', 'МП-19-1', 'внебюджет', 2016, 2019, 'Отчислен', '2020-06-15', 'Троечник', 'Мать: Сидорова Ольга', 'Да', 1),
(4, 'Вараксина', 'Елена', 'Александровна', '2007-03-10', 'Женский', '+79427890123', '9 кл.', 'ЛП', 'ЛП-23-4', 'Бюджет', 2023, 2027, NULL, NULL, 'Отличник', 'Отец: Вараксин Александр', 'Нет', 1),
(5, 'Моисеев', 'Андрей', 'Игоревич', '2006-07-18', 'Мужской', '+79522345678', '9 кл.', 'ВТ', 'ИСВ-22-1', 'Бюджет', 2019, 2023, NULL, NULL, 'Хорошист', 'Мать: Моисеева Ирина', 'Нет', 1),
(6, 'Плешков', 'Данил', 'Александрович', '2006-05-15', 'Мужской', '+79123426729', '9 кл.', 'ВТ', 'ИСВ-22-1', 'Бюджет', 2022, 2026, NULL, NULL, 'Отличник', 'Мать: ПлешковА Анна', 'Нет', 1),
(7, 'Петрова', 'Мария', 'Сергеевна', '2003-08-22', 'Женский', '+79221132234', '11 кл.', 'АД', 'АД-19-2', 'Внебюджет', 2019, 2023, NULL, NULL, 'Хорошист', 'Отец: Петров Сергей', 'Нет', 1),
(8, 'Моденов', 'Павел', 'Алексеевич', '2006-11-30', 'Мужской', '+79324564890', '9 кл.', 'ВТ', 'ИСВ-22-1', 'Бюджет', 2022, 2026, NULL, '2023-06-15', 'Троечник', 'Мать: Моденова Анастасия', 'Да', 1),
(9, 'Маслова', 'Анастасия', 'Сергеевна', '2006-10-01', 'Женский', '+79223028533', '9 кл.', 'ВТ', 'ИСВ-22-1', 'Бюджет', 2022, 2026, NULL, NULL, 'Отличник', 'Отец: Маслов Сергей', 'Нет', NULL);

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
-- Дамп данных таблицы `SVOStatus`
--

INSERT INTO `SVOStatus` (`SVOStatusID`, `StudentID`, `DocumentBasis`, `StatusStart`, `StatusEnd`, `Notes`) VALUES
(1, 4, 'Приказ №1', '2018-09-01', '2023-06-30', 'СВО'),
(2, 2, 'Приказ №2', '2019-09-01', '2023-06-30', 'СВО'),
(3, 3, 'Приказ №3', '2017-09-01', '2023-06-30', 'СВО'),
(4, 6, 'Приказ №4', '2020-09-01', '2024-06-30', 'СВО'),
(5, 7, 'Приказ №5', '2019-09-01', '2023-06-30', 'СВО');

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
  MODIFY `DepartmentID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `DisabledStudents`
--
ALTER TABLE `DisabledStudents`
  MODIFY `DisabledStudentID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `Dormitories`
--
ALTER TABLE `Dormitories`
  MODIFY `DormitoryID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `Errors`
--
ALTER TABLE `Errors`
  MODIFY `ErrorID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `Files`
--
ALTER TABLE `Files`
  MODIFY `FileID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT для таблицы `Orphans`
--
ALTER TABLE `Orphans`
  MODIFY `OrphanID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `RiskGroups`
--
ALTER TABLE `RiskGroups`
  MODIFY `RiskGroupID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `Rooms`
--
ALTER TABLE `Rooms`
  MODIFY `RoomID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `SocialScholarships`
--
ALTER TABLE `SocialScholarships`
  MODIFY `ScholarshipID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `SpecialNeedsStudents`
--
ALTER TABLE `SpecialNeedsStudents`
  MODIFY `SpecialNeedsStudentID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `SPPPMeetings`
--
ALTER TABLE `SPPPMeetings`
  MODIFY `MeetingID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `Students`
--
ALTER TABLE `Students`
  MODIFY `StudentID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `SVOStatus`
--
ALTER TABLE `SVOStatus`
  MODIFY `SVOStatusID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
