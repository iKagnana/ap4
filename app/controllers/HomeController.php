<?php
class HomeController extends Controller
{
    public function index()
    {
        if (isset($_SESSION["userId"])) {
            $this->view("user/dashboard-view", ["lastname" => $_SESSION["userLastname"], "firstname" => $_SESSION["userFirstname"]]);
        } else {
            $this->view("user/login-view");
        }
    }
}