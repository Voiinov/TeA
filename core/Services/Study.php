<?php

namespace Core\Services;

use Core\Database;

class Study
{
    private static $instance = null;

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function DB(): Database
    {
        return Database::getInstance();
    }

    /**
     * Повертає дату початку поточного навчального року
     * @return string
     */
    public function getStudyYearStartDate(): string
    {
        $Y = date('n') < 9 ? date("Y") - 1 : date("Y");
        return ("{$Y}-09-01");
    }

    public function getStudyHoursPlan($subject, $group, $course)
    {
//        foreach (self::DB()->query("SELECT"))
    }

    public function getCourse(string $date1, string $date2 = null): string
    {

        $date1 = new \DateTime($date1);
        $date2 = is_null($date2) ? new \DateTime("now") : new \DateTime($date2);

        $interval = date_diff($date1, $date2);

        return $interval->y + 1;
    }

    public function getActualHourPlansFull()
    {
        $data = [];

        $sql = "SELECT * FROM";
        $sql .= " study_hours_plan LEFT JOIN wf_groups ON wf_groups.hours_plan=study_hours_plan.plan ";
        $sql .= " WHERE wf_groups.close_date>now()";

        foreach (self::DB()->query($sql) as $plan) {
            $data[$plan['id']][$plan['course']][$plan['sid']] = [$plan['total'], $plan['1_semester'], $plan['2_semester']];
        }

        return $data;
    }

    public function getActualHourPlans($groups=null,$subjects=null)
    {
//        $data = [];

        $sql = "SELECT study_hours_plan.*,wf_groups.id AS gid FROM";
        $sql .= " study_hours_plan LEFT JOIN wf_groups ON wf_groups.hours_plan=study_hours_plan.plan ";
        $sql .= " WHERE wf_groups.close_date>now()";
        if(!is_null($groups))
            $sql .= " AND wf_groups.id IN($groups)";

        if(!is_null($subjects))
            $sql .= " AND study_hours_plan.sid IN($subjects)";

        $data = self::DB()->query($sql);
        return $data->fetchAll(\PDO::FETCH_ASSOC);
    }


}