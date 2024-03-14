<?php require_once("../app/views/header-view.php"); ?>
<div class="page-container">
    <div class="flex-col-container">
        <div class="filter-wrapper">
            <div class="filter-header">
                <label for="">Filter :</label>
                <input form="filter" type="submit" value="🔍">
            </div>
            <form class="form-filter-user padding-one" id="filter" action="http://localhost:8089/product/filter"
                method="GET">

                <div class="textfield-label">
                    <label for=" searchbar">Rechercher</label>
                    <input type="text" name="search" id="searchbar">
                </div>
                <div class="textfield-label">
                    <label for="filterBy">Catégorie</label>

                    <select name="category" id="filterBy">
                        <?php
                        echo "<option value='all'>Tous</option>";
                        foreach ($data["categories"] as $category) {
                            echo $data["filterCat"] == $category["id_cat"] ? "<option selected value=" . $category["id_cat"] . ">" . $category["name_cat"] . "</option>" : "<option value=" . $category["id_cat"] . ">" . $category["name_cat"] . "</option>";
                        }
                        ?>
                    </select>
                </div>


            </form>
            <form class=" filter-footer" action="http://localhost:8089/product" method="GET">
                <button type="submit">Réinitialiser les filtres</button>
            </form>
        </div>

        <div>
            <?php echo isset($extra["error"]) ? "<span class='text-error'>" . $extra["error"] . "</span>" : ""; ?>
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
                    echo "<tr>";
                    echo "<th> $product->id </th>";
                    echo "<td> $product->name </td>";
                    echo "<td> $product->price </td>";
                    echo "<td> $product->stock </td>";
                    echo "<td> $product->access_level </td>";
                    echo "<td>" . $product->category["name"] . "</td>";
                    echo "<td>";
                    if ($_SESSION["userRole"] == 0) {
                        echo "<form action=http://localhost:8089/product/details method='GET'>
                        <button name='id' value=" . $product->id . ">✏️</button> 
                            </form>";
                    }
                    echo "<form action=http://localhost:8089/product/cart method='POST'>
                            <button name='id' value=$product->id>Ajouter</button>
                            </form>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>

        </table>
    </div>
</div>
<?php require_once("../app/views/footer-view.php"); ?>