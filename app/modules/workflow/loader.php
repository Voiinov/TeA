<?php

namespace App\Modules;

use Core\Database;
use Core\Services\Auth\Permission;
use Core\Views;

class Workflow extends Views
{
    private static $instance = null;

    public static function start()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        $preSets = self::getPageRequest("p");

//        if(!method_exists("Workflow",$preSets)) {
//            return ["title"=>_("This page doesn't seem to exist"),"module" => self::$instance];
//        }

        return self::$preSets();

    }

    protected static function getContent($page = "index")
    {
        if ($page == "404")
            include(APP_PATH . "/views/errors/404.php");
        else
            include("views/" . $page . ".php");
    }

    private static function DB(): Database
    {
        return Database::getInstance();
    }

    /**
     * @param string $key ключ масиву
     * @return string
     */
    protected static function getPageRequest(string $key): string
    {

        $p = $_GET[$key] ?? "index";

        switch ($p) {
            case("students"):
                $page = "students";
                break;
            case("users"):
                $page = "users";
                break;
            case("groups"):
                $page = "groups";
                break;
            case("group"):
                $page = "group";
                break;
            case("hoursplan"):
                $page = "hoursplan";
                break;
            case("subjects"):
                $page = isset($_GET['sid']) ? "subjects_info" : "subjects";
                break;
            default:
                $page = "index";
        }

        return Permission::pageAccess($page) ? $page : "pageClosed";

    }

    protected static function pageClosed()
    {
        return [
            "module" => self::$instance,
            "title" => null,
            "page" => "404"
        ];
    }

    protected static function group()
    {
        return [
            "module" => self::$instance,
            "title" => _("Group"),
            "page" => "group",
            "plugins" => [
                "header" => ["DataTables" => []],
                "footer" => ["DataTables" => [], "customJSCode" => ["code" => '$(function () {
                table = new DataTable("#studentsList");
                table.on(\'init\',function(){
                table.buttons().container().appendTo("#studentsList_wrapper .col-md-6:eq(0)");
            })});',"src" => "/js/modules/group.js"]]
                ]
        ];
    }

    protected static function index(): array
    {
        return [
            "module" => self::$instance,
            "title" => _("Workflow"),
            "page" => "index",
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

    protected static function users(): array
    {
        $sets = [
            "module" => self::$instance,
            "title" => _("Teachers"),
            "page" => "users",
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
        if (isset($_GET['newuser'])) {
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
        return [
            "title" => _("Hours planning"),
            "module" => self::$instance,
            "page" => "hours_planning"
        ];
    }

    protected static function students(): array
    {
        return [
            "module" => self::$instance,
            "title" => _("Education seekers"),
            "page" => "students",
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
        return [
            "module" => self::$instance,
            "title" => _("Groups"),
            "page" => "groups",
            "breadcrumbs" => [[_("Workflow"), APP_URL_F . "/groups"], [_("Groups")]],
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
        return [
            "module" => self::$instance,
            "title" => _("Subjects"),
            "page" => "subjects",
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
        return [
            "module" => self::$instance,
            "subject" => "sime",
            "title" => $subject[0]['name'],
            "page" => "subjects_info",
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