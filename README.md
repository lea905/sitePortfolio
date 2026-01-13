# Portfolio Symfony

Ce projet est une migration d'un portfolio PHP vers Symfony 7.

## Installation

1.  Cloner le projet.
2.  Installer les dépendances :
    ```bash
    composer install
    ```
3.  Lancer le serveur :
    ```bash
    symfony server:start
    ```

## Accès au Dashboard

Le site dispose d'une interface d'administration pour gérer les projets dynamiquement.

-   **URL de connexion** : [http://127.0.0.1:8000/login](http://127.0.0.1:8000/login)
-   **URL du Dashboard** : [http://127.0.0.1:8000/admin/project](http://127.0.0.1:8000/admin/project)

### Identifiants Admin

-   **Email** : `admin@example.com`
-   **Mot de passe** : `admin`

## Fonctionnalités

-   **Page d'accueil** : Présentation générale.
-   **Compétences** : Liste des compétences avec animations.
-   **Projets** : Liste des projets gérée dynamiquement via le dashboard.
    -   Les projets ajoutés dans le dashboard apparaissent automatiquement sur la page publique `/project`.
