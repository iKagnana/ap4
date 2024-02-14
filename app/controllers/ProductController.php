<?php
include("app/models/product.class.php");
$product = new Product();

switch ($_REQUEST["action"]) {
    case "displayProductMenu":
        $allProducts = $product->getProducts();
        # get product and display view
        include("app/views/product/products_view.php");
        break;
    case "displayFormProduct":
        ## test if the user has access
        if (!isset($_SESSION["user"])) {
            echo "Pas d'utilisateur connecté";
        }
        if ($_SESSION["user"]->role != 0) {
            include("app/views/no_authorized_view.php");
            break;
        }
        $allCategories = $product->getCategories();
        # display form to add product
        include("app/views/product/form_product_view.php");
        break;
    case "createProduct":
        # force reload ? TODO :  test if needed
        include("app/views/product/products_view.php");

        $name = $_POST["name"];
        $price = $_POST["price"];
        $stock = $_POST["stock"];
        $access_level = $_POST["access_level"];
        $category = $_POST["category"];
        $newProduct = new Product();
        $newProduct->setProduct($name, $price, $stock, $access_level, $category);
        $newProduct->createProduct();

}