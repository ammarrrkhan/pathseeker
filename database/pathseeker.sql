-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 13, 2025 at 01:45 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pathseeker`
--

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `job` varchar(100) DEFAULT NULL,
  `resume` varchar(255) DEFAULT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`id`, `first_name`, `last_name`, `email`, `phone`, `job`, `resume`, `submitted_at`) VALUES
(1, 'Wania', 'Ahad', 'wania@gmail.com', '1234567890', 'Software Developer', 'uploads/resume_68c42c2d810251.56886827.docx', '2025-09-12 14:20:29');

-- --------------------------------------------------------

--
-- Table structure for table `feedbacks`
--

CREATE TABLE `feedbacks` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `feedbacks`
--

INSERT INTO `feedbacks` (`id`, `name`, `email`, `message`, `created_at`) VALUES
(1, 'wania', 'wania@gmail.com', 'good website', '2025-09-12 09:38:07'),
(2, 'wania', 'wania@gmail.com', 'good website', '2025-09-12 09:39:51');

-- --------------------------------------------------------

--
-- Table structure for table `quiz_answers`
--

CREATE TABLE `quiz_answers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `answer_text` varchar(255) NOT NULL,
  `is_correct` tinyint(1) DEFAULT 0,
  `weightage` int(11) DEFAULT 0,
  `answered_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `quiz_answers`
--

INSERT INTO `quiz_answers` (`id`, `user_id`, `question_id`, `answer_text`, `is_correct`, `weightage`, `answered_at`) VALUES
(41, 1, 1, 'Analyzing data and solving complex problems', 0, 0, '2025-09-13 10:21:32'),
(42, 1, 2, 'Flexible, creative space with autonomy', 0, 0, '2025-09-13 10:21:32'),
(43, 1, 3, 'Following established procedures and protocols', 0, 0, '2025-09-13 10:21:32'),
(44, 1, 4, 'Logical analysis, technical proficiency, and critical thinking', 0, 0, '2025-09-13 10:21:32'),
(45, 1, 5, 'Business, economics, and organizational management', 0, 0, '2025-09-13 10:21:32'),
(46, 1, 6, 'Explore unconventional approaches and creative solutions', 0, 0, '2025-09-13 10:21:32'),
(47, 1, 7, 'Solving complex problems and intellectual challenges', 0, 0, '2025-09-13 10:21:32'),
(48, 1, 8, 'Creative projects with freedom to explore and experiment', 0, 0, '2025-09-13 10:21:32');

-- --------------------------------------------------------

--
-- Table structure for table `quiz_questions`
--

CREATE TABLE `quiz_questions` (
  `id` int(11) NOT NULL,
  `question_text` text NOT NULL,
  `option1` varchar(255) NOT NULL,
  `option2` varchar(255) NOT NULL,
  `option3` varchar(255) NOT NULL,
  `option4` varchar(255) NOT NULL,
  `correct_answer` varchar(255) NOT NULL,
  `weightage` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `quiz_questions`
--

INSERT INTO `quiz_questions` (`id`, `question_text`, `option1`, `option2`, `option3`, `option4`, `correct_answer`, `weightage`) VALUES
(1, 'Which type of work activity energizes you the most?', 'Collaborating with teams and building relationships', 'Analyzing data and solving complex problems', 'Creating artistic or innovative content', 'Organizing systems and managing projects', 'Collaborating with teams and building relationships', 5),
(2, 'What work environment would help you thrive?', 'Fast-paced, dynamic setting with variety', 'Structured, predictable environment with clear expectations', 'Flexible, creative space with autonomy', 'Quiet, focused atmosphere with minimal interruptions', 'Fast-paced, dynamic setting with variety', 5),
(3, 'What is your preferred approach to solving challenges?', 'Brainstorming solutions with colleagues', 'Researching and analyzing information systematically', 'Using intuition and creative thinking', 'Following established procedures and protocols', 'Brainstorming solutions with colleagues', 5),
(4, 'Which of these skill sets feels most natural to you?', 'Communication, persuasion, and relationship-building', 'Logical analysis, technical proficiency, and critical thinking', 'Imagination, design sensibility, and artistic expression', 'Planning, coordination, and organizational management', 'Communication, persuasion, and relationship-building', 5),
(5, 'Which subject area captures your interest most consistently?', 'Social sciences, psychology, and human behavior', 'Technology, engineering, and mathematics', 'Arts, design, and creative expression', 'Business, economics, and organizational management', 'Social sciences, psychology, and human behavior', 5),
(6, 'How do you typically approach difficult challenges?', 'Seek collaborative support and diverse perspectives', 'Break them down into logical components for systematic solving', 'Explore unconventional approaches and creative solutions', 'Develop structured plans with clear milestones', 'Seek collaborative support and diverse perspectives', 5),
(7, 'What provides you with the strongest sense of motivation at work?', 'Helping others and making positive social impact', 'Solving complex problems and intellectual challenges', 'Expressing creativity and innovating new approaches', 'Achieving measurable goals and receiving recognition', 'Helping others and making positive social impact', 5),
(8, 'Which workday structure sounds most appealing to you?', 'Varied tasks with frequent social interaction and collaboration', 'Focused work on technical challenges with deep concentration', 'Creative projects with freedom to explore and experiment', 'Structured tasks with clear objectives and organized workflow', 'Varied tasks with frequent social interaction and collaboration', 5);

-- --------------------------------------------------------

--
-- Table structure for table `quiz_results`
--

CREATE TABLE `quiz_results` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  `completed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `quiz_results`
--

