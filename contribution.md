# Marche à suivre pour contribuer au projet

##1. Créer une issue sur le repository

Le but étant de qualifier et de comprendre le problème à traiter.
Il faut expliquer le besoin, et proposer une stratégie d'implémentation, au moins dans les grandes lignes.

##2. Développer dans une nouvelle branche

Créer une branche git qu'il faudra supprimer après implémentation.
Les commits ne se font que dans cette branche.
Chaque commit doit représenter des changements qui gardent l'application fonctionnelle.

##3. Coder la partie fonctionnelle

La qualité du code peut faire l'objet d'un livre entier, mais il faut au minimum respecter :
* Les PHP Standard Recommandations, les plus évidentes étant celles relatives au style de code, 
[PSR-1](https://www.php-fig.org/psr/psr-1/) et [PSR-12](https://www.php-fig.org/psr/psr-12/).
* Les [Coding standards de Symfony](https://symfony.com/doc/current/contributing/code/standards.html).

##4. Ecrire les tests

Une proposition de contribution ne peut pas être acceptée si elle n'est pas livrée avec ses tests.

* Préparer l'environnement de test, en écrivant les fixtures ou dataset requis. 
  La base de données de test est déjà précisée.
* Tout nouveau contrôleur doit être testé fonctionnellement, avec WebTestCase.
* Tout nouveau repository ou [code qui interagit avec la base de données](https://symfony.com/doc/current/testing/database.html#functional-testing-of-a-doctrine-repository) doit être testé en intégration, avec KernelTestCase.
* Toute nouvelle classe ou service doit être testé unitairement avec TestCase, et chacune de ses méthodes et sortie doit faire l'objet d'un test.

Les tests permettent de travailler sereinement, en validant le code déjà en place. Ils rendent les améliorations futures plus simples.

##5. Vérifier la qualité et la performance

Une fois les tests passés il est facile de refactoriser et de gagner en qualité :

* PHPSTAN permet de vérifier le code de manière statique, `docker-compose exec backend php vendor/bin/phpstan analyse src/`
* [Blackfire.io](https://blackfire.io) permet de vérifier si les performances sont optimales

Toute optimisation doit bien entendu continuer à passer les tests. Si de nouvelles classes ou méthodes apparaissent, les points 2 et 4 doivent être respectés.

##6. Ouvrir une pull request

Une fois le code et les tests optimaux, il faut faire un dernier commit et push sa branche sur le repository.

Sur ce repository, il est ensuite possible d'ouvrir une Pull Request.

Les autres développeurs vont statuer sur la pertinence du code produit, et accepter si celui si convient.

##7. Merge la branche

Si cette Pull Request est acceptée, la branche sera "merged", déversée dans la branche principale "main".

Un commit sera ensuite produit (fait automatiquement si le repository est github) pour notifier le merge.

On peut finalement supprimer la branche.