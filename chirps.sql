-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 09, 2022 at 02:58 AM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `chirps`
--

-- --------------------------------------------------------

--
-- Table structure for table `adminlikes`
--

CREATE TABLE `adminlikes` (
  `al_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `adminlikes`
--

INSERT INTO `adminlikes` (`al_id`, `user_id`, `post_id`) VALUES
(41, 1, 1),
(45, 1, 52),
(46, 1, 158),
(47, 1, 33),
(49, 1, 162),
(50, 1, 166),
(51, 1, 165),
(52, 1, 167),
(53, 1, 164);

-- --------------------------------------------------------

--
-- Table structure for table `card_packs`
--

CREATE TABLE `card_packs` (
  `pack_id` int(11) NOT NULL,
  `pack_name` varchar(40) NOT NULL,
  `date` datetime NOT NULL,
  `is_ava` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `card_packs`
--

INSERT INTO `card_packs` (`pack_id`, `pack_name`, `date`, `is_ava`) VALUES
(1, 'Starter Pack', '2022-02-25 21:28:55', 1),
(2, 'Bird Words', '2022-03-02 07:34:17', 0),
(3, 'Be Magical', '2022-03-02 07:34:17', 0),
(4, 'Cat Pack', '2022-03-06 23:20:13', 0),
(5, 'New Millennium', '2022-03-06 23:20:13', 0),
(6, 'Naughty Words', '2022-03-06 23:20:13', 0);

-- --------------------------------------------------------

--
-- Table structure for table `card_pack_words`
--

CREATE TABLE `card_pack_words` (
  `cpw_id` int(11) NOT NULL,
  `word_id` int(11) NOT NULL,
  `pack_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `card_pack_words`
--

INSERT INTO `card_pack_words` (`cpw_id`, `word_id`, `pack_id`) VALUES
(1, 1, 1),
(2, 2, 1),
(3, 3, 1),
(4, 4, 1),
(5, 5, 1),
(6, 6, 1),
(7, 7, 1),
(8, 8, 1),
(9, 9, 1),
(10, 10, 1),
(11, 11, 1),
(12, 12, 1),
(13, 13, 1),
(14, 14, 1),
(15, 15, 1),
(16, 16, 1),
(17, 17, 1),
(18, 18, 1),
(19, 19, 1),
(20, 20, 1),
(21, 21, 1),
(22, 22, 1),
(23, 23, 1),
(24, 24, 1),
(25, 25, 1),
(26, 26, 1),
(27, 27, 1),
(28, 28, 1),
(29, 29, 1),
(30, 30, 1),
(31, 31, 1),
(32, 32, 1),
(33, 33, 1),
(34, 34, 1),
(35, 35, 1),
(36, 36, 1),
(37, 37, 1),
(38, 38, 1),
(39, 39, 1),
(40, 40, 1),
(41, 41, 1),
(42, 42, 1),
(43, 43, 1),
(44, 44, 1),
(45, 45, 1),
(46, 46, 1),
(47, 47, 1),
(48, 48, 1),
(49, 49, 1),
(50, 50, 1),
(52, 52, 1),
(55, 55, 1),
(56, 56, 1),
(58, 57, 1),
(59, 58, 1),
(60, 59, 1),
(61, 60, 1),
(62, 61, 1),
(63, 62, 1),
(64, 63, 1),
(65, 64, 1),
(66, 65, 1),
(67, 66, 1),
(72, 71, 1),
(77, 76, 1),
(78, 77, 1),
(79, 78, 1),
(82, 81, 1),
(83, 82, 1),
(84, 83, 1),
(85, 67, 2),
(86, 53, 2),
(87, 75, 2),
(88, 72, 2),
(89, 70, 2),
(90, 69, 2),
(91, 68, 2),
(92, 74, 2),
(93, 80, 2),
(94, 51, 2),
(95, 54, 2),
(96, 73, 2),
(97, 79, 2),
(98, 84, 1),
(99, 85, 1),
(100, 86, 1),
(101, 87, 1),
(102, 88, 3),
(103, 89, 3),
(104, 90, 3),
(105, 91, 3),
(106, 92, 3),
(107, 93, 3),
(108, 94, 3),
(109, 95, 3),
(110, 96, 3),
(111, 97, 3),
(112, 98, 3),
(113, 99, 3),
(114, 100, 3),
(115, 101, 3),
(116, 102, 3),
(117, 103, 3),
(118, 104, 3),
(119, 105, 3),
(120, 106, 3),
(121, 113, 4),
(122, 114, 5),
(123, 115, 4),
(124, 116, 4),
(125, 117, 4),
(126, 118, 4),
(127, 119, 4),
(128, 120, 4),
(129, 121, 4),
(130, 122, 4),
(131, 123, 4),
(132, 124, 4),
(133, 125, 4),
(134, 126, 4),
(135, 127, 4),
(136, 128, 4),
(137, 129, 4),
(138, 130, 4),
(139, 131, 4),
(140, 132, 4),
(141, 133, 5),
(142, 134, 5),
(143, 135, 5),
(144, 136, 5),
(145, 137, 5),
(146, 138, 5),
(147, 139, 5),
(148, 140, 5),
(149, 141, 5),
(150, 142, 5),
(151, 143, 5),
(152, 144, 5),
(153, 145, 5),
(154, 146, 5),
(155, 147, 5),
(156, 148, 5),
(157, 149, 5),
(158, 150, 5),
(159, 151, 5),
(160, 152, 5),
(161, 153, 6),
(162, 154, 6),
(163, 155, 6),
(164, 156, 6),
(165, 157, 6),
(166, 158, 6),
(167, 159, 6),
(168, 160, 6),
(169, 161, 6),
(170, 162, 6),
(171, 163, 6),
(172, 164, 6);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `body` varchar(500) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `user_id`, `post_id`, `body`, `date`) VALUES
(1, 1, 1, 'This is a very good comment.', '2022-02-25 02:17:35'),
(2, 2, 1, 'This is an even better comment.', '2022-02-25 02:17:35'),
(6, 1, 1, 'butts', '2022-02-28 20:56:34'),
(9, 1, 1, 'Why is it giving me the wrong feedback?', '2022-03-01 00:17:43'),
(10, 1, 1, 'okay i fixed it.', '2022-03-01 00:28:26'),
(12, 1, 20, 'Adding a comment', '2022-03-01 16:28:54'),
(17, 1, 20, 'confusion', '2022-03-01 16:35:31'),
(22, 3, 154, 'no comments allowed', '2022-03-03 12:23:21'),
(23, 1, 162, 'Nope.', '2022-03-03 22:28:49'),
(24, 1, 162, 'well maybe', '2022-03-03 22:29:05'),
(25, 1, 163, 'The encoding on these posts are still a bit off. I may switch it at the last minute to domtoimage', '2022-03-03 22:56:11'),
(26, 3, 168, 'butts', '2022-03-05 17:28:38'),
(27, 3, 168, 'butts', '2022-03-05 17:29:01'),
(28, 3, 168, 'butts', '2022-03-05 17:29:13');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `like_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`like_id`, `user_id`, `post_id`) VALUES
(2, 2, 1),
(28, 1, 36),
(29, 1, 52),
(30, 3, 158),
(34, 1, 1),
(36, 1, 160),
(41, 1, 161),
(44, 1, 162),
(45, 3, 162),
(46, 1, 164),
(47, 1, 158),
(50, 3, 164),
(51, 3, 165),
(52, 1, 168),
(53, 3, 168),
(54, 1, 172);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL,
  `title` varchar(60) NOT NULL,
  `post_img` varchar(255) NOT NULL,
  `post_alt` varchar(2500) NOT NULL,
  `body_desc` varchar(1000) NOT NULL,
  `date` datetime NOT NULL,
  `is_published` tinyint(1) NOT NULL,
  `has_username` tinyint(1) NOT NULL,
  `allow_comments` tinyint(1) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_id`, `title`, `post_img`, `post_alt`, `body_desc`, `date`, `is_published`, `has_username`, `allow_comments`, `user_id`) VALUES
