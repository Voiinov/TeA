<?php

namespace Core\Services;

use Core\Database;

class Study
{
    // день початку навчального року
    private int $beginDay = 1;
    // місяць почтку навчального року
    private int $beginMonth = 9;
    // день закінчення навчального року
    private int $endDay = 30;
    // місяць закінчення навчального року
    private int $endMonth = 6;

    private int $sYear;
    public string $beginStudy;
    public string $endStudy;
    public string $endFirstSemesterStudy;
    public string $beginSecondSemesterStudy;
    private static $instance = null;


    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct()
    {

        $this->setStudyYear($this->getStudyYearStartDate("Y"));
        $this->getStudyPeriod();

    }

    public function setStudyYear($year)
    {
        $this->sYear = $year;
    }

    private function getStudyPeriod()
    {
        $data = self::DB()->query("SELECT * FROM study_year WHERE YEAR(start)=?", [$this->sYear]);
        if ($data->rowCount() > 0) {
            $year = $data->fetchAll(\PDO::FETCH_ASSOC);
            $this->beginStudy = $year[0]['start'];
            $this->endStudy = $year[0]['end'];
            $this->endFirstSemesterStudy =  $this->sYear . "-12-31";
            $this->beginSecondSemesterStudy =  $this->sYear+1 . "-01-01";
        } else {
            $this->beginStudy = implode("-", [$this->sYear, $this->beginMonth, $this->beginDay]);
            $this->endStudy = implode("-", [$this->sYear + 1, $this->beginMonth, $this->beginDay]);
            $this->endFirstSemesterStudy =  $this->sYear . "-12-31";
            $this->beginSecondSemesterStudy =  $this->sYear+1 . "-01-01";
        }

    }

    public function getCurrentStudyPeriod(): array
    {
        return date("Y")>$this->sYear ? [$this->beginSecondSemesterStudy,$this->endStudy] : [$this->beginStudy,$this->endFirstSemesterStudy] ;
    }

    private function DB(): Database
    {
        return Database::getInstance();
    }

    /**
     * Повертає дату початку поточного навчального року
     * @param string $format формат дати
     * @return string
     */
    public function getStudyYearStartDate(string $format = "Y-m-d"): string
    {
        $Y = date('n') < $this->beginMonth ? date("Y") - 1 : date("Y");
        return date($format, mktime(0, 0, 0, $this->beginMonth, $this->beginDay, $Y));
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

    public function getActualHourPlans($groups = null, $subjects = null)
    {
//        $data = [];

        $sql = "SELECT study_hours_plan.*,wf_groups.id AS gid FROM";
        $sql .= " study_hours_plan LEFT JOIN wf_groups ON wf_groups.hours_plan=study_hours_plan.plan ";
        $sql .= " WHERE wf_groups.close_date>now()";
        if (!is_null($groups))
            $sql .= " AND wf_groups.id IN($groups)";

        if (!is_null($subjects))
            $sql .= " AND study_hours_plan.sid IN($subjects)";

        $data = self::DB()->query($sql);
        return $data->fetchAll(\PDO::FETCH_ASSOC);
    }


}