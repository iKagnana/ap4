<?php require_once ("../app/views/header-view.php"); ?>
<div class="page-container">
    <form class="form-center-item small-width" action="http://localhost:8089/product/createProduct" method="post">
        <div class="full-width">
            <label for="name">Libellé du produit</label>
            <input type="text" name="name" id="name">
        </div>
        <div class="full-width">
            <label for="price">Prix à l'unité (€)</label>
            <input type="number" min="0,01" step="0.01" name="price" id="price">
        </div>
        <div class="full-width">
            <label for="stock">Stock</label>
            <input type="number" min="0" name="stock" id="stock">
        </div>
        <div class="full-width">
            <label for="access_level">Niveau d'accès</label>
            <input type="number" min="1" name="access_level" id="access_level">
        </div>
        <div class="full-width">
            <label for="category">Catégorie</label>
            <select name="category" id=category">
                <?php
                foreach ($data["allCat"] as $category) {
                    echo "<option value=" . "$category[0]" . ">" . $category[1] . "</option>";
                }
                ?>
            </select>
        </div>

        <button class="styled-button" type="submit">
            <span class="styled-span">Valider</span>
        </button>
    </form>
</div>
<?php require_once ("../app/views/footer-view.php"); ?>