# LBS : une application exemple à base de micro-services
## thématique : acheter des sandwichs

## SETUP

* clone repo
* ```sudo docker-compose up --no-start```
* ```sudo docker-compose start```
* Importer les données dans la base local (port 8080)(Système : MySQL | Serveur : command | Utilisateur : command_lbs | MDP : command_lbs | BDD : command_lbs)
* Importer les données dans la base Mongo (port 8081)
* Pour tout lancer par la suite : ```sudo docker-compose start```
* Dans le conteneur docker de l'API, dans le repertoire avec composer.json : ```composer install```
* Modifier le fichier local /etc/hosts pour ajouter les adresses des API en 127.0.0.1

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