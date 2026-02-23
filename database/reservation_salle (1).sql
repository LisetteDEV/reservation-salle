-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 23 fév. 2026 à 20:23
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `reservation_salle`
--

-- --------------------------------------------------------

--
-- Structure de la table `creneaux`
--

CREATE TABLE `creneaux` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `heure_debut` time NOT NULL,
  `heure_fin` time NOT NULL,
  `type_activite` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `creneaux`
--

INSERT INTO `creneaux` (`id`, `date`, `heure_debut`, `heure_fin`, `type_activite`) VALUES
(1, '2026-02-05', '23:42:00', '23:44:00', 'conférence'),
(2, '2026-02-22', '10:00:00', '17:30:00', 'Culturelle'),
(3, '2026-02-26', '03:57:00', '05:00:00', 'Scolaire'),
(4, '2026-02-27', '08:02:00', '16:02:00', 'Culturelle'),
(5, '2026-03-07', '14:00:00', '18:00:00', 'Scolaire');

-- --------------------------------------------------------

--
-- Structure de la table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `utilisateur_id` int(11) DEFAULT NULL,
  `creneau_id` int(11) DEFAULT NULL,
  `statut` enum('en attente','validée','refusée') DEFAULT 'en attente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `reservations`
--

INSERT INTO `reservations` (`id`, `utilisateur_id`, `creneau_id`, `statut`) VALUES
(1, 4, 1, 'validée'),
(2, 4, 2, 'validée'),
(3, 1, 4, 'validée'),
(4, 1, 3, 'validée'),
(5, 1, 5, 'validée'),
(6, 5, 1, 'en attente'),
(7, 5, 4, 'validée');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `email`, `mot_de_passe`, `role`) VALUES
(1, 'Lisette', 'obognonlisette@gmail.com', '$2y$10$BJP4jA6KjXKff5R7tiFUW.5LBV0K8cZ7MzvUjYgiPJnlRYuLbywPi', 'user'),
(2, 'Cynthia', 'landbeninai@gmail.com', '$2y$10$mt0b7EPTlRM8V1B1T8YZD./k/vB7brOULnr6A/8Swa7CWgEfGAIxK', 'user'),
(4, 'Admin', 'adminlisette@gmail.com', '$2y$10$DTgYOoN4FXZcAFKN97i6XOkk1pefTO8vWO6sAq/5xAxeEoWdfrdoG', 'admin'),
(5, 'Crispus', 'lisettecynthia853@gmail.com', '$2y$10$jWULue4onyl5Ll03YwOhvulN86MDpTIVSOwwaKebvB3lpL50rsWVq', 'user');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `creneaux`
--
ALTER TABLE `creneaux`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilisateur_id` (`utilisateur_id`),
  ADD KEY `creneau_id` (`creneau_id`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `creneaux`
--
ALTER TABLE `creneaux`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`),
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`creneau_id`) REFERENCES `creneaux` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
