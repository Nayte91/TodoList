# Marche à suivre pour contribuer au projet

## 1. Créer une issue sur le repository

Le but étant de qualifier et de comprendre le problème à traiter. Il faut expliquer le besoin, et proposer une stratégie
d'implémentation, au moins dans les grandes lignes.

## 2. Développer dans une nouvelle branche

Créer une branche git qu'il faudra supprimer après implémentation. Les commits ne se font que dans cette branche. Chaque
commit doit représenter des changements qui gardent l'application fonctionnelle.

## 3. Ajouter les nouvelles dépendances si besoin

Même s'il existe d'autres sources, les dépendances à utiliser ne doivent provenir que de composer.

Si les fonctionnalités à ajouter sont réservées au développement, la commande composer doit comporter `--dev`, ceci pour
préserver les performances en production.

Privilégier TOUJOURS les packages régulièrement mis à jour, étant le plus proche de la version PHP du projet, suivant
les standards de code et ayant bonne réputation.

## 4. Coder la partie fonctionnelle

La qualité du code peut faire l'objet d'un livre entier, mais il faut au minimum respecter, pour la forme :

* Les PHP Standard Recommandations, les plus évidentes étant celles relatives au style de code,
  [PSR-1](https://www.php-fig.org/psr/psr-1/) et [PSR-12](https://www.php-fig.org/psr/psr-12/).
* Les [Coding standards de Symfony](https://symfony.com/doc/current/contributing/code/standards.html).

Pour l'architecture du code :

* Les [Best Practices Symfony](https://symfony.com/doc/current/best_practices.html)
* Les [principes SOLID](https://medium.com/prod-io/solid-principles-takeaways-ec0825a07247), soit en résumé :
    * "Single Responsibility Principle", soit le fait qu'une classe doit faire une seule chose.
    * "Open-Close Principle", soit le fait de pouvoir modifier le code sans avoir à toucher une classe existante.
    * "Liskov Substitution", soit le fait que les actions des classes peuvent être remplacées par des interfaces.
    * "Interface Segregation Principle", soit le fait que les interfaces elles aussi ne doivent pas imposer plus d'un
      comportement à la fois.
    * "Dependency Inversion Principle", soit le fait de ne jamais appeler les services requis par des new NomDuService()
      mais en injectant son interface en argument.

## 5. Ecrire les tests

Une proposition de contribution ne peut pas être acceptée si elle n'est pas livrée avec ses tests.

* Préparer l'environnement de test, en écrivant les fixtures ou dataset requis. La base de données de test est déjà
  précisée dans le .env.test; faire bien attention de ne pas vider la base de production.
* L'arborescence du répertoire tests/doit suivre celle du répertoire src/.
* Chaque classe du projet NomDeLaClasse.php dans src/ doit avoir une classe de test appelée NomDeLaClasseTest.php dans
  tests/.
* Tout nom de méthode de test doit commencer par "test" puis être assez explicite pour comprendre l'enjeu du test
  facilement.
* Tout test doit être composé de 3 parties :
    * Le contexte de ce qu'on veut tester, comme la création des fixtures, de la classe à tester ou du client http
    * L'action à mener pour tester, comme l'appel d'une page, la soumoussion d'un formulaire ou l'appel d'une méthode
    * Une assertion, résultat du test où l'on voit si le comportement du code est celui attendu.
* Tout nouveau contrôleur doit être testé fonctionnellement, avec WebTestCase.
* Tout nouveau repository
  ou [code qui interagit avec la base de données](https://symfony.com/doc/current/testing/database.html#functional-testing-of-a-doctrine-repository)
  doit être testé en intégration, avec KernelTestCase.
* Toute nouvelle classe ou service doit être testé unitairement avec TestCase, et chacune de ses méthodes et sortie doit
  faire l'objet d'un test.

Les tests permettent de travailler sereinement, en validant le code déjà en place. Ils rendent les améliorations futures
plus simples.

## 6. Vérifier la qualité et la performance

Une fois les tests passés il est facile de refactoriser et de gagner en qualité :

* PHPSTAN permet de vérifier le code de manière
  statique, `docker-compose exec backend php vendor/bin/phpstan analyse src/`
* [Blackfire.io](https://blackfire.io) permet de vérifier si les performances sont optimales

Toute optimisation doit bien entendu continuer à passer les tests. Si de nouvelles classes ou méthodes apparaissent, les
points 2 et 4 doivent être respectés.

## 7. Ouvrir une pull request

Une fois le code et les tests optimaux, il faut faire un dernier commit et push sa branche sur le repository.

Sur ce repository, il est ensuite possible d'ouvrir une Pull Request.

Les autres développeurs vont statuer sur la pertinence du code produit, et accepter si celui si convient.

## 8. Merge la branche

Si cette Pull Request est acceptée, la branche sera "merged", déversée dans la branche principale "main".

Un commit sera ensuite produit (fait automatiquement si le repository est github) pour notifier le merge.

On peut finalement supprimer la branche.