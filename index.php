<?php
session_start();

# controller to use
if (!isset($_REQUEST["action"])) {
    $_REQUEST["controller"] = "login";
    $_REQUEST["action"] = "goToLogin";
}

$controller = $_REQUEST["controller"];

# hhtml header
include("app/views/header_view.php");

# handle controller. 
# Controller is like a router and make connexion between db and view

switch ($controller) {
    case "login":
        include("app/controllers/login_controller.php");
        break;
    case "product":
        include("app/controllers/product_controller.php");
        break;
    default:
        break;
}
#html footer
include("app/views/footer_view.php");