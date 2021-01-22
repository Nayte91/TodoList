#Project n°8 : BileMo

##Lancement du projet avec Docker

1. Lancer les containers en tapant `docker-compose up --build -d` à la racine du projet
2. Télécharger les dépendances avec `docker-compose exec backend composer install`
3. Fabriquer la structure avec `docker-compose exec backend php bin/console doctrine:schema:create` 
4. charger les data avec `docker-compose exec backend php bin/console doctrine:fixtures:load`
5. Accéder à l'application par https://localhost/

##Lancement du project sans Docker

1. Télécharger les dépendances avec `composer install`
2. Configurer sa base de données en faisant une copie de .env s'appelant .env.local et en modifiant la variable DATABASE_URL
3. Lancer le serveur PHP avec `php -S 127.0.0.1:8000 -t public/` ou `symfony serve -d`
4. Créer la base de données avec `bin/console doctrine:database:create`
5. Fabriquer la structure avec `bin/console doctrine:schema:create`
6. charger les data avec `bin/console doctrine:fixtures:load`
7. Accéder à l'application par http://localhost:8000