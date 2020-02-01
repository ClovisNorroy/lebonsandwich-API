<?php
namespace system;
class Guzzle{
    protected static $client;

    public static function init(){
        self::$client = new \GuzzleHttp\Client([
            'base_uri' => 'http://api.catalogue.local',
            'timeout' => 2.0,
        ]);
    }

    public static function getClient(){
        return self::$client;
    }
}