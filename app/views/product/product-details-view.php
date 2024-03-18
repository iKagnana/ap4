<?php require_once ("../app/views/header-view.php"); ?>
<div class="page-container">
    <div class="flex-col-container">
        <form class="m-1" action="http://localhost:8089/product">
            <button class="styled-button" type="submit">
                <div class="styled-span">
                    <svg xmlns="http://www.w3.org/2000/svg" width=15 viewBox="0 0 24 24" stroke="currentColor"
                        fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M5 12l14 0" />
                        <path d="M5 12l6 6" />
                        <path d="M5 12l6 -6" />
                    </svg>
                    <span>Retour</span>
                </div>
                </svg>
            </button>
        </form>
        <div>
            <?php echo isset ($data["error"]) ? "<span class='text-error'>" . $data["error"] . "</span>" : ""; ?>
        </div>
        <form class="form-center-item" action="http://localhost:8089/product/update" method="post">
            <?php
            $product = $data["selected"];
            echo "<input hidden name='id' value=$product->id>";
            echo "<div class='half-width'>
                <label for='name'>Libellé</label> 
                <input type='text' name='name' value=" . $product->name . ">
            </div>";
            echo "<div class='half-width'>
            <label for='price'>Prix à l'unité €</label> 
            <input type='number' min='0,01' step='0.01' name='price' value=" . $product->price . ">
            </div>";
            echo "
            <div class='half-width'>
                <label for='name'>Stock</label> 
                <input type='number' min='0' name='stock' value=" . $product->stock . ">
                <span class='info-text small-text'>Attention il n'est pas conseillé de modifier cette valeur.</span>
            </div>";
            echo "<div class='half-width'>
            <label for='access_level'>Niveau d'accès</label> 
            <input type='number' min='1' name='access_level' value=" . $product->access_level . ">
            </div>";
            echo "<div class='half-width'>
            <label for='category'>Catégorie</label> 
            <select class='m-1' name='category'>";
            foreach ($data["categories"] as $cat) {
                echo $product->category["id"] = $cat["id_cat"] ? "<option selected value=" . $cat["id_cat"] . ">" . $cat["name_cat"] . "</option>" : "<option value=" . $cat["id_cat"] . ">" . $cat["name_cat"] . "</option>";
            }
            echo "</select>
            </div>";
            ?>
            <button class="styled-button" type="submit">
                <span class="styled-span">Valider</span>
            </button>
        </form>

        <form action="http://localhost:8089/product/delete" method="post">
            <?php
            $product = $data["selected"];
            echo "<input hidden name='id' value=$product->id>"
                ?>
            <button class="styled-button-red" type="submit">
                <span class="styled-span">Supprimer</span>
                </svg>
            </button>
        </form>
    </div>
</div>
<?php require_once ("../app/views/footer-view.php"); ?>