<?php

namespace Core\Services;

use Core\Database;
use Core\Services\Study;

class Timetable
{

    private function DB(): Database
    {
        return Database::getInstance();
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
        $sql .= "wf_groups.hours_plan,wf_groups.open_date,wf_groups.mask,wf_subjects.grouped,wf_timetable.sid";
        $sql .= ",study_hours_plan.total,study_hours_plan.1_semester,study_hours_plan.2_semester";
        $sql .= " FROM wf_timetable";
        $sql .= " LEFT JOIN wf_subjects ON wf_subjects.id = wf_timetable.sid";
        $sql .= " LEFT JOIN wf_groups ON wf_groups.id = wf_timetable.gid";
        $sql .= " LEFT JOIN study_hours_plan ON ((study_hours_plan.sid = wf_subjects.id OR study_hours_plan.sid = wf_subjects.grouped) AND study_hours_plan.plan = wf_groups.hours_plan)";
        $sql .= " WHERE wf_timetable.uid=? AND wf_timetable.start>=? AND wf_timetable.end<=? GROUP BY wf_timetable.gid,wf_timetable.sid";

        foreach (self::DB()->query($sql, [$uid, self::STD()->getStudyYearStartDate(), date("Y-m-d")], true) as $item) {
            $course = self::STD()->getCourse($item['open_date']);
            $groupIndex = sprintf($item['mask'], $course);
//            $hours = $hoursPlan[$item['hours_plan']][$course][$item['sid']][0] ?? 0;
            $result[$item['gid']]['index'] = $groupIndex;
            if ($item['total'] > 0) {
                $plan = $item['total'];
                $prc = $item['finished'] / $item['total'] * 100;
            } else {
                $plan = 0;
                $prc = 0;
            }
            $result[$item['gid']]['subjects'][$item['sid']] =
                [
                    "course" => $course,
                    "subject" => $item['name'],
                    "done" => $item['finished'],
                    "plan" => $plan,
                    "percent" => $prc,
                    "s1" => $item["1_semester"],
                    "s2" => $item["2_semester"]
                ];
        }
        ksort($result);
        return $result;

    }


}