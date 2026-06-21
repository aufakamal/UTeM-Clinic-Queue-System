-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 21, 2026 at 06:15 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `clinic_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `appointmentID` int(11) NOT NULL,
  `userID` varchar(20) NOT NULL,
  `slotID` int(11) NOT NULL,
  `appointmentType` enum('Same-Day','Scheduled') NOT NULL,
  `appointmentStatus` enum('Booked','Completed','Cancelled','No Show') NOT NULL DEFAULT 'Booked',
  `appointmentFor` enum('Self','Dependant') NOT NULL DEFAULT 'Self',
  `dependantName` varchar(100) DEFAULT NULL,
  `dependantRelationship` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointment`
--

INSERT INTO `appointment` (`appointmentID`, `userID`, `slotID`, `appointmentType`, `appointmentStatus`, `appointmentFor`, `dependantName`, `dependantRelationship`) VALUES
(1, 'B032410101', 1, 'Same-Day', 'Booked', 'Self', NULL, NULL),
(2, 'B032410102', 1, 'Same-Day', 'Booked', 'Self', NULL, NULL),
(3, 'B032410103', 2, 'Same-Day', 'Booked', 'Self', NULL, NULL),
(4, 'B032410104', 7, 'Scheduled', 'Booked', 'Self', NULL, NULL),
(5, 'B032410105', 8, 'Scheduled', 'Booked', 'Self', NULL, NULL),
(6, 'B032410106', 9, 'Scheduled', 'Booked', 'Self', NULL, NULL),
(7, 'B032410107', 10, 'Scheduled', 'Booked', 'Self', NULL, NULL),
(8, 'B032410108', 11, 'Scheduled', 'Booked', 'Self', NULL, NULL),
(9, 'S032410001', 7, 'Scheduled', 'Booked', 'Dependant', 'Adam Razak', 'Son'),
(10, 'S032410002', 8, 'Scheduled', 'Booked', 'Self', NULL, NULL),
(11, 'S032410003', 9, 'Scheduled', 'Booked', 'Dependant', 'Nur Hafizah', 'Daughter'),
(12, 'S032410004', 10, 'Scheduled', 'Booked', 'Self', NULL, NULL),
(13, 'S032410005', 11, 'Scheduled', 'Booked', 'Dependant', 'Aiman Karim', 'Son');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `attendanceID` int(11) NOT NULL,
  `appointmentID` int(11) NOT NULL,
  `attendanceStatus` enum('Pending','Arrived','No Show') NOT NULL DEFAULT 'Pending',
  `checkInTime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`attendanceID`, `appointmentID`, `attendanceStatus`, `checkInTime`) VALUES
(1, 1, 'Arrived', '2026-06-21 08:05:00'),
(2, 2, 'Arrived', '2026-06-21 08:10:00'),
(3, 3, 'Arrived', '2026-06-21 12:15:00'),
(4, 4, 'Arrived', '2026-06-21 09:00:00'),
(5, 5, 'Arrived', '2026-06-21 10:00:00'),
(6, 6, 'Pending', NULL),
(7, 7, 'Arrived', '2026-06-22 09:05:00'),
(8, 8, 'Pending', NULL),
(9, 9, 'Arrived', '2026-06-21 09:10:00'),
(10, 10, 'Pending', NULL),
(11, 11, 'Arrived', '2026-06-21 11:05:00'),
(12, 12, 'Pending', NULL),
(13, 13, 'Pending', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `consultation`
--

CREATE TABLE `consultation` (
  `consultationID` int(11) NOT NULL,
  `queueID` int(11) NOT NULL,
  `doctorUserID` varchar(20) NOT NULL,
  `startTime` datetime DEFAULT NULL,
  `endTime` datetime DEFAULT NULL,
  `reasonForVisit` text DEFAULT NULL,
  `clinicalFindings` text DEFAULT NULL,
  `diagnosis` text DEFAULT NULL,
  `treatmentPlan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `consultation`
--

