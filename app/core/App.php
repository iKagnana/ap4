<?php
/** class App 
 * - redirect to the right controller
 */
class App
{
    protected $controller = "HomeController";
    protected $method = "index";
    protected $params = [];

    public function __construct()
    {
        $url = $this->parseUrl();

        # get controller
        if (file_exists("../app/controllers/" . ucfirst($url[0]) . "Controller.php")) {
            $this->controller = ucfirst($url[0]) . "Controller";

            if (!$this->checkRole($this->controller)) {
                $this->controller = "ErrorController";
            }

            unset($url[0]);
        } else {
            die ("La vue n'existe pas.");
        }

        # set controller instance
        $this->controller = new $this->controller;

        # to process if multiple views or request are handle by the controller
        if (isset ($url[1]) && $url[1] != "o") {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                $this->controller->{$this->method}();
                unset($url[1]);
            }
        } else {
            $this->controller->index();
        }
    }

    /** function that get the url and separed the different section in url
     */
    public function parseUrl()
    {
        if (isset ($_GET["url"])) {
            return explode("/", $_GET["url"]); # return an array of string
        } else {
            return "login";
        }
    }

    /** private func to check if user has access to the page
     * @param string $controller
     * @return bool if user has access
     */
    private function checkRole($controller)
    {
        $role = $_SESSION["userRole"] ?? null;

        if (!isset ($role)) {
            return match ($controller) {
                "LoginController" => true,
                "UserController" => true, #check will be done inside controller
                "HomeController" => true, #check will be done inside controller
                default => false,
            };
        } else {
            return match ($controller) {
                "ProviderController" => $role == 0 ? true : false,
                "ProductController" => $role < 2 ? true : false,
                "UserController" => true, #check will be done inside controller
                "OrderController" => true, #check will be done inside controller
                "LoginController" => true,
                "HomeController" => true,
            };
        }

    }
}