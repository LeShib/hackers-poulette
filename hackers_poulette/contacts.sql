-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 21 juin 2023 à 18:09
-- Version du serveur : 10.4.25-MariaDB
-- Version de PHP : 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `becode`
--

-- --------------------------------------------------------

--
-- Structure de la table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `file` mediumblob DEFAULT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `contacts`
--

INSERT INTO `contacts` (`id`, `lastName`, `firstName`, `mail`, `file`, `description`) VALUES
(1, 'Di Bernardo', 'Nikko', 'dibernardonikko@gmail.com', 0x363439333030336231666464642e706e67, 'test3'),
(2, 'Di Bernardo', 'Nikko', 'dibernardonikko@gmail.com', 0x363439333038396131623433372e706e67, 'test 4'),
(3, 'Di Bernardo', 'Nikko', 'dibernardonikko@gmail.com', NULL, 'test5'),
(4, 'Di Bernardo', 'Nikko', 'dibernardonikko@gmail.com', NULL, 'test5'),
(5, 'Di Bernardo', 'Nikko', 'dibernardonikko@gmail.com', NULL, 'test5'),
(6, 'Di Bernardo', 'Nikko', 'dibernardonikko@gmail.com', NULL, 'test5'),
(7, 'Di Bernardo', 'Nikko', 'dibernardonikko@gmail.com', NULL, 'test5');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