INSERT INTO `quiz_results` (`id`, `user_id`, `score`, `completed_at`) VALUES
(6, 1, 0, '2025-09-13 10:21:32');

-- --------------------------------------------------------

--
-- Table structure for table `resources`
--

CREATE TABLE `resources` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `type` varchar(50) NOT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `content_url` varchar(255) NOT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `published_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `resources`
--

INSERT INTO `resources` (`id`, `title`, `description`, `type`, `tags`, `image_url`, `content_url`, `duration`, `published_at`) VALUES
(1, 'How to Plan Your Career Path', 'Learn strategies to plan your career', 'video', 'Career Planning', 'images\\ui-ux.png', 'https://youtu.be/R3GfuzLMPkA?si=Rap7GJl3B7Dgqqow', '5 min', '2023-06-01'),
(2, 'Plan Your Career Path', 'Learn how to map out your career journey', 'video', 'career, planning', 'images\\education specialist.png', 'https://youtu.be/zhpcgpqWc1Q?si=SIykw3VL-hWlMvyy', '4 min', '2025-02-10'),
(3, 'Career Tips ', 'Read this article to learn the best career growth strategies', 'article', 'career, tips, article', 'images\\article.png', 'https://hbr.org/2021/06/career-advice-from-wildly-successful-people', NULL, '2025-09-11'),
(4, 'Top 5 Career Tips', 'Read this article to learn the best career growth strategies', 'article', 'career, tips, article', 'images\\article2.png', 'https://novoresume.com/career-blog/career-tips', NULL, '2025-09-11'),
(5, 'Discover opportunities. Build your path.', 'Your future starts with the right job.', 'tool', 'career, tips, tool', 'images\\indeed.png', 'https://pk.indeed.com/', NULL, '2025-09-11'),
(6, 'Jobs made simple. Careers made possible.', 'Find the career that finds you.', 'tool', 'career, tips, tool', 'images\\linkdin.png', 'https://pk.linkedin.com/', NULL, '2025-09-11'),
(7, 'From Classroom to Career Success.', 'Empowering Skills, Shaping Futures.', 'course', 'Career Planning', 'images\\aptech.png', 'https://aptech-education.com.pk/', NULL, '2023-06-01');

-- --------------------------------------------------------

--
-- Table structure for table `successstories`
--

CREATE TABLE `successstories` (
  `story_id` int(11) NOT NULL,
  `manne` varchar(255) NOT NULL,
  `domain` varchar(100) NOT NULL,
  `story_text` text NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `avatar_url` varchar(255) DEFAULT NULL,
  `submitted_by` int(11) DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `story_title` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `successstories`
--

INSERT INTO `successstories` (`story_id`, `manne`, `domain`, `story_text`, `image_url`, `avatar_url`, `submitted_by`, `approved_by`, `approved_at`, `created_at`, `story_title`, `position`) VALUES
(1, 'wania', 'tech', 'My athletic career ended abruptly with a knee injury during my third season as a professional basketball player. Suddenly, I found myself without a career path and unsure about what to do next.\r\n\r\nThrough PathSeeker, I discovered that my experience with sports injuries and rehabilitation could translate into a career in physical therapy. The platform helped me identify which prerequisites I needed and which PT programs would be the best fit.\r\n\r\nThe transition wasn\'t easy—returning to school after years away was challenging. But PathSeeker\'s resources on adult learning and time management helped me stay organized and focused. I also connected with other career changers through the platform\'s community features.', 'uploads/story_images/story_1757449171_68c08bd365d34.jpeg', NULL, 2, NULL, NULL, '2025-09-09 20:19:31', 'my career transformation', 'developer'),
(2, 'wania', 'tech', 'My athletic career ended abruptly with a knee injury during my third season as a professional basketball player. Suddenly, I found myself without a career path and unsure about what to do next.\r\n\r\nThrough PathSeeker, I discovered that my experience with sports injuries and rehabilitation could translate into a career in physical therapy. The platform helped me identify which prerequisites I needed and which PT programs would be the best fit.\r\n\r\nThe transition wasn\'t easy—returning to school after years away was challenging. But PathSeeker\'s resources on adult learning and time management helped me stay organized and focused. I also connected with other career changers through the platform\'s community features.', 'uploads/story_images/story_1757449857_68c08e81e7411.png', NULL, 2, NULL, NULL, '2025-09-09 20:30:57', 'my career transformation', 'developer'),
(3, 'wania', 'tech', 'Cybersecurity\r\nAfter years in law enforcement, I wanted to apply my investigative skills in a new field. PathSeeker\'s career matching helped me discover cybersecurity...', 'uploads/story_images/story_1757449894_68c08ea624d94.png', NULL, 2, NULL, NULL, '2025-09-09 20:31:34', 'my career transformation', 'techcrop'),
(4, 'wania', 'tech', 'Cybersecurity\r\nAfter years in law enforcement, I wanted to apply my investigative skills in a new field. PathSeeker\'s career matching helped me discover cybersecurity...', 'uploads/story_images/story_1757449909_68c08eb565b6c.png', NULL, 2, NULL, NULL, '2025-09-09 20:31:49', 'my career transformation', 'techcrop'),
(5, 'wania', 'tech', 'Cybersecurity\r\nAfter years in law enforcement, I wanted to apply my investigative skills in a new field. PathSeeker\'s career matching helped me discover cybersecurity...', 'uploads/story_images/story_1757450126_68c08f8ea8687.png', NULL, 2, NULL, NULL, '2025-09-09 20:35:26', 'my career transformation', 'techcrop'),
(6, 'john doe', 'education', 'After 10 years in the classroom, I wanted a change. PathSeeker helped me discover educational technology, where I now develop tools for teachers', '', NULL, 2, NULL, NULL, '2025-09-09 20:37:01', 'my career transformation', 'teacher');

-- --------------------------------------------------------

--
-- Table structure for table `userprofiles`
--

CREATE TABLE `userprofiles` (
  `profile_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `education_level` varchar(255) DEFAULT NULL,
  `interests` text DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `userprofiles`
