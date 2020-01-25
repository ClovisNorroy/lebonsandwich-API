<?php

namespace lbs\catalogue\control;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use system\Json;

class CatalogueController
{
    protected $mongDB;

    public function __construct()
    {
        $mongo = new \MongoDB\Client("mongodb://dbcat");
        $this->mongDB = $mongo->catalogue;
    }


    public function getSandwichs(Request $req, Response $resp, $args)
    {
        $resp = $resp->withHeader('Content-Type', 'application/json');
        $sandwichs = $this->mongDB->sandwichs;
        $allSandwichs = $sandwichs->find();

        if ($allSandwichs) {

            $resp->getBody()->write(Json::resource("commande", $allSandwichs->toArray()));
        } else {
            $resp = $resp->withStatus(404);
            $resp->getBody()->write(Json::error(404, "ressource non disponible"));

        }
        return $resp;
    }
}