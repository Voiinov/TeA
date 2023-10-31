<?php

namespace Core\Services;

use Core\Database;
use Core\Services\Auth\Auth;
use Core\Services\Groups;
use Core\Services\Study;

class Calendar
{
    private array $options;
    private Groups $groups;

    public function __construct()
    {
        $this->groups = new Groups();
    }

    private static function DB(): Database
    {
        return Database::getInstance();
    }

    private function STD(): Study
    {
        return Study::getInstance();
    }

    public function get(string $calendar = "timetable", array $options = [])
    {
        if (method_exists($this, $calendar)) {
            $this->options = $options;
            return $this->$calendar();
        }
        return null;
    }

    private function getRqstArr(): array
    {
        return ["start" => $this->options['start'], "end" => $this->options['end'], "uid" => Auth::userID()];
    }

    private function timetable(): array
    {
        $data = [];
        foreach ($this->getTimetable() as $item) {
            $groupIndex = sprintf($item['mask'], self::STD()->getCourse($item['open_date'], $item['end']));
            $data[] = [
                "id" => $item["id"],
                "title" => $item["title"] . " (" . $groupIndex . " група)",
                "start" => $item['start'],
                "description" => $this->getCalendarDescriptionText($item),
                "end" => $item['end'],
                "gid" => $item['gid'],
                "url"=> APP_URL_F . "/workflow?p=group&lesson=" . $item["id"],
                "group" => $groupIndex,

            ];
        }
        return $data;
    }

    private function getCalendarDescriptionText($data): string
    {
        $sid = $data['grouped'] !==null ? $data['grouped'] : $data["sid"];
        return "
<div class='row'>
<div class='col-6'>
<div class='text-right'>" . _("Start time") . "</div>
<div class='text-right text-danger'><h4>" . date("H:i", strtotime($data['start'])) . "</h4></div>
</div>
<div class='col-6'>
<div>" . _("End time") . "</div>
<div class='text-success'><h4>" . date("H:i", strtotime($data['end'])) . "</h4></div>
</div>
</div>
<a href='" . APP_URL_F . "/workflow?p=group&grade_book=" . $data['gid'] . "&sid={$sid}' type=\"button\" class=\"btn btn-danger btn-block btn-sm\"><i class=\"fa fa-book\"></i> " . _("Assessment") . " </a>
";
    }

    private function timetableforsubject()
    {
        $data = [];
        foreach ($this->getTitmetableBySubject($this->options['sid']) as $item) {
            $data[] = [
                "id" => $item["id"],
                "title" => $item["title"],
                "start" => $item['start'],
                "end" => $item['end']
            ];
        }
        return $data;
    }

    private function getTimetable($uid = null)
    {

        $sql = "SELECT wf_timetable.*,wf_subjects.name AS title, wf_groups.open_date, wf_groups.mask,wf_subjects.grouped";
        $sql .= " FROM wf_timetable";
        $sql .= " LEFT JOIN wf_subjects ON wf_timetable.sid=wf_subjects.id";
        $sql .= " LEFT JOIN wf_groups ON wf_timetable.gid=wf_groups.id";
        $sql .= " WHERE uid=:uid AND start>=:start AND end<=:end";

        return self::DB()->query($sql, $this->getRqstArr(), true);

    }

    private function getTitmetableBySubject($sid)
    {
        $ids = implode(",", array_map(function ($item) {
            return $item['id'];
        }, (self::DB()->query("SELECT id FROM wf_subjects WHERE id=" . $sid . " OR grouped=" . $sid, [], true))));
        $sql = "SELECT wf_timetable.*,wf_subjects.name AS title, wf_groups.open_date, wf_groups.mask";
        $sql .= " FROM wf_timetable";
        $sql .= " LEFT JOIN wf_subjects ON wf_timetable.sid=wf_subjects.id";
        $sql .= " LEFT JOIN wf_groups ON wf_timetable.gid=wf_groups.id";
        $sql .= " WHERE wf_timetable.sid IN($ids) AND start>=? AND end<=?";

        return self::DB()->query($sql, [$this->options['start'], $this->options['end']], true);
    }

}