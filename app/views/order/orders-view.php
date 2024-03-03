<?php require_once("../app/views/header-view.php"); ?>
<div class="page-container">
    <?php
    echo "<div>
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
        echo "<td>" . $order->price . "</td>";
        echo "<td>" . $order->applicant . "</td>";
        echo "<td>" . $order->status . "</td>";
        echo "<td>" . $order->reason . "</td>";
        if (isset($data["openedItem"]) && $data["openedItem"] == $order->id) {
            echo "<td>
                <form action='http://localhost:8089/order' method='GET'>
                    <button type='submit'>X</button>
                </form>
            </td>";
            echo '</tr>';
            echo "<tr><td colspan=6>";
            foreach ($order->products as $product) {
                echo "<p>" . $product["name_p"] . "</p>";
            }
            echo "</td></tr>";
        } else {
            echo "<td>
                <form action='http://localhost:8089/order/detail' method='GET'>
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
<?php require_once("../app/views/footer-view.php"); ?>