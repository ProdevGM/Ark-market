-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 17 jan. 2022 à 07:36
-- Version du serveur :  5.7.31
-- Version de PHP : 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `ark-market`
--
CREATE DATABASE IF NOT EXISTS `ark-market` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `ark-market`;

-- --------------------------------------------------------

--
-- Structure de la table `arme`
--

DROP TABLE IF EXISTS `arme`;
CREATE TABLE IF NOT EXISTS `arme` (
  `id_arme` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `type` enum('objet','plan','deux','') NOT NULL DEFAULT 'objet',
  `categorie` enum('outil','mélé','bouclier','jet','feu','accessoire','explosif','piège','tourelle','tek') NOT NULL,
  `qualité` enum('Commun','Inhabituel','Rare','Épique','Légendaire','Mythique') NOT NULL,
  `dégât` smallint(5) UNSIGNED NOT NULL,
  `prix1` mediumint(8) UNSIGNED NOT NULL,
  `prix2` mediumint(8) UNSIGNED DEFAULT NULL COMMENT 'Prix objet et plan en vente',
  `monnaie` varchar(30) NOT NULL,
  `description` varchar(255) NOT NULL,
  `date_creation` date NOT NULL,
  `id_serveur` smallint(5) UNSIGNED DEFAULT NULL,
  `id_utilisateur` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id_arme`,`nom`),
  KEY `id_serveur` (`id_serveur`),
  KEY `id_utilisateur` (`id_utilisateur`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `arme`
--

INSERT INTO `arme` (`id_arme`, `nom`, `type`, `categorie`, `qualité`, `dégât`, `prix1`, `prix2`, `monnaie`, `description`, `date_creation`, `id_serveur`, `id_utilisateur`) VALUES
(4, 'Pioche', 'objet', 'outil', 'Commun', 250, 100, NULL, '', 'rien à dire', '2021-11-25', 1, 11),
(5, 'lance', 'plan', 'mélé', 'Inhabituel', 250, 220, NULL, 'points', 'bla bla bla', '2021-11-25', 1, 11),
(6, 'arc', 'deux', 'jet', 'Rare', 34, 123, 521, 'points', 'tout doit disparaitre', '2021-11-25', 1, 11),
(7, 'Arbalète', 'deux', 'jet', 'Épique', 250, 100, 150, 'points', 'bmla blabl lal ba labla', '2021-11-30', 5, 11);

-- --------------------------------------------------------

--
-- Structure de la table `armure`
--

DROP TABLE IF EXISTS `armure`;
CREATE TABLE IF NOT EXISTS `armure` (
  `id_armure` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `type` enum('objet','plan','deux','') NOT NULL DEFAULT 'objet',
  `categorie` enum('tissu','cuir','fourrure','désert','camouflage','chitine','métal','radiation','plongé','emeute','tek') NOT NULL,
  `qualité` enum('Commun','Inhabituel','Rare','Épique','Légendaire','Mythique') NOT NULL,
  `armure` smallint(5) UNSIGNED NOT NULL,
  `froid` smallint(5) UNSIGNED NOT NULL,
  `chaleur` smallint(5) UNSIGNED NOT NULL,
  `durabilite` smallint(5) UNSIGNED DEFAULT NULL,
  `prix1` mediumint(8) UNSIGNED NOT NULL,
  `prix2` mediumint(8) UNSIGNED DEFAULT NULL COMMENT 'prix objet et plan en vente',
  `monnaie` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  `date_creation` date NOT NULL,
  `id_serveur` smallint(5) UNSIGNED DEFAULT NULL,
  `id_utilisateur` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id_armure`),
  KEY `id_serveur` (`id_serveur`),
  KEY `id_utilisateur` (`id_utilisateur`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `armure`
--

INSERT INTO `armure` (`id_armure`, `nom`, `type`, `categorie`, `qualité`, `armure`, `froid`, `chaleur`, `durabilite`, `prix1`, `prix2`, `monnaie`, `description`, `date_creation`, `id_serveur`, `id_utilisateur`) VALUES
(4, 'casque en métal', 'deux', 'radiation', 'Épique', 123, 100, 100, 100, 100, 200, 'Points', 'pas cher pas cher', '2021-11-25', 1, 11),
(5, 'bottes en chitine', 'plan', 'chitine', 'Légendaire', 423, 200, 100, 200, 423, NULL, 'points', 'bla bla bla', '2021-11-25', 1, 11),
(6, 'armure en tissu', 'objet', 'tissu', 'Mythique', 123, 100, 100, 100, 220, NULL, 'points', 'fezf zef zef zfzf eg ebgberga ', '2021-11-25', 1, 11),
(7, 'Chaussettes', 'objet', 'tissu', 'Inhabituel', 123, 100, 100, 100, 100, 200, 'points', ' cwcvqsdvsvsdvqsd sd vsd sD sd ', '2021-11-30', 4, 11);

-- --------------------------------------------------------

--
-- Structure de la table `creature`
--

DROP TABLE IF EXISTS `creature`;
CREATE TABLE IF NOT EXISTS `creature` (
  `id_dino` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `categorie` enum('terrestre','volant','aquatique') NOT NULL,
  `sexe` enum('mâle','femelle','castré','mâle / femelle') NOT NULL,
  `niveau` smallint(5) UNSIGNED NOT NULL,
  `vie` mediumint(8) UNSIGNED NOT NULL,
  `energie` mediumint(8) UNSIGNED NOT NULL,
  `oxygène` mediumint(8) UNSIGNED NOT NULL,
  `nourriture` mediumint(8) UNSIGNED NOT NULL,
  `poids` mediumint(8) UNSIGNED NOT NULL,
  `attaque` smallint(5) UNSIGNED NOT NULL,
  `vitesse` smallint(5) UNSIGNED NOT NULL,
  `prix` mediumint(8) UNSIGNED NOT NULL,
  `monnaie` varchar(30) NOT NULL,
  `description` varchar(255) NOT NULL,
  `date_creation` date NOT NULL,
  `id_serveur` smallint(5) UNSIGNED DEFAULT NULL,
  `id_utilisateur` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id_dino`),
  KEY `id_utilisateur` (`id_utilisateur`),
  KEY `dino_ibfk_1` (`id_serveur`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `creature`
--

INSERT INTO `creature` (`id_dino`, `nom`, `categorie`, `sexe`, `niveau`, `vie`, `energie`, `oxygène`, `nourriture`, `poids`, `attaque`, `vitesse`, `prix`, `monnaie`, `description`, `date_creation`, `id_serveur`, `id_utilisateur`) VALUES
(4, 'parasaure', 'terrestre', 'mâle', 150, 100, 100, 100, 15136, 16515, 100, 100, 250, 'points', 'superbe créature !', '2021-11-25', 1, 11),
(5, 'ptéranodon', 'volant', 'femelle', 150, 100, 100, 100, 15136, 16515, 100, 100, 1422, 'points', '', '2021-11-25', 1, 11),
(6, 'mégalodon', 'aquatique', 'castré', 150, 12113, 100, 100, 15136, 16515, 100, 100, 250, 'points', 'négociation possible', '2021-11-25', 1, 11),
(7, 'grodoudou', 'terrestre', 'mâle', 150, 12113, 100, 100, 15136, 16515, 100, 100, 200, 'Points', 'sdcqdvqsdvq vdsv ava v', '2021-11-30', 5, 11),
(8, 'Stégosaure', 'terrestre', 'mâle / femelle', 150, 12113, 100, 100, 15136, 16515, 100, 100, 40, 'points', '', '2021-12-03', 4, 11);

-- --------------------------------------------------------

--
-- Structure de la table `info_serveur`
--

DROP TABLE IF EXISTS `info_serveur`;
CREATE TABLE IF NOT EXISTS `info_serveur` (
  `id_info_serveur` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom_perso` varchar(40) NOT NULL,
  `nom_tribu` varchar(50) NOT NULL,
  `nom_discord` varchar(40) NOT NULL,
  `id_utilisateur` int(10) UNSIGNED DEFAULT NULL,
  `id_serveur` smallint(5) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id_info_serveur`),
  KEY `id_serveur` (`id_serveur`),
  KEY `info_perso_serveur_ibfk_2` (`id_utilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='informations personnelles des joueurs par serveur';

-- --------------------------------------------------------

--
-- Structure de la table `plan`
--

DROP TABLE IF EXISTS `plan`;
CREATE TABLE IF NOT EXISTS `plan` (
  `id_plan` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `prix` mediumint(8) UNSIGNED NOT NULL,
  `negociation` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 = no négociation\r\n1 = négociation possible',
  `monnaie` varchar(50) NOT NULL,
  `commentaire` varchar(255) NOT NULL,
  `date_creation` date NOT NULL,
  `id_serveur` smallint(5) UNSIGNED DEFAULT NULL,
  `id_utilisateur` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id_plan`),
  KEY `id_serveur` (`id_serveur`),
  KEY `id_utilisateur` (`id_utilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `quete`
--

DROP TABLE IF EXISTS `quete`;
CREATE TABLE IF NOT EXISTS `quete` (
  `id_quete` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `titre` varchar(40) NOT NULL,
  `description` varchar(255) NOT NULL,
  `prix` mediumint(8) UNSIGNED NOT NULL,
  `negociation` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 = no négociation\r\n1 = négociation possible',
  `monnaie` varchar(30) NOT NULL,
  `date_creation` date NOT NULL,
  `id_serveur` smallint(5) UNSIGNED DEFAULT NULL,
  `id_utilisateur` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id_quete`),
  KEY `id_serveur` (`id_serveur`),
  KEY `id_utilisateur` (`id_utilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `selle`
--

DROP TABLE IF EXISTS `selle`;
CREATE TABLE IF NOT EXISTS `selle` (
  `id_selle` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `type` enum('objet','plan','deux','') NOT NULL DEFAULT 'objet',
  `categorie` enum('terrestre','volant','aquatique','amphibie') NOT NULL,
  `qualité` enum('Commun','Inhabituel','Rare','Épique','Légendaire','Mythique') NOT NULL,
  `armure` smallint(5) UNSIGNED NOT NULL,
  `prix1` mediumint(8) UNSIGNED NOT NULL,
  `prix2` mediumint(8) UNSIGNED DEFAULT NULL COMMENT 'prix objet et plan en vente',
  `monnaie` varchar(30) NOT NULL,
  `description` varchar(255) NOT NULL,
  `date_creation` date NOT NULL,
  `id_serveur` smallint(5) UNSIGNED DEFAULT NULL,
  `id_utilisateur` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id_selle`),
  KEY `id_utilisateur` (`id_utilisateur`),
  KEY `selle_ibfk_1` (`id_serveur`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `selle`
--

INSERT INTO `selle` (`id_selle`, `nom`, `type`, `categorie`, `qualité`, `armure`, `prix1`, `prix2`, `monnaie`, `description`, `date_creation`, `id_serveur`, `id_utilisateur`) VALUES
(4, 'ptéranodon', 'objet', 'volant', 'Rare', 123, 100, NULL, 'points', 'frf f zerf ', '2021-11-25', 1, 11),
(5, 'parasaure', 'plan', 'terrestre', 'Légendaire', 423, 123, NULL, 'points', 'dr agter gaeg aer', '2021-11-25', 1, 11),
(6, 'mégalodon', 'deux', 'aquatique', 'Légendaire', 123, 100, 200, 'points', 'rf zef rgze frgz ', '2021-11-25', 1, 11),
(7, 'ankylo', 'plan', 'volant', 'Mythique', 1515, 100, NULL, 'Points', 'qsc ff avdfver aezfa bre', '2021-11-30', 4, 11);

-- --------------------------------------------------------

--
-- Structure de la table `serveur`
--

DROP TABLE IF EXISTS `serveur`;
CREATE TABLE IF NOT EXISTS `serveur` (
  `id_serveur` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom_serveur` varchar(50) NOT NULL,
  `page_web` varchar(100) DEFAULT NULL,
  `page_ts` varchar(100) DEFAULT NULL COMMENT 'page top-serveurs',
  `slogan` varchar(255) DEFAULT NULL,
  `image` varchar(50) DEFAULT NULL,
  `monnaie` varchar(20) DEFAULT NULL COMMENT 'Uniquement si il y a un système de monnaie',
  `date_creation` date NOT NULL,
  PRIMARY KEY (`id_serveur`),
  UNIQUE KEY `nom_serveur` (`nom_serveur`),
  UNIQUE KEY `image` (`image`),
  UNIQUE KEY `page_ts` (`page_ts`),
  UNIQUE KEY `page_web` (`page_web`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `serveur`
--

INSERT INTO `serveur` (`id_serveur`, `nom_serveur`, `page_web`, `page_ts`, `slogan`, `image`, `monnaie`, `date_creation`) VALUES
(1, 'Serveur de test', 'www.test.fr', 'www.https://top-serveurs.net/serveur-test', 'rien de mieux qu\'un serveur test pour faire des tests...', '', 'points', '2021-09-19'),
(4, 'Serveur un', 'page_web@serveur1.com', 'page_ts@serveur1.com', NULL, NULL, 'Points', '2021-10-21'),
(5, 'Serveur deux', 'page_web@serveur2.com', 'page_ts@serveur2.com', NULL, NULL, NULL, '2021-10-21');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `id_utilisateur` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `mail` varchar(50) NOT NULL,
  `mdp` varchar(255) NOT NULL,
  `statut` enum('1','2','3') NOT NULL DEFAULT '2' COMMENT '1 = administrateur\r\n2 = utilisateur',
  `date_creation` date NOT NULL,
  `id_serveur` smallint(5) UNSIGNED DEFAULT NULL COMMENT 'Serveur par défaut lors de la connexion au site',
  PRIMARY KEY (`id_utilisateur`),
  UNIQUE KEY `mail` (`mail`),
  KEY `utilisateur_ibfk_1` (`id_serveur`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1 COMMENT='Information concernant les utilisateur';

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_utilisateur`, `mail`, `mdp`, `statut`, `date_creation`, `id_serveur`) VALUES
(11, 'test_creation@gmail.com', '$2y$10$ahLU7AnHIf.6WaUXwpgYAePNTgAcuWtjMvecZaGkeJnuWVTof7RRi', '2', '2021-11-23', 1);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `arme`
--
ALTER TABLE `arme`
  ADD CONSTRAINT `arme_ibfk_1` FOREIGN KEY (`id_serveur`) REFERENCES `serveur` (`id_serveur`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `arme_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `armure`
--
ALTER TABLE `armure`
  ADD CONSTRAINT `armure_ibfk_1` FOREIGN KEY (`id_serveur`) REFERENCES `serveur` (`id_serveur`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `armure_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `creature`
--
ALTER TABLE `creature`
  ADD CONSTRAINT `creature_ibfk_1` FOREIGN KEY (`id_serveur`) REFERENCES `serveur` (`id_serveur`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `creature_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `info_serveur`
--
ALTER TABLE `info_serveur`
  ADD CONSTRAINT `info_serveur_ibfk_1` FOREIGN KEY (`id_serveur`) REFERENCES `serveur` (`id_serveur`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `info_serveur_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `plan`
--
ALTER TABLE `plan`
  ADD CONSTRAINT `plan_ibfk_1` FOREIGN KEY (`id_serveur`) REFERENCES `serveur` (`id_serveur`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `plan_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `quete`
--
ALTER TABLE `quete`
  ADD CONSTRAINT `quete_ibfk_1` FOREIGN KEY (`id_serveur`) REFERENCES `serveur` (`id_serveur`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `quete_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `selle`
--
ALTER TABLE `selle`
  ADD CONSTRAINT `selle_ibfk_1` FOREIGN KEY (`id_serveur`) REFERENCES `serveur` (`id_serveur`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `selle_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD CONSTRAINT `utilisateur_ibfk_1` FOREIGN KEY (`id_serveur`) REFERENCES `serveur` (`id_serveur`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
