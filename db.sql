-- phpMyAdmin SQL Dump
-- version 4.5.4.1
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Dim 21 Mai 2017 à 21:07
-- Version du serveur :  5.6.17-log
-- Version de PHP :  7.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `bully`
--

-- --------------------------------------------------------

--
-- Structure de la table `mail`
--

DROP TABLE IF EXISTS `mail`;
CREATE TABLE IF NOT EXISTS `mail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object` varchar(255) COLLATE utf8_bin NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receptor_id` int(11) NOT NULL,
  `PJ` varchar(255) COLLATE utf8_bin NOT NULL,
  `suppression_status` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT 'none',
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `unregistered_users`
--

DROP TABLE IF EXISTS `unregistered_users`;
CREATE TABLE IF NOT EXISTS `unregistered_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `gender` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT 'man',
  `pseudo` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `type` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT 'victime',
  `location` varchar(5) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `indic` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `psy_id` int(11) NOT NULL,
  `lawyer_id` int(11) NOT NULL,
  `free_slot` int(11) NOT NULL,
  `birthdate` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_2` (`email`,`pseudo`),
  UNIQUE KEY `email_3` (`email`,`pseudo`),
  UNIQUE KEY `pseudo` (`pseudo`(100))
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `gender` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT 'man',
  `pseudo` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `type` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT 'victime',
  `location` varchar(5) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `indic` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `psy_id` int(11) NOT NULL,
  `lawyer_id` int(11) NOT NULL,
  `free_slot` int(11) NOT NULL,
  `birthdate` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`(100)),
  UNIQUE KEY `pseudo` (`pseudo`(100))
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=latin1;

--
-- Contenu de la table `users`
--

INSERT INTO `users` (`id`, `email`, `gender`, `pseudo`, `password`, `type`, `location`, `indic`, `psy_id`, `lawyer_id`, `free_slot`, `birthdate`) VALUES
(8, 'eugenie.poupet@supinternet.fr', 'woman', 'admin', '$2y$10$R6auX1gFObVtmj3z.KMjDuiQ6BFb.4wSd7k5zGzXV6pY9xcc17Omy', 'admin', '95213', 'keep it simple', 0, 0, 0, '1951-11-30');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
