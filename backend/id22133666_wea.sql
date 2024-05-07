-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Počítač: localhost
-- Vytvořeno: Úte 07. kvě 2024, 08:25
-- Verze serveru: 10.5.20-MariaDB
-- Verze PHP: 7.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `id22133666_wea`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `articles`
--

CREATE TABLE `articles` (
  `id` int(11) NOT NULL,
  `title` varchar(150) DEFAULT NULL,
  `createdAt` date DEFAULT NULL,
  `publishedAt` date DEFAULT NULL,
  `categoryId` int(11) DEFAULT NULL,
  `authorId` int(11) DEFAULT NULL,
  `image` varchar(300) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `perex` varchar(200) DEFAULT NULL,
  `urlSlug` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Vypisuji data pro tabulku `articles`
--

INSERT INTO `articles` (`id`, `title`, `createdAt`, `publishedAt`, `categoryId`, `authorId`, `image`, `content`, `perex`, `urlSlug`) VALUES
(1, 'Latest Tech Gadgets', '2024-05-01', '2024-05-02', 1, 1, 'tech_gadgets.jpg', 'Check out the newest gadgets hitting the market.', 'Explore the latest in technology with these cutting-edge gadgets.', 'latest-tech-gadgets'),
(2, 'Healthy Eating Tips', '2024-04-25', '2024-04-26', 2, 2, 'healthy_eating_tips.jpg', 'Discover ways to improve your diet and overall health.', 'Learn simple yet effective tips for healthier eating habits.', 'healthy-eating-tips'),
(3, 'Business Strategies for Success', '2024-04-20', '2024-04-21', 3, 3, 'business_strategies.jpg', 'Learn about proven strategies for business growth and success.', 'Get insights into the latest trends and techniques in business management.', 'business-strategies');

-- --------------------------------------------------------

--
-- Struktura tabulky `authors`
--

CREATE TABLE `authors` (
  `id` int(11) NOT NULL,
  `firstName` varchar(20) DEFAULT NULL,
  `lastName` varchar(30) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `phoneNumber` varchar(15) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `image` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Vypisuji data pro tabulku `authors`
--

INSERT INTO `authors` (`id`, `firstName`, `lastName`, `email`, `phoneNumber`, `content`, `image`) VALUES
(1, 'John', 'Doe', 'john.doe@example.com', '+123456789', 'Experienced technology writer', 'john_doe.jpg'),
(2, 'Jane', 'Smith', 'jane.smith@example.com', '+987654321', 'Health and wellness expert', 'jane_smith.jpg'),
(3, 'Michael', 'Johnson', 'michael.johnson@example.com', '+555555555', 'Business analyst and consultant', 'michael_johnson.jpg');

-- --------------------------------------------------------

--
-- Struktura tabulky `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `content` text DEFAULT NULL,
  `image` varchar(300) DEFAULT NULL,
  `inMenu` tinyint(1) DEFAULT NULL,
  `name` varchar(25) DEFAULT NULL,
  `urlSlug` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Vypisuji data pro tabulku `categories`
--

INSERT INTO `categories` (`id`, `content`, `image`, `inMenu`, `name`, `urlSlug`) VALUES
(1, 'Technology news and updates', 'tech_image.jpg', 1, 'Technology', 'technology'),
(2, 'Health and wellness articles', 'health_image.jpg', 1, 'Health', 'health'),
(3, 'Business insights and trends', 'business_image.jpg', 1, 'Business', 'business');

-- --------------------------------------------------------

--
-- Struktura tabulky `users_admin`
--

CREATE TABLE `users_admin` (
  `id` int(11) NOT NULL,
  `email` varchar(200) DEFAULT NULL,
  `loginName` varchar(100) DEFAULT NULL,
  `loginPassword` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Vypisuji data pro tabulku `users_admin`
--

INSERT INTO `users_admin` (`id`, `email`, `loginName`, `loginPassword`) VALUES
(1, 'admin@example.com', 'admin', 'admin_password');

--
-- Indexy pro exportované tabulky
--

--
-- Indexy pro tabulku `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categoryId` (`categoryId`),
  ADD KEY `authorId` (`authorId`);

--
-- Indexy pro tabulku `authors`
--
ALTER TABLE `authors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexy pro tabulku `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexy pro tabulku `users_admin`
--
ALTER TABLE `users_admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`categoryId`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `articles_ibfk_2` FOREIGN KEY (`authorId`) REFERENCES `authors` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
