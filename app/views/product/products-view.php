<?php require_once("../app/views/header-view.php"); ?>
<div class="page-container">
    <div class="flex-col-container">
        <div class="flex-row-container">
            <form action="http://localhost:8089/product/searchProduct" method="GET">
                <label for=" searchbar">Rechercher le nom</label>
                <input type="text" name="search" id="searchbar">
                <input type="submit" value="🔍">
            </form>

            <form action="http://localhost:8089/product/filter" method="GET">
                <?php
                if ($data["order"] == "asc") {
                    echo "<button name='order' value='desc'>↑</button>";
                } else {
                    echo "<button name='order' value='asc'>↓</button>";
                }
                ?>
            </form>
            <button>
                <a href="http://localhost:8089/product/displayFormProduct">Ajouter</a>
            </button>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Libellé</th>
                    <th>Prix à l'unité (€)</th>
                    <th>Stock</th>
                    <th>Niveau d'accès</th>
                    <th>Catégorie</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($data["products"] as $product) {
                    echo "<tr>";
                    echo "<th> $product->id </th>";
                    echo "<td> $product->name </td>";
                    echo "<td> $product->price </td>";
                    echo "<td> $product->stock </td>";
                    echo "<td> $product->access_level </td>";
                    echo "<td> $product->category </td>";
                    echo "</td>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once("../app/views/footer-view.php"); ?>