Vous trouverez ici un rapport sur l'application Web : To Do and Co.

## Qualité du code

Le code suit les [standards préconisées par Symfony](https://symfony.com/doc/current/contributing/code/standards.html). En plus de cette ligne de conduite, l'utilisation de [PHP Coding Standards Fixer (symfony.com)](https://cs.symfony.com/) permet de s'assurer tout au long du travail que le code respect ces standards.

## Analyse du code via Code Climate

Les données présentées ici sont [accessibles publiquement sur Code Climate]((https://codeclimate.com/github/leomoille/todo-and-co).

![[code-climate-to-do-and-co-global.png]]

Le but ici était de garder une note de maintenabilité au minimum à **B** pour l'intégralité du projet. Ici le projet reçois une note de **A**. Il en va de même pour chaque fichier.

![[code-climate-to-do-and-co-details.png]]

## Audit des performances via le profiler Symfony

En utilisant le profiler de Symfony, on obtient des informations utiles et détaillées sur les différentes requêtes et leurs temps de chargement. Nous allons voir en détails les différentes métriques lors d'une utilisation concrète de l'application.

### Page d'accueil

Notre page d'accueil se charge plutôt rapidement (40ms). On peut voir dans la partie `controller` l'exécution des templates Twig (`base` et `default`) en 7ms. Cette page n'a pas de complexité et son temps de chargement est correct.

![[profiler-home.png]]

### Page de connexion

Comme pour la page d'accueil, les performances sont relativement bonnes. Pour un total de 45ms de chargement dont 5ms pour le rendu Twig. Voyons maintenant les performances lors de la connexion

![[profiler-login.png]]

### Inscription

Voyons les performances de l'application pour l'inscription d'un utilisateur :

![[profiler-register.png]]

Un temps d'exécution total de 810ms, ce qui est correct. Voyons ce qu'il se passe du côté de doctrine et de la base de données :

![[register-doctrine-query.png]]

La première requête vérifie que le mail n'est pas utilisé puis l'on créer l'utilisateur, le tout en 4.87ms.

### Connexion à l'application

Lors de la soumission du formulaire de connexion, une redirection se produit, puis l'affichage de la page.

Voyons tout d'abord la redirection qui va se charger d'identifier notre utilisateur :

![[profiler-on-login-redirect.png]]

Ici la requête à durée 2569ms, cette valeur supérieur à ce que l'on a pu voir jusqu'à maintenant est liée au fait que l'on interrogé la base de données en plus des listeners en charge de nous authentifier.

> [!info]
> L'audit est effectué en local et ne reflète pas forcément la puissance d'un serveur Web.
> 2500ms est le résultat du premier test. En le reproduisant on tombe facilement à environ 500ms.
> Le but ici est de s'assurer que ce qui est exécuté ne prend pas de temps trop élevées.

Si l'on regarde du côté des requêtes en base, on a bien qu'une seule requête pour récupérer notre utilisateur :

![[login-doctrine-query.png]]

Une fois la redirection effectué, la page d'accueil est affiché en 51ms :

![[profiler-on-login-home.png]]
### Création d'une tâche

La création d'une tâche nécessite d'être authentifié. Une fois authentifier, on peut donc gérer ses tâches.

Voyons les performances lorsque l'on ajoute une nouvelle tâche : 

![[profiler-task-create.png]]

On peut de nouveau voir un appel de doctrine, examinons ça de plus près :

![[task-create-doctrine-query.png]]

Ici on récupère l'utilisateur courant, puis on créer l'entrée en base de données pour la tâche nouvellement créée. Le temps total de cette opération est de 10.29ms. Ce qui est convenable pour l'ajout d'une tâche.

### Modification d'une tâche
Voyons maintenant ce qu'il se passe lorsque l'on modifie cette tâche :

![[profiler-task-edit.png]]

Notre requête est exécuté en 130ms ce qui est plutôt positif. Regardons du côté de doctrine :

![[task-edit-doctrine-query.png]]

Ici on récupère l'utilisateur courant, la tâche à modifier puis on effectue les modifications, le tout en 5.63ms.

### Marquer une tâche comme terminée

Marquons maintenant cette tâche comme terminée :

![[profiler-task-done.png]]

Il y a évidemment encore un appel à doctrine, ici le temps d'exécution est de 61ms ce qui est également correct. 

Regardons du côté des requêtes en base de données :

![[task-done-query.png]]

Comme on aurait pu s'en douter, on retrouve une requête pour récupérer l'utilisateur, une pour récupérer la tâche et enfin une requête pour passer la tâche en terminée. Pour un temps de 2.51ms ce qui est plus que convenable pour ce genre d'opérations.

### Supprimer une tâche

Voyons maintenant les performances lors de la suppression d'une tâche :

![[profiler-task-delete 1.png]]

Un temps d'exécution plutôt rapide encore une fois pour un total de 50ms.

Nous avons de nouveau un appel à doctrine pour la suppression de cette tâche, voyons le détails des requêtes :

![[task-delete-doctrine-query.png]]

Sans surprise, on récupère de nouveau notre utilisateur et sa tâche, puis on la supprime, le tout en 2.78ms.

## Conclusion

L'application To Do and Co est relativement réactive. Lors des différents tests les temps d'exécutions sont plutôt bon. Il n'y a pas de processus superflus qui viennent ralentir l'applications et les requêtes faites en base de données concorde bien avec ce qu'on attend lors des différentes actions.

To Do and Co est amené à grandir et voir sa base d'utilisateur suivre cette voie également. Avec le temps, un audit supplémentaire pourrait être utile pour voir ce qui pourra et devra être optimisé.

Pour l'heure, To Do and Co est prêt à accueillir ses nouveaux utilisateurs convenablement !