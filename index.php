<?php
require_once("service/db_service.inc.php");
include("views/login_view.php");

# declare in order to be use in all the app
try {
    $dbService = DbService::getSelfService();
} catch (Exception $e) {
    echo "oops" . $e->getMessage();
}

$controller = $_REQUEST["page"];
# handle controller 
switch ($controller) {
    case "login":
        include("controllers/login_controller.php");
    default:
        break;
}
?>