-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 21, 2024 at 06:58 AM
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
-- Database: `ciadb2`
--

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `branch_id` int(11) NOT NULL,
  `branch_name` varchar(255) NOT NULL,
  `branch_address` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `branch_manager` varchar(255) DEFAULT NULL,
  `date_established` date DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT NULL,
  `total_employees` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`branch_id`, `branch_name`, `branch_address`, `city`, `state`, `contact_number`, `email`, `branch_manager`, `date_established`, `status`, `total_employees`) VALUES
(1, 'CIA Vidyanagar', 'Nagpur', 'Nagpur', 'Maharashtra', '07218420585', 'tejaslandge21@gmail.com', 'Jyoti Raut', '2024-09-01', 'active', 3),
(3, 'CIA Sadar', 'Nagpur', 'Nagpur', 'Maharashtra', '07218420585', 'tejaslandge21@gmail.com', 'Tejas Landge', '2024-09-05', 'active', 4),
(4, 'CIA Mumbai', 'Mumbai', 'Mumbai', 'Maharashtra', '07218420585', 'tejaslandge21@gmail.com', 'Harsh Patle', '2024-09-07', 'active', 5);

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `course_id` int(11) NOT NULL,
  `course_name` varchar(255) DEFAULT NULL,
  `course_description` text DEFAULT NULL,
  `course_duration` int(11) DEFAULT NULL,
  `course_fee` decimal(10,2) DEFAULT NULL,
  `status` enum('active','completed','cancelled') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`course_id`, `course_name`, `course_description`, `course_duration`, `course_fee`, `status`) VALUES
(1, 'Web Development', 'Learn HTML, CSS, JavaScript, and more to build websites.', 6, 12000.00, 'active'),
(2, 'Data Science', 'Master data analysis, visualization, and machine learning.', 8, 18000.00, 'active'),
(3, 'Mobile App Development', 'Create mobile apps using Android or iOS platforms.', 5, 15000.00, 'completed'),
(4, 'Cloud Computing', 'Understand cloud services, infrastructure, and deployment models.', 7, 20000.00, 'active'),
(5, 'Cybersecurity', 'Learn to secure networks and systems from cyber threats.', 6, 17000.00, 'cancelled'),
(6, 'C++ Programming', 'nothing', 3, 6000.00, 'completed'),
(8, 'Python', 'Bohot Maja Aata hai krke dekh ek bar', 6, 10000.00, 'active'),
(9, 'Internship 2024', 'ABC', 6, 15000.00, 'active');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(11) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `course_name` varchar(255) NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `postal_code` varchar(10) DEFAULT NULL,
  `enrollment_date` date DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT NULL,
  `fees_paid` decimal(10,2) DEFAULT NULL,
  `total_fees` decimal(10,2) DEFAULT NULL,
  `balance_fees` decimal(10,2) DEFAULT NULL,
  `guardian_name` varchar(255) DEFAULT NULL,
  `guardian_contact` varchar(20) DEFAULT NULL,
  `profile` varchar(255) DEFAULT NULL,
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `first_name`, `last_name`, `email`, `phone_number`, `course_name`, `date_of_birth`, `gender`, `address`, `city`, `state`, `postal_code`, `enrollment_date`, `status`, `fees_paid`, `total_fees`, `balance_fees`, `guardian_name`, `guardian_contact`, `profile`, `remarks`) VALUES
(1, 'Tejas', 'Landge', 'tejaslandge21@gmail.com', '07218420585', 'C++ Programming', '2024-09-10', 'male', 'Nagpur', 'Nagpur', 'Maharashtra', '441113', '2024-09-04', NULL, NULL, NULL, NULL, 'Prakash Landge', '0987656789', 'stdImg (19).jpeg', 'Add Something'),
(2, 'Harsh', 'Patle', 'harsh@g.com', '3453212432', 'FullStack Development', '2024-09-05', 'male', 'Koradi', 'Nagpur', 'Maharashtra', '441113', '2024-09-13', NULL, NULL, NULL, NULL, 'Tejas', '87656787623', 'stdImg (15).jpeg', 'Nothing'),
(3, 'Darshan', 'Narekar', 'd@g', 'abc', 'sdfd', '2024-09-13', 'male', 'Nagpur', 'Nagpur', 'Maharashtra', '441113', '2024-09-13', NULL, NULL, NULL, NULL, 'NA', '123245655', 'stdImg (20).jpeg', '');

