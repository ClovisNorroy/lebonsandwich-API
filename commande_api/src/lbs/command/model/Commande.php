<?php

namespace lbs\command\model;


use Illuminate\Database\Capsule\Manager as DB;
use system\Guzzle;

class Commande extends \Illuminate\Database\Eloquent\Model
{
    protected $table = "commande";
    protected $primaryKey = "id";
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = true;

    protected $defaultSize = 10;

    public function addItem($item)
    {
        $api_res = Guzzle::getClient()->get($item["uri"]);
        $sandwich = \GuzzleHttp\json_decode($api_res->getBody())->sandwich[0];

        return DB::table("item")->insert([
            "uri" => $item["uri"],
            "libelle" => $sandwich->nom,
            "tarif" => $sandwich->prix->numberDecimal*$item["q"],
            "quantite" => $item["q"],
            "command_id" => $this->id
        ]);
    }

    public function toArray()
    {
        $array = parent::toArray();
        $datetime = new \DateTime(parent::toArray()["livraison"]);
        unset($array["livraison"]);
        $array["livraison"] = [];
        $array["livraison"]["date"] = $datetime->format("d-m-Y");
        $array["livraison"]["heure"] = $datetime->format("H:i");

        return $array;
    }
}