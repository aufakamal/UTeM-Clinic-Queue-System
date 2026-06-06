-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 06, 2026 at 08:21 AM
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
-- Table structure for table `admin_profile`
--

CREATE TABLE `admin_profile` (
  `adminProfileID` varchar(10) NOT NULL,
  `accessLevel` varchar(30) NOT NULL,
  `manageUsers` tinyint(1) NOT NULL DEFAULT 0,
  `manageQueue` tinyint(1) NOT NULL DEFAULT 0,
  `systemSettings` tinyint(1) NOT NULL DEFAULT 0,
  `clinicStatus` varchar(10) NOT NULL DEFAULT 'Open',
  `queueStatus` varchar(10) NOT NULL DEFAULT 'Enabled',
  `userID` varchar(10) NOT NULL
) ;

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `appointmentID` varchar(10) NOT NULL,
  `appointmentDate` date NOT NULL DEFAULT curdate(),
  `timeSlot` varchar(20) NOT NULL,
  `appointmentType` varchar(30) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Pending',
  `userID` varchar(10) NOT NULL
) ;

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `attendanceID` varchar(10) NOT NULL,
  `checkInTime` datetime NOT NULL DEFAULT current_timestamp(),
  `attendanceStatus` varchar(20) NOT NULL DEFAULT 'Arrived',
  `appointmentID` varchar(10) NOT NULL
) ;

-- --------------------------------------------------------

--
-- Table structure for table `consultation`
--

CREATE TABLE `consultation` (
  `consultationID` varchar(10) NOT NULL,
  `startTime` datetime NOT NULL DEFAULT current_timestamp(),
  `endTime` datetime DEFAULT NULL,
  `queueID` varchar(6) NOT NULL,
  `userID` varchar(10) DEFAULT NULL
) ;

-- --------------------------------------------------------

--
-- Table structure for table `doctor_profile`
--

CREATE TABLE `doctor_profile` (
  `doctorProfileID` varchar(10) NOT NULL,
  `specialization` varchar(100) NOT NULL,
  `doctorLicenseNo` varchar(20) NOT NULL,
  `roomNo` varchar(10) NOT NULL,
  `userID` varchar(10) NOT NULL
) ;

-- --------------------------------------------------------

--
-- Table structure for table `medical_record`
--

CREATE TABLE `medical_record` (
  `medicalRecordID` varchar(6) NOT NULL,
  `reasonForVisit` varchar(100) NOT NULL,
  `clinicalFindings` text NOT NULL,
  `diagnosis` varchar(100) NOT NULL,
  `treatmentPlan` text NOT NULL,
  `consultationID` varchar(10) NOT NULL
) ;

-- --------------------------------------------------------

--
-- Table structure for table `medicine`
--

CREATE TABLE `medicine` (
  `medicineID` varchar(6) NOT NULL,
  `medicineName` varchar(100) NOT NULL,
  `genericName` varchar(100) NOT NULL,
  `strength` varchar(30) NOT NULL,
  `form` varchar(30) NOT NULL,
  `unit` varchar(20) NOT NULL,
  `stockQuantity` int(11) NOT NULL DEFAULT 0
) ;

-- --------------------------------------------------------

--
-- Table structure for table `patient_profile`
--

CREATE TABLE `patient_profile` (
  `patientProfileID` varchar(10) NOT NULL,
  `allergy` varchar(100) DEFAULT NULL,
  `chronicCondition` varchar(100) DEFAULT NULL,
  `currentMed` varchar(100) DEFAULT NULL,
  `bloodType` varchar(5) DEFAULT NULL,
  `emergencyContact` varchar(15) NOT NULL,
  `userID` varchar(10) NOT NULL
) ;

-- --------------------------------------------------------

--
-- Table structure for table `pharmacist_profile`
--

CREATE TABLE `pharmacist_profile` (
  `pharmacistProfileID` varchar(10) NOT NULL,
  `pharmacistLicenseNo` varchar(20) NOT NULL,
  `userID` varchar(10) NOT NULL
) ;

-- --------------------------------------------------------

--
-- Table structure for table `prescription`
--

CREATE TABLE `prescription` (
  `prescriptionID` varchar(6) NOT NULL,
  `prescriptionDate` date NOT NULL DEFAULT curdate(),
  `note` varchar(255) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Pending',
  `medicalRecordID` varchar(6) NOT NULL
) ;

-- --------------------------------------------------------

--
-- Table structure for table `prescription_item`
--

