<?php
/** class Controller, will be inherited
 * return the rifgt model and display the right view
 */

class Controller
{
    # no method __construct

    /** function model that return the right model
     * @param string $model
     */
    public function model($model)
    {
        require_once ("../app/models/" . $model . ".php");
        return new $model;
    }

    /** function view that return the right view
     * @param string $view don't forget to pass the right path
     * @param [] $data if we need to pass data to the view
     */
    public function view($view, $data = [])
    {
        $viewPath = "../app/views/" . $view . ".php";
        if (file_exists($viewPath)) {
            require_once ($viewPath);
        } else {
            die ("La vue n'existe pas ");
        }
    }

    /** function to test if a user can execute request or method
     * @param string $testable role to test
     * ["admin", "adminNUser", "user", "client"]
     */
    public function checkAccess($testable)
    {
        $role = $_SESSION["userRole"] ?? null;
        if (!isset ($role)) {
            return false;
        }

        return match ($testable) {
            "admin" => $role == 0,
            "adminNUser" => $role < 2,
            "user" => $role == 1,
            "client" => $role == 2,
        };
    }
}