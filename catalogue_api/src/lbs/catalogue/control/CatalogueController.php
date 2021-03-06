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
            $resp->getBody()->write(Json::resource("sandwich", $allSandwichs->toArray()));
        } else {
            $resp = $resp->withStatus(404);
            $resp->getBody()->write(Json::error(404, "ressource non disponible"));

        }
        return $resp;
    }

    public function getSandwichByRef(Request $req, Response $resp, $args)
    {
        $resp = $resp->withHeader('Content-Type', 'application/json');

        $sandwich = $this->sandwichs->find(["ref" => $args["ref"]]);


        if ($sandwich) {
            $resp->getBody()->write(Json::resource("sandwich", $sandwich->toArray()));
        } else {
            $resp = $resp->withStatus(404);
            $resp->getBody()->write(Json::error(404, "ressource non disponible"));
        }
        return $resp;
    }

    public function getSandwichsFromCategorie(Request $req, Response $resp, $args)
    {
        $resp = $resp->withHeader('Content-Type', 'application/json');
        $catSelected = $this->categories->findOne(["id" => intval($args['id'])]);
        $sandwichsOfCat = $this->sandwichs->find(["categories" => $catSelected->nom]);
        if ($sandwichsOfCat) {
            $resp->getBody()->write(Json::resource("sandwich", $sandwichsOfCat->toArray()));
        } else {
            $resp = $resp->withStatus(404);
            $resp->getBody()->write(Json::error(404, "ressource non disponible"));

        }
        return $resp;

    }

    public function getCategorieByID(Request $req, Response $resp, $args)
    {
        $resp = $resp->withHeader('Content-Type', 'application/json');
        $id = $args['id'];
        $catSelected = $this->categories->findOne(["id" => intval($id)], ["typeMap" => []]);
        if ($catSelected) {
            $catSelected->links = [
                "sandwichs" => "/categories/" . $catSelected->id . "/sandwichs/",
                "self" => "/categories/" . $catSelected->id . "/"
            ];
            $resp->getBody()->write(Json::resource("categorie", (array)$catSelected));
        } else {
            $resp = $resp->withStatus(404);
            $resp->getBody()->write(Json::error(404, "ressource non disponible"));
        }
        return $resp;
    }
}