<?php
namespace lbs\pointvente\model;

class Commande extends \Illuminate\Database\Eloquent\Model{
    protected $table = "commande";
    protected $primaryKey = "id";
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = true;

    protected $defaultSize = 10;
}