CREATE TABLE `prescription_item` (
  `prescriptionItemID` varchar(6) NOT NULL,
  `dosage` varchar(50) NOT NULL,
  `frequency` varchar(50) NOT NULL,
  `duration` varchar(30) NOT NULL,
  `instruction` varchar(255) DEFAULT NULL,
  `medicineID` varchar(6) NOT NULL,
  `prescriptionID` varchar(6) NOT NULL
) ;

-- --------------------------------------------------------

--
-- Table structure for table `queue`
--

CREATE TABLE `queue` (
  `queueID` varchar(6) NOT NULL,
  `queueNo` varchar(10) NOT NULL,
  `attendanceID` varchar(10) NOT NULL
) ;

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `roleID` varchar(10) NOT NULL,
  `roleName` varchar(50) NOT NULL
) ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userID` varchar(10) NOT NULL,
  `fullName` varchar(100) NOT NULL,
  `emailAddress` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phoneNo` varchar(15) NOT NULL
) ;

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

CREATE TABLE `user_role` (
  `userID` varchar(10) NOT NULL,
  `roleID` varchar(10) NOT NULL
) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_profile`
--
ALTER TABLE `admin_profile`
  ADD PRIMARY KEY (`adminProfileID`),
  ADD UNIQUE KEY `userID` (`userID`);

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`appointmentID`),
  ADD KEY `userID` (`userID`);

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
  ADD UNIQUE KEY `queueID` (`queueID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `doctor_profile`
--
ALTER TABLE `doctor_profile`
  ADD PRIMARY KEY (`doctorProfileID`),
  ADD UNIQUE KEY `doctorLicenseNo` (`doctorLicenseNo`),
  ADD UNIQUE KEY `userID` (`userID`);

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
  ADD PRIMARY KEY (`patientProfileID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `pharmacist_profile`
--
ALTER TABLE `pharmacist_profile`
  ADD PRIMARY KEY (`pharmacistProfileID`),
  ADD UNIQUE KEY `pharmacistLicenseNo` (`pharmacistLicenseNo`),
  ADD UNIQUE KEY `userID` (`userID`);

--
-- Indexes for table `prescription`
--
ALTER TABLE `prescription`
  ADD PRIMARY KEY (`prescriptionID`),
  ADD KEY `medicalRecordID` (`medicalRecordID`);

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
-- Constraints for table `admin_profile`
--
ALTER TABLE `admin_profile`
  ADD CONSTRAINT `admin_profile_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`);

--
-- Constraints for table `appointment`
--
ALTER TABLE `appointment`
  ADD CONSTRAINT `appointment_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`);

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`appointmentID`) REFERENCES `appointment` (`appointmentID`);

--
-- Constraints for table `consultation`
--
ALTER TABLE `consultation`
  ADD CONSTRAINT `consultation_ibfk_1` FOREIGN KEY (`queueID`) REFERENCES `queue` (`queueID`),
  ADD CONSTRAINT `consultation_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`);

--
-- Constraints for table `doctor_profile`
--
ALTER TABLE `doctor_profile`
  ADD CONSTRAINT `doctor_profile_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`);

--
-- Constraints for table `medical_record`
--
ALTER TABLE `medical_record`
  ADD CONSTRAINT `medical_record_ibfk_1` FOREIGN KEY (`consultationID`) REFERENCES `consultation` (`consultationID`);

--
-- Constraints for table `patient_profile`
--
ALTER TABLE `patient_profile`
  ADD CONSTRAINT `patient_profile_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`);

--
-- Constraints for table `pharmacist_profile`
--
ALTER TABLE `pharmacist_profile`
  ADD CONSTRAINT `pharmacist_profile_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`);

--
-- Constraints for table `prescription`
--
ALTER TABLE `prescription`
  ADD CONSTRAINT `prescription_ibfk_1` FOREIGN KEY (`medicalRecordID`) REFERENCES `medical_record` (`medicalRecordID`);

--
-- Constraints for table `prescription_item`
--
ALTER TABLE `prescription_item`
  ADD CONSTRAINT `prescription_item_ibfk_1` FOREIGN KEY (`medicineID`) REFERENCES `medicine` (`medicineID`),
  ADD CONSTRAINT `prescription_item_ibfk_2` FOREIGN KEY (`prescriptionID`) REFERENCES `prescription` (`prescriptionID`);

--
-- Constraints for table `queue`
--
ALTER TABLE `queue`
  ADD CONSTRAINT `queue_ibfk_1` FOREIGN KEY (`attendanceID`) REFERENCES `attendance` (`attendanceID`);

--
-- Constraints for table `user_role`
--
ALTER TABLE `user_role`
  ADD CONSTRAINT `user_role_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`),
  ADD CONSTRAINT `user_role_ibfk_2` FOREIGN KEY (`roleID`) REFERENCES `role` (`roleID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
