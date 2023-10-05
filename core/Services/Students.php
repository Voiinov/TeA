<?php

namespace Core\Services;

use Core\Database;

class Students
{
    private function DB(): Database
    {
        return Database::getInstance();
    }

    public function getGroupStudentsList($gid, $lesson = null)
    {

        $sql = "SELECT wf_students.id,CONCAT(wf_students.last_name,' ',wf_students.first_name) AS name,wf_students.nick_name,wf_students.photo,";
        $sql .= "wf_students.email,wf_students.bdate,wf_students.gender,";
        $sql .= "wf_students_gradebook.mark,wf_students.enrolled,wf_students.expelled";
        $sql .= " FROM wf_students";
        $sql .= " LEFT JOIN wf_students_gradebook ON (wf_students_gradebook.studentid=wf_students.id AND wf_students_gradebook.lessonid=$lesson)";
        $sql .= " WHERE wf_students.gid=:gid ORDER BY last_name,first_name";

        return self::DB()->query($sql, ["gid" => $gid], true);

    }

    public function isBirthsday($date)
    {
        return (date("md") == date("md", strtotime($date)));
    }

    public function outOfListCheck($enrolled,$expelled,$onDate)
    {
        if(is_null($enrolled) && is_null($expelled))
            return false;

        $onDate = strtotime($onDate);

        if(!is_null($enrolled) && strtotime($enrolled)>$onDate)
            return _("Enrolled on") . " " . $enrolled;

        if(!is_null($expelled) && strtotime($expelled)<=$onDate)
            return _("Expelled on") . " " . $expelled;


        return false;

    }
}