<div class="page-container">
    <div class="flex-col-container">
        <a href="product/displayFormProduct">Ajouter</a>

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
                foreach ($data as $product) {
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