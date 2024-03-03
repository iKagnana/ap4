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
        echo "<th>" . $order->id . "</th>";
        echo "<th>" . $order->date . "</th>";
        echo "<th>" . $order->price . "</th>";
        echo "<th>" . $order->applicant . "</th>";
        echo "<th>" . $order->status . "</th>";
        echo "<th>" . $order->reason . "</th>";
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
        echo "<th>" . $order->id . "</th>";
        echo "<th>" . $order->date . "</th>";
        echo "<th>" . $order->price . "</th>";
        echo "<th>" . $order->applicant . "</th>";
        echo "<th>" . $order->status . "</th>";
        echo "<th>" . $order->reason . "</th>";
    }
    echo "</tbody>
        </table>
            </div>";

    ?>

</div>
<?php require_once("../app/views/footer-view.php"); ?>