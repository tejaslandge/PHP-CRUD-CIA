-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 02, 2024 at 11:06 AM
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
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `action` varchar(255) NOT NULL,
  `log_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`log_id`, `user_id`, `username`, `action`, `log_time`) VALUES
(1, 2, 'admin', 'Add data of Course:lnln', '2024-10-01 16:35:07'),
(2, 2, 'admin', 'Add data of Course:lnln', '2024-10-01 16:36:58'),
(3, 2, 'admin', 'Delete data of Course', '2024-10-01 16:37:09'),
(4, 2, 'admin', 'Update  Details of Student : Tejas Landge', '2024-10-01 16:38:03'),
(5, 2, 'admin', 'Add data of Course:kndnv', '2024-10-01 16:39:39'),
(6, 2, 'admin', 'Delete data of Course', '2024-10-01 16:40:00'),
(7, 2, 'admin', 'Add data of Course:kdjb', '2024-10-01 16:44:47'),
(8, 2, 'admin', 'User logged in', '2024-10-02 08:22:25'),
(9, 2, 'admin', 'Added a new Task : slideshow', '2024-10-02 08:56:20'),
(10, 2, 'admin', 'Update Task: slideshow', '2024-10-02 08:59:22'),
(11, 2, 'admin', 'Delete Task', '2024-10-02 09:04:56');

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
(7, 'CIA Sadar', 'Nagpur', 'Nagpur', 'Maharashtra', '7218420585', 'tejaslandge21@gmail.com', 'Jyoti Raut', '2024-09-04', 'active', 32),
(13, 'CIA Sadar', 'Nagpur', 'Nagpur', 'Maharashtra', '9172007007', 'jyoti@gmail.com', 'Jyoti Raut', '2024-09-14', 'active', 4),
(71, 'ZZZADAA', 'Nagpur', 'Biswanath', 'Assam', '7218420585', 'asd@gmail.com', 'sdvs', '2024-09-04', 'active', 3),
(72, 'Isapur', 'Nagpur', 'Nagpur', 'Maharashtra', '2433123456', 'lkjhg21@gmail.com', 'da', '2024-09-27', 'active', 4),
(73, 'CIA Vidyanagar', 'Nagpur', 'Nagpur', 'Maharashtra', '9172007007', 'codeinsightacademy@gmail.com', 'Jyoti Raut', '2020-12-28', 'active', 3),
(74, 'svdsv', 'sdvvsv', 'Barpeta', 'Assam', '2132435323', 'adc@gmail.com', 'dcsvs', '2024-08-28', 'active', 5),
(76, 'CIA Sadar', 'sdvsdc ', 'Bhiwani', 'Haryana', '5632424353', 'sdjbcskjb@gmail.com', 'da', '2024-08-30', 'active', 5),
(77, 'mumbai', 'Nagpur', 'Mumbai', 'Maharashtra', '9172007007', 'jsdkjb@gmail.com', 'dvjbsdj', '2024-09-10', 'active', 5),
(78, 'Nashik', 'djbcdjsab', 'Nashik', 'Maharashtra', '3421314564', 'cscsk@gmail.com', 'kfouwgu', '2024-09-04', 'active', 5);

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
  `status` enum('active','inactive') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`course_id`, `course_name`, `course_description`, `course_duration`, `course_fee`, `status`) VALUES
(1, 'Web Development', 'Learn HTML, CSS, JavaScript, and more to build websites.', 6, 23000.00, 'active'),
(2, 'Data Science', 'Master data analysis, visualization, and machine learning.', 8, 18000.00, 'active'),
(3, 'Mobile App Development', 'Create mobile apps using Android or iOS platforms.', 5, 15000.00, 'active'),
(4, 'Cloud Computing', 'Understand cloud services, infrastructure, and deployment models.', 7, 20000.00, 'active'),
(5, 'Cybersecurity', 'Learn to secure networks and systems from cyber threats.', 6, 17000.00, 'active'),
(6, 'C++ Programming', 'nothing', 3, 6000.00, 'active'),
(11, 'lnln', 'lknlkn', 4, 50000.00, 'active'),
(13, 'kdjb', 'bsjb', 3, 5000.00, 'active');

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
(1, 'Tejas', 'Landge', 'tejaslandge21@gmail.com', '07218420585', 'C++ Programming', '2024-09-10', 'male', 'at ispaur post pipla d.b. tah saoner dist. nagpur state maharashtra,country india 441113', 'Nagpur', 'Maharashtra', '441113', '2024-09-04', 'active', 3500.00, 6000.00, 2500.00, 'Prakash Landge', '0987656789', 'stdImg (19).jpeg', 'i dont want'),
(4, 'Tejas', 'Landge', 'tejaslandge21@gmail.com', '07218420585', 'C++ Programming', '2024-08-27', 'male', 'Nagpur', 'Nagpur', 'Maharashtra', '441113', '2024-09-12', 'active', 3500.00, 6000.00, 2000.00, 'Prakash Landge', '0987656789', '_MG_7868.JPG', 'ioewfhi');

-- --------------------------------------------------------

--
-- Table structure for table `task_sheet`
--

CREATE TABLE `task_sheet` (
  `task_id` int(11) NOT NULL,
  `task_name` varchar(255) NOT NULL,
  `task_description` text DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `completion_date` date DEFAULT NULL,
  `task_status` varchar(50) DEFAULT NULL,
  `priority_level` varchar(50) DEFAULT NULL,
  `remarks` text DEFAULT NULL
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
  `trainer_branch` varchar(255) NOT NULL,
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

INSERT INTO `trainers` (`trainer_id`, `first_name`, `last_name`, `email`, `phone_number`, `expertise`, `qualification`, `experience_years`, `joining_date`, `trainer_branch`, `status`, `salary`, `photo`, `address`, `date_of_birth`, `gender`, `trainer_bio`, `certifications`, `availability_schedule`) VALUES
(6, 'Tejas', 'Landge', 'tejaslandge21@gmail.com', '07218420585', 'Python', 'MSC', 3, '2024-09-02', 'CIA Sadar', 'active', 15000.00, 'trainer_66f67eb92de806.50949448.jpeg', 'Nagpur', '2002-06-21', 'male', 'adsc', 'Java', 'Mon to Fri '),
(7, 'Tejas', 'Landge', 'tejaslandge21@gmail.com', '07218420585', 'jhv', 'khv', 5, '2024-08-28', 'Isapur', 'active', 200000.00, 'trainer_66f693083a6208.33053314.jpeg', 'Nagpur', '2024-08-29', 'male', 'hbhk', 'hv', 'vjv'),
(8, 'khhwsh', 'gsuhus', 'tejaslandge21@gmail.com', '07218420585', 'sgs', 'ifgdssuhsu', 1, '2332-06-05', 'CIA Sadar', 'active', 7666.00, 'trainer_66f6935166a583.55424825.jpeg', 'Nagpur', '3434-04-04', 'male', 'jgfjg', 'hg', 'fdf'),
(9, 'slfvjsssds', 'sdfs', 'tejaslandge21@gmail.com', '3432343232', 'gfdveg', 'rgfge', 3, '1211-12-21', 'CIA Sadar', 'active', 121211.00, 'trainer_66f693db913bb5.30841740.jpeg', 'Nagpur', '2024-08-29', 'male', 'wrgew', 'wefw', 'wef'),
(10, 'wegwge', 'Landge', 'tejaslandge21@gmail.com', '07218420585', 'wefwsf', 'weffw', 3, '2024-09-13', 'Isapur', 'active', 324234.00, 'trainer_66f6941fb8ea73.51373284.jpeg', 'Nagpur', '2024-09-19', 'male', 'wrf', 'wefrw', 'wef'),
(11, 'rwfgw', 'werf', 'asda@g.com', '07218420585', 'fdv', 'ergf', 3, '2024-09-13', 'CIA Sadar', 'active', 2332.00, 'trainer_66f6946f17df93.38867198.jpeg', 'Nagpur', '2024-09-04', 'male', 'ergf', 'df', 'fsv'),
(12, 'Suraj', 'Meshram', 'suraj@gmai.com', '7656567224', 'Python', 'MSC', 2, '2024-09-30', 'CIA Sadar', 'active', 15000.00, 'trainer_66fc13133be692.88685231.jpeg', 'Nagpur', '2004-06-16', 'male', 'nothing', 'Javascript', 'monday to friday'),
(13, 'Tejas', 'Landge', 'tejaslandge21@gmail.com', '7218420585', 'Python', 'MSC', 1, '2023-11-06', 'CIA Vidyanagar', 'active', 15000.00, 'trainer_66f7a8c6cd53f9.00473377.JPG', 'at ispaur post pipla d.b. tah saoner dist. nagpur state maharashtra,country india 441113', '2002-06-21', 'male', 'nothing', 'java python etc', 'Mon to Fri ');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('active','inactive','pending') NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `status`, `updated_at`) VALUES
(1, 'tejaslandge', 'tejaslandge21@gmail.com', 'asd', 'active', '2024-09-27 04:28:01'),
(2, 'admin', 'admin@gmail.com', '123', 'active', '2024-09-28 07:01:29');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`log_id`);

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
  ADD PRIMARY KEY (`task_id`);

--
-- Indexes for table `trainers`
--
ALTER TABLE `trainers`
  ADD PRIMARY KEY (`trainer_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `branch_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `task_sheet`
--
ALTER TABLE `task_sheet`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `trainers`
--
ALTER TABLE `trainers`
  MODIFY `trainer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
