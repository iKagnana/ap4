<?php
# define root for file import
define("ROOT", dirname(__DIR__));
# start session 
session_start();

# import needed file core
require_once("../app/core/Database.php");
require_once("../app/core/App.php");
require_once("../app/core/Controller.php");
# import controller
require_once("../app/controllers/LoginController.php");
require_once("../app/controllers/ProductController.php");
require_once("../app/controllers/HomeController.php");
require_once("../app/controllers/OrderController.php");

$app = new App();