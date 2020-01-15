<?php

namespace system;
class Json
{
    public static function error(int $error_code, string $message)
    {
        return json_encode([
            "type" => "error",
            "error" => $error_code,
            "message" => $message
        ]);
    }

    public static function collection(string $name_collection, array $collections, $count)
    {
        return json_encode([
            "type" => "collection",
            "count" => $count,
            "size" => count($collections),
            $name_collection => $collections
        ]);
    }

    public static function resource(string $name_resource, array $resource)
    {
        return json_encode([
            "type" => "resource",
            $name_resource => $resource
        ]);
    }

    public static function isJson($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}