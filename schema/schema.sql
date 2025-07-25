-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 15, 2025 at 01:50 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mof_smartdesk`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `email`, `password`, `created_at`) VALUES
(1, 'smartdesk@mofep.gov.gh', 'SmartD123', '2025-07-09 13:36:10');

-- --------------------------------------------------------

--
-- Table structure for table `chat_history`
--

CREATE TABLE `chat_history` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `chat_identifier` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chat_history`
--

INSERT INTO `chat_history` (`id`, `user_id`, `chat_identifier`, `title`, `created_at`, `updated_at`) VALUES
(42, 1, 'chat_686bd138649744.99885721', 'how do i rest my password', '2025-07-07 13:52:56', '2025-07-07 14:08:45'),
(51, 1, 'chat_686cf26666f975.16620287', 'Hello', '2025-07-08 10:26:46', '2025-07-08 10:26:50'),
(55, 1, 'chat_686cf302a1c864.53564112', 'MY PRINTER IS OFFLINE', '2025-07-08 10:29:22', '2025-07-08 10:30:11'),
(58, 1, 'chat_686cf3fa027d67.26503819', 'MY DOCUMENT IS NOT PRINTING HOW DO I FIX IT?', '2025-07-08 10:33:30', '2025-07-08 10:34:11'),
(61, 1, 'chat_686cfc863ddfa4.13103816', 'my browser is not working', '2025-07-08 11:09:58', '2025-07-08 11:10:07'),
(62, 1, 'chat_686cfcbfcd5d60.50560131', 'my broswer is not working', '2025-07-08 11:10:55', '2025-07-08 11:11:03'),
(63, 1, 'chat_686cfceb5c96a9.48877548', 'printer not working', '2025-07-08 11:11:39', '2025-07-08 11:11:44'),
(65, 1, 'chat_686cfdcae5c375.93168534', 'my outlook is not receiving emails', '2025-07-08 11:15:22', '2025-07-08 11:15:31'),
(66, 1, 'chat_686cfe022a6479.51268947', 'my outlook is not receiving emails', '2025-07-08 11:16:18', '2025-07-08 11:16:29'),
(119, 1, 'chat_686d27024f1701.59673107', 'printer offline', '2025-07-08 14:11:14', '2025-07-08 14:17:30'),
(121, 1010, 'chat_686e6af6c789a6.88238518', 'New Chat', '2025-07-09 13:13:26', '2025-07-09 13:13:26'),
(122, 1010, 'chat_686e6af80ec063.30216758', 'New Chat', '2025-07-09 13:13:28', '2025-07-09 13:13:28'),
(123, 1010, 'chat_686e6afa563282.20009221', 'New Chat', '2025-07-09 13:13:30', '2025-07-09 13:13:30'),
(127, 1, 'chat_686fb2debe03a0.78740435', 'New Chat', '2025-07-10 12:32:30', '2025-07-10 12:32:30');

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` int(11) NOT NULL,
  `chat_id` int(11) NOT NULL,
  `role` enum('user','assistant') NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chat_messages`
--

