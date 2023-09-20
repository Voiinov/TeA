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

        if($data['mark']==0) {
            self::DB()->query("DELETE FROM wf_students_gradebook WHERE lessonid=:lessonid AND studentid=:studentid", [
                "lessonid" => $data['lid'],
                "studentid" => $data['student']
            ]);
            return $data['mark'];
        }

        $rqst = self::DB()->query("SELECT * FROM wf_students_gradebook WHERE lessonid=:lessonid AND studentid=:studentid",[
            "lessonid"=>$data['lid'],
            "studentid"=>$data['student']
        ]);
        if($rqst->rowCount()>0){
            $rqst = self::DB()->query("UPDATE wf_students_gradebook SET uid=:uid, mark=:mark WHERE lessonid=:lessonid AND studentid=:studentid",[
                "lessonid"=>$data['lid'],
                "studentid"=>$data['student'],
                "uid"=>Auth::userID(),
                "mark"=>$data['mark']
            ]);
        }else{
            $rqst = self::DB()->query("INSERT INTO wf_students_gradebook (lessonid,studentid,uid,mark) VALUES (:lessonid,:studentid,:uid,:mark)",[
                "lessonid"=>$data['lid'],
                "studentid"=>$data['student'],
                "uid"=>Auth::userID(),
                "mark"=>$data['mark']
            ]);
        }

        return $data['mark'];

    }


}