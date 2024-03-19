<?php
require_once ("../app/models/Provider.php");

class ProviderController extends Controller
{
    private $provider; # used for function db 
    public function __construct()
    {
        $this->provider = new Provider();
    }

    ##### provider list 

    /** method to display provider-view
     * @param []|null $extra optionnal
     */
    public function index($extra = null)
    {
        $res = $this->provider->getProvider();
        $allProvider = $res["data"];
        $error = $res["error"] ?? null;

        if (isset ($extra["error"])) {
            $error = $extra["error"];
        }

        $sendData = ["all" => $allProvider, "error" => $error];

        if (isset ($extra["searchName"])) {
            $filtered = $this->provider->searchProvider($extra["searchName"], $allProvider);
            $sendData = ["all" => $filtered];
        }

        if (isset ($extra["selected"])) {
            $sendData = array_merge($sendData, ["selected" => $extra["selected"]]);
        }
        $this->view("provider/provider-view", $sendData);
    }

    public function search()
    {
        $searchName = $_GET["search"];
        $this->index(["searchName" => $searchName]);
    }

    public function details()
    {
        $id = $_REQUEST["id"];
        $this->index(["selected" => $id]);
    }

    public function update()
    {
        $id = $_POST("id");
        $name = $_POST["name"];
        $email = $_POST["email"];
        $updateProvider = new Provider();
        $check = $updateProvider->setProvider($name, $email, $id);

        if (isset ($check["error"])) {
            $this->index(["error" => $check["error"]]);
            return;
        }

        $res = $updateProvider->updateProvider();
        $this->index(["error" => $res["error"] ?? null]);
    }

    public function delete()
    {
        $id = $_POST("id");
        $res = $this->provider->deleteProvider($id);
        $this->index(["error" => $res["error"] ?? null]);
    }

    ##### form

    public function form($extra = null)
    {
        if (isset ($extra["form"])) {
            $newProvider = $extra["form"];
        }
        if (isset ($extra["error"])) {
            $error = $extra["error"];
        }
        $this->view("provider/provider-form", ["error" => $error ?? null, "form" => $newProvider ?? null]);
    }

    public function create()
    {
        $name = $_POST["name"];
        $email = $_POST["email"];
        $provider = new Provider();
        $check = $provider->setProvider($name, $email);

        if (isset ($check["error"])) {
            $this->form(["error" => $check["error"], "form" => $provider]);
            return;
        }

        $res = $provider->createProvider();
        $this->index(["error" => $res["error"] ?? null]);
    }
}