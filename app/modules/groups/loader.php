<?php 
namespace App\Modules;

use App\Helper\Builder;
use Core\Views;

Class Groups extends Views{

    private static $instance = null;

    public static function start(){
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return [
            "module"=>self::$instance,
            "title"=>"Групи",
            "plugins"=>[
                "header"=>["DataTables"=>[]],
                "footer"=>["DataTables"=>[],"customJSCode"=>["code"=>'$(function () {
                $("#example1").DataTable({
                  "responsive": true, "lengthChange": false, "autoWidth": false,
                  "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                }).buttons().container().appendTo("#example1_wrapper .col-md-6:eq(0)");
                $("#example2").DataTable({
                  "paging": true,
                  "lengthChange": false,
                  "searching": false,
                  "ordering": true,
                  "info": true,
                  "autoWidth": false,
                  "responsive": true,
                });
              });']]
                ]
        ];
        
    }

    protected static function getContent($page="index"){

        if(!method_exists("Journal",$page))
            $page = "index";

        return self::$page();

    }

    protected static function index(){
        include_once("views/index.php");
    }

}


?>