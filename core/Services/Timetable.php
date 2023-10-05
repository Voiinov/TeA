<?php

namespace Core\Services;

use Core\Database;
use Core\Services\Study;
use App\Helper\Calculator;

class Timetable
{

    private function DB(): Database
    {
        return Database::getInstance();
    }
    private function Calc():Calculator
    {
        return Calculator::getInstance();
    }

    private function STD(): Study
    {
        return Study::getInstance();
    }

    public function getLesson($uid, $lesson = "current")
    {
        $sql = "SELECT wf_timetable.id,wf_timetable.start,wf_timetable.sid,wf_timetable.end,wf_timetable.gid,wf_subjects.name,";
        $sql .= "wf_groups.mask,wf_groups.open_date,wf_groups.curator,wf_groups.master,curator.username AS c,master.username AS m";
        $sql .= " FROM wf_timetable";
        $sql .= " LEFT JOIN wf_subjects ON wf_subjects.id=wf_timetable.sid";
        $sql .= " LEFT JOIN wf_groups ON wf_groups.id=wf_timetable.gid";
        $sql .= " LEFT JOIN users AS master ON master.id=wf_groups.master";
        $sql .= " LEFT JOIN users AS curator ON curator.id=wf_groups.curator";
        if ($lesson == "current")
            $sql .= " WHERE wf_timetable.uid=:uid AND wf_timetable.end>now() ORDER BY wf_timetable.end ASC LIMIT 1";
        else
            $sql .= " WHERE wf_timetable.uid=:uid AND wf_timetable.id = $lesson ORDER BY wf_timetable.end ASC LIMIT 1";

        $rqst = self::DB()->query($sql, ["uid" => $uid]);

        return $rqst->rowCount() > 0 ? $rqst->fetch() : false;

    }

    public function finishedLessons($uid)
    {

        $result = [];

        $sql = "SELECT COUNT(wf_timetable.id) AS finished,wf_timetable.gid,wf_subjects.name,";
        $sql .= "wf_groups.hours_plan,wf_groups.open_date,wf_groups.mask,wf_subjects.grouped,wf_timetable.sid,study_hours_plan.course";
        $sql .= ",study_hours_plan.total,study_hours_plan.1_semester,study_hours_plan.2_semester";
        $sql .= " FROM wf_timetable";
        $sql .= " LEFT JOIN wf_subjects ON wf_subjects.id = wf_timetable.sid";
        $sql .= " LEFT JOIN wf_groups ON wf_groups.id = wf_timetable.gid";
        $sql .= " LEFT JOIN study_hours_plan ON ((study_hours_plan.sid = wf_subjects.id OR study_hours_plan.sid = wf_subjects.grouped) AND study_hours_plan.plan = wf_groups.hours_plan)";
        $sql .= " WHERE wf_timetable.uid=:uid AND wf_timetable.start>=:start AND wf_timetable.end<=:end GROUP BY wf_timetable.gid,wf_timetable.sid,study_hours_plan.course";
        foreach (self::DB()->query($sql, ["uid" => $uid, "start" => self::STD()->getStudyYearStartDate(), "end" => date("c")], true) as $item) {
            $course = self::STD()->getCourse($item['open_date']);
            if ($item['course'] == $course) {
                $groupIndex = sprintf($item['mask'], $course);
                $result[$item['gid']]['index'] = $groupIndex;
                $plan = $prc = $sp1 = $sp2 = $sh1 = $sh2 = 0;

                if ($item['total'] > 0) {
                    $plan = $item['total'];
                    $prc =  self::Calc()->getPercent($item['finished'],$item['total'],0);

                    if($item['finished'] < $item["1_semester"]) {
                        $sh1 = $item["1_semester"] - $item['finished'];
                        $sh2 = $item["2_semester"];
                        $sp1 = self::Calc()->getPercent($item['1_semester'],$item['total'],0) - $prc;
                        $sp2 = 100 - $sp1 - $prc;
                    }else {
                        $sh2 = $item["total"] - $item['finished'];
                        $sp2 = self::Calc()->getPercent($item['2_semester'],$item['total'],0) - $prc;
                    }
                }


                $result[$item['gid']]['subjects'][$item['sid']] =
                    [
                        "course" => $course,
                        "subject" => $item['name'],
                        "done" => $item['finished'],
                        "plan" => $plan,
                        "%" => $prc,
                        1=>[
                            "p" => $item["1_semester"],
                            "%" => $sp1,
                            "h" => $sh1,
                        ],
                        2=>[
                            "p" => $item["2_semester"],
                            "%" => $sp2,
                            "h" => $sh2
                        ]
                    ];
            }
        }
        ksort($result);
        return $result;

    }

    public function getLessonDatesInStudyYear(int $gid, int $sid, array $period)
    {
        $data = [
            "start" => $period[0],
            "end" => $period[1],
            "gid" => $gid,
            "sid" => $sid
        ];

        $sql = "SELECT DATE_FORMAT(wf_timetable.start,'%m-%d') as start";
        $sql .= ",wf_subjects.name,wf_timetable.id";
        $sql .= " FROM wf_timetable";
        $sql .= " LEFT JOIN wf_subjects ON wf_subjects.id=wf_timetable.sid";
        $sql .= " WHERE wf_timetable.start>=:start AND wf_timetable.end<=:end";
        $sql .= " AND wf_timetable.gid=:gid AND (wf_subjects.id=:sid OR wf_subjects.grouped=:sid)";
        $sql .= " ORDER BY wf_timetable.start";

        return self::DB()->query($sql, $data, true);
    }

}