INSERT INTO `chat_messages` (`id`, `chat_id`, `role`, `content`, `created_at`) VALUES
(11, 42, 'user', 'how do i rest my password', '2025-07-07 14:08:45'),
(12, 42, 'assistant', 'I\'m sorry, I couldn\'t process your request at this time. Please try again later.', '2025-07-07 14:08:47'),
(13, 42, 'user', 'how do i reset my password', '2025-07-07 14:09:36'),
(14, 42, 'assistant', 'Password reset:\r\n1. Go to portal.mof.gov.gh/reset\r\n2. Enter staff ID\r\n3. Verify identity\r\n4. Set new password', '2025-07-07 14:09:39'),
(15, 42, 'user', 'can you give me detail steps in filling my Monthly Activity Report System report', '2025-07-07 14:11:19'),
(16, 42, 'assistant', 'I\'d be happy to help you with that. Here are the step-by-step instructions for filling out your Monthly Activity Report System (MARS) report:\n\n**Step 1: Login and Access MARS Report**\n\n1. Go to the Ministry of Finance\'s website and click on the \"MARS\" tab.\n2. Enter your username and password to log in to the system.\n3. Once logged in, click on the \"Monthly Activity Report\" link to access the report.\n\n**Step 2: Review Report Template**\n\n1. Review the MARS report template to familiarize yourself with the sections and fields that need to be completed.\n2. Make sure you have all the necessary information and data required to complete the report.\n\n**Step 3: Fill Out Section 1: General Information**\n\n1. Enter your name, position, and department in the respective fields.\n2. Select the month and year for which you are submitting the report.\n3. Enter the date of submission.\n\n**Step 4: Fill Out Section 2: Activities and Achievements**\n\n• List all the activities and achievements you have accomplished during the month, including:\n	+ Program/initiative names\n	+ Brief descriptions\n	+ Relevant dates and timelines\n	+ Measurable outcomes and impact\n\n**Step 5: Fill Outattachments and Supporting Documents**\n\n1. Attach any supporting documents or evidence that support your activities and achievements, such as:\n	+ Reports, papers, or articles\n	+ Photos, videos, or other multimedia\n	+ Charts, graphs, or infographics\n2. Make sure all attachments are relevant, concise, and easily accessible.\n\n**Step 6: Review and Edit Report**\n\n1. Review your report for accuracy, completeness, and clarity.\n2. Edit your report as necessary to ensure it meets the requirements.\n3. Save your report regularly as you work on it.\n\n**Step 7: Submit Report**\n\n1. Once you are satisfied with your report, click the \"Submit\" button.\n2. Review the report one last time before submitting it.\n3. Confirm that you have submitted the report successfully.\n\n**Step 8: Print and Retain a Copy**\n\n1. Print a copy of your submitted report for your records.\n2. Retain a copy in your files for future reference.\n\nRemember to submit your report on or before the due date to ensure timely processing and consideration. If you have any questions or issues, please contact the MARS support team', '2025-07-07 14:12:15'),
(17, 51, 'user', 'Hello', '2025-07-08 10:26:50'),
(18, 51, 'assistant', 'Welcome to MoF Smart Desk, your virtual assistant for the Ministry of Finance, Ghana. I\'m here to assist you with any inquiries or tasks you may have. How can I help you today?', '2025-07-08 10:26:56'),
(19, 51, 'user', 'How do i reset my password', '2025-07-08 10:27:20'),
(20, 51, 'assistant', 'Password reset:\r\n1. Go to portal.mof.gov.gh/reset\r\n2. Enter staff ID\r\n3. Verify identity\r\n4. Set new password', '2025-07-08 10:27:23'),
(21, 51, 'user', 'How do i turn on my pc', '2025-07-08 10:28:14'),
(22, 51, 'assistant', 'Here\'s a step-by-step guide to help you turn on your PC:\r\n\r\n1. **Locate the Power Button**: Find the power button on your PC, which is usually located on the front or top of the tower, or on the keyboard.\r\n\r\n2. **Press the Power Button**: Press the power button firmly to turn on your PC. You may hear a slight click or feel a slight vibration when the button is pressed.\r\n\r\n3. **Wait for the Boot Process**: Wait for your PC to boot up. You will hear the fans spinning and see the lights on the front of the tower turn on.\r\n\r\n4. **Log in to Your Account**: Once the boot process is complete, you will be prompted to log in to your account. Enter your username and password to access your PC.\r\n\r\n• **Note**: If your PC is not turning on, try checking the power cord, ensuring it is securely plugged into both the PC and the wall outlet. Also, try pressing the power button for a longer duration or trying a different power outlet.\r\n\r\nThat\'s it! Your PC should now be turned on and ready for use.', '2025-07-08 10:28:38'),
(23, 55, 'user', 'MY PRINTER IS OFFLINE', '2025-07-08 10:30:11'),
(24, 55, 'assistant', '1. Check if the printer is powered on.\r\n2. Ensure the USB or network cable is connected.\r\n3. Restart the printer.', '2025-07-08 10:30:13'),
(25, 58, 'user', 'MY DOCUMENT IS NOT PRINTING HOW DO I FIX IT?', '2025-07-08 10:34:11'),
(26, 58, 'assistant', 'I\'m sorry, I couldn\'t process your request at this time. Please try again later.', '2025-07-08 10:34:13'),
(27, 58, 'user', 'HOW DO I FIX MY DOCUMENT NOT PRINTING?', '2025-07-08 10:34:42'),
(28, 58, 'assistant', 'I\'m sorry, I couldn\'t process your request at this time. Please try again later.', '2025-07-08 10:34:44'),
(29, 58, 'user', 'HOW DO I TURN ON MY PC', '2025-07-08 10:36:16'),
(30, 58, 'assistant', 'I\'m sorry, I couldn\'t process your request at this time. Please try again later.', '2025-07-08 10:36:18'),
(31, 61, 'user', 'my browser is not working', '2025-07-08 11:10:07'),
(32, 61, 'assistant', 'If the scanner isn\'t working:\r\n1. Ensure it\'s powered and connected\r\n2. Reopen scanning software\r\n3. Restart the device\r\n4. Report to IT if issue continues.', '2025-07-08 11:10:11'),
(33, 62, 'user', 'my broswer is not working', '2025-07-08 11:11:03'),
(34, 62, 'assistant', 'Ensure printer has paper and is not jammed.\r\nClear stuck print jobs from the queue.\r\nVerify login credentials.\r\nRestart printer.\r\nContact IT Helpdesk: itsupport@mof.gov.gh or +233 (0)30 XXXXXXX.', '2025-07-08 11:11:08'),
(35, 62, 'user', 'my browser is not working', '2025-07-08 11:11:19'),
(36, 62, 'assistant', 'Ensure printer has paper and is not jammed.\r\nClear stuck print jobs from the queue.\r\nVerify login credentials.\r\nRestart printer.\r\nContact IT Helpdesk: itsupport@mof.gov.gh or +233 (0)30 XXXXXXX.', '2025-07-08 11:11:24'),
(37, 63, 'user', 'printer not working', '2025-07-08 11:11:44'),
(38, 63, 'assistant', 'Ensure printer has paper and is not jammed.\r\nClear stuck print jobs from the queue.\r\nVerify login credentials.\r\nRestart printer.\r\nContact IT Helpdesk: itsupport@mof.gov.gh or +233 (0)30 XXXXXXX.', '2025-07-08 11:11:49'),
(39, 63, 'user', 'my pc is not booting', '2025-07-08 11:12:11'),
(40, 63, 'assistant', 'Check if PC is plugged into a working outlet.\r\nLook for power light at the back.\r\nHold power button for 5–10 seconds, release, and wait.\r\nContact IT Support via the support form.', '2025-07-08 11:12:16'),
(41, 65, 'user', 'my outlook is not receiving emails', '2025-07-08 11:15:31'),
(42, 65, 'assistant', 'Check your emails at outlook.office365.com. Sign in with your MoF account.', '2025-07-08 11:15:33'),
(43, 66, 'user', 'my outlook is not receiving emails', '2025-07-08 11:16:29'),
(44, 66, 'assistant', 'Not seeing your emails in Outlook? Here’s what to try:\r\n\r\n1. Confirm your internet connection is working.\r\n\r\n2. Check your junk or spam folders and mailbox storage limits.\r\n\r\n3. Restart Outlook to refresh it.\r\n\r\n4. If emails are bouncing, verify the recipient’s address and your inbox quota.\r\n\r\n5. Need more help? Reach out to IT at itsupport@mof.gov.gh.', '2025-07-08 11:16:38'),
(45, 119, 'user', 'printer offline', '2025-07-08 14:17:29'),
(46, 119, 'assistant', '1. Check if the printer is powered on.\r\n2. Ensure the USB or network cable is connected.\r\n3. Restart the printer.', '2025-07-08 14:17:31'),
(47, 123, 'user', 'what going on', '2025-07-09 13:13:36'),
(48, 123, 'assistant', 'Hello! As MoF Smart Desk, Im here to assist you with any queries or tasks related to the Ministry of Finance, Ghana. If you have any questions or need help with a specific task, feel free to ask!\r\n\r\nIs there something specific you\'d like to know or accomplish?', '2025-07-09 13:16:20');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `mof_staff_id` varchar(50) DEFAULT NULL,
  `role` varchar(50) DEFAULT 'staff',
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password_hash`, `mof_staff_id`, `role`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'oseidivine35@gmail.com', '$2y$10$MYEc1BJAUs.by32U8Zu8OeRz99xWeD2GIUA9QVWdTyJuerN4ifRdK', '111222', 'staff', '2025-07-09 13:19:58', '2025-07-04 19:01:01', '2025-07-09 13:19:58'),
(1010, 'guest@mof.gov.gh', '', 'guest', 'guest', NULL, '2025-07-09 13:08:05', '2025-07-09 13:08:05'),
(1014, 'sdf.sf@mofep.gov.gh', '$2y$10$f1.2mwXMw94TQr1UC3W95eavPpKIUo8pdzywTBhEFcCMGwxG8aZoW', '111223', 'staff', NULL, '2025-07-09 13:18:54', '2025-07-09 13:18:54'),
(1015, 'account@gmail.com', '$2y$10$uU.VtekoSztylkdhysE9LueQz.nTsbGmgNQyAJyt3BOM1wdpv7Gp.', '111225', 'staff', NULL, '2025-07-09 13:19:38', '2025-07-09 13:19:38');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `chat_history`
--
ALTER TABLE `chat_history`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `chat_identifier` (`chat_identifier`),
  ADD KEY `idx_ch_user_created` (`user_id`,`created_at`);

--
-- Indexes for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_cm_chat_created` (`chat_id`,`created_at`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `mof_staff_id` (`mof_staff_id`),
  ADD KEY `idx_users_email` (`email`),
  ADD KEY `idx_users_mof_staff_id` (`mof_staff_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `chat_history`
--
ALTER TABLE `chat_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=128;

--
-- AUTO_INCREMENT for table `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1016;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chat_history`
--
ALTER TABLE `chat_history`
  ADD CONSTRAINT `fk_ch_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD CONSTRAINT `fk_cm_chat` FOREIGN KEY (`chat_id`) REFERENCES `chat_history` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
