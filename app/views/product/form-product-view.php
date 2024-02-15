<div class="page-container">
    <form action="http://localhost:8089/product/createProduct" method="post">
        <div class="textfield">
            <label for="name">Libellé du produit</label>
            <input type="text" name="name" id="name">
        </div>
        <div class="textfield">
            <label for="price">Prix à l'unité (€)</label>
            <input type="number" step="0.01" name="price" id="price">
        </div>
        <div class="textfield">
            <label for="stock">Stock</label>
            <input type="number" name="stock" id="stock">
        </div>
        <div class="textfield">
            <label for="access_level">Niveau d'accès</label>
            <input type="number" name="access_level" id="access_level">
        </div>
        <div class="textfield">
            <label for="category">Catégorie</label>
            <select name="category" id=category">
                <?php
                foreach ($data as $category) {
                    echo "<option value=" . "$category[0]" . ">" . $category[1] . "</option>";
                }
                ?>
            </select>
        </div>

        <input type="submit" value="Valider">
    </form>
</div>