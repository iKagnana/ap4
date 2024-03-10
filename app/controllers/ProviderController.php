<?php
require_once("../app/models/Provider.php");

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
        $allProvider = $this->provider->getProvider();
        $sendData = ["all" => $allProvider];

        if (isset($extra["searchName"])) {
            $filtered = $this->provider->searchProvider($extra["searchName"], $allProvider);
            $sendData = ["all" => $filtered];
        }

        if (isset($extra["selected"])) {
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
        $updateProvider->setProvider($name, $email, $id);
        $updateProvider->updateProvider();
        $this->index();
    }

    public function delete()
    {
        $id = $_POST("id");
        $this->provider->deleteProvider($id);
        $this->index();
    }

    ##### form

    public function form()
    {
        $this->view("provider/provider-form");
    }

    public function create()
    {
        $name = $_POST["name"];
        $email = $_POST["email"];
        $provider = new Provider();
        $provider->setProvider($name, $email);
        $provider->createProvider();
        $this->index();
    }
}