<?php require_once("../app/views/header-view.php"); ?>
<div class="page-container">
    <div class="flex-col-center">
        <form class="item-container" action="http://localhost:8089/product">
            <button type="submit">Retour</button>
        </form>

        <form class="item-container" action="http://localhost:8089/product/update" method="post">
            <?php
            $product = $data["selected"];
            echo "<input hidden name='id' value=$product->id>";
            echo "<div class='flex-column item-container'>
                <label for='name'>Libellé</label> 
                <input type='text' name='name' value=" . $product->name . ">
            <diV>";
            echo "<div class='flex-column item-container'>
            <label for='price'>Prix à l'unité €</label> 
            <input type='number' min='0,01' step='0.01' name='price' value=" . $product->price . ">
            <diV>";
            echo "
            <div class='flex-column item-container'>
                <label for='name'>Stock</label> 
                <input type='number' min='0' name='stock' value=" . $product->stock . ">
                <span>Attention il n'est pas conseillé de modifier cette valeur.</span>
            </div>";
            echo "<div class='flex-column item-container'>
            <label for='access_level'>Niveau d'accès</label> 
            <input type='number' min='1' name='access_level' value=" . $product->access_level . ">
            </div>";
            echo "<div class='flex-column item-container'>
            <label for='category'>Catégorie</label> 
            <select class='item-container' name='category'>";
            foreach ($data["categories"] as $cat) {
                echo $product->category["id"] = $cat["id_cat"] ? "<option selected value=" . $cat["id_cat"] . ">" . $cat["name_cat"] . "</option>" : "<option value=" . $cat["id_cat"] . ">" . $cat["name_cat"] . "</option>";
            }
            echo "</select>
            </div>";
            ?>
            <button type="submit">Valider</button>
        </form>

        <form action="http://localhost:8089/product/delete" method="post">
            <?php
            $product = $data["selected"];
            echo "<input hidden name='id' value=$product->id>"
                ?>
            <button type="submit">Supprimer</button>
        </form>
    </div>
</div>
<?php require_once("../app/views/footer-view.php"); ?>