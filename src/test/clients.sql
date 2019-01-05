-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  sam. 05 jan. 2019 à 15:30
-- Version du serveur :  5.7.19
-- Version de PHP :  7.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `hotel-db`
--

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

DROP TABLE IF EXISTS `clients`;
CREATE TABLE IF NOT EXISTS `clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lastname` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `country_id` int(11) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `clients`
--

INSERT INTO `clients` (`id`, `lastname`, `firstname`, `address`, `city`, `country_id`, `phone`, `email`) VALUES
(1, 'Wrynnn', 'Varian', '12 donjon de hurlevent', 'Hurlevent', 1, '06 26 85 69 05', 'Varian.Wrynn@gmail.fe'),
(2, 'Coursevent', 'Sylvanas', '42 de l\'Apothicarium', 'Fossoyeuse', 2, '06 02 34 75 86', 'Sylvanas.coursement@fossoyeuse.ct'),
(3, 'Hurlorage', 'Illidan', '32  des haut quartier', 'temple noir', 3, '66 66 66 66 66', 'illidan.Hurlorage@legion.ardente'),
(4, 'Portvaillant', 'Jaina', '12 rue des mage', 'Kul Tiras', 4, '35 64 52 89 19', 'jaina.portvaillant@dalaran.al'),
(5, 'Menethil', 'Arthas', ' 1 du trone de glace', 'Citadelle de la couronne de glace', 5, '06 66 66 66 66', 'arthas.menethil@fleau.az'),
(6, 'Murmevent', 'Tyrande', '18 temple de la Lune', 'Darnassus', 6, '06 53 64 87 15', 'tyrande.murmevent@teldrassil.el'),
(7, 'Hurlorage', 'Malfurion', '26 rue de Cénarius', 'Reflet-de-Lune', 7, '04 98 56 16 57', 'malfurion.hurlorage@reflet.el');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
