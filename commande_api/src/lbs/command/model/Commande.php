<?php
namespace lbs\command\model;

class Commande extends \Illuminate\Database\Eloquent\Model{
    protected $table = "commande";
    protected $primaryKey = "id";
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = true;

    protected $defaultSize = 10;


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