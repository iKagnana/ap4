<div class="page-container">
    <div class="flex-col-container">
        <button>
            placeholder
        </button>

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
                <tr>
                    <?php
                    foreach ($allProducts as $product) {
                        echo "<th> $product[0] </th>";
                        echo "<td> $product[1] </td>";
                        echo "<td> $product[2] </td>";
                        echo "<td> $product[3] </td>";
                        echo "<td> $product[4] </td>";
                    }
                    ?>
                </tr>
            </tbody>
        </table>
    </div>
</div>