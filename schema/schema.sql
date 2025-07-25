-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 25, 2025 at 03:01 PM
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
-- Table structure for table `api_keys`
--

CREATE TABLE `api_keys` (
  `id` int(11) NOT NULL,
  `service_name` varchar(100) NOT NULL,
  `api_key` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `api_keys`
--

INSERT INTO `api_keys` (`id`, `service_name`, `api_key`, `created_at`) VALUES
(1, 'together', '5e33e8e11b89e8a9e67655927363c762c7b56f082a454e072458a4eecb5fa845', '2025-07-22 14:53:50');

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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('reviewed','not reviewed') NOT NULL DEFAULT 'not reviewed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chat_history`
--

INSERT INTO `chat_history` (`id`, `user_id`, `chat_identifier`, `title`, `created_at`, `updated_at`, `status`) VALUES
(42, 1, 'chat_686bd138649744.99885721', 'how do i rest my password', '2025-07-07 13:52:56', '2025-07-07 14:08:45', 'not reviewed'),
(51, 1, 'chat_686cf26666f975.16620287', 'Hello', '2025-07-08 10:26:46', '2025-07-08 10:26:50', 'not reviewed'),
(55, 1, 'chat_686cf302a1c864.53564112', 'MY PRINTER IS OFFLINE', '2025-07-08 10:29:22', '2025-07-08 10:30:11', 'not reviewed'),
(58, 1, 'chat_686cf3fa027d67.26503819', 'MY DOCUMENT IS NOT PRINTING HOW DO I FIX IT?', '2025-07-08 10:33:30', '2025-07-08 10:34:11', 'not reviewed'),
(61, 1, 'chat_686cfc863ddfa4.13103816', 'my browser is not working', '2025-07-08 11:09:58', '2025-07-08 11:10:07', 'not reviewed'),
(62, 1, 'chat_686cfcbfcd5d60.50560131', 'my broswer is not working', '2025-07-08 11:10:55', '2025-07-08 11:11:03', 'not reviewed'),
(63, 1, 'chat_686cfceb5c96a9.48877548', 'printer not working', '2025-07-08 11:11:39', '2025-07-08 11:11:44', 'not reviewed'),
(65, 1, 'chat_686cfdcae5c375.93168534', 'my outlook is not receiving emails', '2025-07-08 11:15:22', '2025-07-08 11:15:31', 'not reviewed'),
(66, 1, 'chat_686cfe022a6479.51268947', 'my outlook is not receiving emails', '2025-07-08 11:16:18', '2025-07-08 11:16:29', 'not reviewed'),
(119, 1, 'chat_686d27024f1701.59673107', 'printer offline', '2025-07-08 14:11:14', '2025-07-08 14:17:30', 'not reviewed'),
(121, 1010, 'chat_686e6af6c789a6.88238518', 'New Chat', '2025-07-09 13:13:26', '2025-07-09 13:13:26', 'not reviewed'),
(122, 1010, 'chat_686e6af80ec063.30216758', 'New Chat', '2025-07-09 13:13:28', '2025-07-09 13:13:28', 'not reviewed'),
(123, 1010, 'chat_686e6afa563282.20009221', 'New Chat', '2025-07-09 13:13:30', '2025-07-09 13:13:30', 'not reviewed'),
(133, 1010, 'chat_687fa15143ddd0.94369614', 'New Chat', '2025-07-22 14:33:53', '2025-07-22 14:33:53', 'not reviewed'),
(134, 1010, 'chat_687fa20d2b02a6.91534382', 'New Chat', '2025-07-22 14:37:01', '2025-07-22 14:37:01', 'not reviewed'),
(135, 1010, 'chat_687fa20e783832.08383855', 'New Chat', '2025-07-22 14:37:02', '2025-07-22 14:37:02', 'not reviewed'),
(136, 1010, 'chat_687fa2114a76f0.59893248', 'New Chat', '2025-07-22 14:37:05', '2025-07-22 14:37:05', 'not reviewed'),
(137, 1010, 'chat_687fa2335520b8.60654325', 'New Chat', '2025-07-22 14:37:39', '2025-07-22 14:37:39', 'not reviewed'),
(138, 1010, 'chat_687fa294dc18c2.55471859', 'New Chat', '2025-07-22 14:39:16', '2025-07-22 14:39:16', 'not reviewed'),
(139, 1010, 'chat_687fa29f091d08.87205796', 'New Chat', '2025-07-22 14:39:27', '2025-07-22 14:39:27', 'not reviewed'),
(140, 1010, 'chat_687fa2f4594f15.01843580', 'New Chat', '2025-07-22 14:40:52', '2025-07-22 14:40:52', 'not reviewed'),
(141, 1010, 'chat_687fa2f66184c0.08221118', 'New Chat', '2025-07-22 14:40:54', '2025-07-22 14:40:54', 'not reviewed'),
(142, 1010, 'chat_687fa729449061.81407461', 'New Chat', '2025-07-22 14:58:49', '2025-07-22 14:58:49', 'not reviewed'),
(143, 1010, 'chat_687faa6895e9a2.99549314', 'New Chat', '2025-07-22 15:12:40', '2025-07-22 15:12:40', 'not reviewed'),
(144, 1010, 'chat_687faa6a03e5c1.52497351', 'New Chat', '2025-07-22 15:12:42', '2025-07-22 15:12:42', 'not reviewed'),
(145, 1010, 'chat_687fab8175d6d0.78827573', 'New Chat', '2025-07-22 15:17:21', '2025-07-22 15:17:21', 'not reviewed'),
(201, 1, 'chat_6880e79d6f13b4.12947127', 'hello', '2025-07-23 13:46:05', '2025-07-23 13:46:09', 'not reviewed'),
(212, 1, 'chat_688217f3235062.50035454', 'who is the minister of finance', '2025-07-24 11:24:35', '2025-07-24 11:24:41', 'not reviewed'),
(213, 1, 'chat_68821a3b4c6450.12750478', 'my internet is slow', '2025-07-24 11:34:19', '2025-07-24 11:34:41', 'not reviewed'),
(214, 1, 'chat_68821a9ddf86a5.70865937', 'who is the president of ghana', '2025-07-24 11:35:57', '2025-07-24 11:36:03', 'not reviewed'),
(215, 1, 'chat_68821b203cfd71.15532395', 'who is the president of ghana', '2025-07-24 11:38:08', '2025-07-24 16:23:03', 'reviewed'),
(216, 1, 'chat_68821ba2cd9c41.39431533', 'who is the president of ghana', '2025-07-24 11:40:18', '2025-07-25 12:34:08', 'reviewed'),
(217, 1, 'chat_688221c50d9727.54559066', 'who is the ahafo regional minister', '2025-07-24 12:06:29', '2025-07-25 12:34:09', 'reviewed'),
(236, 1, 'chat_688373ed24cd87.40828790', 'hello .. who is jj rawlings', '2025-07-25 12:09:17', '2025-07-25 12:09:30', 'not reviewed'),
(237, 1, 'chat_68838011cc04d1.04557122', 'New Chat', '2025-07-25 13:01:05', '2025-07-25 13:01:05', 'not reviewed');

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
(48, 123, 'assistant', 'Hello! As MoF Smart Desk, I\'m here to assist you with any queries or tasks related to the Ministry of Finance, Ghana. If you have any questions or need help with a specific task, feel free to ask!\r\n\r\nIs there something specific you\'d like to know or accomplish?', '2025-07-09 13:16:20'),
(49, 141, 'user', 'WHO IS THE CURRENT PRESIDENT OF GHANA', '2025-07-22 14:41:03'),
(50, 141, 'assistant', 'The current President of Ghana is Nana Addo Dankwa Akufo-Addo.', '2025-07-22 14:41:46'),
(51, 142, 'user', 'hello', '2025-07-22 14:58:54'),
(52, 142, 'assistant', 'Hello! I\'m MoF Smart Desk, your virtual assistant for the Ministry of Finance, Ghana. How can I assist you today?', '2025-07-22 14:58:59'),
(53, 144, 'user', 'Who is the president of Ghana', '2025-07-22 15:12:50'),
(54, 144, 'assistant', 'I\'m sorry, I couldn\'t process your request at this time. Please try again later.', '2025-07-22 15:12:51'),
(55, 144, 'user', 'Who is the president of Ghana', '2025-07-22 15:13:39'),
(56, 144, 'assistant', 'As MoF Smart Desk, I can provide you with the most up-to-date information. According to official records, the current President of Ghana is:\r\n\r\n**Nana Addo Dankwa Akufo-Addo**\r\n\r\nPlease note that this information is subject to change, and for the most accurate and current information, I recommend visiting the official website of the Republic of Ghana or the Ministry of Information, Ghana.\r\n\r\nHere\'s a step-by-step guide to verify the information:\r\n\r\n1. Visit the official website of the Republic of Ghana: [www.ghana.gov.gh](http://www.ghana.gov.gh)\r\n2. Navigate to the \"President\" section\r\n3. Click on the \"Bio\" or \"Profile\" tab to access the current President\'s information\r\n\r\nAlternatively, you can visit the official website of the Ministry of Information, Ghana, which also provides updated information on the current President:\r\n\r\n1. Visit the official website of the Ministry of Information, Ghana: [www.info.gov.gh](http://www.info.gov.gh)\r\n2. Navigate to the \"President\" section\r\n3. Click on the \"Bio\" or \"Profile\" tab to access the current President\'s information\r\n\r\nRemember to always verify information through official and credible sources to ensure accuracy.', '2025-07-22 15:14:06'),
(57, 145, 'user', 'who is the president of ghana', '2025-07-22 15:17:27'),
(58, 145, 'assistant', 'As of my last update in 2021, the President of Ghana was Nana Akufo-Addo. However, this may be outdated. For the most accurate and up-to-date information, I recommend visiting the official website of the Office of the President at https://presidency.gov.gh.', '2025-07-22 15:17:35'),
(59, 201, 'user', 'hello', '2025-07-23 13:46:09'),
(60, 201, 'assistant', 'Welcome to the MoF Smart Desk! I\'m here to assist you with any inquiries or requests related to the Ministry of Finance, Ghana. What can I help you with today?', '2025-07-23 13:46:14'),
(61, 201, 'user', 'my network is slow', '2025-07-23 13:46:33'),
(62, 201, 'assistant', '1. Check the taskbar for the network icon. If it is not visible, unplug the Ethernet cable and reconnect it securely.\r\n2. If you are using Wi-Fi, ensure you are connected to a network and that the signal strength is strong.\r\n3. Try accessing the required resource using an alternative browser such as Microsoft Edge.\r\n4. If the issue persists, contact MOF IT Support via the official support form.', '2025-07-23 13:46:34'),
(63, 212, 'user', 'who is the minister of finance', '2025-07-24 11:24:41'),
(64, 212, 'assistant', 'As of my last update in 2022, the Minister of Finance for Ghana is Ken Ofori-Atta. However, this may be outdated. For the most accurate and up-to-date information, I recommend visiting the official website of the Ministry of Finance, Ghana at https://mofep.gov.gh or the Office of the President, Ghana at https://presidency.gov.gh.', '2025-07-24 11:25:49'),
(65, 212, 'user', 'who is the minister of finance', '2025-07-24 11:25:56'),
(66, 212, 'assistant', 'The current Deputy Minister, Finance is Thomas Nyarko Ampem (since 2025-01-22).', '2025-07-24 11:25:57'),
(67, 212, 'user', 'who is the president of ghana', '2025-07-24 11:26:05'),
(68, 212, 'assistant', 'The current Vice President is Jane Naana Opoku-Agyemang (since 2025-01-07).', '2025-07-24 11:26:07'),
(69, 212, 'user', 'who is the minister of finence', '2025-07-24 11:26:32'),
(70, 212, 'assistant', 'The current Deputy Minister, Finance is Thomas Nyarko Ampem (since 2025-01-22).', '2025-07-24 11:26:33'),
(71, 213, 'user', 'my internet is slow', '2025-07-24 11:34:41'),
(72, 213, 'assistant', '1. Check the taskbar for the network icon. If it is not visible, unplug the Ethernet cable and reconnect it securely.\r\n2. If you are using Wi-Fi, ensure you are connected to a network and that the signal strength is strong.\r\n3. Try accessing the required resource using an alternative browser such as Microsoft Edge.\r\n4. If the issue persists, contact MOF IT Support via the official support form.', '2025-07-24 11:34:43'),
(73, 213, 'user', 'who is the president', '2025-07-24 11:35:04'),
(74, 213, 'assistant', 'The current Vice President is Jane Naana Opoku-Agyemang (since 2025-01-07).', '2025-07-24 11:35:05'),
(75, 214, 'user', 'who is the president of ghana', '2025-07-24 11:36:03'),
(76, 214, 'assistant', 'As of my last update in 2022, the President of Ghana is Nana Akufo-Addo. However, this may be outdated. For the most accurate and up-to-date information, I recommend visiting the official website of the Office of the President: https://presidency.gov.gh', '2025-07-24 11:37:27'),
(77, 215, 'user', 'who is the president of ghana', '2025-07-24 11:38:16'),
(78, 215, 'assistant', 'As of my last update in [year], the President of Ghana was Nana Akufo-Addo. However, this may be outdated. For the most accurate and up-to-date information, I recommend visiting the official website of the Office of the President at https://presidency.gov.gh.\r\n\r\nPlease note that presidential information can change frequently, and I may not have the most recent information. I recommend verifying the information through official sources for the most accurate and up-to-date information.', '2025-07-24 11:38:31'),
(79, 215, 'user', 'who is the minister of finance', '2025-07-24 11:38:31'),
(80, 215, 'assistant', 'As of my last update in 2022, the Minister of Finance for the Republic of Ghana is Ken Ofori-Atta. However, this may be outdated. For the most accurate and up-to-date information, I recommend visiting the official website of the Ministry of Finance: https://mofep.gov.gh.', '2025-07-24 11:38:40'),
(81, 216, 'user', 'who is the president of ghana', '2025-07-24 11:40:25'),
(82, 216, 'assistant', 'The current President is John Dramani Mahama (since 2025-01-07).', '2025-07-24 11:40:28'),
(83, 216, 'user', 'who is the minister of finance', '2025-07-24 11:40:36'),
(84, 216, 'assistant', 'As of my last update in 2022, the Minister of Finance for Ghana is Hon. Ken Ofori-Atta. However, this may be outdated. For the most accurate and up-to-date information, I recommend visiting the official website of the Ministry of Finance, Ghana at https://mofep.gov.gh or the General Government Info website at https://info.gov.gh.', '2025-07-24 11:40:49'),
(85, 216, 'user', 'who is the energy minister', '2025-07-24 11:41:38'),
(86, 216, 'assistant', 'The current Minister, Energy is John Abdulai Jinapor (since 2025-01-22).', '2025-07-24 11:41:42'),
(87, 216, 'user', 'who is the minister of finance and economic planning', '2025-07-24 11:42:30'),
(88, 216, 'assistant', 'The current Minister, Finance & Economic Planning is Cassiel Ato Forson (since 2025-01-22).', '2025-07-24 11:42:32'),
(89, 216, 'user', 'who is the minister of finance', '2025-07-24 11:42:39'),
(90, 216, 'assistant', 'As of my last update in 2022, the Minister of Finance for Ghana is Mr. Ken Ofori-Atta. However, this may be outdated. For the most accurate and up-to-date information, I recommend visiting the official website of the Ministry of Finance and Economic Planning: https://mofep.gov.gh.\r\n\r\nPlease note that ministerial appointments can change, and it\'s essential to verify the information from the official sources for the most current and accurate information.', '2025-07-24 11:43:01'),
(91, 216, 'user', 'who is the minister of finance', '2025-07-24 11:43:05'),
(92, 216, 'assistant', 'As of my last update in 2021, the Minister of Finance for the Republic of Ghana is Ken Ofori-Atta. However, this may be outdated. For the most accurate and up-to-date information, I recommend visiting the official website of the Ministry of Finance at https://mofep.gov.gh.', '2025-07-24 11:43:19'),
(93, 216, 'user', 'who is the vice president', '2025-07-24 11:43:24'),
(94, 216, 'assistant', 'The current Vice President is Jane Naana Opoku-Agyemang (since 2025-01-07).', '2025-07-24 11:43:27'),
(95, 216, 'user', 'who is the minister of finance', '2025-07-24 11:43:41'),
(96, 216, 'user', 'who is the defence minister', '2025-07-24 11:44:09'),
(97, 216, 'assistant', 'As of my last update in [year], the Minister of Finance for the Republic of Ghana is [Minister\'s Name]. However, this may be outdated. For the most accurate and up-to-date information, I recommend visiting the Ministry of Finance\'s official website at https://mofep.gov.gh or checking other reliable sources.\r\n\r\nPlease note that the information I provide may be outdated, and for the most current information, I recommend checking the official government sources.', '2025-07-24 11:44:11'),
(98, 216, 'assistant', 'The current Minister, Defence is Edward Omane Boamah (since 2025-01-30).', '2025-07-24 11:44:13'),
(99, 216, 'user', 'what position does Edward Omane Boamah  hold in office', '2025-07-24 11:44:34'),
(100, 216, 'assistant', 'The current Minister, Health is Kwabena Mintah Akandoh (since 2025-02-07).', '2025-07-24 11:44:39'),
(101, 216, 'user', 'who is john mahama', '2025-07-24 11:44:56'),
(102, 216, 'assistant', 'The current President is John Dramani Mahama (since 2025-01-07).', '2025-07-24 11:44:58'),
(103, 216, 'user', 'who it ato forson', '2025-07-24 11:45:08'),
(104, 216, 'assistant', 'As of my last update in 2022, Dr. Kwame Ato Forson is the current Minister of Finance for the Republic of Ghana. However, this may be outdated. For the most accurate and up-to-date information, I recommend visiting the official website of the Ministry of Finance and Economic Planning at https://mofep.gov.gh.\r\n\r\nPlease note that I\'m a virtual assistant, and my information may not reflect the most recent changes or updates. For the most accurate information, it\'s always best to visit official government sources.', '2025-07-24 11:45:23'),
(105, 216, 'user', 'who is the gender minister', '2025-07-24 11:45:41'),
(106, 216, 'assistant', 'As of my last update in 2021, the Minister of Gender, Children and Social Protection is Honourable Cynthia Morrison. However, this may be outdated. For the most accurate and up-to-date information, I recommend visiting the official website of the Ministry of Gender, Children and Social Protection at https://mog.gov.gh.', '2025-07-24 11:45:51'),
(107, 216, 'user', 'who is the president of ghana', '2025-07-24 11:50:10'),
(108, 216, 'assistant', 'The current President is John Dramani Mahama (since 2025-01-07).', '2025-07-24 11:50:12'),
(109, 216, 'user', 'who is the finance minister', '2025-07-24 11:50:18'),
(110, 216, 'assistant', 'The current Minister, Finance  is Cassiel Ato Forson (since 2025-01-22).', '2025-07-24 11:50:19'),
(111, 216, 'user', 'who is Cassiel Ato Forson', '2025-07-24 11:50:30'),
(112, 216, 'assistant', 'A great question!\r\n\r\nAs of my last update in 2022, Cassiel Ato Forson is a Ghanaian politician who has served as the Member of Parliament (MP) for the Ajumako-Enyan-Esiam Constituency in the Central Region of Ghana. He is also a former Deputy Minister of Finance under the John Mahama administration from 2013 to 2017.\r\n\r\nHowever, this information may be outdated. For the most accurate and up-to-date information on Cassiel Ato Forson, I recommend visiting the official websites of the Parliament of Ghana or the Ministry of Finance and Economic Planning.\r\n\r\nWould you like to know more about the current status of Cassiel Ato Forson or the Ministry of Finance?', '2025-07-24 11:50:52'),
(113, 216, 'user', 'who is the minister of food and agric', '2025-07-24 11:51:25'),
(114, 216, 'assistant', 'The current Minister, Food & Agriculture is Eric Opoku (since 2025-01-22).', '2025-07-24 11:51:27'),
(115, 217, 'user', 'who is the ahafo regional minister', '2025-07-24 12:06:36'),
(116, 217, 'assistant', 'The current Ahafo Regional Minister is Charity Gardiner (since 2025-02-07).', '2025-07-24 12:06:46'),
(117, 217, 'user', 'when was rawlings the president of ghana?', '2025-07-24 12:08:00'),
(118, 217, 'assistant', 'The current President is John Dramani Mahama (since 2025-01-07).', '2025-07-24 12:08:02'),
(119, 217, 'user', 'who is the minister of finance of ghana?', '2025-07-24 12:08:31'),
(120, 217, 'assistant', 'The current Minister, Finance  is Cassiel Ato Forson (since 2025-01-22).', '2025-07-24 12:08:33'),
(121, 217, 'user', 'when was rawlings the president of ghana?', '2025-07-24 12:15:49'),
(122, 217, 'assistant', 'Jerry John Rawlings was the President of Ghana twice:\r\n\r\n1. First term: January 24, 1981 - December 20, 2001 (initially as a military leader, then as a democratically elected president)\r\n2. Second term: January 7, 1993 - January 1, 2001 (as a democratically elected president)', '2025-07-24 12:15:58'),
(123, 217, 'user', 'who is the president of ghana', '2025-07-24 12:16:06'),
(124, 217, 'assistant', 'The current President is John Dramani Mahama (since 2025-01-07).', '2025-07-24 12:16:10'),
(125, 236, 'user', 'hello .. who is jj rawlings', '2025-07-25 12:09:30'),
(126, 236, 'assistant', 'Jerry John Rawlings is a prominent figure in Ghanaian politics. He was a military officer who led two coups in Ghana, first in 1979 and again in 1981. He overthrew the democratically-elected government of President Hilla Limann in 1979, and then again in 1981, he overthrew the military government of General Fred Akuffo.\r\n\r\nRawlings established the Provisional National Defence Council (PNDC) in 1982, which ruled Ghana from 1982 to 1992. During his tenure, he implemented several economic and social reforms, including the nationalization of key industries, the introduction of a new constitution, and the establishment of a new political party, the National Democratic Congress', '2025-07-25 12:09:48'),
(127, 236, 'user', 'who created you', '2025-07-25 12:09:55'),
(128, 236, 'assistant', 'I was created by MoF Smart Desk, a virtual assistant for the Republic of Ghana\'s Ministry of Finance.', '2025-07-25 12:09:59'),
(129, 236, 'user', 'Tell me a joke', '2025-07-25 12:10:10'),
(130, 236, 'assistant', 'I\'m happy to try! However, as a MoF Smart Desk, I\'m primarily focused on providing informative and helpful responses. But, I\'ll try to come up with a financial-themed joke for you:\r\n\r\nWhy did the Ghanaian cedi go to therapy?\r\n\r\n• Because it was feeling a little \"devalued\"!\r\n\r\n(Sorry, I know it\'s a bit of a \"stretch\" – but I hope it brought a smile to your face!)\r\n\r\nLet me know if you\'d like to know more about a specific financial topic, and I\'ll do my best to assist you!', '2025-07-25 12:10:22'),
(131, 236, 'user', 'who was the president of ghana in 2022', '2025-07-25 12:10:42'),
(132, 236, 'assistant', 'The current President is John Dramani Mahama (since 2025-01-07).', '2025-07-25 12:10:44'),
(133, 236, 'user', 'who was the president of ghana in 2020', '2025-07-25 12:10:57'),
(134, 236, 'assistant', 'As of 2020, the President of Ghana was Nana Akufo-Addo. He was first elected in 2016 and re-elected in 2020, serving his second term as President of Ghana from January 7, 2021, to January 7, 2025.', '2025-07-25 12:11:03'),
(135, 236, 'user', 'who was the president of ghana in 2022', '2025-07-25 12:11:08'),
(136, 236, 'assistant', 'The current President is John Dramani Mahama (since 2025-01-07).', '2025-07-25 12:11:10'),
(137, 236, 'user', 'who was the president of ghana in 2021', '2025-07-25 12:11:17'),
(138, 236, 'assistant', 'The current President is John Dramani Mahama (since 2025-01-07).', '2025-07-25 12:11:20');

-- --------------------------------------------------------

--
-- Table structure for table `current_affairs`
--

CREATE TABLE `current_affairs` (
  `id` int(11) NOT NULL,
  `position` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date DEFAULT NULL,
  `updated_on` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `current_affairs`
--

INSERT INTO `current_affairs` (`id`, `position`, `name`, `from_date`, `to_date`, `updated_on`, `created_on`) VALUES
(63, 'President', 'John Dramani Mahama', '2025-01-07', NULL, '2025-07-24 11:16:00', '2025-07-24 11:16:00'),
(64, 'Vice President', 'Jane Naana Opoku-Agyemang', '2025-01-07', NULL, '2025-07-24 11:16:00', '2025-07-24 11:16:00'),
(65, 'Minister, Finance ', 'Cassiel Ato Forsons', '2025-01-22', NULL, '2025-07-24 12:09:02', '2025-07-24 11:16:00'),
(66, 'Minister, Foreign Affairs', 'Samuel Okudzeto Ablakwa', '2025-02-07', NULL, '2025-07-24 11:16:00', '2025-07-24 11:16:00'),
(67, 'Minister, Interior', 'Mohammed Mubarak Muntaka', '2025-01-30', NULL, '2025-07-24 11:16:00', '2025-07-24 11:16:00'),
(68, 'Minister, Defence', 'Edward Omane Boamah', '2025-01-30', NULL, '2025-07-24 11:16:00', '2025-07-24 11:16:00'),
(69, 'Attorney General & Justice Minister', 'Dominic Akuritinga Ayine', '2025-01-22', NULL, '2025-07-24 11:16:00', '2025-07-24 11:16:00'),
(70, 'Minister, Education', 'Haruna Iddrisu', '2025-01-22', NULL, '2025-07-24 11:16:00', '2025-07-24 11:16:00'),
(71, 'Minister, Food & Agriculture', 'Eric Opoku', '2025-01-22', NULL, '2025-07-24 11:16:00', '2025-07-24 11:16:00'),
(72, 'Minister, Fisheries & Aquaculture', 'Emelia Arthur', '2025-01-25', NULL, '2025-07-24 11:16:00', '2025-07-24 11:16:00'),
(73, 'Minister, Trade & Industry', 'Elizabeth Ofosu-Adjare', '2025-01-25', NULL, '2025-07-24 11:16:00', '2025-07-24 11:16:00'),
(74, 'Minister, Health', 'Kwabena Mintah Akandoh', '2025-02-07', NULL, '2025-07-24 11:16:00', '2025-07-24 11:16:00'),
(75, 'Minister, Communications, Digitalisation & Innovation', 'Samuel Nartey George', '2025-02-07', NULL, '2025-07-24 11:16:00', '2025-07-24 11:16:00'),
(76, 'Minister, Energy', 'John Abdulai Jinapor', '2025-01-22', NULL, '2025-07-24 11:16:00', '2025-07-24 11:16:00'),
(77, 'Minister, Transport', 'Joseph Bukari Nikpe', '2025-02-07', NULL, '2025-07-24 11:16:00', '2025-07-24 11:16:00'),
(78, 'Minister, Roads & Highways', 'Kwame Governs Agbodza', '2025-01-22', NULL, '2025-07-24 11:16:00', '2025-07-24 11:16:00'),
(79, 'Minister, Lands & Natural Resources', 'Emmanuel Armah Kofi Buah', '2025-01-30', NULL, '2025-07-24 11:16:00', '2025-07-24 11:16:00'),
(80, 'Minister, Environment, Science & Technology', 'Ibrahim Murtala Muhammed', '2025-02-07', NULL, '2025-07-24 11:16:00', '2025-07-24 11:16:00'),
(81, 'Minister, Local Government, Chieftaincy & Religious Affairs', 'Ahmed Ibrahim', '2025-01-25', NULL, '2025-07-24 11:16:00', '2025-07-24 11:16:00'),
(82, 'Minister, Tourism, Culture & Creative Arts', 'Dzifa Gomashie', '2025-01-25', NULL, '2025-07-24 11:16:00', '2025-07-24 11:16:00'),
(83, 'Minister, Labour, Jobs & Employment', 'Abdul‑Rashid Pelpuo', '2025-02-07', NULL, '2025-07-24 11:16:00', '2025-07-24 11:16:00'),
(84, 'Minister, Works, Housing & Water Resources', 'Kenneth Gilbert Adjei', '2025-01-30', NULL, '2025-07-24 11:16:00', '2025-07-24 11:16:00'),
(85, 'Minister, Youth Development & Empowerment', 'George Opare Addo', '2025-01-25', NULL, '2025-07-24 11:16:00', '2025-07-24 11:16:00'),
(86, 'Minister, Sports & Recreation', 'Kofi Iddie Adams', '2025-02-07', NULL, '2025-07-24 11:16:00', '2025-07-24 11:16:00'),
(87, 'Minister, Gender, Children & Social Protection', 'Agnes Naa Momo Lartey', '2025-01-30', NULL, '2025-07-24 11:16:00', '2025-07-24 11:16:00'),
(88, 'Minister of State (Special Initiatives)', 'Emmanuel Kwadwo‑Agyekum', '2025-01-30', NULL, '2025-07-24 11:16:00', '2025-07-24 11:16:00'),
(89, 'Minister of State (Govt Communications)', 'Felix Kwakye Ofosu', '2025-02-07', NULL, '2025-07-24 11:16:00', '2025-07-24 11:16:00'),
(90, 'Minister of State (Climate Change & Sustainability)', 'Issifu Seidu', '2025-02-07', NULL, '2025-07-24 11:16:00', '2025-07-24 11:16:00'),
(91, 'Minister of State (Public Sector Reforms)', 'Lydia Lamisi Akanvariba', '2025-02-07', NULL, '2025-07-24 11:16:00', '2025-07-24 11:16:00'),
(92, 'Ahafo Regional Minister', 'Charity Gardiner', '2025-02-07', NULL, '2025-07-24 12:01:15', '2025-07-24 11:16:00'),
(93, 'Ashanti Regional Minister', 'Frank Amoakohene', '2025-01-30', NULL, '2025-07-24 11:16:00', '2025-07-24 11:16:00'),
(94, 'Bono Regional Minister', 'Joseph Addae Akwaboah', '2025-02-07', NULL, '2025-07-24 11:16:00', '2025-07-24 11:16:00'),
(95, 'Bono East Regional Minister', 'Francis Owusu Antwi', '2025-02-07', NULL, '2025-07-24 11:16:00', '2025-07-24 11:16:00'),
(96, 'Central Regional Minister', 'Eduamoah Ekow Panyin Okyere', '2025-02-07', NULL, '2025-07-24 11:16:00', '2025-07-24 11:16:00'),
(97, 'Eastern Regional Minister', 'Rita Akosua Adjei Awatey', '2025-01-30', NULL, '2025-07-24 11:16:00', '2025-07-24 11:16:00'),
(98, 'Greater Accra Regional Minister', 'Linda Obenewaa Akweley Ocloo', '2025-01-30', NULL, '2025-07-24 11:16:00', '2025-07-24 11:16:00'),
(99, 'Northern Regional Minister', 'Ali Adolf John', '2025-01-30', NULL, '2025-07-24 11:16:00', '2025-07-24 11:16:00'),
(100, 'North East Regional Minister', 'Ibrahim Tia', '2025-01-30', NULL, '2025-07-24 11:16:00', '2025-07-24 11:16:00'),
(101, 'Oti Regional Minister', 'John Kwadwo Gyapong', '2025-01-30', NULL, '2025-07-24 11:16:00', '2025-07-24 11:16:00'),
(102, 'Savannah Regional Minister', 'Salisu Be‑Awuribe Issifu', '2025-02-07', NULL, '2025-07-24 11:16:00', '2025-07-24 11:16:00'),
(103, 'Upper East Regional Minister', 'Akamugri Atanga Donatus', '2025-01-30', NULL, '2025-07-24 11:16:00', '2025-07-24 11:16:00'),
(104, 'Upper West Regional Minister', 'Puozuing Charles Lwanga', '2025-02-07', NULL, '2025-07-24 11:16:00', '2025-07-24 11:16:00'),
(105, 'Volta Regional Minister', 'James Gunu', '2025-01-30', NULL, '2025-07-24 11:16:00', '2025-07-24 11:16:00'),
(106, 'Western Regional Minister', 'Joseph Nelson', '2025-02-07', NULL, '2025-07-24 11:16:00', '2025-07-24 11:16:00'),
(107, 'Western North Regional Minister', 'Wilbert Petty Brentum', '2025-02-07', NULL, '2025-07-24 11:16:00', '2025-07-24 11:16:00'),
(108, 'Deputy Minister, Finance', 'Thomas Nyarko Ampem', '2025-01-22', NULL, '2025-07-24 11:16:00', '2025-07-24 11:16:00'),
(109, 'Chief Director, MoF', 'Patrick Nomo', '2025-01-22', NULL, '2025-07-24 11:16:00', '2025-07-24 11:16:00'),
(110, 'Coordinating Director, MoF', 'David Klotey Collison', '2025-01-22', NULL, '2025-07-24 11:16:00', '2025-07-24 11:16:00'),
(111, 'Dir, Financial Sector, MoF', 'Louis Kwame Amo', '2025-01-22', NULL, '2025-07-24 11:16:00', '2025-07-24 11:16:00'),
(112, 'Dir, Real Sector, MoF', 'Samuel Danquah Arkhurst', '2025-01-22', NULL, '2025-07-24 11:16:00', '2025-07-24 11:16:00'),
(113, 'Dir, Legal, MoF', 'Grace Mbrokoh‑Ewoal', '2025-01-22', NULL, '2025-07-24 11:16:00', '2025-07-24 11:16:00'),
(114, 'Dir, Budget Office, MoF', 'Isaac Fraikue', '2025-01-22', NULL, '2025-07-24 11:16:00', '2025-07-24 11:16:00'),
(115, 'Dir, Policy Coord & M&E, MoF', 'Augustus Kwasi Adu', '2025-01-22', NULL, '2025-07-24 11:16:00', '2025-07-24 11:16:00'),
(116, 'Dir, Procurement, MoF', 'Maame Aba Odeiba Abbey', '2025-01-22', NULL, '2025-07-24 11:16:00', '2025-07-24 11:16:00'),
(117, 'Dir, Public Debt Mgmt, MoF', 'Yaa Asantewa Asante', '2025-01-22', NULL, '2025-07-24 11:16:00', '2025-07-24 11:16:00'),
(118, 'Dir, Revenue Policy, MoF', 'Edward Abrokwah', '2025-01-22', NULL, '2025-07-24 11:16:00', '2025-07-24 11:16:00'),
(119, 'Dir, Research, MoF', 'Nelly Mireku', '2025-01-22', NULL, '2025-07-24 11:16:00', '2025-07-24 11:16:00'),
(120, 'Dir, Internal Audit, MoF', 'Jacob Ahadzi', '2025-01-22', NULL, '2025-07-24 11:16:00', '2025-07-24 11:16:00'),
(121, 'Dir, Public Investment & Asset Mgmt, MoF', 'Anthony S. Dzadzra', '2025-01-22', NULL, '2025-07-24 11:16:00', '2025-07-24 11:16:00'),
(122, 'Dir, Compliance, MoF', 'Benjamin Sowah Adjetey', '2025-01-22', NULL, '2025-07-24 11:16:00', '2025-07-24 11:16:00'),
(123, 'Dir, General Admin, MoF', 'Benjamin Botchway', '2025-01-22', NULL, '2025-07-24 11:16:00', '2025-07-24 11:16:00'),
(124, 'Dir, Finance, MoF', 'Afua Animah Brown‑Pobee', '2025-01-22', NULL, '2025-07-24 11:16:00', '2025-07-24 11:16:00'),
(125, 'Deputy Minister, Education', 'Gifty Twum-Ampofo', '2025-01-25', NULL, '2025-07-24 11:17:39', '2025-07-24 11:17:39'),
(126, 'Deputy Minister, Health', 'Tina Gifty Mensah', '2025-01-25', NULL, '2025-07-24 11:17:39', '2025-07-24 11:17:39'),
(127, 'Deputy Minister, Trade & Industry', 'Stephen Amoah', '2025-01-25', NULL, '2025-07-24 11:17:39', '2025-07-24 11:17:39'),
(128, 'Deputy Minister, Energy', 'William Owuraku Aidoo', '2025-01-25', NULL, '2025-07-24 11:17:39', '2025-07-24 11:17:39'),
(129, 'Deputy Minister, Interior', 'Naana Eyiah', '2025-01-25', NULL, '2025-07-24 11:17:39', '2025-07-24 11:17:39'),
(130, 'Deputy Minister, Local Government', 'Collins Ntim', '2025-01-25', NULL, '2025-07-24 11:17:39', '2025-07-24 11:17:39'),
(131, 'Deputy Minister, Roads & Highways', 'Stephen Jalulah', '2025-01-25', NULL, '2025-07-24 11:17:39', '2025-07-24 11:17:39'),
(132, 'Deputy Minister, Works & Housing', 'Abdulai Abanga', '2025-01-25', NULL, '2025-07-24 11:17:39', '2025-07-24 11:17:39'),
(133, 'Deputy Minister, Gender & Social Protection', 'Lariba Zuweira Abudu', '2025-01-25', NULL, '2025-07-24 11:17:39', '2025-07-24 11:17:39'),
(134, 'Coordinator, National Identification Authority', 'Prof. Kenneth Agyemang Attafuah', '2025-01-25', NULL, '2025-07-24 11:17:39', '2025-07-24 11:17:39'),
(135, 'Coordinator, National Service Scheme', 'Osei Assibey Antwi', '2025-01-25', NULL, '2025-07-24 11:17:39', '2025-07-24 11:17:39'),
(136, 'Special Advisor on Digital Transformation', 'Kwame Oppong', '2025-01-25', NULL, '2025-07-24 11:17:39', '2025-07-24 11:17:39'),
(137, 'Special Advisor to the President on Security', 'Yaw Donkor', '2025-01-25', NULL, '2025-07-24 11:17:39', '2025-07-24 11:17:39'),
(138, 'Special Advisor to the President on Economic Affairs', 'Dr. Nii Moi Thompson', '2025-01-25', NULL, '2025-07-24 11:17:39', '2025-07-24 11:17:39'),
(139, 'National Coordinator, School Feeding Programme', 'Gertrude Quashigah', '2025-01-25', NULL, '2025-07-24 11:17:39', '2025-07-24 11:17:39'),
(140, 'Executive Secretary, Free SHS Secretariat', 'William Darkwah', '2025-01-25', NULL, '2025-07-24 11:17:39', '2025-07-24 11:17:39'),
(141, 'Speaker of Parliament', 'Alban Sumana Kingsford Bagbin', '2025-01-07', NULL, '2025-07-24 11:17:39', '2025-07-24 11:17:39'),
(142, 'Majority Leader, Parliament', 'Osei Kyei Mensah Bonsu', '2025-01-07', NULL, '2025-07-24 11:17:39', '2025-07-24 11:17:39'),
(143, 'Minority Leader, Parliament', 'Dr. Cassiel Ato Forson', '2025-01-07', NULL, '2025-07-24 11:17:39', '2025-07-24 11:17:39'),
(144, 'Clerk of Parliament', 'Cyril Kwabena Oteng Nsiah', '2025-01-07', NULL, '2025-07-24 11:17:39', '2025-07-24 11:17:39'),
(145, 'Chief of Staff', 'Julius Debrah', '2025-01-07', NULL, '2025-07-24 11:17:39', '2025-07-24 11:17:39'),
(146, 'Cabinet Secretary', 'Mercy Debrah-Karikari', '2025-01-07', NULL, '2025-07-24 11:17:39', '2025-07-24 11:17:39'),
(147, 'Director of Communications, Presidency', 'Stan Dogbe', '2025-01-07', NULL, '2025-07-24 11:17:39', '2025-07-24 11:17:39'),
(148, 'Secretary to the President', 'Nii Quaye Kumah', '2025-01-07', NULL, '2025-07-24 11:17:39', '2025-07-24 11:17:39'),
(149, 'CEO, Ghana Investment Promotion Centre', 'Yoofi Grant', '2025-01-25', NULL, '2025-07-24 11:17:39', '2025-07-24 11:17:39'),
(150, 'Executive Secretary, National Peace Council', 'George Amoh', '2025-01-25', NULL, '2025-07-24 11:17:39', '2025-07-24 11:17:39'),
(151, 'Commissioner General, GRA', 'Ammishaddai Owusu-Amoah', '2025-01-25', NULL, '2025-07-24 11:17:39', '2025-07-24 11:17:39'),
(152, 'CEO, COCOBOD', 'Joseph Boahen Aidoo', '2025-01-25', NULL, '2025-07-24 11:17:39', '2025-07-24 11:17:39'),
(153, 'CEO, Ghana Health Service', 'Dr. Patrick Kuma-Aboagye', '2025-01-25', NULL, '2025-07-24 11:17:39', '2025-07-24 11:17:39'),
(154, 'Governor, Bank of Ghana', 'Dr. Ernest Addison', '2025-01-25', NULL, '2025-07-24 11:17:39', '2025-07-24 11:17:39'),
(155, 'Chief Justice', 'Justice Gertrude Torkornoo', '2025-01-25', NULL, '2025-07-24 11:18:08', '2025-07-24 11:18:08'),
(156, 'Attorney General’s Secretary', 'Beatrice Annan', '2025-01-25', NULL, '2025-07-24 11:18:08', '2025-07-24 11:18:08'),
(157, 'Director, Judicial Service', 'Alex Nartey', '2025-01-25', NULL, '2025-07-24 11:18:08', '2025-07-24 11:18:08'),
(158, 'Inspector General of Police', 'COP George Akuffo Dampare', '2025-01-25', NULL, '2025-07-24 11:18:08', '2025-07-24 11:18:08'),
(159, 'National Security Coordinator', 'Edward Asomani', '2025-01-25', NULL, '2025-07-24 11:18:08', '2025-07-24 11:18:08'),
(160, 'Chief of Defence Staff', 'Vice Admiral Seth Amoama', '2025-01-25', NULL, '2025-07-24 11:18:08', '2025-07-24 11:18:08'),
(161, 'Director General, Prisons Service', 'Isaac Kofi Egyir', '2025-01-25', NULL, '2025-07-24 11:18:08', '2025-07-24 11:18:08'),
(162, 'Director General, Immigration Service', 'Kwame Asuah Takyi', '2025-01-25', NULL, '2025-07-24 11:18:08', '2025-07-24 11:18:08'),
(163, 'National Fire Service Chief', 'Julius Aalebkure Kuunuor', '2025-01-25', NULL, '2025-07-24 11:18:08', '2025-07-24 11:18:08'),
(164, 'CEO, Ghana Water Company Ltd', 'Dr. Clifford Braimah', '2025-01-25', NULL, '2025-07-24 11:18:08', '2025-07-24 11:18:08'),
(165, 'CEO, Volta River Authority', 'Emmanuel Antwi-Darkwa', '2025-01-25', NULL, '2025-07-24 11:18:08', '2025-07-24 11:18:08'),
(166, 'CEO, Electricity Company of Ghana', 'Samuel Dubik Mahama', '2025-01-25', NULL, '2025-07-24 11:18:08', '2025-07-24 11:18:08'),
(167, 'CEO, Ghana National Petroleum Corporation', 'Opoku-Ahweneeh Danquah', '2025-01-25', NULL, '2025-07-24 11:18:08', '2025-07-24 11:18:08'),
(168, 'Managing Director, Ghana Ports & Harbours Authority', 'Michael Luguje', '2025-01-25', NULL, '2025-07-24 11:18:08', '2025-07-24 11:18:08'),
(169, 'Presidential Advisor on Legal Affairs', 'Nii Armah Ashitey', '2025-01-25', NULL, '2025-07-24 11:18:08', '2025-07-24 11:18:08'),
(170, 'Presidential Advisor on National Security', 'Francis Poku', '2025-01-25', NULL, '2025-07-24 11:18:08', '2025-07-24 11:18:08'),
(171, 'Director, Diaspora Affairs Office', 'Kwame Agyei', '2025-01-25', NULL, '2025-07-24 11:18:08', '2025-07-24 11:18:08'),
(172, 'Coordinator, Zongo Development Fund', 'Abdul-Aziz Mohammed', '2025-01-25', NULL, '2025-07-24 11:18:08', '2025-07-24 11:18:08'),
(173, 'CEO, Youth Employment Agency', 'Kofi Baah Agyepong', '2025-01-25', NULL, '2025-07-24 11:18:08', '2025-07-24 11:18:08');

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Stores FAQ questions and answers';

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`id`, `category_id`, `question`, `answer`, `created_at`, `updated_at`) VALUES
(1, 1, 'Document not printing', '1. Ensure the printer has paper and no paper jam\r\n2. Clear any stuck print jobs\r\n3. Confirm credentials are correct\r\n4. Restart the printer', '2025-07-24 21:14:54', '2025-07-25 09:53:08'),
(2, 1, 'PC not booting', '1. Plug PC into a working power outlet\n2. Check for power light indicator at the back\n3. Hold the power button for 5–10 seconds\n4. Contact IT Support', '2025-07-24 21:14:54', '2025-07-24 21:14:54'),
(3, 1, 'Internet or browser not working', '1. Try accessing www.mofep.gov.gh in another browser\n2. Check network icon or Wi-Fi status\n3. Confirm Ethernet light is on, if wired\n4. Reinsert the Ethernet cable\n5. Contact IT Support', '2025-07-24 21:14:54', '2025-07-24 21:14:54'),
(4, 1, 'Outlook not receiving emails', '1. Confirm internet is active\n2. Check spam/junk folder and mailbox storage\n3. Restart Outlook\n4. Contact IT Support', '2025-07-24 21:14:54', '2025-07-24 21:14:54'),
(5, 1, 'Unable to sign in to Microsoft Office', '1. Ensure internet is working\n2. Confirm login credentials\n3. Restart your PC\n4. Contact IT Support', '2025-07-24 21:14:54', '2025-07-24 21:14:54'),
(6, 1, 'PC not on domain', 'Contact IT Support via the official support form to resolve domain issues', '2025-07-24 21:14:54', '2025-07-24 21:14:54'),
(7, 1, 'Slow PC performance', '1. Delete unnecessary or large files to free up space\n2. Close unused background apps\n3. Restart the PC\n4. Contact IT Support if unresolved', '2025-07-24 21:14:54', '2025-07-24 21:14:54'),
(8, 1, 'Slow internet connection', '1. Restart the router or your device\n2. Contact IT Support for diagnostics', '2025-07-24 21:14:54', '2025-07-24 21:14:54'),
(9, 1, 'Wi-Fi not connecting', '1. Confirm Wi-Fi password is correct\n2. Ensure device is discoverable and Wi-Fi is enabled\n3. Contact IT Support', '2025-07-24 21:14:54', '2025-07-24 21:14:54'),
(10, 1, 'Faulty Ethernet cable', 'Contact IT Support for inspection or replacement', '2025-07-24 21:14:54', '2025-07-24 21:14:54'),
(11, 1, 'Password reset request', 'Contact IT Support via the official support form to reset your password securely', '2025-07-24 21:14:54', '2025-07-24 21:14:54'),
(12, 1, 'Monitor not displaying output', '1. Check power and display cables\n2. Confirm monitor is powered on and correct input is selected\n3. Try a different cable or monitor', '2025-07-24 21:14:54', '2025-07-24 21:14:54'),
(13, 1, 'Keyboard or mouse not working', '1. Reconnect the device or change batteries\n2. Try different USB ports or another PC\n3. Contact IT Support if unresponsive', '2025-07-24 21:14:54', '2025-07-24 21:14:54'),
(14, 1, 'Unable to access shared drive or folder', '1. Ensure you are connected to the network\n2. Restart your PC\n3. Contact IT Support for access permissions', '2025-07-24 21:14:54', '2025-07-24 21:14:54'),
(15, 1, 'Application crashing or not opening', '1. Restart the application or PC\n2. Ensure the software is updated\n3. Contact IT Support', '2025-07-24 21:14:54', '2025-07-24 21:14:54'),
(16, 1, 'Printer shows \'Offline\' status', '1. Make sure the printer is powered on and network-connected\n2. Set printer to \'Use Printer Online\' in settings\n3. Restart PC and printer', '2025-07-24 21:14:54', '2025-07-24 21:14:54'),
(17, 1, 'Audio not working', '1. Check volume settings and mute status\n2. Confirm correct playback device is selected\n3. Reconnect audio devices or restart PC', '2025-07-24 21:14:54', '2025-07-24 21:14:54'),
(18, 1, 'Screen freezing or system hanging', '1. Wait or press Ctrl + Alt + Delete to access Task Manager\n2. End unresponsive tasks or restart the PC\n3. Contact IT Support for persistent issues', '2025-07-24 21:14:54', '2025-07-24 21:14:54'),
(19, 1, 'Unable to access intranet or internal portals', '1. Confirm connection to the office network\n2. Clear browser cache or try a different browser\n3. Contact IT Support', '2025-07-24 21:14:54', '2025-07-24 21:14:54'),
(20, 1, 'VPN connection failing', '1. Ensure internet is active before connecting\n2. Confirm VPN credentials\n3. Restart VPN app or your device', '2025-07-24 21:14:54', '2025-07-24 21:14:54'),
(21, 1, 'File not saving or syncing to OneDrive', '1. Confirm you\'re signed into OneDrive with internet access\n2. Check file path and name for unsupported characters\n3. Restart OneDrive', '2025-07-24 21:14:54', '2025-07-24 21:14:54'),
(22, 1, 'Microsoft Teams not working', '1. Restart Teams or use the web version\n2. Clear Teams cache from app settings\n3. Confirm you’re logged into the correct account', '2025-07-24 21:14:54', '2025-07-24 21:14:54'),
(23, 1, 'Software installation request', 'Submit a request through the official IT support form for approval and installation', '2025-07-24 21:14:54', '2025-07-24 21:14:54'),
(24, 1, 'Security alert or suspicious email', '1. Do not click on any suspicious links or attachments\n2. Report the email to IT Security via support form\n3. Delete the email after reporting', '2025-07-24 21:14:54', '2025-07-24 21:14:54'),
(25, 1, 'Device auto-restarting or shutting down', '1. Check for overheating or blocked vents\n2. Ensure all power cables are secure\n3. Contact IT Support for hardware inspection', '2025-07-24 21:14:54', '2025-07-24 21:14:54'),
(26, 1, 'My Crome is not Working', '1. Try another browser alternative\r\n2. Scan the support form to contact MoF Helpdesk', '2025-07-25 09:54:21', '2025-07-25 09:54:21');

-- --------------------------------------------------------

--
-- Table structure for table `faq_categories`
--

CREATE TABLE `faq_categories` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Stores FAQ categories';

--
-- Dumping data for table `faq_categories`
--

INSERT INTO `faq_categories` (`id`, `title`, `created_at`, `updated_at`) VALUES
(1, 'General Technical Issues', '2025-07-24 21:14:54', '2025-07-24 21:14:54');

-- --------------------------------------------------------

--
-- Table structure for table `resources`
--

CREATE TABLE `resources` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `type` enum('Forms','Reports','Manuals') NOT NULL,
  `description` text NOT NULL,
  `file_format` enum('PDF','DOCX') NOT NULL,
  `file_size` decimal(5,1) NOT NULL COMMENT 'File size in MB',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `file_path` varchar(255) DEFAULT NULL COMMENT 'Path to the resource file'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `resources`
--

INSERT INTO `resources` (`id`, `title`, `type`, `description`, `file_format`, `file_size`, `created_at`, `updated_at`, `file_path`) VALUES
(10, 'IT & Email Usage Policy (Staff Reference Sheet)', 'Manuals', 'This document outlines the general guidelines for responsible use of IT resources and official email within the Ministry of Finance. It serves as a quick reference for staff to maintain security, professionalism, and compliance in daily digital operations.', 'PDF', 0.1, '2025-07-25 10:18:02', '2025-07-25 10:18:02', 'files/_____Ministry_of_Finance_____IT___Email_Usage_Policy__Staff_Reference_Sheet_.pdf'),
(13, 'Cybersecurity Awareness Tips', 'Manuals', 'This document provides essential tips for protecting Ministry of Finance systems and data from cyber threats. It serves as a quick reference to promote safe digital practices among all staff.', 'PDF', 0.1, '2025-07-25 11:06:12', '2025-07-25 11:06:12', 'files/Cybersecurity_Awareness_Tips.pdf'),
(14, 'Ministry of Finance (MoF) SmartDesk Documentation', 'Manuals', 'The Ministry of Finance (MoF) SmartDesk is an AI-powered digital assistant developed by the ICT Division to provide instant, accurate, and efficient support to staff and stakeholders. It centralizes information through AI chatbots, FAQs, visual step-by-step guides, and a comprehensive resources hub, enabling 24/7 self-service, reducing response times, and enhancing operational efficiency within the Ministry.', 'PDF', 0.2, '2025-07-25 13:00:57', '2025-07-25 13:00:57', 'files/Ministry_of_Finance__MoF__SmartDesk_Documentation.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(255) DEFAULT NULL,
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

INSERT INTO `users` (`id`, `fullname`, `email`, `password_hash`, `mof_staff_id`, `role`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'Divine Osei', 'oseidivine35@gmail.com', '$2y$10$MYEc1BJAUs.by32U8Zu8OeRz99xWeD2GIUA9QVWdTyJuerN4ifRdK', '111222', 'staff', '2025-07-24 16:25:21', '2025-07-04 19:01:01', '2025-07-24 16:25:21'),
(1010, 'Guess', 'guest@mof.gov.gh', '', 'guest', 'guest', NULL, '2025-07-09 13:08:05', '2025-07-24 16:01:10'),
(1014, 'Test Account', 'sdf.sf@mofep.gov.gh', '$2y$10$f1.2mwXMw94TQr1UC3W95eavPpKIUo8pdzywTBhEFcCMGwxG8aZoW', '111223', 'staff', NULL, '2025-07-09 13:18:54', '2025-07-24 16:01:29'),
(1015, 'Test Account', 'account@gmail.com', '$2y$10$uU.VtekoSztylkdhysE9LueQz.nTsbGmgNQyAJyt3BOM1wdpv7Gp.', '111225', 'staff', NULL, '2025-07-09 13:19:38', '2025-07-24 16:01:24');

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
-- Indexes for table `api_keys`
--
ALTER TABLE `api_keys`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `current_affairs`
--
ALTER TABLE `current_affairs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `faq_categories`
--
ALTER TABLE `faq_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `resources`
--
ALTER TABLE `resources`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `api_keys`
--
ALTER TABLE `api_keys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `chat_history`
--
ALTER TABLE `chat_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=238;

--
-- AUTO_INCREMENT for table `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;

--
-- AUTO_INCREMENT for table `current_affairs`
--
ALTER TABLE `current_affairs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=175;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `faq_categories`
--
ALTER TABLE `faq_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `resources`
--
ALTER TABLE `resources`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

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

--
-- Constraints for table `faqs`
--
ALTER TABLE `faqs`
  ADD CONSTRAINT `faqs_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `faq_categories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
