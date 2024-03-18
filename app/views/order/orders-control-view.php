<?php require_once ("../app/views/header-view.php"); ?>
<div class="page-container">
    <div class="flex-col-container">
        <div>
            <?php echo isset ($data["error"]) ? "<span class='text-error'>" . $data["error"] . "</span>" : ""; ?>
        </div>
        <div class="flex-row-container">
            <?php
            echo "<div class='left-side'>
            <table>
            <caption>Demandes à traiter</caption>
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
            foreach ($data["todo"] as $order) {
                echo "<tr>";
                echo "<th>" . $order->id . "</th>";
                echo "<th>" . $order->date . "</th>";
                echo "<th>" . $order->price . "</th>";
                echo "<th>" . $order->applicant . "</th>";
                echo "<th>" . $order->status . "</th>";
                echo "<th>" . $order->reason . "</th>";

                if (isset ($data["selectedTodo"]) && $data["selectedTodo"] == $order->id) {
                    echo "<td>
                <form action='http://localhost:8089/order/control' method='GET'>
                    <button type='submit'>X</button>
                </form>
            </td>";
                    echo '</tr>';
                    echo "<tr><td colspan=6>";
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
                } else if (isset ($data["onDoingItem"]) && $data["onDoingItem"] == $order->id) {
                    echo "<tr>
                <form action='http://localhost:8089/order/treatment' method='POST'>
                    <td colspan=3>
                    <label for='status'>Status :</label>
                        <select name='status'>
                            <option value='Validé'>Validé</option>
                            <option value='Refusé'>Refusé</option>
                        </select>
                    </td>";
                    echo "<td colspan=3>
                <label for='reason'>Commentaire :</label>
                <input type='text' name='reason'>
            </td>";
                    echo "<td>
                <button type='submit'>Valider</button>
                <input hidden name='id' type='text' value=" . $order->id . ">
                </td>
            ";
                    echo " </form> </tr>";
                } else {
                    echo "<td>
                <form action='http://localhost:8089/order/detail' method='POST'>
                    <button name='selectedTodo' type='submit' value=" . $order->id . ">Détails</button>
                </form>
                <form action='http://localhost:8089/order/detail' method='POST'>
                    <button name='onDoingItem' type='submit' value=" . $order->id . ">Contrôler</button>
                </form>
            </td>";
                    echo '</tr>';
                }
            }
            echo "</tbody>
        </table>
            </div>";
            echo "<div>
            <table>
            <caption>Demandes traitées</caption>
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
            foreach ($data["done"] as $order) {
                echo "<tr>";
                echo "<th>" . $order->id . "</th>";
                echo "<th>" . $order->date . "</th>";
                echo "<th>" . $order->price . "</th>";
                echo "<th>" . $order->applicant . "</th>";
                echo "<th>" . $order->status . "</th>";
                echo "<th>" . $order->reason . "</th>";

                if (isset ($data["selectedDone"]) && $data["selectedDone"] == $order->id) {
                    echo "<td>
                <form action='http://localhost:8089/order/control' method='GET'>
                    <button type='submit'>X</button>
                </form>
            </td>";
                    echo '</tr>';
                    echo "<tr><td colspan=6>";
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
                    <button name='selectedDone' type='submit' value=" . $order->id . ">Détails</button>
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
</div>
<?php require_once ("../app/views/footer-view.php"); ?>