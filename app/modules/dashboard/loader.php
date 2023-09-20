<?php
namespace App\Modules;

use Core\Views;

Class Dashboard extends Views{

    private static $instance = null;

    public static function start(){
        if (self::$instance === null) {
            self::$instance = new self();
        }

        $preSets = self::getPageRequest("p");

        return self::$preSets();

    }

    protected static function getContent($page="index"){

        include("views/" . $page . ".php");

    }

    /**
     * @param string $key ключ масиву
     * @return string
     */
    protected static function getPageRequest(string $key): string
    {

        $p = $_GET[$key] ?? "index";

        switch ($p){
            case("students"):
                return "students";
                break;
            case("users"):
                return "users";
            case("profile"):
                return "profile";
                break;
            default:
                return "index";
        }

    }

    protected static function index()
    {
        return [
            "module"=>self::$instance,
            "title"=>_("Dashboard"),
            "page"=>"index",
            "plugins"=>[
                "header"=>["DataTables"=>[],"fullcalendar"=>[]],
                "footer"=>["DataTables"=>[],"fullcalendar"=>[],"customJSCode"=>[
                    "code"=>"
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
        eventSources:['api.php?calendar=timetable',{googleCalendarId:'m0im41odup88meongd7m3b5odg@group.calendar.google.com'}],
        eventClick: function(info) {
                    info.jsEvent.preventDefault(); // don't let the browser navigate
                    $('#modal-calendar .modal-title').html(info.event.title);
                    $('#modal-calendar .modal-body').html(info.event.extendedProps.description);
                    $('#modal-calendar .lesson').attr('href','" . APP_URL_F . "/workflow?p=group&lesson=' + info.event.id);
                    $('#modal-calendar').modal();
                },
         businessHours: {
                  startTime: '08:00', // a start time
                  endTime: '17:00', // an end time
                },
        googleCalendarApiKey: 'AIzaSyDDoB7cMLOjeQ8dmgdfXlV16gFcBQXR5w8',
        events:{
            googleCalendarId:'m0im41odup88meongd7m3b5odg@group.calendar.google.com'
        }
    });
    calendar.render();
})"
                ]
                ]
            ]
        ];
    }

}


?>