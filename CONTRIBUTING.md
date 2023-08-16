Vous venez de rejoindre l'équipe de ToDo and Co ? Découvrez comment participer au projet.

<!-- TOC -->
* [Contribuer au projet To Do List App](#contribuer-au-projet-to-do-list-app)
  * [Pour commencer](#pour-commencer)
  * [Effectuer des modifications](#effectuer-des-modifications)
    * [Avant de créer une _pull request_](#avant-de-créer-une-pull-request)
    * [Créez votre _pull request_](#créez-votre-pull-request)
<!-- TOC -->

---

# Contribuer au projet To Do List App

## Pour commencer

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

## Effectuer des modifications

Avant toute chose, une _issue_ doit être ouverte. Soyez explicite en la nommant. Vous pouvez également détailler en quoi
va consister votre travail dans la partie description.

Maintenant que l'issue est ouverte, créez une branche à partie de cette _issue_.

Effectuez votre travail sur cette branche. Lorsque que votre travail est prêt à être controller, créer une _pull
request_.

### Avant de créer une _pull request_

Assurez vous d'avoir écrit les tests en lien avec la fonctionnalité sur laquelle vous êtes en train de travailler et que
vos modifications n'ont pas invalidées des tests antérieurs.

### Créez votre _pull request_

Une fois prêt créez votre _pull request_. Code Climate va alors analyser votre code pour s'assurer de sa qualité. En
fonction de l'analyse, vous aurez probablement des modifications à effectuer. Faite en sorte de corriger ces éventuels
problèmes et _commitez_ de nouveau votre travail. Vous pourriez avoir plusieurs allers/retours à faire avant que votre
code soit considéré ccomme valide par CodeClimate mais ce processus garanti une bonne maintenabilité du code.
