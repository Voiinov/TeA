<?php

namespace Core\Services;

use Core\Database;
use Core\Services\User;
use Core\Services\Study;

class Subjects
{
    private User $user;
    private Study $study;

    public function __construct()
    {
        $this->user = new User();
        $this->study = new Study();
    }


    private function DB(): Database
    {
        return Database::getInstance();
    }

    /**
     * @param int $status статус предмета (1 - активний, 0- неактивний)
     * @return array
     */
    public function getList(int $status = 1): array
    {
        $sql = "SELECT wf_subjects.id,wf_subjects.name,wf_subjects.shortname,wf_subjects.grouped";
        $sql .= ",tbl_conn.uid, users.username, users.gender";
        $sql .= " FROM wf_subjects";
        $sql .= " LEFT JOIN tbl_conn ON(tbl_conn.table_name = :table_name AND tbl_conn.tid=wf_subjects.id)";
        $sql .= " LEFT JOIN users ON users.id = tbl_conn.uid";
        $sql .= " WHERE wf_subjects.status=:status GROUP BY wf_subjects.id,tbl_conn.uid ORDER BY Name ASC";

        $result = $this->DB()->query($sql,
            [
                "status" => $status,
                "table_name" => "wf_subjects"
            ], true);

        return $result > 0 ? $this->subjectsListConstruct($result) : [];

    }

    public function getSubjectInfo(int $sid, bool $subonly = false)
    {
        $sql = "SELECT * FROM wf_subjects WHERE id=:id";
        if ($subonly === false)
            $sql .= " OR grouped=:id";

        $sql .= " GROUP BY id LIMIT 1";

        return self::DB()->query("SELECT * FROM wf_subjects WHERE id=:id LIMIT 1", ["id" => (int)$_GET['sid']], true);
    }

    private function subjectsListConstruct($list): array
    {
        $newList = [];
        foreach ($list as $subject) {
            if (!is_null($subject['grouped'])) {
                $subjectID = $subject['grouped'];
                $newList[$subject['grouped']]['sub'][$subject['id']] = [
                    'name' => $subject['name'],
                    'shortname' => $subject['shortname']
                ];
            } else {
                $subjectID = $subject['id'];
                $newList[$subject['id']] = [
                    'name' => $subject['name'],
                    'shortname' => $subject['shortname']
                ];
            }
            if (!is_null($subject['uid']))
                $newList[$subjectID]['users'][$subject['uid']] = ["username" => $subject['username'], "ava" => User::avatar($subject['uid'], $subject['gender'])];
        }
        return $newList;
    }

    public function subjectUsersList(int $sid, string $styear = "actual")
    {
        $result = [];

        $sql = "SELECT users.username,users.contacts,users.email, users.id, options.value AS post FROM users";
        $sql .= " LEFT JOIN wf_timetable ON wf_timetable.uid = users.id";
        $sql .= " LEFT JOIN options ON options.id = users.post";

        $sql .= " WHERE wf_timetable.sid=:sid";

        if ($styear == "actual")
            $sql .= " AND wf_timetable.start>=:date";

        $sql .= " GROUP BY users.id";

        $result = self::DB()->query($sql, ["sid" => $sid, "date" => $this->study->getStudyYearStartDate()], true);

        return $result;
    }

}