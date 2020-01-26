<?php


namespace lbs\pointvente\model;


class Items extends \Illuminate\Database\Eloquent\Model{

    protected $table = "item";
    protected $primaryKey = "id";
    public $incrementing = true;
    public $timestamps = false;

    public function Command(){
        return $this->belongsTo('lbs\pointvente\model\Command', 'command_id')->as('Items');
    }
}