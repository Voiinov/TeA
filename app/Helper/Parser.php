<?php

namespace App\Helper;

class Parser
{

    public static function edbo(int $id, string $type = "json")
    {
        return file_get_contents("https://registry.edbo.gov.ua/api/university/?id={$id}&exp={$type}");
    }

    public static function csv(int $id)
    {
        return file_get_contents("https://registry.edbo.gov.ua/api/university/?id={$id}&exp={$type}");
    }

    private static function parseJsonData(string $data): array
    {
        return json_decode($data,true);
    }

}