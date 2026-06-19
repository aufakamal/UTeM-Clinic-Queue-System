-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 18, 2026 at 07:53 PM
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
  `appointmentID` varchar(10) NOT NULL,
  `appointmentType` varchar(20) NOT NULL,
  `appointmentStatus` varchar(20) NOT NULL,
  `appointmentFor` varchar(20) NOT NULL,
  `dependantName` varchar(100) DEFAULT NULL,
  `dependantRelationship` varchar(50) DEFAULT NULL,
  `userID` varchar(20) NOT NULL,
  `slotID` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointment`
--

INSERT INTO `appointment` (`appointmentID`, `appointmentType`, `appointmentStatus`, `appointmentFor`, `dependantName`, `dependantRelationship`, `userID`, `slotID`) VALUES
('A001', 'Scheduled', 'Completed', 'Self', NULL, NULL, 'B032410101', 'TS001'),
('A002', 'Scheduled', 'Booked', 'Dependant', 'Aiman Rahman', 'Son', 'S032410005', 'TS002'),
('A003', 'Scheduled', 'Completed', 'Self', NULL, NULL, 'D032410102', 'TS001'),
('A004', 'Scheduled', 'Booked', 'Self', NULL, NULL, 'P032410104', 'TS004'),
('A005', 'Scheduled', 'Cancelled', 'Self', NULL, NULL, 'M032410103', 'TS003'),
('A006', 'Same-Day', 'Booked', 'Self', NULL, NULL, 'B032410101', 'TS002'),
('A007', 'Scheduled', 'Booked', 'Dependant', 'Aiman Rahman', 'Son', 'S032410005', 'TS002'),
('A008', 'Scheduled', 'Completed', 'Self', NULL, NULL, 'P032410104', 'TS003');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `attendanceID` varchar(10) NOT NULL,
  `checkInTime` datetime NOT NULL,
  `attendanceStatus` varchar(20) NOT NULL,
  `appointmentID` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`attendanceID`, `checkInTime`, `attendanceStatus`, `appointmentID`) VALUES
('AT001', '2026-06-25 08:10:00', 'Arrived', 'A001'),
('AT003', '2026-06-25 08:20:00', 'Arrived', 'A003'),
('AT008', '2026-06-25 10:05:00', 'Arrived', 'A008');

-- --------------------------------------------------------

--
-- Table structure for table `consultation`
--

CREATE TABLE `consultation` (
  `consultationID` varchar(10) NOT NULL,
  `startTime` datetime NOT NULL,
  `endTime` datetime DEFAULT NULL,
  `queueID` varchar(10) NOT NULL,
  `doctorUserID` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `consultation`
--

INSERT INTO `consultation` (`consultationID`, `startTime`, `endTime`, `queueID`, `doctorUserID`) VALUES
('C001', '2026-06-25 08:15:00', '2026-06-25 08:25:00', 'Q001', 'S032410002'),
('C003', '2026-06-25 08:30:00', '2026-06-25 08:40:00', 'Q003', 'S032410003'),
('C008', '2026-06-25 10:10:00', '2026-06-25 10:20:00', 'Q008', 'S032410002');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_profile`
--

