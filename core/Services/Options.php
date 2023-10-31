<?php

namespace Core\Services;

use Core\Database;

class Options
{
    private static function DB(): Database
    {
        return Database::getInstance();
    }

    public function getOptionValue(string $option):string
    {
        $data = self::DB()->query("SELECT value FROM options WHERE param=? LIMIT 1",[$option]);
        if($data->rowCount()>0) {
            $value = $data->fetchAll(2);
            return $value[0]['value'];
        }
        return "";
    }

    public function getPosts(int $id = null): array
    {

        $sql = "SELECT * FROM options WHERE ";
        $sql .= is_null($id) ? "param='post'" : "id={$id}";
        $sql .= " ORDER BY level DESC";

        return self::DB()->query($sql, [], true);

    }

    public function getRoles(int $id = null): array
    {

        $sql = "SELECT id,value FROM options WHERE ";
        $sql .= is_null($id) ? "param='role'" : "id={$id}";
        $sql .= " ORDER BY level DESC";
        return self::DB()->query($sql, [], true);

    }


}