-- phpMyAdmin SQL Dump
-- version 5.2.1deb1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : jeu. 03 sep. 2026 à 13:01
-- Version du serveur : 10.11.6-MariaDB-0+deb12u1
-- Version de PHP : 8.2.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `qdm-jeremy`
--

-- --------------------------------------------------------

--
-- Structure de la table `annonce`
--

CREATE TABLE `annonce` (
  `titre` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `etat` varchar(50) NOT NULL,
  `prix_depart` decimal(10,2) NOT NULL,
  `date_fin` datetime NOT NULL,
  `vendeur_id` int(11) NOT NULL,
  `categorie_id` int(11) NOT NULL,
  `date_creation` datetime NOT NULL,
  `statut` varchar(50) DEFAULT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `annonce`
--

INSERT INTO `annonce` (`titre`, `description`, `etat`, `prix_depart`, `date_fin`, `vendeur_id`, `categorie_id`, `date_creation`, `statut`, `id`) VALUES
('annonce 1', 'test de modification d\'annonce', 'neuf', 35.00, '2026-09-24 10:20:00', 1, 88, '2026-09-03 07:23:36', 'enCours', 1),
('annonce 2', 'test pour la recherche est l\'affichage des annonces', 'neuf', 50000.00, '2026-09-30 10:50:00', 2, 109, '2026-09-03 08:53:31', 'enCours', 2),
('annonce 4', 'test déplacement photo dans le dossier image', 'neuf', 12.00, '2026-09-15 10:38:00', 1, 125, '2026-09-03 09:39:32', 'enCours', 4),
('annonce 5', 'vérification déplacement de photos', 'bonEtat', 3.00, '2026-09-22 11:55:00', 1, 109, '2026-09-03 09:57:09', 'enCours', 6),
('annonce 6', 'test statut', 'tresBonEtat', 15.00, '2026-09-03 14:00:00', 2, 115, '2026-09-03 11:56:43', 'terminee', 7);

-- --------------------------------------------------------

--
-- Structure de la table `enchere`
--

CREATE TABLE `enchere` (
  `montant` decimal(10,2) NOT NULL,
  `date_enchere` datetime NOT NULL,
  `utilisateur_id` int(11) NOT NULL,
  `annonce_id` int(11) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `enchere`
--

INSERT INTO `enchere` (`montant`, `date_enchere`, `utilisateur_id`, `annonce_id`, `id`) VALUES
(36.00, '2026-09-03 08:45:05', 2, 1, 1),
(37.00, '2026-09-03 08:46:04', 2, 1, 2),
(38.00, '2026-09-03 08:52:39', 2, 1, 3),
(54.00, '2026-09-03 11:28:13', 3, 1, 4),
(17.00, '2026-09-03 12:00:52', 1, 7, 5);

-- --------------------------------------------------------

--
-- Structure de la table `photo`
--

CREATE TABLE `photo` (
  `url` varchar(255) DEFAULT NULL,
  `annonce_id` int(11) NOT NULL,
  `principale` tinyint(1) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `photo`
--

INSERT INTO `photo` (`url`, `annonce_id`, `principale`, `id`) VALUES
('images/6a99238ed0edf_lego-star-war.webp', 1, 1, 1),
('images/6a99278078121_forza-game.webp', 1, 0, 2),
('images/6a992817b333c_forza-game.webp', 1, 0, 3),
('images/6a9935baa6d5d_forza-horizon.webp', 2, 1, 4),
('images/6a99449ec36a0_forza-horizon.webp', 6, 1, 10),
('images/6a99465a9c7a4_control-game.webp', 4, 1, 11);

-- --------------------------------------------------------

--
-- Structure de la table `suivi`
--

CREATE TABLE `suivi` (
  `utilisateur_id` int(11) NOT NULL,
  `annonce_id` int(11) NOT NULL,
  `date_suivi` datetime NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `suivi`
--

INSERT INTO `suivi` (`utilisateur_id`, `annonce_id`, `date_suivi`, `id`) VALUES
(1, 2, '2026-09-03 11:04:29', 1),
(1, 7, '2026-09-03 14:36:31', 2);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `pseudo` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`pseudo`, `email`, `password`, `id`) VALUES
('testeure', 'test@mail.fr', '$2y$10$p4JZeMU9fAMWNo43f.8Zb.edCBSxxO0QXS6WmAnrqrHsZc9NQbIZG', 1),
('utilisateur', 'user@mail.fr', '$2y$10$3o9jeihp9xCPEOtJFzOQsercKo9tPaL5q1kMD6Ny6quo6e6ubhTHC', 2),
('personne', 'pers@mail.fr', '$2y$10$OOb1WDb.gsoi4hoGGnoYKOdRMuISyqkVB951/r14aAgHywtV9pHW6', 3);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `annonce`
--
ALTER TABLE `annonce`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `enchere`
--
ALTER TABLE `enchere`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `photo`
--
ALTER TABLE `photo`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `suivi`
--
ALTER TABLE `suivi`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pseudo` (`pseudo`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `annonce`
--
ALTER TABLE `annonce`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `enchere`
--
ALTER TABLE `enchere`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `photo`
--
ALTER TABLE `photo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `suivi`
--
ALTER TABLE `suivi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
