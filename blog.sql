-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 01, 2024 at 11:32 AM
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
-- Database: `blog`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `title`, `description`) VALUES
(1, 'Food', 'This is food category'),
(2, 'Uncategorized', 'This is uncategorised section'),
(7, 'Gaming', 'this is gaming category'),
(8, 'Movies', 'Movies category'),
(9, 'Science &amp; Technology', 'This is Science &amp; Technology category'),
(10, 'Travel', 'this is travel category'),
(12, 'Music ', 'This is music category');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `author_name` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `date_time` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `post_id`, `author_name`, `body`, `date_time`) VALUES
(4, 30, 'sugam', 'hello', '2024-06-25 17:47:02'),
(5, 30, 'sugam', 'hikkk', '2024-06-25 17:47:47'),
(6, 29, 'sugam', 'aaa', '2024-06-25 18:11:14'),
(7, 29, 'sugam', 'hello', '2024-06-26 08:30:24'),
(8, 26, 'sugam', 'hello', '2024-06-26 08:31:59'),
(9, 33, 'benup', 'very nice article', '2024-06-26 10:18:27'),
(10, 34, 'shitoshma', 'hello', '2024-06-30 17:22:48'),
(11, 34, 'sugam', 'jjj', '2024-07-01 11:05:27'),
(12, 40, 'sugam', 'hello', '2024-07-01 12:29:29');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `thumbnail` varchar(255) NOT NULL,
  `date_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `category_id` int(11) UNSIGNED DEFAULT NULL,
  `author_id` int(11) UNSIGNED NOT NULL,
  `is_featured` tinyint(1) NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `body`, `thumbnail`, `date_time`, `category_id`, `author_id`, `is_featured`, `status`) VALUES
(34, 'Valorant', 'Valorant is a popular tactical first-person shooter (FPS) game developed and published by Riot Games. It was officially released on June 2, 2020, and has gained significant attention in the gaming community, especially among fans of competitive shooters.\r\n\r\n', '1719376271valor.jpg', '2024-06-26 04:31:11', 7, 30, 0, 'approved'),
(35, 'Fast food chains', 'Fast food chains are quick-service restaurants that serve prepared food items such as burgers, fries, pizza, sandwiches, fried chicken, and other fast food. They are known for their convenience, affordability, and speed of service. Here are some of the most popular fast food chains globally:\r\n\r\n1. **McDonald&#039;s**: Famous for its Big Mac, McNuggets, and fries. McDonald&#039;s has a global presence with thousands of outlets worldwide.\r\n\r\n2. **Burger King**: Known for its Whopper burger, Burger King is another major player in the fast food industry with locations around the world.\r\n\r\n3. **KFC (Kentucky Fried Chicken)**: Specializes in fried chicken and offers a variety of chicken-based meals. KFC is known for its secret blend of 11 herbs and spices.\r\n\r\n4. **Subway**: Specializes in submarine sandwiches (subs) and salads. Customers can customize their sandwiches with a wide range of toppings and dressings.\r\n\r\n5. **Pizza Hut**: Known for its pizza, pasta, and side dishes. Pizza Hut offers a variety of crust options and toppings.\r\n\r\n6. **Taco Bell**: Known for its Mexican-inspired menu, including tacos, burritos, quesadillas, and nachos.\r\n\r\n7. **Wendy&#039;s**: Famous for its square hamburgers, sea salt fries, and Frosty desserts. Wendy&#039;s emphasizes fresh, never frozen beef.\r\n\r\n8. **Domino&#039;s Pizza**: A major player in the pizza delivery market, known for its fast delivery and wide variety of pizza options.\r\n\r\n9. **Chick-fil-A**: Known for its chicken sandwiches and waffle fries, Chick-fil-A is also noted for its customer service and commitment to quality.\r\n\r\n10. **Dunkin&#039; (formerly Dunkin&#039; Donuts)**: Known for its coffee, donuts, and breakfast items. Dunkin&#039; has expanded its menu to include a variety of beverages and sandwiches.\r\n\r\nThese chains have become household names and are a significant part of the global food industry, offering quick and tasty meals to millions of customers every day.', '1719376882McDonalds-Hacks-Menu-FT-1-BLOG0122-4ac9d62f6c9143be8da3d0a8553348b0.jpg', '2024-06-26 04:41:22', 1, 31, 0, 'rejected'),
(41, 'aa', 'aa', '1719816646772a2223c36b34c129ec4d9f085ad0a9.jpg', '2024-07-01 06:50:46', 1, 33, 0, 'approved');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `reported_by` int(11) NOT NULL,
  `reason` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `post_id`, `reported_by`, `reason`, `created_at`) VALUES
(1, 34, 33, 'rejected', '2024-07-01 07:17:53'),
(2, 34, 32, 'aa', '2024-07-01 08:13:41'),
(3, 41, 32, 'jpaitei', '2024-07-01 09:31:34');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `is_admin` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `username`, `email`, `password`, `avatar`, `is_admin`) VALUES
(19, 'abc', 'abc', 'abc', 'abc@222.com', '$2y$10$oaNRzZ99eNia4JpeId8BWuEe/SBRKRRqbF5wgXqdl/OhZXnK.x.eW', '1719305696View-of-the-PF-1000-electrodes.png', 0),
(28, 'shreya', 'pokeherel', 'shreyya', 'pokhrelshreya944@gmail.com', '$2y$10$JptD./5.6rzLzBih1QIl0Oiu4ss3xoDE9TXU/RCJIxXfuVMwQSq5i', '1719369653View-of-the-PF-1000-electrodes.png', 0),
(30, 'ben', 'up', 'benup', 'basnetbenup32@gmail.com', '$2y$10$mbMha9QD2N.bWJRvf4uwZ.Vz3lkFaBieUccV91cbAZzaJABOAcmVG', '1719375928caedaac01feeb99d70d415f13c3faaf1.jpg', 0),
(31, 'shitoshma', 'khanal', 'shitoshma', 'shitoshma@gmail.com', '$2y$10$3eszLyvAatsudzr/2F6OQ.rgREHLPWcHdlhjeb1lPlj8CoLHDS0TW', '1719376605WhatsApp Image 2024-04-21 at 8.54.58 AM.jpeg', 1),
(32, 'Sugam', 'Gautam', 'sugam', 'sugamgautam.128@gmail.com', '$2y$10$etc1KNQznU4eUObQ5hMKJeRhwyax./oCcHQXMl7WD9p/u90u.4I7W', '1719750749772a2223c36b34c129ec4d9f085ad0a9.jpg', 1),
(33, 'Rajeev', 'kc', 'rajeev', 'kcrajeev1110@gmail.com', '$2y$10$WhfGQg9jFiFNV45l860G1OMMe1MCgoTF0qk.oY0u41KxOZelSOrgq', '1719815447772a2223c36b34c129ec4d9f085ad0a9.jpg', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_blog_category` (`category_id`),
  ADD KEY `FK_blog_author` (`author_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `FK_blog_author` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_blog_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
