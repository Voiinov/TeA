<?php

namespace App\Modules;

use Core\Services\Options;
use Core\Views;

class Dashboard extends Views
{

    private static $instance = null;
    private static string $page = "index";

    public static function start(array $get): array
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        $p = $get['p'] ?? "index";

        return self::getPageRequest($p);

    }

    protected static function getContent($page = "index"): void
    {

        include("views/" . self::$page . ".php");

    }

    /**
     * @param string $p ключ масиву
     * @return array
     */
    protected static function getPageRequest(string $p): array
    {

        return match ($p) {
            "hours" => self::hoursPrint(),
            default => self::index()
        };
    }

    protected static function hoursPrint(): array
    {
        self::$page = "hours";
        return [
            "module" => self::$instance,
            "title" => "Вичитка годин",
            "page" => "hours"
        ];
    }

    protected static function index(): array
    {
        $options = new Options();
        return [
            "module" => self::$instance,
            "title" => _("Dashboard"),
            "page" => "index",
            "plugins" => [
                "header" => ["DataTables" => [], "fullcalendar" => []],
                "footer" => ["DataTables" => [], "fullcalendar" => [], "customJSCode" => [
                    "code" => "
$(function () {
    var date = new Date()
    var d = date.getDate(),
    m = date.getMonth(),
    y = date.getFullYear()
    
    var Calendar = FullCalendar.Calendar;
//    var Draggable = FullCalendar.Draggable;
    
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
        footerToolbar: {left: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'},
        headerToolbar: {start: 'title',center: '',end: 'today prev,next'},
        initialView: 'listWeek',
        firstDay: 1,
        themeSystem: 'bootstrap',
        eventSources:[
            'api.php?calendar=timetable',
             {
                googleCalendarId:'m0im41odup88meongd7m3b5odg@group.calendar.google.com',
                borderColor:'#c49300',
                textColor:'white',
                backgroundColor:'#ffc107',
                eventClick: function(info){ alert('test') }
            }],
        eventClick: function(info) {
            calendarEventModal(info)
                },
         businessHours: {
                  startTime: '08:00', // a start time
                  endTime: '17:00', // an end time
                },
        googleCalendarApiKey: '" . $options->getOptionValue("googleCalendarApiKey") . "',
       
    });
    calendar.render();
})"
                ]
                ]
            ]
        ];
    }

}
