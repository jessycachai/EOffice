-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 11, 2026 at 11:50 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `leave_portal`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `UserName` varchar(100) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `updationDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `UserName`, `Password`, `updationDate`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', '2026-06-04 07:57:13');

-- --------------------------------------------------------

--
-- Table structure for table `employee_leave_types`
--

CREATE TABLE `employee_leave_types` (
  `id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL,
  `leave_type_id` int(11) NOT NULL,
  `available_days` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee_leave_types`
--

INSERT INTO `employee_leave_types` (`id`, `emp_id`, `leave_type_id`, `available_days`) VALUES
(1, 9, 3, 0),
(2, 9, 4, 12),
(3, 9, 5, 3);

-- --------------------------------------------------------

--
-- Table structure for table `tblattendance`
--

CREATE TABLE `tblattendance` (
  `attendance_id` int(11) NOT NULL,
  `staff_id` varchar(20) NOT NULL,
  `date` date NOT NULL,
  `time_in` time NOT NULL,
  `time_out` time DEFAULT NULL,
  `total_hours` time GENERATED ALWAYS AS (timediff(`time_out`,`time_in`)) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblattendance`
--

INSERT INTO `tblattendance` (`attendance_id`, `staff_id`, `date`, `time_in`, `time_out`) VALUES
(9, 'LLM 001', '2026-06-08', '18:56:55', '20:29:54');

-- --------------------------------------------------------

--
-- Table structure for table `tblchat_messages`
--

