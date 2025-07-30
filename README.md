 # EcoRide – Application de covoiturage écologique 🌱

Bienvenue sur le dépôt du projet **EcoRide**, une application web de covoiturage pensée pour l'écologie.  
Cette plateforme permet de créer, chercher et réserver des trajets en voiture, en mettant l’accent sur les véhicules électriques.

---

## 🚀 Stack technique

- **Front-end** : HTML5, CSS3, Bootstrap, JavaScript, React
- **Back-end** : PHP 8.x avec Symfony
- **Base de données relationnelle** : MySQL / MariaDB
- **Base de données NoSQL** : MongoDB
- **Outils** : GitHub, Trello, Postman, VS Code

---

## 🔧 Installation en local

1. Cloner le dépôt GitHub :  
   git clone https://github.com/Perry-sama/ecoride.git

2. Aller dans le dossier back-end :  
   cd ecoride/back

3. Installer les dépendances PHP :  
   composer install

4. Créer la base de données :  
   php bin/console doctrine:database:create

5. Lancer les migrations :  
   php bin/console doctrine:migrations:migrate

6. Importer les données :  
   php bin/console doctrine:fixtures:load

7. Démarrer le serveur Symfony :  
   symfony server:start

8. Pour le front :  
   cd ../front  
   npm install  
   npm start  
   
### 1. Cloner le projet
git clone https://github.com/Perry-sama/ecoride.git  
cd ecoride

### 2. Installer le back-end Symfony
cd back  
composer install  
php bin/console doctrine:database:create  
php bin/console doctrine:migrations:migrate  
php bin/console doctrine:fixtures:load  
symfony server:start  

### 3. Installer le front

cd ../front  
npm install  
npm start  

## Accès test

Admin : admin@ecoride.fr  
 / Admin123!

Utilisateur : user@ecoride.fr  
 / User123!

## Arborescence

EcoRide/  
├── assets/  
├── bin/  
├── config/  
├── migrations/  
├── node_modules/   (si tu fais `npm install`)  
├── public/  
├── src/  
├── templates/  
├── tests/  
├── translations/  
├── var/  
├── vendor/  
├── .env  
├── composer.json  
├── package.json  
└── ...  

🧱 Création manuelle de la base de données
En raison d’un blocage persistant avec Doctrine Migrations (MetadataStorageError), la structure de la base de données a été créée manuellement. Les tables user et trajet ont été créées à partir des entités Symfony. Voici le détail des structures :

Table user  
id (int, clé primaire, auto-incrémentée)  

email (varchar 180, unique)  

roles (json)  

password (varchar 255)  

is_verified (booléen)  

Table trajet  
id (int, clé primaire)  

depart, destination (varchar 255)  

date (datetime)  

nb_places (int)  

prix (double)  

description (texte long, optionnel)  

conducteur_id (clé étrangère vers user.id, avec suppression en cascade)  


## 📋 Licence

Ce projet est développé dans le cadre d’une évaluation en cours de formation pour le titre Développeur Web et Web Mobile.
