<?php

namespace Core\Services;

use Core\Database;

class Options
{

    public function getPosts(int $id=null): array
    {

        $sql = "SELECT * FROM options WHERE ";
        $sql .= is_null($id) ? "option='post'" : "id={$id}";
        $sql .= " ORDER BY level DESC";

        return $this->DB()->query($sql, [],true);

    }

    public function getRoles(int $id=null): array
    {

        $sql = "SELECT id,value FROM options WHERE ";
        $sql .= is_null($id) ? "option='role'" : "id={$id}";
        $sql .= " ORDER BY level DESC";
        return $this->DB()->query($sql, [],true);

    }

    private function DB(): Database
    {
        return Database::getInstance();
    }

}