# SallePro - Gestion de Réservations

Application web de gestion de réservations de salle développée en PHP/MySQL.

## Technologies utilisées
- PHP / MySQL
- HTML / CSS
- Bootstrap 5
- JavaScript
- Chart.js

## Installation

1. Cloner le projet
git clone https://github.com/LisetteDEV/reservation-salle.git

2. Importer la base de données
- Ouvrir phpMyAdmin
- Créer une base de données `reservation_salle`
- Importer le fichier `database/reservation_salle.sql`

3. Configurer la connexion
- Ouvrir `config.php`
- Renseigner vos identifiants MySQL

4. Lancer le projet
- Démarrer XAMPP
- Ouvrir `http://localhost/reservation-salle`

## Fonctionnalités

### Côté utilisateur
- Inscription et connexion
- Consulter les créneaux disponibles
- Réserver un créneau
- Voir ses réservations et leur statut

### Côté administrateur
- Tableau de bord avec statistiques (Chart.js)
- Gérer les créneaux (ajout, modification, suppression)
- Valider ou refuser les réservations

## Comptes de test
- **Admin** : adminlisette@gmail.com , mot de passe : admin@2026