CREATE TABLE `tblchat_messages` (
  `id` int(11) NOT NULL,
  `sender_id` varchar(50) NOT NULL,
  `receiver_id` varchar(50) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbldepartments`
--

CREATE TABLE `tbldepartments` (
  `id` int(11) NOT NULL,
  `department_name` varchar(255) NOT NULL,
  `department_desc` text DEFAULT NULL,
  `creation_date` datetime NOT NULL,
  `last_modified_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbldepartments`
--

INSERT INTO `tbldepartments` (`id`, `department_name`, `department_desc`, `creation_date`, `last_modified_date`) VALUES
(1, 'Finance', 'Finance', '2024-08-05 21:59:01', '2026-06-11 14:32:48'),
(2, 'Human Resources', 'Managing the workflows', '2024-08-05 21:59:56', NULL),
(3, 'IT', 'IT', '2026-06-05 21:21:46', '2026-06-11 14:33:13'),
(4, 'Sales', 'Sales', '2026-06-11 14:33:51', NULL),
(5, 'Marketing', 'Marketing\n', '2026-06-11 14:34:01', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblemployees`
--

CREATE TABLE `tblemployees` (
  `emp_id` int(11) NOT NULL,
  `department` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `designation` varchar(255) DEFAULT NULL,
  `email_id` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  `staff_id` varchar(20) NOT NULL,
  `is_supervisor` int(11) NOT NULL DEFAULT 0,
  `password_reset` tinyint(1) NOT NULL DEFAULT 0,
  `lock_unlock` tinyint(1) NOT NULL DEFAULT 0,
  `date_created` datetime DEFAULT current_timestamp(),
  `supervisor_id` int(11) DEFAULT NULL,
  `can_be_assigned` enum('YES','NO') NOT NULL DEFAULT 'YES',
  `image_content` mediumblob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblemployees`
--

INSERT INTO `tblemployees` (`emp_id`, `department`, `first_name`, `last_name`, `middle_name`, `phone_number`, `designation`, `email_id`, `password`, `gender`, `image_path`, `role`, `staff_id`, `is_supervisor`, `password_reset`, `lock_unlock`, `date_created`, `supervisor_id`, `can_be_assigned`, `image_content`) VALUES
(1, 2, 'Conan', 'Edogawa', '', '000000000', 'Admin', 'admin@gmail.com', '0192023a7bbd73250516f069df18b500', 'Male', '/leave_portal/uploads/images/LLM_001_57c37bc0ddd0cfa1.jpg', 'Admin', 'LLM 001', 1, 1, 0, '2024-08-05 22:02:37', NULL, 'NO', NULL),
(2, 1, 'Sonoko', 'Suzuki', '', '0000000001', 'Staff Finance', 'sonoko@gmail.com', '6500618a81cd20491c705410b8b2a943', 'Female', '../uploads/images/sonokosuzuki.jpg', 'Staff', 'LLM 007', 0, 1, 0, '2024-08-11 09:21:32', 3, 'YES', NULL),
(3, 1, 'Ran', 'Mouri', '', '0000000011', 'Manager Finance', 'ranmouri@gmail.com', '1b31fd1a6af172db05d433fe2ea3663b', 'Female', '../uploads/images/ranmouri.jpg', 'Manager', 'LLM 003', 1, 1, 0, '2024-08-11 19:56:20', NULL, 'YES', NULL),
(8, 2, 'Haibara', 'Ai', '', '08419412412', 'Supervisor HR', 'haibara@gmail.com', '34e730c84b4b603cdf03feeb494f51ab', 'Female', '/leave_portal/uploads/images/LLM_004_0b609ce6139de546.jpg', 'Staff', 'LLM 004', 1, 1, 0, '2026-06-05 21:20:41', NULL, 'YES', NULL),
(9, 2, 'Tony', 'Chopper', 'Tony', '000000000003', 'Staff HR', 'chopper@gmail.com', 'ef0a706ad9aa3538892200ecef97bfde', 'Male', '/leave_portal/uploads/images/LLM_005_ff0d563fc7b6741e.jpg', 'Staff', 'LLM 005', 0, 1, 0, '2026-06-08 15:03:20', 8, 'YES', NULL),
(10, 3, 'Nami', 'Nakama', '', '0000004', 'Manager Finance', 'nami@gmail.com', 'e060f899996d7712efe1e38487cc7f14', 'Female', '/leave_portal/uploads/images/LLM_006_0e453f2848b40d4d.jpg', 'Manager', 'LLM 006', 1, 0, 0, '2026-06-08 15:05:06', NULL, 'YES', NULL),
(11, 2, 'Luffy', 'Monkey', 'D.', '082931631', 'Director', 'luffy@gmail.com', '48ce5595dfa835bb50f98372671a724c', 'Male', '/leave_portal/uploads/images/LLM_008_5a3e3f47cc07a2ae.jpg', 'Admin', 'LLM 008', 1, 0, 0, '2026-06-11 14:36:44', NULL, 'YES', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblleave`
--

CREATE TABLE `tblleave` (
  `id` int(11) NOT NULL,
  `leave_type_id` int(11) NOT NULL,
  `requested_days` int(11) NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `leave_status` int(11) NOT NULL DEFAULT 0,
  `empid` int(11) NOT NULL,
  `remarks` text DEFAULT NULL,
  `sick_file` varchar(255) DEFAULT NULL,
  `reviewed_by` int(11) DEFAULT NULL,
  `reviewed_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblleave`
--

INSERT INTO `tblleave` (`id`, `leave_type_id`, `requested_days`, `from_date`, `to_date`, `created_date`, `leave_status`, `empid`, `remarks`, `sick_file`, `reviewed_by`, `reviewed_date`) VALUES
(1, 3, 2, '2026-06-09', '2026-06-10', '2026-06-08 16:25:43', 1, 9, 'I got fever', 'b2fa15b7f217cc1d7cce27471a0b8728.jpeg', NULL, NULL),
(2, 4, 4, '2026-06-09', '2026-06-12', '2026-06-08 16:33:00', 0, 9, '', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblleavetype`
--

CREATE TABLE `tblleavetype` (
  `id` int(11) NOT NULL,
  `leave_type` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `creation_date` datetime NOT NULL,
  `assign_days` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblleavetype`
--

INSERT INTO `tblleavetype` (`id`, `leave_type`, `description`, `creation_date`, `assign_days`, `status`) VALUES
(2, 'Work From Home', 'only available in circum conditions', '2026-06-05 21:31:49', 2, 2),
(3, 'Sick Leave', 'Sick', '2026-06-05 21:32:04', 2, 1),
(4, 'Annual Leave', '12 days for each year', '2026-06-05 21:32:32', 12, 1),
(5, 'Emergency Leave', 'ask to manager ', '2026-06-05 21:32:55', 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbltask`
--

CREATE TABLE `tbltask` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `assigned_to` int(11) NOT NULL,
  `assigned_by` int(11) NOT NULL,
  `status` enum('Pending','In Progress','Completed') DEFAULT 'Pending',
  `priority` enum('Low','Medium','High') DEFAULT 'Medium',
  `start_date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `attachment` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbltask`
--

INSERT INTO `tbltask` (`id`, `title`, `description`, `assigned_to`, `assigned_by`, `status`, `priority`, `start_date`, `due_date`, `created_at`, `updated_at`, `attachment`) VALUES
(1, 'Tarikkan report absensi', '<p><br></p>', 9, 8, 'Completed', 'Medium', '2026-06-09', '2026-06-11', '2026-06-11 07:37:48', '2026-06-11 09:40:38', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_leave_types`
--
ALTER TABLE `employee_leave_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblattendance`
--
ALTER TABLE `tblattendance`
  ADD PRIMARY KEY (`attendance_id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Indexes for table `tblchat_messages`
--
ALTER TABLE `tblchat_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbldepartments`
--
ALTER TABLE `tbldepartments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblemployees`
--
ALTER TABLE `tblemployees`
  ADD PRIMARY KEY (`emp_id`),
  ADD UNIQUE KEY `staff_id` (`staff_id`);

--
-- Indexes for table `tblleave`
--
ALTER TABLE `tblleave`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblleavetype`
--
ALTER TABLE `tblleavetype`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbltask`
--
ALTER TABLE `tbltask`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assigned_to` (`assigned_to`),
  ADD KEY `assigned_by` (`assigned_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `employee_leave_types`
--
ALTER TABLE `employee_leave_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tblattendance`
--
ALTER TABLE `tblattendance`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tblchat_messages`
--
ALTER TABLE `tblchat_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbldepartments`
--
ALTER TABLE `tbldepartments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tblemployees`
--
ALTER TABLE `tblemployees`
  MODIFY `emp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tblleave`
--
ALTER TABLE `tblleave`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tblleavetype`
--
ALTER TABLE `tblleavetype`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbltask`
--
ALTER TABLE `tbltask`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tblattendance`
--
ALTER TABLE `tblattendance`
  ADD CONSTRAINT `tblattendance_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `tblemployees` (`staff_id`);

--
-- Constraints for table `tbltask`
--
ALTER TABLE `tbltask`
  ADD CONSTRAINT `tbltask_ibfk_1` FOREIGN KEY (`assigned_to`) REFERENCES `tblemployees` (`emp_id`),
  ADD CONSTRAINT `tbltask_ibfk_2` FOREIGN KEY (`assigned_by`) REFERENCES `tblemployees` (`emp_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
