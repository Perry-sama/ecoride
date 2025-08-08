# EcoRide – Application de covoiturage écologique 🌱

Bienvenue sur le dépôt du projet **EcoRide**, une application web de covoiturage pensée pour l'écologie.  
Cette plateforme permet de créer, chercher et réserver des trajets en voiture, avec un focus sur les véhicules électriques.  

---

## 🚀 Stack technique

- **Front-end** : HTML5, CSS3, Bootstrap, JavaScript, React  
- **Back-end** : PHP 8.x avec Symfony  
- **Base de données relationnelle** : MySQL / MariaDB  
- **Base de données NoSQL** : MongoDB (utilisation complémentaire)  
- **Outils** : GitHub, Trello, Postman, VS Code  

---

## 🔧 Installation en local

### 1. Cloner le dépôt

git clone https://github.com/Perry-sama/ecoride.git  
cd ecoride/back  

### 2. Installer le back-end Symfony

composer install  
php bin/console doctrine:database:create  
php bin/console doctrine:migrations:migrate  
php bin/console doctrine:fixtures:load  
symfony server:start  

### 3. Installer le front-end React

cd ../front  
npm install  
npm start  

## 📁 Arborescence du projet

<img width="314" height="687" alt="Capture d'écran 2025-08-08 125115" src="https://github.com/user-attachments/assets/157c38a1-6d73-4f6f-88b4-64e247fff23a" />

## 🔐 Sécurité

Gestion des rôles (ROLE_USER, ROLE_EMPLOYEE, ROLE_ADMIN) avec hiérarchie définie dans security.yaml  
Suspension des comptes via flag isActive (contrôlé par UserChecker)  
Hashage sécurisé des mots de passe (algorithme auto de Symfony)  
Contrôle d’accès basé sur les rôles et l’état des comptes  

## 🚀 Déploiement

Configurer un serveur Linux avec PHP 8.1+, MySQL, Apache ou Nginx  

Cloner le projet sur le serveur  
Configurer .env pour l’environnement production  
Installer les dépendances (composer install --no-dev)  
Appliquer les migrations ou importer la base manuellement  
Configurer SSL (Let’s Encrypt recommandé)  
Mettre en place la supervision et backups  

## 📚 Documentation & Livrables

Manuel d’utilisation (PDF)  
Charte graphique (PDF) incluant palette de couleurs et typographies  
Diagrammes UML (modèle conceptuel, cas d’utilisation, séquences)  
Documentation technique (architecture, choix technologiques, déploiement)  
Gestion de projet sous forme de Kanban (Trello)  
README complet avec instructions de déploiement  

## 📝 Licence

Ce projet a été développé dans le cadre de la formation Développeur Web et Web Mobile - ECF Studi.  
Reproduction interdite sans autorisation.  

## Merci de votre intérêt pour EcoRide !
