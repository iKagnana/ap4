<?php
# define root for file import
define("ROOT", dirname(__DIR__));
# start session 
session_start();

# import needed file
require_once("../app/core/Database.php");
require_once("../app/core/App.php");
require_once("../app/core/Controller.php");
require_once("../app/controllers/LoginController.php");

include("../app/views/header-view.php");
$app = new App();
include("../app/views/footer-view.php");