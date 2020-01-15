# LBS : une application exemple à base de micro-services
## thématique : acheter des sandwichs

## Services

* 1 service de prise de commandes : commande_api
* 1 service pour l'accès au catalogue : catalogue_api
* 1 service de suivi des commandes point de vente : point_vente_api
* 1 service de gestion du catalogue : catalogue_web

## Bases de données

* 1 base de données mongo catalogue
* 1 base de données sql commandes

## docker-compose

* un fichier docker-compose.yml de démarrage est fourni. Il permet de construire un environnement d'exécution php/mysql/mongo.
* il contient la description des services de gestion des données (mysql+mongo), le service de prise de comandes et le service catalogue,
* il sera à compléter avec l'avancement du projet pour décrire l'ensemble des services nécessaires.
* il est basé sur des images docker que vous trouverez [ici] (https://bitbucket.org/canals5/docker-php-dev/) et en particulier le [boilerplate que voilà](https://bitbucket.org/canals5/docker-php-dev/src/master/boilerplate/).