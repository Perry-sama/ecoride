# SECURITE - EcoRide

Ce document décrit les mesures de sécurité mises en place dans le projet EcoRide et la justification technique pour chacune.

## 1. Authentification & mot de passe
- **Hashage** : Utilisation du gestionnaire de mots de passe de Symfony (bcrypt/argon2i selon configuration PHP) pour stocker les mots de passe.
- **Justification** : Empêche la récupération des mots de passe en clair en cas de fuite de la base.

## 2. Contrôle d'accès
- **Roles** : ROLE_USER, ROLE_EMPLOYEE, ROLE_ADMIN.
- **UserChecker & voter** : Accès aux actions protégées par rôle + état `is_active`.
- **Justification** : Principes de moindre privilège et séparation des responsabilités.

## 3. Validation & nettoyage des données
- **Côté serveur** : Validation des payloads via contraintes Symfony Validator.
- **Côté client** : Validation superficielle pour UX mais pas de substitution à la validation serveur.
- **Justification** : Protection contre injection SQL et données malformées.

## 4. Protection contre CSRF & XSS
- **CSRF** : Tokens CSRF sur formulaires stateful.
- **XSS** : Echappement des templates Twig, sanitation des champs text.
- **Justification** : Protège l'intégrité des sessions et le DOM.

## 5. Communication & stockage
- **HTTPS** : Forcer TLS en production (Let’s Encrypt).
- **CORS** : Configurer CORS pour autoriser uniquement les origines connues.
- **Stockage secrets** : Variables d'environnement (pas de secrets dans le repo).

## 6. Base de données & accès
- **Principes** : comptes avec droits limités, sauvegarde régulière.
- **SQL** : Utilisation de requêtes préparées/ORM pour éviter injections.
- **NoSQL** : Accès via bibliothèque officielle (ex : mongodb/mongodb) avec paramètres d'authentification.

## 7. Logging & monitoring
- **Logs sensibles** : Ne pas stocker de mots de passe, token ou PII en clair.
- **NoSQL** : utilisation du service MongoLogger pour stocker certains événements (ex. connexions) à des fins d'audit.

## 8. Déploiement
- **CI/CD** : pipeline de build + tests. Dépendances scannées pour vulnérabilités.
- **Mises à jour** : plan de MAJ régulier des dépendances.
