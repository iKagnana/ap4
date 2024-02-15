<?php
/** class App 
 * - redirect to the right controller
 * - 
 */
class App
{
    protected $controller = "LoginController";
    protected $method = "index";
    protected $params = [];

    public function __construct()
    {
        $url = $this->parseUrl();

        # get controller
        if (file_exists("../app/controllers/" . ucfirst($url[0]) . "Controller.php")) {
            $this->controller = ucfirst($url[0]) . "Controller";
            unset($url[0]);
        }

        # set controller instance
        $this->controller = new $this->controller;

        # to process if multiple views or request are handle by the controller
        if (isset($url[1]) && $url[1] != "o") {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                $this->controller->{$this->method}();
                unset($url[1]);
            }
        } else {
            $this->controller->index();
        }
    }

    /** function that get the url
     * 
     */
    public function parseUrl()
    {
        if (isset($_GET["url"])) {
            return explode("/", $_GET["url"]); # return an array of string
        } else {
            return "login";
        }
    }
}