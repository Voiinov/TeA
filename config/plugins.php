<?php
function googleFont($layout, $param)
{
    return "<!-- Google Font: Source Sans Pro -->
    <link rel=\"stylesheet\" href=\"https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback\">";
}

function fontAwesomeIcons($layout, $param)
{
    return "    <!-- Font Awesome -->
    <link rel=\"stylesheet\" href=\"" . APP_ASSETS_FOLDER . "/plugins/fontawesome-free/css/all.css\">";
}

function adminLTE($layout, $param)
{

    if ($layout == "header")
        return "    <!-- Theme style -->
        <link rel=\"stylesheet\" href=\"" . APP_ASSETS_FOLDER . "/css/adminlte.min.css\">";
    if ($layout == "footer")
        return "<script src=\"" . APP_ASSETS_FOLDER . "/js/adminlte.js\"></script>";

}

function jQuery($layout, $param)
{
    return "<!-- jQuery -->
    <script src=\"" . APP_ASSETS_FOLDER . "/plugins/jquery/jquery.min.js\"></script>";
}

function bootstrap($layout, $params)
{
    return "<!-- Bootstrap 4 -->\n<script src=\"" . APP_ASSETS_FOLDER . "/plugins/bootstrap/js/bootstrap.bundle.min.js\"></script>";
}

function app($layout, $params)
{
    return "<script src=\"" . APP_ASSETS_FOLDER . "/js/app.js\"></script>";
}

function jqueryValidation($layout, $params)
{
    return "<!-- query-validation -->\n<script src=\"" . APP_ASSETS_FOLDER . "/plugins/jquery-validation/jquery.validate.min.js\"></script>\n" .
        "<script src=\"" . APP_ASSETS_FOLDER . "/plugins/jquery-validation/jquery.validate.min.js\"></script>";
}

function customJSCode($layout, $params): string
{
    return "<script>" . $params["code"] . "</script>";
}

function Ionicons($layout)
{
    return "<!-- Font Awesome -->\n<link rel=\"stylesheet\" href=\"https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css\">";
}

/**
 * DataTables is a plug-in for the jQuery Javascript library. It is a highly flexible tool, built upon the foundations of progressive enhancement, that adds all of these advanced features to any HTML table.
 */
function DataTables($layout){
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
<script src=\"" . APP_ASSETS_FOLDER . "/plugins/datatables-buttons/js/buttons.colVis.min.js\"></script>";
}