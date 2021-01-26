#Project n°8 : BileMo

##Lancement du projet avec Docker

1. Lancer les containers en tapant `docker-compose up --build -d` à la racine du projet
2. Télécharger les dépendances avec `docker-compose exec backend composer install`
3. Fabriquer la structure avec `docker-compose exec backend php bin/console d:s:c` 
4. charger les data avec `docker-compose exec backend php bin/console d:f:l -n`
5. Accéder à l'application par https://localhost/

##Lancement du project sans Docker

1. Télécharger les dépendances avec `composer install`
2. Configurer sa base de données en faisant une copie de .env s'appelant .env.local et en modifiant la variable DATABASE_URL
3. Lancer le serveur PHP avec `php -S 127.0.0.1:8000 -t public/` ou `symfony serve -d`
4. Créer la base de données avec `bin/console d:d:c`
5. Fabriquer la structure avec `bin/console d:s:c`
6. charger les data avec `bin/console d:f:l -n`
7. Accéder à l'application par http://localhost:8000

##Utiliser le projet

Un premier utilisateur avec les droits administrateur est créé avec les fixtures. Il pourra alors administrer tous les autres. Ses identifiants :
```
Login : admin@changezmoi.fr
pass : admin
```

##Tests

* Pour lancer les tests et générer le rapport de couverture de code en html, qui sera dans le répertoire reports/ :

`docker-compose exec backend php bin/phpunit --coverage-html reports/`

* Pour lancer l'analyse statique du code :

`docker-compose exec backend php vendor/bin/phpstan analyse src/`