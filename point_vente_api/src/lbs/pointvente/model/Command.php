<?php
namespace lbs\pointvente\model;

class Command extends \Illuminate\Database\Eloquent\Model{
    protected $table = "commande";
    protected $primaryKey = "id";
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = true;

    protected $defaultSize = 10;

    public function Items(){
        return $this->hasMany('lbs\pointvente\model\Items', 'command_id');
    }
}