INSERT INTO `consultation` (`consultationID`, `queueID`, `doctorUserID`, `startTime`, `endTime`, `reasonForVisit`, `clinicalFindings`, `diagnosis`, `treatmentPlan`) VALUES
(1, 1, 'D032410001', '2026-06-21 08:05:00', '2026-06-21 08:20:00', 'Fever', 'Temperature 38.2C', 'Viral Fever', 'Rest and medication'),
(2, 2, 'D032410002', '2026-06-21 08:25:00', '2026-06-21 08:35:00', 'Headache', 'No neurological deficit', 'Tension Headache', 'Pain relief medication'),
(3, 4, 'D032410003', '2026-06-21 09:00:00', '2026-06-21 09:15:00', 'Flu Symptoms', 'Runny nose and cough', 'Upper Respiratory Infection', 'Symptomatic treatment'),
(4, 7, 'D032410004', '2026-06-21 11:05:00', '2026-06-21 11:20:00', 'Blood Pressure Review', 'BP 145/90', 'Hypertension', 'Continue medication');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_profile`
--

CREATE TABLE `doctor_profile` (
  `userID` varchar(20) NOT NULL,
  `docLicenseNo` varchar(50) NOT NULL,
  `specialization` varchar(100) DEFAULT NULL,
  `roomNo` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor_profile`
--

INSERT INTO `doctor_profile` (`userID`, `docLicenseNo`, `specialization`, `roomNo`) VALUES
('D032410001', 'MMC10001', 'General Medicine', 'Room 1'),
('D032410002', 'MMC10002', 'Family Medicine', 'Room 2'),
('D032410003', 'MMC10003', 'Internal Medicine', 'Room 3'),
('D032410004', 'MMC10004', 'Primary Care', 'Room 4'),
('D032410005', 'MMC10005', 'Occupational Health', 'Room 5');

-- --------------------------------------------------------

--
-- Table structure for table `medicine`
--

