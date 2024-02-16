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
                    <?php
                    if ($_SESSION["userRole"] == 0) {
                        echo "<th>Action</th>";
                    }
                    ?>
                </tr>
            </thead>

            <tbody>
                <?php
                foreach ($data["products"] as $product) {
                    echo "<tr>";
                    if (isset($data["open"]) && $data["open"]->id == $product->id) {
                        echo "<form id='update' action=http://localhost:8089/product/update method='POST'>";
                        echo "<th> $product->id </th>";
                        echo "<td> <input type='text' name='name' value=" . $product->name . "></td>";
                        echo "<td> <input type='number' step='0.01' name='price' value=" . $product->price . "></td>";
                        echo "<td> <input type='number' name='stock' value=" . $product->stock . "></td>";
                        echo "<td> <input type='number' name='access_level' value=" . $product->access_level . "></td>";
                        echo "<td> $product->category </td>";
                        echo "</form>";
                        if ($_SESSION["userRole"] == 0) {
                            echo "<td> 
                        <button form='update' name='id' value=" . $product->id . ">✅</button> 

                        <form action=http://localhost:8089/product method='GET'>
                        <input type='submit' value='❌'>
                        </form>
                        <form action=http://localhost:8089/product/delete method='GET'>
                            <button name='id' value=" . $product->id . ">🗑️</button> 
                            </form>
                        </td>";
                        }
                    } else {
                        echo "<tr>";
                        echo "<th> $product->id </th>";
                        echo "<td> $product->name </td>";
                        echo "<td> $product->price </td>";
                        echo "<td> $product->stock </td>";
                        echo "<td> $product->access_level </td>";
                        echo "<td> $product->category </td>";
                        if ($_SESSION["userRole"] == 0) {
                            echo "<td> 
                        <form action=http://localhost:8089/product/open method='GET'>
                        <button name='id' value=" . $product->id . ">✏️</button> 
                            </form>
                        </td>";
                        }
                    }
                    echo "</tr>";
                }
                ?>
            </tbody>

        </table>
    </div>
</div>
<?php require_once("../app/views/footer-view.php"); ?>