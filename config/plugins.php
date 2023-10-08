<?php
function googleFont($layout, $param):string
{
    return "<!-- Google Font: Source Sans Pro -->
    <link rel=\"stylesheet\" href=\"https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback\">";
}

function fontAwesomeIcons($layout, $param):string
{
    return "    <!-- Font Awesome -->
    <link rel=\"stylesheet\" href=\"" . APP_ASSETS_FOLDER . "/plugins/fontawesome-free/css/all.css\">";
}

function adminLTE($layout, $param):string
{
    if ($layout == "header")
        return "    <!-- Theme style -->
        <link rel=\"stylesheet\" href=\"" . APP_ASSETS_FOLDER . "/css/adminlte.css\">
        <link rel=\"stylesheet\" href=\"" . APP_ASSETS_FOLDER . "/css/style.css\">";
//    if ($layout == "footer")
    return "<script src=\"" . APP_ASSETS_FOLDER . "/js/adminlte.js\"></script>";

}

function jQuery($layout, $param):string
{
    return "<!-- jQuery -->
    <script src=\"" . APP_ASSETS_FOLDER . "/plugins/jquery/jquery.min.js\"></script>";
}

function bootstrap($layout, $params):string
{
    return "<!-- Bootstrap 4 -->\n<script src=\"" . APP_ASSETS_FOLDER . "/plugins/bootstrap/js/bootstrap.bundle.min.js\"></script>";
}

function app($layout, $params):string
{
    return "<script src=\"" . APP_ASSETS_FOLDER . "/js/app.js\"></script>";
}

function jqueryValidation($layout, $params):string
{
return "<!-- query-validation -->\n<script src=\"" . APP_ASSETS_FOLDER . "/plugins/jquery-validation/jquery.validate.min.js\"></script>\n<script src=\"" . APP_ASSETS_FOLDER . "/plugins/jquery-validation/additional-methods.min.js\"></script>";
}

function customJSCode($layout, $params): string
{
    if (isset($params["code"])) {
        return "<script>" . $params["code"] . "</script>";
    }

    if (isset($params["src"])) {
        return '<script src="' . APP_ASSETS_FOLDER . $params["src"] . '"></script>';
    }
    return "";

}

function Ionicons($layout):string
{
    return "<!-- Font Awesome -->\n<link rel=\"stylesheet\" href=\"https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css\">";
}

/**
 * DataTables is a plug-in for the jQuery Javascript library. It is a highly flexible tool, built upon the foundations of progressive enhancement, that adds all of these advanced features to any HTML table.
 */
function DataTables($layout):string
{
    if ($layout == "header")
        return "
        <!-- DataTables -->
        <link rel=\"stylesheet\" href=\"" . APP_ASSETS_FOLDER . "/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css\">
        <link rel=\"stylesheet\" href=\"" . APP_ASSETS_FOLDER . "/plugins/datatables-responsive/css/responsive.bootstrap4.min.css\">
        <link rel=\"stylesheet\" href=\"" . APP_ASSETS_FOLDER . "/plugins/datatables-buttons/css/buttons.bootstrap4.min.css\">";
    if ($layout == "footer")
        return "<!-- DataTables  & Plugins -->
<script src=\"" . APP_ASSETS_FOLDER . "/plugins/datatables/jquery.dataTables.min.js\"></script>
<script src=\"" . APP_ASSETS_FOLDER . "/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js\"></script>
<script src=\"" . APP_ASSETS_FOLDER . "/plugins/datatables-responsive/js/dataTables.responsive.min.js\"></script>
<script src=\"" . APP_ASSETS_FOLDER . "/plugins/datatables-responsive/js/responsive.bootstrap4.min.js\"></script>
<script src=\"" . APP_ASSETS_FOLDER . "/plugins/datatables-buttons/js/dataTables.buttons.min.js\"></script>
<script src=\"" . APP_ASSETS_FOLDER . "/plugins/datatables-buttons/js/buttons.bootstrap4.min.js\"></script>
<script src=\"" . APP_ASSETS_FOLDER . "/plugins/jszip/jszip.min.js\"></script>
<script src=\"" . APP_ASSETS_FOLDER . "/plugins/pdfmake/pdfmake.min.js\"></script>
<script src=\"" . APP_ASSETS_FOLDER . "/plugins/pdfmake/vfs_fonts.js\"></script>
<script src=\"" . APP_ASSETS_FOLDER . "/plugins/datatables-buttons/js/buttons.html5.min.js\"></script>
<script src=\"" . APP_ASSETS_FOLDER . "/plugins/datatables-buttons/js/buttons.print.min.js\"></script>
<script src=\"" . APP_ASSETS_FOLDER . "/plugins/datatables-buttons/js/buttons.colVis.min.js\"></script>
<script>Object.assign(DataTable.defaults, {
     'responsive': false,
     'lengthChange': true,
     'autoWidth': false,
     'buttons':['excel', 'pdf', 'print', 'colvis'],
     'language': {
        'url':'" . APP_ASSETS_FOLDER . "/plugins/datatables/localization/" . APP_LANG . ".json'
      }
})</script>";
}

function fullcalendar($layout):string
{
    return "<!-- fullCalendar -->
<script src=\"" . APP_ASSETS_FOLDER . "/plugins/moment/moment.min.js\"></script>
<script src=\"" . APP_ASSETS_FOLDER . "/plugins/fullcalendar/index.global.min.js\"></script>
<script src=\"" . APP_ASSETS_FOLDER . "/plugins/fullcalendar/locales-all.min.js\"></script>";

}

function toastr($layout, $param): string
{
    if ($layout == "header")
        return "    <!-- Toastr -->
        <link rel=\"stylesheet\" href=\"" . APP_ASSETS_FOLDER . "/plugins/toastr/toastr.css\">";
    return "    <!-- Toastr -->\n<script src=\"" . APP_ASSETS_FOLDER . "/plugins/toastr/toastr.min.js\"></script>";
}

function sweetalert2($layout, $param): string
{
    return "    <!-- SweetAlert2  -->\n<script src=\"" . APP_ASSETS_FOLDER . "/plugins/sweetalert2/sweetalert2.min.js\"></script>";
}