-- --------------------------------------------------------

--
-- Table structure for table `task_sheet`
--

CREATE TABLE `task_sheet` (
  `task_id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `task_name` varchar(255) DEFAULT NULL,
  `task_description` text DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `completion_date` date DEFAULT NULL,
  `task_status` enum('pending','in progress','completed','delayed') DEFAULT NULL,
  `priority_level` enum('low','medium','high') DEFAULT NULL,
  `assigned_by` int(11) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `monthly_report_id` int(11) DEFAULT NULL,
  `progress_percentage` decimal(5,2) DEFAULT NULL,
  `hours_spent` decimal(5,2) DEFAULT NULL,
  `task_category` varchar(255) DEFAULT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `attachments` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trainers`
--

CREATE TABLE `trainers` (
  `trainer_id` int(11) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `expertise` varchar(255) DEFAULT NULL,
  `qualification` varchar(255) DEFAULT NULL,
  `experience_years` int(11) DEFAULT NULL,
  `joining_date` date DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `trainer_bio` text DEFAULT NULL,
  `certifications` varchar(255) DEFAULT NULL,
  `availability_schedule` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trainers`
--

INSERT INTO `trainers` (`trainer_id`, `first_name`, `last_name`, `email`, `phone_number`, `expertise`, `qualification`, `experience_years`, `joining_date`, `branch_id`, `status`, `salary`, `photo`, `address`, `date_of_birth`, `gender`, `trainer_bio`, `certifications`, `availability_schedule`) VALUES
(1, 'Tejas', 'Landge', 'tejaslandge21@gmail.com', '07218420585', 'Python', 'MSC', 1, '2023-11-06', 1, NULL, 15000.00, '../trainer_profile/trainer_66eb9f6570aee4.59592733.jpeg', 'Nagpur', '2002-06-21', 'male', 'Nothing to say', 'Python PHP', 'Monday to Friday 12pm to 5pm'),
(2, 'Aman', 'ssss', 'tejas@gmail.com', '07218420585', 'Python', 'MSC', 2, '2024-09-07', NULL, 'inactive', 15000.00, '../trainer_profile/trainer_66eba4c23e1966.81724457.jpeg', 'Nagpur', '2024-09-03', 'male', 'wdefrgt', 'afds', 'adfs');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('active','inactive','pending') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `status`) VALUES
(1, 'tejaslandge', 'tejaslandge21@gmail.com', 'Tejas@123', 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`branch_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`course_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`);

--
-- Indexes for table `task_sheet`
--
ALTER TABLE `task_sheet`
  ADD PRIMARY KEY (`task_id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `assigned_by` (`assigned_by`);

--
-- Indexes for table `trainers`
--
ALTER TABLE `trainers`
  ADD PRIMARY KEY (`trainer_id`),
  ADD KEY `branch_id` (`branch_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `branch_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `task_sheet`
--
ALTER TABLE `task_sheet`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trainers`
--
ALTER TABLE `trainers`
  MODIFY `trainer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `task_sheet`
--
ALTER TABLE `task_sheet`
  ADD CONSTRAINT `task_sheet_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `trainers` (`trainer_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `task_sheet_ibfk_2` FOREIGN KEY (`assigned_by`) REFERENCES `trainers` (`trainer_id`) ON DELETE SET NULL;

--
-- Constraints for table `trainers`
--
ALTER TABLE `trainers`
  ADD CONSTRAINT `trainers_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`branch_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
