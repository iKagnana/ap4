<?php require_once("../app/views/header-view.php"); ?>
<div class="page-container">
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

        if (isset($data["selectedTodo"]) && $data["selectedTodo"] == $order->id) {
            echo "<td>
                <form action='http://localhost:8089/order/control' method='GET'>
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
                <form action='http://localhost:8089/order/detailsControl' method='POST'>
                    <button name='selectedTodo' type='submit' value=" . $order->id . ">Détails</button>
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

        if (isset($data["selectedDone"]) && $data["selectedDone"] == $order->id) {
            echo "<td>
                <form action='http://localhost:8089/order/control' method='GET'>
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
                <form action='http://localhost:8089/order/detailsControl' method='POST'>
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
<?php require_once("../app/views/footer-view.php"); ?>