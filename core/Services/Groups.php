<?php

namespace Core\Services;

use Core\Database;

class Groups
{
    private array $courses = [];

    private static function DB()
    {
        return Database::getInstance();
    }

    private function STD(): Study
    {
        return Study::getInstance();
    }

    public function getGroupsList($date = null)
    {

        $rqst["date"] = is_null($date) ? date("Y-m-d") : $date;

        $list = [];
        $sql = "SELECT wf_groups.*, curator.username AS c,master.username AS m,";
        $sql .= "study_professions.title AS profession_name,study_professions.licenses";
        $sql .= " FROM wf_groups";
        $sql .= " INNER JOIN users AS curator ON (curator.id = wf_groups.curator OR wf_groups.curator IS NULL)";
        $sql .= " INNER JOIN users AS master ON (master.id = wf_groups.master OR wf_groups.master IS NULL)";
        $sql .= " LEFT JOIN study_professions ON study_professions.id = wf_groups.profession";
        $sql .= " WHERE wf_groups.close_date>=:date GROUP BY id";

        foreach (self::DB()->query($sql, $rqst, true) as $group) {
            $course = self::STD()->getCourse($group['open_date']);
            $list[] = [
                "gid" => $group['id'],
                "index" => sprintf($group['mask'], $course),
                "course" => $course,
                "master" => $group["master"] != "" ? ["id" => $group["master"], "name" => $group['m']] : ["id" => NULL, "name" => NULL],
                "curator" => $group["curator"] != "" ? ["id" => $group['curator'], "name" => $group['c']] : ["id" => NULL, "name" => NULL],
                "prof" => $group['profession_name'],
                "open" => $group['open_date'],
                "close" => $group['close_date'],
            ];
        }

        return $list;

    }

    public function getGroupsShortListBySubject(int $sid, bool $actual = true)
    {
        $list = [];

        $subjectsIDs = $this->getSubjectIds($sid);

        $sql = "SELECT wf_timetable.gid AS id, study_professions.licenses as code,wf_groups.hours_plan,wf_groups.mask,wf_groups.open_date";
        $sql .= ",COUNT(wf_timetable.id) AS hoursDone";
        $sql .= " FROM wf_timetable LEFT JOIN wf_groups ON wf_groups.id=wf_timetable.gid";
        $sql .= " LEFT JOIN wf_subjects ON wf_subjects.id=wf_timetable.sid";
        $sql .= " LEFT JOIN study_professions ON wf_groups.profession=study_professions.id";
        $sql .= " WHERE wf_timetable.sid IN (" . implode(",", $subjectsIDs) . ")";

        if ($actual) {
            $year = date('n') < 9 ? date("Y") - 1 : date("Y");
            $sql .= " AND close_date>now() AND wf_timetable.start>='{$year}-09-01'";
        }
        $sql .= " AND wf_timetable.end<=now()";
        $sql .= " GROUP BY wf_groups.id";


        foreach (self::DB()->query($sql, [], true) as $group) {
            $course = self::STD()->getCourse($group['open_date']);
            $plan = $this->getSubjectHoursPlan($group['hours_plan'], $subjectsIDs, $course);
            $list[$group['id']] = [
                "index" => sprintf($group['mask'], $course),
                "course" => $course,
                "hours" => [
                    "done" => $group['hoursDone'],
                    "plan" => $plan,
                    "prc" => isset($plan[0]['total']) ? ($group['hoursDone'] / $plan[0]['total']) * 100 : 0
                ],
                "code" => $this->getGroupNamesByCodes($group["code"])
            ];
        }

        return $list;

    }

    public function getGroupGradeBook(int $gid): array
    {
        $sql = "SELECT wf_students.id,wf_students.last_name, wf_students.first_name";
        $sql .= ", AVG(wf_students_gradebook.mark) AS mark,wf_students_gradebook.sid";
        $sql .= " FROM wf_students";
        $sql .= " LEFT JOIN wf_students_gradebook ON (wf_students_gradebook.studentid=wf_students.id AND wf_students_gradebook.mark>0)";
        $sql .= " WHERE wf_students.gid=? GROUP BY last_name";

        $data = self::DB()->query($sql, [$gid]);
        if ($data->rowCount() > 0) {
            foreach ($data->fetchAll(2) as $student) {
                $result[$student['id']]["name"]=$student['last_name'] . " " . $student['first_name'];
                $result[$student['id']]["marks"][$student['sid']]=$student['mark'];
            }
            return $result;
        }
        return [];
        return self::DB()->query($sql, [$gid], true);

    }

    public function getSubjectHoursPlan(int $plan = null, array $sid, int $course): array
    {
        $plan = self::DB()->query("SELECT total FROM study_hours_plan WHERE course=:course AND plan=:plan AND sid IN(" . implode(",", $sid) . ")", ["plan" => $plan, "course" => $course], true);
        return $plan;

    }

    private function getSubjectIds(int $sid)
    {
        return array_map(function ($item) {
            return $item['id'];
        }, (self::DB()->query("SELECT id FROM wf_subjects WHERE id={$sid} OR grouped={$sid}", [], true)));
    }

    /**
     * @param array $list список груп у форматі [group_io=>codes]
     * @return array
     */
    public function getGroupNamesByCodes(string $list): array
    {
        foreach (self::DB()->query("SELECT code,title FROM study_licenses WHERE code IN(" . str_replace(";", ",", $list) . " )") as $item) {
            $result[$item['code']] = $item['title'];
        }
        return $result ?? [];
    }

    /**
     * Повертає список прежметів для групи
     * @param int $gid
     * @return void
     */
    public function getGroupSubjectsList(int $gid): array
    {
        $sql = "SELECT wf_timetable.sid,wf_subjects.name";
        $sql .= " FROM wf_timetable";
        $sql .= " RIGHT JOIN wf_subjects ON wf_subjects.id=wf_timetable.sid";
        $sql .= " WHERE wf_timetable.gid=? AND wf_subjects.grouped IS NULL GROUP BY wf_timetable.sid";

        $sql = "SELECT wf_timetable.sid,wf_subjects.name,wf_subjects.id";
//        $sql .= ",g_subjects.name,g_subjects.id";
        $sql .= " FROM wf_timetable";
        $sql .= " LEFT JOIN wf_subjects ON (wf_subjects.id=wf_timetable.sid)";
//        $sql .= " LEFT JOIN wf_subjects AS g_subjects ON g_subjects.id=wf_subjects.grouped";
        $sql .= " WHERE wf_timetable.gid=? GROUP BY wf_subjects.id,wf_subjects.grouped";

        return self::DB()->query($sql, [$gid], true);
    }

}

