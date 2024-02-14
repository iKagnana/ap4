<?php
$action = $_REQUEST["action"];
$allProducts = $dbService->getProducts();

switch ($action) {
    case "displayProductMenu":
        # get product and display view
        include("views/products_view.php");
        break;
    case "createProduct":
        # force reload ? TODO :  test if needed
        include("views/products_view.php");

        $name = $_POST["name"];
        $price = $_POST["price"];
        $stock = $_POST["stock"];
        $access_level = $_POST["access_level"];
        $category = $_POST["category"];
        $newProduct = new Product($name, $price, $stock, $access_level, $category);

        $dbService->createProduct($newProduct);

}