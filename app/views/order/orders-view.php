<?php require_once ("../app/views/header-view.php"); ?>
<div class="page-container">
    <div class="flex-col-container">
        <div class=" filter-wrapper">
            <div class="filter-header">
                <label for="">Filter :</label>
                <input form="filter" type="submit" value="🔍">
            </div>
            <form class="form-filter-user padding-one" id="filter" action="http://localhost:8089/order/filter"
                method="GET">

                <div class="textfield-label">
                    <label for=" searchbar">Rechercher par demandeur</label>
                    <input type="text" name="search" id="searchbar">
                </div>
                <div class="textfield-label">
                    <label for="filterBy">Status</label>

                    <select name="filter" id="filterBy">
                        <?php
                        echo "<option value='all'>Tous</option>";
                        echo isset ($data["filter"]) && $data["filter"] == "waiting" ? "<option value='waiting' selected>En attente de validation</option>" : "<option value='waiting' >En attente de validation</option>";
                        echo isset ($data["filter"]) && $data["filter"] == "valid" ? "<option value='valid' selected>Validé</option>" : "<option value='valid' >Validé</option>";
                        echo isset ($data["filter"]) && $data["filter"] == "refused" ? "<option value='refused' selected>Refusé</option>" : "<option value='refused' >Refusé</option>";
                        ?>
                    </select>
                </div>


            </form>
            <form class=" filter-footer" action="http://localhost:8089/order" method="GET">
                <button type="submit">Réinitialiser les filtres</button>
            </form>
        </div>
        <?php
        echo "<div class='padding-one'>
            <table>
            <caption>Commandes</caption>
            <thead>
                <tr>
                    <th>id</th>
                    <th>Date</th>
                    <th>Prix total</th>
                    <th>Demandeur</th>
                    <th>Status</th>
                    <th>Raison</th>
                </tr>
            </thead>
            <tbody>";
        foreach ($data["all"] as $order) {
            echo "<tr>";
            echo "<td>" . $order->id . "</td>";
            echo "<td>" . $order->date . "</td>";
            echo "<td>" . $order->price . "€</td>";
            echo "<td>" . $order->applicant . "</td>";
            echo "<td>" . $order->status . "</td>";
            echo "<td>" . $order->reason . "</td>";
            if (isset ($data["openedItem"]) && $data["openedItem"] == $order->id) {
                echo "<td>
                <form action='http://localhost:8089/order' method='GET'>
                    <button type='submit'>X</button>
                </form>
            </td>";
                echo '</tr>';
                echo "<tr>";
                if (isset ($order->provider)) {
                    echo "<td> Commande fournisseur : " . $order->provider . "</td>";
                } else {
                    echo "<td>Commande Cliente</td>";
                }
                echo "<td colspan=6>";
                echo "<div class='group-grid-5-col text-bold'>
                <span>Libellé</span>
                <span>Quantité</span>
                <span>Prix unité</span>
                <span>Catégorie</span>
                <span>Stock</span>
                </div>";
                foreach ($order->products as $products) {
                    foreach ($products as $product) {
                        echo "<div class='group-grid-4-col'>";
                        echo "<p>" . $product["name_p"] . "</p>";
                        echo "<p>" . abs($product["quantity"]) . "</p>";
                        echo "<p>" . $product["price"] . "€</p>";
                        echo "<p>" . $product["name_cat"] . "</p>";
                        echo "<p>" . $product["stock"] . "</p>";
                        echo "</div>";
                    }
                }
                echo "</td></tr>";
            } else {
                echo "<td>
                <form action='http://localhost:8089/order/detail' method='POST'>
                    <button name='item' type='submit' value=" . $order->id . ">Détails</button>
                </form>
            </td>";
                echo '</tr>';
            }
        }
        echo "</tbody>
        </table>
        </div>";
        ?>

    </div>
</div>
<?php require_once ("../app/views/footer-view.php"); ?>