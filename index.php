<?php
require_once("service/db_service.inc.php");
session_start();
# declare in order to be use in all the app
$dbService = DbService::getSelfService();

# controller to use
if (!isset($_REQUEST["action"])) {
    $_REQUEST["controller"] = "login";
    $_REQUEST["action"] = "goToLogin";
}

$controller = $_REQUEST["controller"];

# hhtml header
include("views/header_view.php");

# handle controller. 
# Controller is like a router and make connexion between db and view

switch ($controller) {
    case "login":
        include("controllers/login_controller.php");
        break;
    case "product":
        include("controllers/product_controller.php");
        break;
    default:
        break;
}
#html footer
include("views/footer_view.php");