CREATE TABLE `medicine` (
  `medicineID` int(11) NOT NULL,
  `medicineName` varchar(100) NOT NULL,
  `genericName` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `stockQuantity` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medicine`
--

INSERT INTO `medicine` (`medicineID`, `medicineName`, `genericName`, `description`, `stockQuantity`) VALUES
(1, 'Panadol', 'Paracetamol', 'Pain reliever', 200),
(2, 'Uphamol', 'Paracetamol', 'Pain reliever', 150),
(3, 'Brufen', 'Ibuprofen', 'Anti-inflammatory', 120),
(4, 'Amoxil', 'Amoxicillin', 'Antibiotic', 100),
(5, 'Augmentin', 'Amoxicillin + Clavulanic Acid', 'Antibiotic', 80),
(6, 'Zyrtec', 'Cetirizine', 'Antihistamine', 100),
(7, 'Claritin', 'Loratadine', 'Antihistamine', 90),
(8, 'Ventolin', 'Salbutamol', 'Asthma inhaler', 50),
(9, 'Metformin', 'Metformin', 'Diabetes medication', 120),
(10, 'Norvasc', 'Amlodipine', 'Blood pressure medication', 110),
(11, 'Omeprazole', 'Omeprazole', 'Acid reflux treatment', 100),
(12, 'Actifed', 'Triprolidine + Pseudoephedrine', 'Cold medicine', 90),
(13, 'Difflam', 'Benzydamine', 'Sore throat treatment', 70),
(14, 'Voltaren', 'Diclofenac', 'Pain relief', 60),
(15, 'Prednisolone', 'Prednisolone', 'Steroid', 50),
(16, 'Vitamin C', 'Ascorbic Acid', 'Supplement', 300),
(17, 'Vitamin B Complex', 'Vitamin B Complex', 'Supplement', 250),
(18, 'ORS', 'Oral Rehydration Salts', 'Rehydration', 150),
(19, 'Charcoal Tablet', 'Activated Charcoal', 'Food poisoning', 40),
(20, 'Methyl Salicylate Cream', 'Methyl Salicylate', 'Muscle pain relief', 40);

-- --------------------------------------------------------

--
-- Table structure for table `patient_profile`
--

CREATE TABLE `patient_profile` (
  `userID` varchar(20) NOT NULL,
  `patientType` enum('Student','Staff') NOT NULL,
  `allergy` text DEFAULT NULL,
  `chronicCondition` text DEFAULT NULL,
  `currentMed` text DEFAULT NULL,
  `bloodType` enum('A+','A-','B+','B-','AB+','AB-','O+','O-') DEFAULT NULL,
  `emergencyContactName` varchar(100) DEFAULT NULL,
  `emergencyContactPhone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient_profile`
--

INSERT INTO `patient_profile` (`userID`, `patientType`, `allergy`, `chronicCondition`, `currentMed`, `bloodType`, `emergencyContactName`, `emergencyContactPhone`) VALUES
('B032410101', 'Student', 'Peanuts', NULL, NULL, 'O+', 'Abu Bakar', '0191111111'),
('B032410102', 'Student', NULL, NULL, NULL, 'A+', 'Azman', '0192222222'),
('B032410103', 'Student', 'Dust', 'Asthma', 'Ventolin', 'B+', 'Jamaludin', '0193333333'),
('B032410104', 'Student', NULL, NULL, NULL, 'AB+', 'Kamal', '0194444444'),
('B032410105', 'Student', NULL, NULL, NULL, 'O-', 'Tan Ah Kow', '0195555555'),
('B032410106', 'Student', 'Seafood', NULL, NULL, 'B-', 'Yahya', '0196666666'),
('B032410107', 'Student', NULL, NULL, NULL, 'A-', 'Roslan', '0197777777'),
('B032410108', 'Student', NULL, NULL, NULL, 'AB-', 'Tan Wei Seng', '0198888888'),
('B032410109', 'Student', NULL, 'Hypertension', 'Amlodipine', 'O+', 'Razak', '0199999999'),
('B032410110', 'Student', NULL, NULL, NULL, 'A+', 'Wong Ah Chai', '0191010101'),
('S032410001', 'Staff', NULL, 'Hypertension', 'Amlodipine', 'A+', 'Siti', '0161111111'),
('S032410002', 'Staff', 'Seafood', NULL, NULL, 'B+', 'Azman', '0162222222'),
('S032410003', 'Staff', NULL, 'Diabetes', 'Metformin', 'O+', 'Aisyah', '0163333333'),
('S032410004', 'Staff', NULL, NULL, NULL, 'AB+', 'Rahman', '0164444444'),
('S032410005', 'Staff', 'Dust', 'Asthma', 'Ventolin', 'A-', 'Faridah', '0165555555');

-- --------------------------------------------------------

--
-- Table structure for table `pharmacist_profile`
--

CREATE TABLE `pharmacist_profile` (
  `userID` varchar(20) NOT NULL,
  `licenseNo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pharmacist_profile`
--

INSERT INTO `pharmacist_profile` (`userID`, `licenseNo`) VALUES
('P032410001', 'PHM2025001'),
('P032410002', 'PHM2025002');

-- --------------------------------------------------------

--
-- Table structure for table `prescription`
--

CREATE TABLE `prescription` (
  `prescriptionID` int(11) NOT NULL,
  `consultationID` int(11) NOT NULL,
  `prescriptionDate` date NOT NULL,
  `status` enum('Pending','Ready','Dispensed') NOT NULL DEFAULT 'Pending',
  `note` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prescription`
--

INSERT INTO `prescription` (`prescriptionID`, `consultationID`, `prescriptionDate`, `status`, `note`) VALUES
(1, 1, '2026-06-21', 'Dispensed', NULL),
(2, 2, '2026-06-21', 'Ready', NULL),
(3, 3, '2026-06-21', 'Pending', 'Awaiting preparation'),
(4, 4, '2026-06-21', 'Dispensed', 'Collected successfully');

-- --------------------------------------------------------

--
-- Table structure for table `prescription_item`
--

CREATE TABLE `prescription_item` (
  `prescriptionItemID` int(11) NOT NULL,
  `prescriptionID` int(11) NOT NULL,
  `medicineID` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `dosage` varchar(100) DEFAULT NULL,
  `frequency` varchar(100) DEFAULT NULL,
  `duration` varchar(100) DEFAULT NULL,
  `instructions` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prescription_item`
--

INSERT INTO `prescription_item` (`prescriptionItemID`, `prescriptionID`, `medicineID`, `quantity`, `dosage`, `frequency`, `duration`, `instructions`) VALUES
(1, 1, 1, 10, '2 tablets', '3 times daily', '5 days', 'Take after meals'),
(2, 1, 6, 5, '1 tablet', 'Once daily', '5 days', 'Take before sleep'),
(3, 2, 3, 15, '1 tablet', 'Twice daily', '7 days', 'Take after meals'),
(4, 3, 1, 10, '2 tablets', '3 times daily', '5 days', 'Take after meals'),
(5, 4, 10, 30, '1 tablet', 'Once daily', '30 days', 'Take every morning'),
(6, 4, 9, 30, '1 tablet', 'Twice daily', '30 days', 'Take after meals');

-- --------------------------------------------------------

--
-- Table structure for table `queue`
--

CREATE TABLE `queue` (
  `queueID` int(11) NOT NULL,
  `attendanceID` int(11) NOT NULL,
  `queueNo` int(11) NOT NULL,
  `queueStatus` enum('Waiting','Called','Completed') NOT NULL DEFAULT 'Waiting'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `queue`
--

INSERT INTO `queue` (`queueID`, `attendanceID`, `queueNo`, `queueStatus`) VALUES
(1, 1, 1, 'Completed'),
(2, 2, 2, 'Completed'),
(3, 3, 3, 'Waiting'),
(4, 4, 4, 'Completed'),
(5, 5, 5, 'Called'),
(6, 7, 6, 'Waiting'),
(7, 9, 7, 'Completed'),
(8, 11, 8, 'Waiting');

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `roleID` int(11) NOT NULL,
  `roleName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`roleID`, `roleName`) VALUES
(1, 'Admin'),
(2, 'Doctor'),
(4, 'Patient'),
(3, 'Pharmacist');

-- --------------------------------------------------------

--
-- Table structure for table `time_slot`
--

CREATE TABLE `time_slot` (
  `slotID` int(11) NOT NULL,
  `slotDate` date NOT NULL,
  `startTime` time NOT NULL,
  `endTime` time NOT NULL,
  `slotType` enum('Same-Day Morning','Same-Day Afternoon','Scheduled') NOT NULL,
  `capacity` int(11) NOT NULL,
  `slotStatus` enum('Available','Closed') DEFAULT 'Available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `time_slot`
--

INSERT INTO `time_slot` (`slotID`, `slotDate`, `startTime`, `endTime`, `slotType`, `capacity`, `slotStatus`) VALUES
(1, '2026-06-21', '08:00:00', '12:00:00', 'Same-Day Morning', 15, 'Available'),
(2, '2026-06-21', '12:00:00', '19:00:00', 'Same-Day Afternoon', 15, 'Available'),
(3, '2026-06-22', '08:00:00', '12:00:00', 'Same-Day Morning', 15, 'Available'),
(4, '2026-06-22', '12:00:00', '19:00:00', 'Same-Day Afternoon', 15, 'Available'),
(5, '2026-06-23', '08:00:00', '12:00:00', 'Same-Day Morning', 15, 'Available'),
(6, '2026-06-23', '12:00:00', '19:00:00', 'Same-Day Afternoon', 15, 'Available'),
(7, '2026-06-21', '09:00:00', '10:00:00', 'Scheduled', 5, 'Available'),
(8, '2026-06-21', '10:00:00', '11:00:00', 'Scheduled', 5, 'Available'),
(9, '2026-06-21', '11:00:00', '12:00:00', 'Scheduled', 5, 'Available'),
(10, '2026-06-22', '09:00:00', '10:00:00', 'Scheduled', 5, 'Available'),
(11, '2026-06-22', '10:00:00', '11:00:00', 'Scheduled', 5, 'Available'),
(12, '2026-06-22', '11:00:00', '12:00:00', 'Scheduled', 5, 'Available');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userID` varchar(20) NOT NULL,
  `fullName` varchar(100) NOT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `dateOfBirth` date DEFAULT NULL,
  `address` text DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `phoneNo` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userID`, `fullName`, `gender`, `dateOfBirth`, `address`, `email`, `phoneNo`, `password`) VALUES
('A032410001', 'Muhammad Hafiz Hamdan', 'Male', '1990-01-15', 'Taman Bukit Beruang, Melaka', 'hafiz.admin@utem.edu.my', '0121111111', '$2y$10$6XaFinvhhDXEao4NwwiCiO6C42wxeVKZ/K2EHthoDDKtY2a09rBrW'),
('A032410002', 'Nurul Syafiqah Rahman', 'Female', '1992-06-20', 'Ayer Keroh, Melaka', 'syafiqah.admin@utem.edu.my', '0122222222', '$2y$10$6XaFinvhhDXEao4NwwiCiO6C42wxeVKZ/K2EHthoDDKtY2a09rBrW'),
('B032410101', 'Ali Imran Abu Bakar', 'Male', '2003-05-01', 'Kolej Kediaman Satria', 'ali@student.utem.edu.my', '0171111111', '$2y$10$WyzhqBex28.YaB3TAOhl3OCroQU1ArzXOtZrd9LcLOeYvOj/QB.Ga'),
('B032410102', 'Nur Ain Syuhada Azman', 'Female', '2003-06-02', 'Kolej Kediaman Lestari', 'nurain@student.utem.edu.my', '0172222222', '$2y$10$WyzhqBex28.YaB3TAOhl3OCroQU1ArzXOtZrd9LcLOeYvOj/QB.Ga'),
('B032410103', 'Ahmad Firdaus Jamaludin', 'Male', '2002-07-03', 'Kolej Kediaman Satria', 'firdaus@student.utem.edu.my', '0173333333', '$2y$10$WyzhqBex28.YaB3TAOhl3OCroQU1ArzXOtZrd9LcLOeYvOj/QB.Ga'),
('B032410104', 'Siti Sarah Kamal', 'Female', '2003-08-04', 'Kolej Kediaman Lestari', 'sarah@student.utem.edu.my', '0174444444', '$2y$10$WyzhqBex28.YaB3TAOhl3OCroQU1ArzXOtZrd9LcLOeYvOj/QB.Ga'),
('B032410105', 'Jason Tan Wei Ming', 'Male', '2002-09-05', 'Kolej Kediaman Tuah', 'jason@student.utem.edu.my', '0175555555', '$2y$10$WyzhqBex28.YaB3TAOhl3OCroQU1ArzXOtZrd9LcLOeYvOj/QB.Ga'),
('B032410106', 'Nurul Izzati Yahya', 'Female', '2003-02-14', 'Kolej Kediaman Tuah', 'izzati@student.utem.edu.my', '0176666666', '$2y$10$WyzhqBex28.YaB3TAOhl3OCroQU1ArzXOtZrd9LcLOeYvOj/QB.Ga'),
('B032410107', 'Muhammad Danish Roslan', 'Male', '2002-10-10', 'Kolej Kediaman Satria', 'danish@student.utem.edu.my', '0177777777', '$2y$10$WyzhqBex28.YaB3TAOhl3OCroQU1ArzXOtZrd9LcLOeYvOj/QB.Ga'),
('B032410108', 'Tan Jia Hui', 'Female', '2003-03-18', 'Kolej Kediaman Lestari', 'jiahui@student.utem.edu.my', '0178888888', '$2y$10$WyzhqBex28.YaB3TAOhl3OCroQU1ArzXOtZrd9LcLOeYvOj/QB.Ga'),
('B032410109', 'Mohamad Akmal Razak', 'Male', '2002-11-22', 'Kolej Kediaman Tuah', 'akmal@student.utem.edu.my', '0179999999', '$2y$10$WyzhqBex28.YaB3TAOhl3OCroQU1ArzXOtZrd9LcLOeYvOj/QB.Ga'),
('B032410110', 'Wong Kai Jie', 'Male', '2003-01-30', 'Kolej Kediaman Satria', 'kaijie@student.utem.edu.my', '0171010101', '$2y$10$WyzhqBex28.YaB3TAOhl3OCroQU1ArzXOtZrd9LcLOeYvOj/QB.Ga'),
('D032410001', 'Dr Ahmad Hakim Ismail', 'Male', '1980-03-15', 'Melaka', 'ahmad@utem.edu.my', '0131111111', '$2y$10$iPzSwKwapnBcGFdT92TwUO5UY1gv9W3BLGZumZmNezQNX0uSTSwUe'),
('D032410002', 'Dr Nur Aisyah Zulkifli', 'Female', '1984-07-10', 'Melaka', 'aisyah@utem.edu.my', '0132222222', '$2y$10$iPzSwKwapnBcGFdT92TwUO5UY1gv9W3BLGZumZmNezQNX0uSTSwUe'),
('D032410003', 'Dr Jason Lim Wei Kiat', 'Male', '1979-05-20', 'Melaka', 'jason@utem.edu.my', '0133333333', '$2y$10$iPzSwKwapnBcGFdT92TwUO5UY1gv9W3BLGZumZmNezQNX0uSTSwUe'),
('D032410004', 'Dr Siti Hajar Mohamad', 'Female', '1982-11-11', 'Melaka', 'siti@utem.edu.my', '0134444444', '$2y$10$iPzSwKwapnBcGFdT92TwUO5UY1gv9W3BLGZumZmNezQNX0uSTSwUe'),
('D032410005', 'Dr Kumaravel Arumugam', 'Male', '1981-08-18', 'Melaka', 'kumar@utem.edu.my', '0135555555', '$2y$10$iPzSwKwapnBcGFdT92TwUO5UY1gv9W3BLGZumZmNezQNX0uSTSwUe'),
('P032410001', 'Farah Nadia Hassan', 'Female', '1988-04-12', 'Melaka', 'farah@utem.edu.my', '0141111111', '$2y$10$Kr8NsU9CU41PSDygYR9YPu0HCbfBVLI1z/sx0nDeParDtZoSq4PT6'),
('P032410002', 'Aiman Firdaus Mohd Noor', 'Male', '1989-09-25', 'Melaka', 'aiman@utem.edu.my', '0142222222', '$2y$10$Kr8NsU9CU41PSDygYR9YPu0HCbfBVLI1z/sx0nDeParDtZoSq4PT6'),
('S032410001', 'Razak Abdullah', 'Male', '1980-01-01', 'UTeM Staff Quarters', 'razak@utem.edu.my', '0181111111', '$2y$10$P4orhZPwFi0mFjmff2jkuObi2ugKogHCDk8HEFRVAKAD91azc1bRK'),
('S032410002', 'Salmah Mohd Noor', 'Female', '1982-02-02', 'UTeM Staff Quarters', 'salmah@utem.edu.my', '0182222222', '$2y$10$P4orhZPwFi0mFjmff2jkuObi2ugKogHCDk8HEFRVAKAD91azc1bRK'),
('S032410003', 'Hafiz Rahman', 'Male', '1985-03-03', 'UTeM Staff Quarters', 'hafiz@utem.edu.my', '0183333333', '$2y$10$P4orhZPwFi0mFjmff2jkuObi2ugKogHCDk8HEFRVAKAD91azc1bRK'),
('S032410004', 'Aisyah Hassan', 'Female', '1984-04-04', 'UTeM Staff Quarters', 'aisyah.staff@utem.edu.my', '0184444444', '$2y$10$P4orhZPwFi0mFjmff2jkuObi2ugKogHCDk8HEFRVAKAD91azc1bRK'),
('S032410005', 'Faizal Karim', 'Male', '1983-05-05', 'UTeM Staff Quarters', 'faizal@utem.edu.my', '0185555555', '$2y$10$P4orhZPwFi0mFjmff2jkuObi2ugKogHCDk8HEFRVAKAD91azc1bRK');

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

CREATE TABLE `user_role` (
  `userRoleID` int(11) NOT NULL,
  `userID` varchar(20) NOT NULL,
  `roleID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_role`
--

INSERT INTO `user_role` (`userRoleID`, `userID`, `roleID`) VALUES
(25, 'A032410001', 1),
(26, 'A032410002', 1),
(27, 'D032410001', 2),
(28, 'D032410002', 2),
(29, 'D032410003', 2),
(30, 'D032410004', 2),
(31, 'D032410005', 2),
(32, 'P032410001', 3),
(33, 'P032410002', 3),
(34, 'B032410101', 4),
(35, 'B032410102', 4),
(36, 'B032410103', 4),
(37, 'B032410104', 4),
(38, 'B032410105', 4),
(39, 'B032410106', 4),
(40, 'B032410107', 4),
(41, 'B032410108', 4),
(42, 'B032410109', 4),
(43, 'B032410110', 4),
(44, 'S032410001', 4),
(45, 'S032410002', 4),
(46, 'S032410003', 4),
(47, 'S032410004', 4),
(48, 'S032410005', 4);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`appointmentID`),
  ADD KEY `userID` (`userID`),
  ADD KEY `slotID` (`slotID`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`attendanceID`),
  ADD KEY `appointmentID` (`appointmentID`);

--
-- Indexes for table `consultation`
--
ALTER TABLE `consultation`
  ADD PRIMARY KEY (`consultationID`),
  ADD KEY `queueID` (`queueID`),
  ADD KEY `doctorUserID` (`doctorUserID`);

--
-- Indexes for table `doctor_profile`
--
ALTER TABLE `doctor_profile`
  ADD PRIMARY KEY (`userID`);

--
-- Indexes for table `medicine`
--
ALTER TABLE `medicine`
  ADD PRIMARY KEY (`medicineID`);

--
-- Indexes for table `patient_profile`
--
ALTER TABLE `patient_profile`
  ADD PRIMARY KEY (`userID`);

--
-- Indexes for table `pharmacist_profile`
--
ALTER TABLE `pharmacist_profile`
  ADD PRIMARY KEY (`userID`);

--
-- Indexes for table `prescription`
--
ALTER TABLE `prescription`
  ADD PRIMARY KEY (`prescriptionID`),
  ADD KEY `consultationID` (`consultationID`);

--
-- Indexes for table `prescription_item`
--
ALTER TABLE `prescription_item`
  ADD PRIMARY KEY (`prescriptionItemID`),
  ADD KEY `prescriptionID` (`prescriptionID`),
  ADD KEY `medicineID` (`medicineID`);

--
-- Indexes for table `queue`
--
ALTER TABLE `queue`
  ADD PRIMARY KEY (`queueID`),
  ADD KEY `attendanceID` (`attendanceID`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`roleID`),
  ADD UNIQUE KEY `roleName` (`roleName`);

--
-- Indexes for table `time_slot`
--
ALTER TABLE `time_slot`
  ADD PRIMARY KEY (`slotID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userID`);

--
-- Indexes for table `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`userRoleID`),
  ADD KEY `userID` (`userID`),
  ADD KEY `roleID` (`roleID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `appointmentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `attendanceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `consultation`
--
ALTER TABLE `consultation`
  MODIFY `consultationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `medicine`
--
ALTER TABLE `medicine`
  MODIFY `medicineID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `prescription`
--
ALTER TABLE `prescription`
  MODIFY `prescriptionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `prescription_item`
--
ALTER TABLE `prescription_item`
  MODIFY `prescriptionItemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `queue`
--
ALTER TABLE `queue`
  MODIFY `queueID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `roleID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `time_slot`
--
ALTER TABLE `time_slot`
  MODIFY `slotID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `user_role`
--
ALTER TABLE `user_role`
  MODIFY `userRoleID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointment`
--
ALTER TABLE `appointment`
  ADD CONSTRAINT `appointment_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`),
  ADD CONSTRAINT `appointment_ibfk_2` FOREIGN KEY (`slotID`) REFERENCES `time_slot` (`slotID`);

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`appointmentID`) REFERENCES `appointment` (`appointmentID`) ON DELETE CASCADE;

--
-- Constraints for table `consultation`
--
ALTER TABLE `consultation`
  ADD CONSTRAINT `consultation_ibfk_1` FOREIGN KEY (`queueID`) REFERENCES `queue` (`queueID`) ON DELETE CASCADE,
  ADD CONSTRAINT `consultation_ibfk_2` FOREIGN KEY (`doctorUserID`) REFERENCES `user` (`userID`);

--
-- Constraints for table `doctor_profile`
--
ALTER TABLE `doctor_profile`
  ADD CONSTRAINT `doctor_profile_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`) ON DELETE CASCADE;

--
-- Constraints for table `patient_profile`
--
ALTER TABLE `patient_profile`
  ADD CONSTRAINT `patient_profile_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`) ON DELETE CASCADE;

--
-- Constraints for table `pharmacist_profile`
--
ALTER TABLE `pharmacist_profile`
  ADD CONSTRAINT `pharmacist_profile_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`) ON DELETE CASCADE;

--
-- Constraints for table `prescription`
--
ALTER TABLE `prescription`
  ADD CONSTRAINT `prescription_ibfk_1` FOREIGN KEY (`consultationID`) REFERENCES `consultation` (`consultationID`) ON DELETE CASCADE;

--
-- Constraints for table `prescription_item`
--
ALTER TABLE `prescription_item`
  ADD CONSTRAINT `prescription_item_ibfk_1` FOREIGN KEY (`prescriptionID`) REFERENCES `prescription` (`prescriptionID`) ON DELETE CASCADE,
  ADD CONSTRAINT `prescription_item_ibfk_2` FOREIGN KEY (`medicineID`) REFERENCES `medicine` (`medicineID`);

--
-- Constraints for table `queue`
--
ALTER TABLE `queue`
  ADD CONSTRAINT `queue_ibfk_1` FOREIGN KEY (`attendanceID`) REFERENCES `attendance` (`attendanceID`) ON DELETE CASCADE;

--
-- Constraints for table `user_role`
--
ALTER TABLE `user_role`
  ADD CONSTRAINT `user_role_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_role_ibfk_2` FOREIGN KEY (`roleID`) REFERENCES `role` (`roleID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
