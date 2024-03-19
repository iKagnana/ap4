<?php require_once ("../app/views/header-view.php"); ?>
<div class="page-container align-top">
    <div class="flex-col-container">
        <div class="filter-wrapper">
            <div class="filter-header">
                <label for="">Filtres :</label>
                <button class="button-outlined" type="submit" form="filter">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                        <path d="M21 21l-6 -6" />
                    </svg>
                </button>
            </div>
            <form class="form-filter-user padding-one" id="filter" action="http://localhost:8089/product/filter"
                method="GET">

                <div class="textfield-label">
                    <label for=" searchbar">Rechercher</label>
                    <?php echo isset ($data["searchName"]) ? "<input type='text' name='search' id='searchbar' value=" . $data["searchName"] . ">" : "<input type='text' name='search' id='searchbar'>"; ?>
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
            <form class="filter-footer" action="http://localhost:8089/product" method="GET">
                <button class="button-outlined" type="submit">Réinitialiser les filtres</button>
            </form>
        </div>

        <div>
            <?php echo isset ($data["error"]) ? "<span class='text-error'>" . $data["error"] . "</span>" : ""; ?>
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
                        echo "<form action=http://localhost:8089/product/details method='GET'>";
                        echo "<button class='button-outlined' name='id' value=$product->id>";
                        echo "<div><svg xmlns='http://www.w3.org/2000/svg'  width='20'  height='20'  viewBox='0 0 24 24'  fill='none'  stroke='currentColor'  stroke-width='2'  stroke-linecap='round'  stroke-linejoin='round'><path stroke='none' d='M0 0h24v24H0z' fill='none'/><path d='M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4' /><path d='M13.5 6.5l4 4' /></svg></div>";
                        echo "</button>";
                        echo "</form>";
                    }
                    echo "<form action=http://localhost:8089/product/cart method='POST'>
                            <button class='button-outlined' name='id' value=$product->id>
                            <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' viewBox='0 0 24 24' fill='none' stroke='currentColor'
                                stroke-width='2' stroke-linecap='round' stroke-linejoin='round'>
                                <path stroke='none' d='M0 0h24v24H0z' fill='none' />
                                <path d='M4 19a2 2 0 1 0 4 0a2 2 0 0 0 -4 0' />
                                <path d='M12.5 17h-6.5v-14h-2' />
                                <path d='M6 5l14 1l-.86 6.017m-2.64 .983h-10.5' />
                                <path d='M16 19h6' />
                                <path d='M19 16v6' />
                            </svg>
                            </button>
                            </form>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once ("../app/views/footer-view.php"); ?>