--

INSERT INTO `userprofiles` (`profile_id`, `user_id`, `education_level`, `interests`, `profile_image`, `updated_at`) VALUES
(1, 1, '', '', NULL, '2025-09-09 19:51:44'),
(2, 2, '', '', NULL, '2025-09-09 20:01:27'),
(3, 3, '', '', NULL, '2025-09-10 17:51:48'),
(4, 4, 'Some College', 'Software Engineering', NULL, '2025-09-10 18:14:36'),
(6, 6, 'High School', '', NULL, '2025-09-13 11:08:26'),
(7, 7, 'Doctorate', '', NULL, '2025-09-11 18:22:18'),
(8, 8, '', '', NULL, '2025-09-13 11:24:28');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `uname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('student','graduate','professional') NOT NULL,
  `email_token` varchar(100) DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `uname`, `email`, `password_hash`, `role`, `email_token`, `is_verified`, `created_at`, `updated_at`) VALUES
(1, 'Wania', 'wania@gmail.com', '$2y$10$v4wFc6clJ2zBmAR3qJ3Q/eXaAdGALgASa1/oAHikjFSu735rj.IHG', 'graduate', NULL, 0, '2025-09-09 19:51:44', '2025-09-10 08:01:39'),
(2, 'wania ahad', 'wania.ahad20@gmail.com', '$2y$10$BZiL.mXLeiDDOeQrfNkqZeh5MlPVClaNimo.0hL3tI9G3iZxOQUfG', 'graduate', NULL, 0, '2025-09-09 20:01:27', '2025-09-10 08:01:39'),
(3, 'ALI', 'alikhan@gmail.com', '$2y$10$pbig.gKHKNfcpPmq2HG.l.5Gg.36dLdHVcjLrqZv0fBy8BaQ1AWiu', 'student', NULL, 0, '2025-09-10 17:51:48', '2025-09-10 17:51:48'),
(4, 'Malik Khan', 'malik@gmail.com', '$2y$10$OjzlJ57KoE.DTWYaukYaMu0hqaqMhuoMMDv3hyB92NhqtaTWViF9G', 'graduate', NULL, 0, '2025-09-10 18:07:07', '2025-09-10 18:07:07'),
(6, 'emily', 'emily@gmail.com', '$2y$10$KF3UG2hrMyXmuBuMoYw1AeOh82iSYwcB1M7kWWnFBZE7Bab/er4..', 'professional', NULL, 0, '2025-09-11 16:22:36', '2025-09-11 16:22:36'),
(7, 'Ali', 'ali@gmail.com', '$2y$10$EnEH1WKx.I6j8cUW0/LP8.ZqgM/9eRk//eDfj.Wy3ZrocEvgOEPdS', 'professional', NULL, 0, '2025-09-11 18:21:45', '2025-09-11 18:21:45'),
(8, 'Ammar Ahmed Khan', 'ammarrrahmed07@gmail.com', '$2y$10$iSA0QD7yfcA8d2wbDCW9V.owd7IOU17N8QKcBddmTSEEKAm2GW89W', 'student', '145af545aaea029996dd7ac9b4d0f799299e2f799eaeec4d7f74ef281d692431', 0, '2025-09-13 11:24:28', '2025-09-13 11:24:28');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quiz_answers`
--
ALTER TABLE `quiz_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quiz_results`
--
ALTER TABLE `quiz_results`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `resources`
--
ALTER TABLE `resources`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `successstories`
--
ALTER TABLE `successstories`
  ADD PRIMARY KEY (`story_id`),
  ADD KEY `submitted_by` (`submitted_by`),
  ADD KEY `approved_by` (`approved_by`);

--
-- Indexes for table `userprofiles`
--
ALTER TABLE `userprofiles`
  ADD PRIMARY KEY (`profile_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `quiz_answers`
--
ALTER TABLE `quiz_answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `quiz_results`
--
ALTER TABLE `quiz_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `resources`
--
ALTER TABLE `resources`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `successstories`
--
ALTER TABLE `successstories`
  MODIFY `story_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `userprofiles`
--
ALTER TABLE `userprofiles`
  MODIFY `profile_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `quiz_answers`
--
ALTER TABLE `quiz_answers`
  ADD CONSTRAINT `quiz_answers_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `quiz_questions` (`id`);

--
-- Constraints for table `successstories`
--
ALTER TABLE `successstories`
  ADD CONSTRAINT `successstories_ibfk_1` FOREIGN KEY (`submitted_by`) REFERENCES `users` (`user_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `successstories_ibfk_2` FOREIGN KEY (`approved_by`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `userprofiles`
--
ALTER TABLE `userprofiles`
  ADD CONSTRAINT `userprofiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
