<?php

namespace App\Modules;

use Core\Database;
use Core\Services\Auth\Permission;
use Core\Services\Groups;
use Core\Views;

class Workflow extends Views
{
    private static $instance = null;
    private static string $page = "index";

    public static function start(array $get): array
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::getPageRequest($get);

    }

    protected static function getContent($page = "index"): void
    {
        if (Permission::pageAccess(self::$page))
            include("views/" . self::$page . ".php");
        else
            include(APP_PATH . "/views/errors/404.php");
    }

    private static function DB(): Database
    {
        return Database::getInstance();
    }

    /**
     * @param string $p ключ масиву
     * @return array
     */
    protected static function getPageRequest(array $get): array
    {
        $p = $get['p'] ?? "index";
        return match ($p) {
            "students" => self::students(),
            "users" => self::users($get),
            "groups" => self::groups(),
            "group" => self::group(),
            "hoursplan" => self::hoursplan(),
            "subjects" => self::subjectPage($get),
            default => self::index()
        };
    }

    protected static function subjectPage(array $get): array
    {
        return isset($_GET['sid']) ? self::subjects_info() : self::subjects();
    }

    protected static function pageClosed(): array
    {
        return [
            "module" => self::$instance,
            "title" => null,
            "page" => "404"
        ];
    }

    protected static function group(): array
    {
        $Groups = new Groups();
        $group = $Groups->getGroupById((int)$_GET['grade_book']);
        self::$page = "group";
        return [
            "module" => self::$instance,
            "title" => _("Group") . " " . $group["index"],
            "breadcrumbs" => [[_("Groups"), APP_URL_F . "/workflow?p=groups"], [_("Grade book")]],
            "plugins" => [
                "header" => ["DataTables" => []],
                "footer" => ["DataTables" => [], "customJSCode" => ["code" => '$(function () {
                table = new DataTable("#studentsList",{fixedColumns:{leftColumns: 1},
                   scrollX: true,scrollY: 300,select: true,autoWidth: true});
                table.on(\'init\',function(){
                table.buttons().container().appendTo("#studentsList_wrapper .col-md-6:eq(0)");
            })});', "src" => "/js/modules/group.js"]]
            ]
        ];
    }

    protected static function index(): array
    {
        self::$page = "index";
        return [
            "module" => self::$instance,
            "title" => _("Workflow"),
            "plugins" => [
                "header" => ["DataTables" => []],
                "footer" => ["DataTables" => [], "customJSCode" => ["code" => '$(function () {
                    new DataTable("#studentsList",{
                      "responsive": true, "lengthChange": false, "autoWidth": false,
                      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                    }).buttons().container().appendTo("#studentsList_wrapper .col-md-6:eq(0)");
                  });']
                ]
            ]
        ];
    }

    protected static function users(array $get): array
    {
        self::$page = "users";
        $sets = [
            "module" => self::$instance,
            "title" => _("Teachers"),
            "breadcrumbs" => [[_("Workflow"), APP_URL_F . "/workflow"], [_("Educators")]],
            "plugins" => [
                "header" => ["DataTables" => []],
                "footer" => ["DataTables" => [], "customJSCode" => ["code" => '$(function () {
                   new DataTable("#studentsList",{
                      "responsive": true, "lengthChange": false, "autoWidth": false,
                      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                    }).buttons().container().appendTo("#studentsList_wrapper .col-md-6:eq(0)");
                  });']
                ]
            ]
        ];
        if (isset($get['newuser'])) {
            self::$page = "users_new";
            $sets['title'] = _("Add new user");
            $sets['page'] = "users_new";
            $sets["breadcrumbs"] = [
                [_("Workflow"), APP_URL_F . "/workflow"], [_("Educators"), "workflow?p=users"], [_("Add new user")]
            ];
        }
        return $sets;
    }

    protected static function hoursplan()
    {
        self::$page = "hours_planning";
        return [
            "title" => _("Hours planning"),
            "module" => self::$instance
        ];
    }

    protected static function students(): array
    {
        self::$page = "students";
        return [
            "module" => self::$instance,
            "title" => _("Education seekers"),
            "breadcrumbs" => [[_("Workflow"), APP_URL_F . "/users"], [_("Education seekers")]],
            "plugins" => [
                "header" => ["DataTables" => []],
                "footer" => ["DataTables" => [], "customJSCode" => ["code" => '$(function () {
                    new DataTable("#studentsList",{
                      "responsive": true, "lengthChange": false, "autoWidth": false,
                      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                    }).buttons().container().appendTo("#studentsList_wrapper .col-md-6:eq(0)");
                  });']
                ]
            ]
        ];
    }

    protected static function groups(): array
    {
        self::$page = "groups";
        return [
            "module" => self::$instance,
            "title" => _("Groups"),
            "breadcrumbs" => [[_("Groups")]],
            "plugins" => [
                "header" => ["DataTables" => []],
                "footer" => ["DataTables" => [], "customJSCode" => ["code" => '$(function () {
                   new DataTable("#studentsList",{
                      "responsive": true, "lengthChange": false, "autoWidth": false,
                      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                    }).buttons().container().appendTo("#studentsList_wrapper .col-md-6:eq(0)");
                  });']
                ]
            ]
        ];
    }

    protected static function subjects(): array
    {
        self::$page = "subjects";
        return [
            "module" => self::$instance,
            "title" => _("Subjects"),
            "breadcrumbs" => [[_("Workflow"), APP_URL_F . "/workflow"], [_("Subjects")]],
            "plugins" => [
                "header" => ["DataTables" => []],
                "footer" => ["DataTables" => [], "customJSCode" => ["code" => '$(function () {
                    new DataTable("#studentsList",{
                      "responsive": true, "lengthChange": false, "autoWidth": false,
                      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                    }).buttons().container().appendTo("#studentsList_wrapper .col-md-6:eq(0)");
                  });']
                ]
            ]
        ];
    }

    protected static function subjects_info(): array
    {
        $link = APP_URL_F . "/workflow";
        $subject = self::DB()->query("SELECT name, shortname FROM wf_subjects WHERE id=:id LIMIT 1", ["id" => (int)$_GET['sid']], true);
        self::$page = "subjects_info";
        return [
            "module" => self::$instance,
            "subject" => "sime",
            "title" => $subject[0]['name'],
            "breadcrumbs" => [[_("Workflow"), $link], [_("Subjects"), $link . "?p=subjects"], [$subject[0]['shortname']]],
            "plugins" => [
                "header" => ["DataTables" => [], "fullcalendar" => []],
                "footer" => ["DataTables" => [], "fullcalendar" => [], "customJSCode" => ["code" => "$(function () {
                    var date = new Date()
    var d = date.getDate(),
    m = date.getMonth(),
    y = date.getFullYear()
    
    var Calendar = FullCalendar.Calendar;
    var Draggable = FullCalendar.Draggable;
    
    var containerEl = document.getElementById('external-events');
    var checkbox = document.getElementById('drop-remove');
    var calendarEl = document.getElementById('calendar');
    
    var calendar = new Calendar(calendarEl, {
                        locale:'" . APP_LANG . "',
        headerToolbar: {
                            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        themeSystem: 'bootstrap',
        eventSources:['api.php?calendar=timetableforsubject&sid=" . $_GET['sid'] . "']
    });
    calendar.render();
})"]
                ]
            ]
        ];
    }
}


?>