CREATE TABLE `doctor_profile` (
  `userID` varchar(20) NOT NULL,
  `specialization` varchar(100) NOT NULL,
  `doctorLicenseNo` varchar(50) NOT NULL,
  `roomNo` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor_profile`
--

INSERT INTO `doctor_profile` (`userID`, `specialization`, `doctorLicenseNo`, `roomNo`) VALUES
('S032410002', 'General Medicine', 'DOC1001', 'R01'),
('S032410003', 'Family Medicine', 'DOC1002', 'R02'),
('S032410006', 'Occupational Health', 'DOC1003', 'R03');

-- --------------------------------------------------------

--
-- Table structure for table `medical_record`
--

CREATE TABLE `medical_record` (
  `medicalRecordID` varchar(10) NOT NULL,
  `reasonForVisit` text NOT NULL,
  `clinicalFindings` text DEFAULT NULL,
  `diagnosis` text DEFAULT NULL,
  `treatmentPlan` text DEFAULT NULL,
  `consultationID` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medical_record`
--

INSERT INTO `medical_record` (`medicalRecordID`, `reasonForVisit`, `clinicalFindings`, `diagnosis`, `treatmentPlan`, `consultationID`) VALUES
('MR001', 'Fever and headache for 2 days', 'Temperature 38.2°C. Mild sore throat.', 'Viral Upper Respiratory Tract Infection', 'Rest, hydration and medication', 'C001'),
('MR003', 'Mild headache', 'No abnormal findings', 'Tension headache', 'Rest and hydration', 'C003'),
('MR008', 'Sneezing and runny nose', 'Mild allergic symptoms', 'Allergic Rhinitis', 'Medication prescribed', 'C008');

-- --------------------------------------------------------

--
-- Table structure for table `medicine`
--

CREATE TABLE `medicine` (
  `medicineID` varchar(10) NOT NULL,
  `medicineName` varchar(100) NOT NULL,
  `genericName` varchar(100) DEFAULT NULL,
  `strength` varchar(50) DEFAULT NULL,
  `form` varchar(50) DEFAULT NULL,
  `unit` varchar(20) DEFAULT NULL,
  `stockQuantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medicine`
--

INSERT INTO `medicine` (`medicineID`, `medicineName`, `genericName`, `strength`, `form`, `unit`, `stockQuantity`) VALUES
('M001', 'Panadol', 'Paracetamol', '500mg', 'Tablet', 'Tablet', 120),
('M002', 'Amoxicillin', 'Amoxicillin', '500mg', 'Capsule', 'Capsule', 80),
('M003', 'Loratadine', 'Loratadine', '10mg', 'Tablet', 'Tablet', 60),
('M004', 'Ventolin', 'Salbutamol', '100mcg', 'Inhaler', 'Unit', 25),
('M005', 'Cetirizine', 'Cetirizine', '10mg', 'Tablet', 'Tablet', 90);

-- --------------------------------------------------------

--
-- Table structure for table `patient_profile`
--

CREATE TABLE `patient_profile` (
  `userID` varchar(20) NOT NULL,
  `allergy` varchar(255) DEFAULT NULL,
  `chronicCondition` varchar(255) DEFAULT NULL,
  `currentMed` varchar(255) DEFAULT NULL,
  `bloodType` varchar(5) DEFAULT NULL,
  `emergencyContactName` varchar(100) DEFAULT NULL,
  `emergencyContactPhone` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient_profile`
--

INSERT INTO `patient_profile` (`userID`, `allergy`, `chronicCondition`, `currentMed`, `bloodType`, `emergencyContactName`, `emergencyContactPhone`) VALUES
('B032410101', 'Peanuts', 'Asthma', 'Ventolin', 'A+', 'Rahman Imran', '0123456789'),
('D032410102', 'None', 'None', 'None', 'O-', 'Salmah Hassan', '0134567890'),
('M032410103', 'Seafood', 'Migraine', 'Panadol', 'B+', 'Hakim Salleh', '0145678901'),
('P032410104', 'None', 'None', 'None', 'AB+', 'Azman Ismail', '0156789012'),
('S032410005', 'Penicillin', 'Hypertension', 'Amlodipine', 'O+', 'Nuraini Rahman', '0199999999');

-- --------------------------------------------------------

--
-- Table structure for table `pharmacist_profile`
--

CREATE TABLE `pharmacist_profile` (
  `userID` varchar(20) NOT NULL,
  `pharmacistLicenseNo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pharmacist_profile`
--

INSERT INTO `pharmacist_profile` (`userID`, `pharmacistLicenseNo`) VALUES
('S032410004', 'PH1001');

-- --------------------------------------------------------

--
-- Table structure for table `prescription`
--

CREATE TABLE `prescription` (
  `prescriptionID` varchar(10) NOT NULL,
  `prescriptionDate` datetime NOT NULL,
  `note` text DEFAULT NULL,
  `status` varchar(20) NOT NULL,
  `medicalRecordID` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prescription`
--

INSERT INTO `prescription` (`prescriptionID`, `prescriptionDate`, `note`, `status`, `medicalRecordID`) VALUES
('PR001', '2026-06-25 08:26:00', 'Take medication after meals', 'Dispensed', 'MR001'),
('PR008', '2026-06-25 10:21:00', 'Take before sleep', 'Pending', 'MR008');

-- --------------------------------------------------------

--
-- Table structure for table `prescription_item`
--

CREATE TABLE `prescription_item` (
  `prescriptionItemID` varchar(10) NOT NULL,
  `quantity` int(11) NOT NULL,
  `dosage` varchar(100) DEFAULT NULL,
  `frequency` varchar(100) DEFAULT NULL,
  `duration` varchar(100) DEFAULT NULL,
  `instruction` varchar(255) DEFAULT NULL,
  `medicineID` varchar(10) NOT NULL,
  `prescriptionID` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prescription_item`
--

INSERT INTO `prescription_item` (`prescriptionItemID`, `quantity`, `dosage`, `frequency`, `duration`, `instruction`, `medicineID`, `prescriptionID`) VALUES
('PI001', 15, '1 Tablet', '3 Times Daily', '5 Days', 'Take after meals', 'M001', 'PR001'),
('PI002', 5, '1 Tablet', 'Once Daily', '5 Days', 'Take before sleep', 'M005', 'PR001'),
('PI008', 10, '1 Tablet', 'Once Daily', '10 Days', 'Take before sleep', 'M005', 'PR008');

-- --------------------------------------------------------

--
-- Table structure for table `queue`
--

CREATE TABLE `queue` (
  `queueID` varchar(10) NOT NULL,
  `queueNo` int(11) NOT NULL,
  `queueStatus` varchar(20) NOT NULL,
  `attendanceID` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `queue`
--

INSERT INTO `queue` (`queueID`, `queueNo`, `queueStatus`, `attendanceID`) VALUES
('Q001', 1, 'Completed', 'AT001'),
('Q003', 2, 'Completed', 'AT003'),
('Q008', 3, 'Completed', 'AT008');

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `roleID` varchar(10) NOT NULL,
  `roleName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`roleID`, `roleName`) VALUES
('R001', 'Admin'),
('R002', 'Doctor'),
('R004', 'Patient'),
('R003', 'Pharmacist');

-- --------------------------------------------------------

--
-- Table structure for table `time_slot`
--

CREATE TABLE `time_slot` (
  `slotID` varchar(10) NOT NULL,
  `slotDate` date NOT NULL,
  `startTime` time NOT NULL,
  `endTime` time NOT NULL,
  `scheduledCapacity` int(11) NOT NULL,
  `sameDayCapacity` int(11) NOT NULL,
  `slotStatus` varchar(20) NOT NULL
) ;

--
-- Dumping data for table `time_slot`
--

INSERT INTO `time_slot` (`slotID`, `slotDate`, `startTime`, `endTime`, `scheduledCapacity`, `sameDayCapacity`, `slotStatus`) VALUES
('TS001', '2026-06-25', '08:00:00', '09:00:00', 15, 5, 'Available'),
('TS002', '2026-06-25', '09:00:00', '10:00:00', 15, 5, 'Available'),
('TS003', '2026-06-25', '10:00:00', '11:00:00', 15, 5, 'Available'),
('TS004', '2026-06-26', '08:00:00', '09:00:00', 15, 5, 'Available');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userID` varchar(20) NOT NULL,
  `fullName` varchar(100) NOT NULL,
  `emailAddress` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phoneNo` varchar(15) NOT NULL
) ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userID`, `fullName`, `emailAddress`, `password`, `phoneNo`) VALUES
('B032410101', 'Ali Imran', 'ali@student.utem.edu.my', '123456', '0111111111'),
('D032410102', 'Siti Nur', 'siti@student.utem.edu.my', '123456', '0122222222'),
('M032410103', 'Faris Hakim', 'faris@student.utem.edu.my', '123456', '0133333333'),
('P032410104', 'Nurul Ain', 'nurul@student.utem.edu.my', '123456', '0144444444'),
('S032410001', 'Mohd Hisham', 'hisham@utem.edu.my', '123456', '0161279356'),
('S032410002', 'Dr Ahmad Azlan', 'ahmad.azlan@utem.edu.my', '123456', '0171111111'),
('S032410003', 'Dr Nur Aisyah', 'nur.aisyah@utem.edu.my', '123456', '0172222222'),
('S032410004', 'Natasya Farzana', 'natasya@utem.edu.my', '123456', '0183333333'),
('S032410005', 'Azhar Rahman', 'azhar@utem.edu.my', '123456', '0195555555'),
('S032410006', 'Dr Muhammad Faiz', 'faiz@utem.edu.my', '123456', '0173333333');

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

CREATE TABLE `user_role` (
  `userID` varchar(20) NOT NULL,
  `roleID` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_role`
--

INSERT INTO `user_role` (`userID`, `roleID`) VALUES
('B032410101', 'R004'),
('D032410102', 'R004'),
('M032410103', 'R004'),
('P032410104', 'R004'),
('S032410001', 'R001'),
('S032410002', 'R002'),
('S032410002', 'R004'),
('S032410003', 'R002'),
('S032410003', 'R004'),
('S032410004', 'R003'),
('S032410004', 'R004'),
('S032410005', 'R004'),
('S032410006', 'R002'),
('S032410006', 'R004');

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
  ADD UNIQUE KEY `appointmentID` (`appointmentID`);

--
-- Indexes for table `consultation`
--
ALTER TABLE `consultation`
  ADD PRIMARY KEY (`consultationID`),
  ADD UNIQUE KEY `queueID` (`queueID`),
  ADD KEY `doctorUserID` (`doctorUserID`);

--
-- Indexes for table `doctor_profile`
--
ALTER TABLE `doctor_profile`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `doctorLicenseNo` (`doctorLicenseNo`);

--
-- Indexes for table `medical_record`
--
ALTER TABLE `medical_record`
  ADD PRIMARY KEY (`medicalRecordID`),
  ADD UNIQUE KEY `consultationID` (`consultationID`);

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
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `pharmacistLicenseNo` (`pharmacistLicenseNo`);

--
-- Indexes for table `prescription`
--
ALTER TABLE `prescription`
  ADD PRIMARY KEY (`prescriptionID`),
  ADD UNIQUE KEY `medicalRecordID` (`medicalRecordID`);

--
-- Indexes for table `prescription_item`
--
ALTER TABLE `prescription_item`
  ADD PRIMARY KEY (`prescriptionItemID`),
  ADD KEY `medicineID` (`medicineID`),
  ADD KEY `prescriptionID` (`prescriptionID`);

--
-- Indexes for table `queue`
--
ALTER TABLE `queue`
  ADD PRIMARY KEY (`queueID`),
  ADD UNIQUE KEY `attendanceID` (`attendanceID`);

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
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `emailAddress` (`emailAddress`);

--
-- Indexes for table `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`userID`,`roleID`),
  ADD KEY `roleID` (`roleID`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointment`
--
ALTER TABLE `appointment`
  ADD CONSTRAINT `appointment_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `appointment_ibfk_2` FOREIGN KEY (`slotID`) REFERENCES `time_slot` (`slotID`) ON UPDATE CASCADE;

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`appointmentID`) REFERENCES `appointment` (`appointmentID`) ON UPDATE CASCADE;

--
-- Constraints for table `consultation`
--
ALTER TABLE `consultation`
  ADD CONSTRAINT `consultation_ibfk_1` FOREIGN KEY (`queueID`) REFERENCES `queue` (`queueID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `consultation_ibfk_2` FOREIGN KEY (`doctorUserID`) REFERENCES `user` (`userID`) ON UPDATE CASCADE;

--
-- Constraints for table `doctor_profile`
--
ALTER TABLE `doctor_profile`
  ADD CONSTRAINT `doctor_profile_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `medical_record`
--
ALTER TABLE `medical_record`
  ADD CONSTRAINT `medical_record_ibfk_1` FOREIGN KEY (`consultationID`) REFERENCES `consultation` (`consultationID`) ON UPDATE CASCADE;

--
-- Constraints for table `patient_profile`
--
ALTER TABLE `patient_profile`
  ADD CONSTRAINT `patient_profile_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pharmacist_profile`
--
ALTER TABLE `pharmacist_profile`
  ADD CONSTRAINT `pharmacist_profile_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `prescription`
--
ALTER TABLE `prescription`
  ADD CONSTRAINT `prescription_ibfk_1` FOREIGN KEY (`medicalRecordID`) REFERENCES `medical_record` (`medicalRecordID`) ON UPDATE CASCADE;

--
-- Constraints for table `prescription_item`
--
ALTER TABLE `prescription_item`
  ADD CONSTRAINT `prescription_item_ibfk_1` FOREIGN KEY (`medicineID`) REFERENCES `medicine` (`medicineID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `prescription_item_ibfk_2` FOREIGN KEY (`prescriptionID`) REFERENCES `prescription` (`prescriptionID`) ON UPDATE CASCADE;

--
-- Constraints for table `queue`
--
ALTER TABLE `queue`
  ADD CONSTRAINT `queue_ibfk_1` FOREIGN KEY (`attendanceID`) REFERENCES `attendance` (`attendanceID`) ON UPDATE CASCADE;

--
-- Constraints for table `user_role`
--
ALTER TABLE `user_role`
  ADD CONSTRAINT `user_role_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_role_ibfk_2` FOREIGN KEY (`roleID`) REFERENCES `role` (`roleID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
