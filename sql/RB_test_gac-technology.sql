-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : Dim 06 juin 2021 à 21:39
-- Version du serveur :  10.4.17-MariaDB
-- Version de PHP : 8.0.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `tickets_appels_bdd`
--

-- --------------------------------------------------------

--
-- Structure de la table `détail des appels bdd`
--

CREATE TABLE `détail des appels bdd` (
  `id` int(11) NOT NULL,
  `compte_facture` varchar(255) NOT NULL,
  `num_facture` varchar(255) NOT NULL,
  `num_abonne` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `heure` time NOT NULL,
  `durée` varchar(255) DEFAULT NULL,
  `volume_réel` float DEFAULT NULL,
  `duree_facture` varchar(255) DEFAULT NULL,
  `volume_facture` float DEFAULT NULL,
  `type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `détail des appels bdd`
--
ALTER TABLE `détail des appels bdd`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `détail des appels bdd`
--
ALTER TABLE `détail des appels bdd`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
