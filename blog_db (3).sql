-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 25, 2025 at 11:37 AM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blog_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

DROP TABLE IF EXISTS `blogs`;
CREATE TABLE IF NOT EXISTS `blogs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `author` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `blogs`
--

INSERT INTO `blogs` (`id`, `title`, `content`, `author`, `created_at`, `updated_at`) VALUES
(1, 'Welcome to My Blog', 'This is the first blog post. Students can add, edit, and delete blogs.', 'Admin', '2025-10-19 17:44:44', '2025-10-19 17:44:44'),
(2, 'About  LPU', 'Exploring Lovely Professional University (LPU): A Hub of Innovation and Excellence\r\n\r\nLovely Professional University (LPU), located in Punjab, India, is one of the largest and most renowned private universities in the country. Established in 2005 by Dr. Ashok Mittal, LPU has grown into a sprawling campus offering diverse courses, state-of-the-art facilities, and a unique student-centric environment.\r\n\r\nAcademic Excellence\r\n\r\nLPU offers over 200 undergraduate, postgraduate, and doctoral programs across various fields, including engineering, management, arts, sciences, law, and agriculture. The university emphasizes a practical learning approach through workshops, internships, industry visits, and live projects. With highly qualified faculty and global collaborations, LPU ensures that students are prepared for both national and international career opportunities.\r\n\r\nCampus and Infrastructure\r\n\r\nSpread across 600+ acres, LPU’s campus is a blend of modern architecture and eco-friendly spaces. From high-tech classrooms, smart labs, and libraries with millions of books to sports complexes, auditoriums, and hostels, the campus is designed to provide a holistic experience. Students have access to modern technology and resources that enhance learning and creativity.\r\n\r\nStudent Life and Culture\r\n\r\nAt LPU, student life extends beyond academics. The university hosts numerous cultural, technical, and sports festivals, including LUCK (LPU’s Cultural Festival) and LPUNEST (Entrance Test for Competitive Exams), encouraging students to explore talents and build networks. Clubs, societies, and events foster leadership, teamwork, and creativity, ensuring a vibrant campus atmosphere.\r\n\r\nGlobal Exposure\r\n\r\nLPU has collaborations with over 200 universities worldwide, enabling students to participate in exchange programs, international internships, and joint research projects. These global opportunities broaden perspectives and prepare students for a competitive global environment.\r\n\r\nInnovation and Research\r\n\r\nLPU promotes innovation through its Research & Development initiatives, incubation centers, and startup support. Students and faculty work on cutting-edge projects in AI, robotics, biotechnology, renewable energy, and more. The university encourages entrepreneurship and supports students in launching their ventures.\r\n\r\nScholarships and Financial Support\r\n\r\nTo make education accessible, LPU provides numerous merit-based and need-based scholarships. These scholarships reward academic excellence, sports achievements, and cultural contributions, ensuring that talented students from all backgrounds can pursue their dreams.\r\n\r\nConclusion\r\n\r\nLovely Professional University stands as a beacon of modern education in India, blending academic excellence, innovation, global exposure, and vibrant student life. With its commitment to nurturing talent, fostering research, and preparing students for the real world, LPU is not just a university—it’s a gateway to opportunities, growth, and success.\r\n\r\nGreat Work\r\nhiii', 'Devansh Sahi ', '2025-10-19 17:58:32', '2025-10-22 18:27:27');

-- --------------------------------------------------------

--
-- Table structure for table `blog_images`
--

DROP TABLE IF EXISTS `blog_images`;
CREATE TABLE IF NOT EXISTS `blog_images` (
  `id` int NOT NULL AUTO_INCREMENT,
  `blog_id` int NOT NULL,
  `image_path` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `blog_id` (`blog_id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `blog_images`
--

INSERT INTO `blog_images` (`id`, `blog_id`, `image_path`) VALUES
(1, 1, 'uploads/lpu.jpeg'),
(2, 1, 'uploads/lpu.jpeg'),
(3, 2, 'uploads/lpu2.jpg'),
(5, 2, 'uploads/lpu.jpeg'),
(6, 1, 'uploads/lpu.jpeg'),
(7, 1, 'uploads/lpu.jpeg'),
(8, 2, 'uploads/lpu3.jpg'),
(10, 2, 'uploads/lpu.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','student') NOT NULL DEFAULT 'student',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'admin', '123', 'admin');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
