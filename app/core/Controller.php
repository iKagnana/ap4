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
        require_once("../app/models/" . $model . ".php");
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
            require_once($viewPath);
        } else {
            die("La vue n'existe pas ");
        }
    }
}