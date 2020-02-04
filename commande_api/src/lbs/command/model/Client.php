<?php

namespace lbs\command\model;


use Illuminate\Database\Capsule\Manager as DB;
use system\Guzzle;

class Client extends \Illuminate\Database\Eloquent\Model
{
    protected $table = "client";
    protected $primaryKey = "id";
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = true;

}