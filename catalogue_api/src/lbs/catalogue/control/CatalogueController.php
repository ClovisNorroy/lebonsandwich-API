<?php

namespace lbs\catalogue\control;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use system\Json;

class CatalogueController
{
    protected $categories;
    protected $sandwichs;

    public function __construct()
    {
        $mongo = new \MongoDB\Client("mongodb://dbcat");
        $mongoDB = $mongo->catalogue;
        $this->categories = $mongoDB->categories;
        $this->sandwichs = $mongoDB->sandwichs;
    }


    public function getSandwichs(Request $req, Response $resp, $args)
    {
        $resp = $resp->withHeader('Content-Type', 'application/json');
        $allSandwichs = $this->sandwichs->find();

        if ($allSandwichs) {

            $resp->getBody()->write(Json::resource("commande", $allSandwichs->toArray()));
        } else {
            $resp = $resp->withStatus(404);
            $resp->getBody()->write(Json::error(404, "ressource non disponible"));

        }
        return $resp;
    }

    public function getSandwichsFromCategorie(Request $req, Response $resp, $args){
        $resp = $resp->withHeader('Content-Type', 'application/json');
        $id = $args['id'];
        $catSelected = $this->categories->findOne(["id" => intval($id)]);
        $sandwichsOfCat = $this->sandwichs->find(["categories"=>$catSelected->nom]);
        if ($sandwichsOfCat) {

            $resp->getBody()->write(Json::resource("commande", $sandwichsOfCat->toArray()));
        } else {
            $resp = $resp->withStatus(404);
            $resp->getBody()->write(Json::error(404, "ressource non disponible"));

        }
        return $resp;

    }
}