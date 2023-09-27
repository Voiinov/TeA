<?php

namespace App\modules\workflow;

use Core\Database;
use Core\Services\Auth\Auth;

class Api
{

    private static function DB()
    {
        return Database::getInstance();
    }

    public function giveRating(array $data)
    {

        if ($data['mark'] == 0) {
            self::DB()->query("DELETE FROM wf_students_gradebook WHERE lessonid=:lessonid AND studentid=:studentid", [
                "lessonid" => $data['lid'],
                "studentid" => $data['student']
            ]);
            return $data['mark'];
        }

        $rqst = self::DB()->query("SELECT * FROM wf_students_gradebook WHERE lessonid=:lessonid AND studentid=:studentid", [
            "lessonid" => $data['lid'],
            "studentid" => $data['student']
        ]);
        if ($rqst->rowCount() > 0) {
            $rqst = self::DB()->query("UPDATE wf_students_gradebook SET uid=:uid, mark=:mark WHERE lessonid=:lessonid AND studentid=:studentid", [
                "lessonid" => $data['lid'],
                "studentid" => $data['student'],
                "uid" => Auth::userID(),
                "mark" => $data['mark']
            ]);
        } else {
            $sid = $this->getLessonSubject($data['lid']);
            if($sid) {
                $rqst = self::DB()->query("INSERT INTO wf_students_gradebook (lessonid,sid,studentid,uid,mark) VALUES (:lessonid,:sid,:studentid,:uid,:mark)", [
                    "lessonid" => $data['lid'],
                    "studentid" => $data['student'],
                    "sid" => $sid,
                    "uid" => Auth::userID(),
                    "mark" => $data['mark']
                ]);
            }
        }

        return $data['mark'];

    }

    private function getLessonSubject(int $lesson): int
    {
        $sql = "SELECT wf_timetable.sid,wf_subjects.grouped";
        $sql .= " FROM wf_subjects";
        $sql .= " LEFT JOIN wf_timetable ON wf_timetable.sid=wf_subjects.id";
        $sql .= " WHERE wf_timetable.id=? LIMIT 1";

        $data = self::DB()->query($sql,[$lesson]);
        if($data->rowCount()>0){
           $result =  $data->fetchAll(\PDO::FETCH_ASSOC);
            return $result[0]['grouped'] ?? $result[0]['sid'];
        }
        return false;
    }


}