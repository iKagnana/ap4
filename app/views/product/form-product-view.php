<?php require_once ("../app/views/header-view.php"); ?>
<div class="page-container">
    <div class="flex-col-container">
        <div>
            <?php echo isset ($data["error"]) ? "<span class='text-error'>" . $data["error"] . "</span>" : ""; ?>
        </div>
        <?php echo "<form class='form-center-item not-full-width' action=$host/product/createProduct method='post'>"; ?>

        <div class="full-width">
            <label for="name">Libellé du produit</label>
            <?php echo isset ($data["form"]->name) ? "<input type='text' name='name' id='name' value=" . $data["form"]->name . ">" : "<input type='text' name='name' id='name'>" ?>
        </div>
        <div class="full-width">
            <label for="price">Prix à l'unité (€)</label>
            <?php echo isset ($data["form"]->price) ? "<input type='number' step='0.01' name='price' id='price' value=" . $data["form"]->price . ">" : "<input type='number' step='0.01' name='price' id='price'>" ?>
        </div>
        <div class="full-width">
            <label for="stock">Stock</label>
            <?php echo isset ($data["form"]->stock) ? "<input type='number' min=0 name='stock' id='stock' value=" . $data["form"]->stock . ">" : "<input type='number' min=0 name='stock' id='stock'>" ?>
        </div>
        <div class="full-width">
            <label for="access_level">Niveau d'accès</label>
            <?php echo isset ($data["form"]->levelAccess) ? "<input type='number' min=1 max=3 name='access_level' id='access_level' value=" . $data["form"]->levelAccess . ">" : "<input type='number' min=1 max=3 name='access_level' id='access_level'>" ?>
        </div>
        <div class="full-width">
            <label for="category">Catégorie</label>
            <select name="category" id=category">
                <?php
                foreach ($data["allCat"] as $category) {
                    if (isset ($data["form"]->category) && $data["form"]->category == $category[0]) {
                        echo "<option selected value=" . "$category[0]" . ">" . $category[1] . "</option>";
                    } else {
                        echo "<option value=" . "$category[0]" . ">" . $category[1] . "</option>";
                    }
                }
                ?>
            </select>
        </div>

        <button class="styled-button" type="submit">
            <span class="styled-span">Valider</span>
        </button>
        </form>
    </div>
</div>
<?php require_once ("../app/views/footer-view.php"); ?>