(52, 'done for the night', 'img/posts/034f7748e820a3580cd210f0ef1c209b820f3693.png', 'and with that , this is it', 'One more for the road, then it\'s time to upload.', '2022-03-01 22:58:45', 1, 1, 1, 1),
(160, 'Needs a second post to count', 'img/posts/45ea016df7c7dfc4148b2e2a882612c166cb826c.png', 'is nt it enchant ing ?', '', '2022-03-03 15:24:37', 1, 1, 1, 1),
(164, 'better work', 'img/posts/6a5dac1523d204279f8258ba0658d5b07abcf88d.png', 'you you you you you you you you', '', '2022-03-05 13:11:02', 1, 1, 1, 1),
(169, 'Affirmations', 'img/posts/83643b6d18bafe1cfa6cbf24d05613781b3b35db.png', 'all will be better . they are enchant ing the home s and keep ing the magic .', 'I really just needed to make a poem today so that the system has something to read tomorrow as most liked. Also, making poems is fun.', '2022-03-06 15:58:44', 1, 1, 1, 3),
(172, 'fixed?', 'img/posts/66c7947f3299129fd3c7e742bbf61a8f1f6ec295.png', 'feather feather feather flight flock', '', '2022-03-06 20:54:09', 1, 1, 1, 1),
(173, 'Clicking the words is fun', 'img/posts/c50cabf10c95ce3c9aa8cc48c5676f92f8e62094.png', 'kitten lion magic long magical meta lead look and nap ?', 'Next steps: connect into sentences? Connect word ends to associated words?', '2022-03-07 10:32:49', 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(60) NOT NULL,
  `profile_pic` varchar(255) NOT NULL,
  `bio` varchar(1000) NOT NULL,
  `color_scheme` int(11) NOT NULL,
  `is_admin` tinyint(1) NOT NULL,
  `date_joined` datetime NOT NULL,
  `hashbrowns` varchar(60) NOT NULL,
  `reset_hash` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `profile_pic`, `bio`, `color_scheme`, `is_admin`, `date_joined`, `hashbrowns`, `reset_hash`) VALUES
