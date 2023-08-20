ToDoList
========

[![Maintainability](https://api.codeclimate.com/v1/badges/aebdd573805461313ebc/maintainability)](https://codeclimate.com/github/leomoille/todo-and-co/maintainability)

Base du projet #8 : Améliorez un projet existant

https://openclassrooms.com/projects/ameliorer-un-projet-existant-1

---
## Vous faites partie de l'équipe de To Do and Co ?

[Découvrez comment contribuer](CONTRIBUTING.md) dès maintenant !

---

## Installation du projet en local

Récupérez le projet sur votre machine local.

```shell
git clone https://github.com/leomoille/todo-and-co.git
```

Une fois le projet téléchargé, rendez-vous à la racine de ce dernier pour configurer les fichiers `.env.local`
et `.env.test.local`.

```dotenv
# env.local
DATABASE_URL="mysql://user:password@127.0.0.1:3306/todo-and-co?serverVersion=10.4.28-MariaDB&charset=utf8mb4"

# env.local.test
DATABASE_URL_TEST=mysql://user:password@127.0.0.1:3306/todo-and-co?serverVersion=10.4.28-MariaDB&charset=utf8mb4
```

Une fois vos deux fichiers configurés, télécharger les dépendances PHP via Composer.

```shell
composer install
```

Vous pouvez maintenant créer les deux bases de données, une pour le développement et une pour les tests.

```shell
# Base de données de développement
symfony console doctrine:database:create

# Base de données de tests
symfony console doctrine:database:create --env=test
```

Pour ensuite lancer les migrations sur ces deux bases.

```shell
# Base de données de développement
symfony console doctrine:migrations:migrate

# Base de données de tests
symfony console doctrine:migrations:migrate --env=test
```

Vous pouvez aussi charger le jeu de données pour remplir la base de données fictives.

```shell
symfony console doctrine:fictures:load
```

Vous pouvez retrouver le contenu des fixtures dans le fichier [AppFixtures](src/DataFixtures/AppFixtures.php).

Une fois ces étapes réalisées, lancez le serveur en veillant à ce que votre serveur MySQL(MariaDB) soit également
démarré.

```shell
symfony console server:start

# Si vous ne souhaitez pas voir les logs et garder votre terminal actif
symfony console server:start -d
```