(1, 'vgraybill', '$2y$10$3nQW2d4BOajlcOmj/HfCrOvBL34CHCpOaE.HrkDLpKruKhbXc/rZO', 'virginiaegraybill@gmail.com', 'img/avatars/bd839080fdb009b898b3316d4e02e98684b92b03_small.jpg', 'Hi. I\'m the creator of chirps.', 3, 1, '2022-02-24 21:37:27', '6d4562832ec9ef17c525dc792ce6f35a78ac90f212f30ffc15ad69a4c5d2', '0'),
(3, 'JustInTime', '$2y$10$jkxC.l5BZ1ODn.3616e9teSdCh6m/SaF6I96Pv5CH8pf158Bn80Ga', 'justin@time.com', 'img/avatars/8d867262b827a9c35e1e3e48760d39d5cabbb5b1_small.jpg', 'Okay, now that everything in the bio works, I should probably add the color selection. The profile just looks so bare.  Also mobile needs to be fixed. And Debug is starting to bug me.', 3, 0, '2022-03-03 07:33:27', '4191f8bf5aa7cecf7927d6bdbfb6e1cf68fdbe45c172c0e2d72dc9b274f5', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_car_packs`
--

CREATE TABLE `user_car_packs` (
  `ucp_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `pack_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_car_packs`
--

INSERT INTO `user_car_packs` (`ucp_id`, `user_id`, `pack_id`) VALUES
(1, 1, 1),
(2, 3, 1),
(3, 3, 2),
(4, 1, 2),
(5, 1, 3),
(6, 3, 3),
(7, 1, 4),
(8, 1, 5),
(9, 1, 6),
(10, 3, 5);

-- --------------------------------------------------------

--
-- Table structure for table `words`
--

CREATE TABLE `words` (
  `word_id` int(11) NOT NULL,
  `word` varchar(20) NOT NULL,
  `type` varchar(20) NOT NULL,
  `is_inf` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `words`
--

INSERT INTO `words` (`word_id`, `word`, `type`, `is_inf`) VALUES
(1, 'this', 'adjective', 1),
(2, 'those', 'adjective', 1),
(3, 'that', 'adjective', 1),
(4, 'then', 'adverb', 1),
(5, 'a', 'article', 1),
(6, 'an', 'article', 1),
(7, 'the', 'article', 1),
(8, 'and', 'conjunction', 1),
(9, 'but', 'conjunction', 1),
(10, 'for', 'preposition', 1),
(11, 'at', 'preposition', 1),
(12, 'you', 'pronoun', 1),
(13, 'it', 'pronoun', 1),
(14, 'him', 'pronoun', 1),
(15, 'her', 'pronoun', 1),
(16, 'their', 'pronoun', 1),
(17, 'she', 'pronoun', 1),
(18, 'he', 'pronoun', 1),
(19, 'they', 'pronoun', 1),
(20, 'we', 'pronoun', 1),
(21, 'was', 'preposition', 1),
(22, 'will', 'preposition', 1),
(23, 'can', 'preposition', 1),
(24, 'do', 'preposition', 1),
(25, 'be', 'preposition', 1),
(26, 'ing', 'ends', 1),
(27, 'ed', 'ends', 1),
(28, 's', 'ends', 1),
(29, 'es', 'ends', 1),
(30, 'er', 'ends', 1),
(31, 'ly', 'ends', 1),
(32, 'est', 'ends', 1),
(33, 'all', 'adjective', 0),
(34, 'big', 'adjective', 0),
(35, 'both', 'adjective', 0),
(36, 'little', 'adjective', 0),
(37, 'better', 'adjective', 0),
(38, 'young', 'adjective', 0),
(39, 'down', 'adverb', 0),
(40, 'where', 'adverb', 0),
(41, 'somewhere', 'noun', 0),
(42, 'could', 'verb', 0),
(43, 'keep', 'verb', 0),
(44, 'lead', 'verb', 0),
(45, 'look', 'verb', 0),
(46, 'made', 'verb', 0),
(47, 'make', 'verb', 0),
(48, 'should', 'verb', 0),
(49, 'sing', 'verb', 0),
(50, 'tell', 'verb', 0),
(51, 'travel', 'verb', 0),
(52, 'would', 'verb', 0),
(53, 'bird', 'noun', 0),
(54, 'tree', 'noun', 0),
(55, 'home', 'noun', 0),
(56, 'cat', 'noun', 0),
(57, 'choice', 'noun', 0),
(58, 'if', 'conjunction', 1),
(59, 'is', 'preposition', 1),
(60, 'are', 'verb', 1),
(62, 'of', 'preposition', 1),
(63, 'to', 'preposition', 1),
(65, 'nt', 'ends', 1),
(66, 'not', 'adverb', 1),
(67, 'beak', 'noun', 0),
(68, 'fly', 'verb', 0),
(69, 'flock', 'noun', 0),
(70, 'flight', 'noun', 0),
(71, 'sky', 'noun', 0),
(72, 'feather', 'noun', 0),
(73, 'wing', 'noun', 0),
(74, 'nest', 'noun', 0),
(75, 'chirp', 'verb', 0),
(76, 'in', 'preposition', 1),
(77, 'them', 'pronoun', 1),
(78, 'with', 'preposition', 1),
(79, 'tweet', 'verb', 1),
(80, 'song', 'noun', 1),
(81, '.', 'punctuation', 1),
(82, ',', 'punctuation', 1),
(83, ';', 'punctuation', 1),
(84, '\"', 'punctuation', 1),
(85, '-', 'punctuation', 1),
(86, '!', 'punctuation', 1),
(87, '?', 'punctuation', 1),
(88, 'wizard', 'noun', 0),
(89, 'witch', 'noun', 0),
(90, 'illusion', 'noun', 0),
(91, 'magic', 'noun', 0),
(92, 'magical', 'adjective', 0),
(93, 'spirit', 'noun', 0),
(94, 'charm', 'verb', 0),
(95, 'sparkle', 'verb', 0),
(96, 'electric', 'adjective', 0),
(97, 'enchant', 'verb', 0),
(98, 'enchantment', 'noun', 0),
(99, 'conjure', 'verb', 0),
(100, 'wand', 'noun', 0),
(101, 'staff', 'noun', 0),
(102, 'wonder', 'verb', 0),
(103, 'dazzle', 'verb', 0),
(104, 'energy', 'noun', 0),
(105, 'slight', 'adjective', 0),
(106, 'potion', 'noun', 0),
(108, 'I', 'pronoun', 1),
(109, 'me', 'pronoun', 1),
(110, 'myself', 'pronoun', 1),
(111, 'your', 'pronoun', 1),
(112, 'yet', 'adverb', 1),
(113, 'purr', 'verb', 0),
(114, 'whim', 'noun', 1),
(115, 'whimsy', 'noun', 0),
(116, 'elegant', 'adjective', 0),
(117, 'fur', 'noun', 0),
(118, 'claw', 'noun', 0),
(119, 'kitten', 'noun', 0),
(120, 'warm', 'adjective', 0),
(121, 'feral', 'adjective', 0),
(122, 'stray', 'adjective', 0),
(123, 'agile', 'adjective', 0),
(124, 'long', 'adjective', 0),
(125, 'crazy', 'adjective', 0),
(126, 'pounce', 'verb', 0),
(127, 'pride', 'noun', 0),
(128, 'prey', 'noun', 0),
(129, 'lion', 'noun', 0),
(130, 'mouse', 'noun', 0),
(131, 'nap', 'verb', 0),
(132, 'mischief', 'verb', 0),
(133, 'simp', 'noun', 0),
(134, 'meta', 'adjective', 0),
(135, 'crypto', 'adjective', 0),
(136, 'smart', 'adjective', 0),
(137, 'fetch', 'verb', 0),
(138, 'fam', 'noun', 0),
(139, 'stan', 'verb', 0),
(140, 'salty', 'adjective', 0),
(141, 'woke', 'adjective', 0),
(142, 'drip', 'verb', 0),
(143, 'vibe', 'noun', 0),
(144, 'drag', 'verb', 0),
(145, 'extra', 'adjective', 0),
(146, 'diss', 'verb', 0),
(147, 'homey', 'noun', 0),
(148, 'noob', 'noun', 0),
(149, 'peeps', 'noun', 0),
(150, 'sweet', 'adjective', 0),
(151, 'bling', 'noun', 0),
(152, 'sketchy', 'adjective', 0),
(153, 'heck', 'noun', 0),
(154, 'darn', 'verb', 0),
(155, 'dang', 'verb', 0),
(156, 'sugar', 'noun', 0),
(157, 'poop', 'noun', 0),
(158, 'butt', 'noun', 0),
(159, 'thick', 'adjective', 0),
(160, 'frick', 'verb', 0),
(161, 'fudge', 'noun', 0),
(162, 'shoot', 'verb', 0),
(163, 'dongle', 'noun', 0),
(164, 'ball', 'noun', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adminlikes`
--
ALTER TABLE `adminlikes`
  ADD PRIMARY KEY (`al_id`);

--
-- Indexes for table `card_packs`
--
ALTER TABLE `card_packs`
  ADD PRIMARY KEY (`pack_id`);

--
-- Indexes for table `card_pack_words`
--
ALTER TABLE `card_pack_words`
  ADD PRIMARY KEY (`cpw_id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`like_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_car_packs`
--
ALTER TABLE `user_car_packs`
  ADD PRIMARY KEY (`ucp_id`);

--
-- Indexes for table `words`
--
ALTER TABLE `words`
  ADD PRIMARY KEY (`word_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adminlikes`
--
ALTER TABLE `adminlikes`
  MODIFY `al_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `card_packs`
--
ALTER TABLE `card_packs`
  MODIFY `pack_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `card_pack_words`
--
ALTER TABLE `card_pack_words`
  MODIFY `cpw_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=173;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `like_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=174;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_car_packs`
--
ALTER TABLE `user_car_packs`
  MODIFY `ucp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `words`
--
ALTER TABLE `words`
  MODIFY `word_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=165;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
