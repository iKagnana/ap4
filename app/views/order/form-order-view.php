<?php require_once ("../app/views/header-view.php"); ?>
<div class="page-container">
    <div class="left-side">
        <div class="flex-col-container">
            <form class="flex-row-container" action="http://localhost:8089/order/search" method="post">
                <label for="searchName">Rechercher par produit :</label>
                <div class="m-1">
                    <input class="half-width" type="text" name="search" id="searchName">
                    <button class="styled-button" type="submit">
                        <span class="styled-span">Rechercher</span>
                    </button>
                </div>
            </form>
        </div>
        <table class="table-scroll">
            <thead>
                <tr>
                    <th>Libellé</th>
                    <th>Prix</th>
                    <th>Catégorie</th>
                    <th>Stock</th>
                    <th>Quantité</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($data["allProducts"] as $product) {
                    echo "<tr>";
                    echo "<form action='http://localhost:8089/order/addProduct' method='post'>";
                    echo "<td>" . $product->name . "</td>";
                    echo "<td>" . $product->price . "</td>";
                    echo "<td>" . $product->category["name"] . "</td>";
                    echo "<td>" . $product->stock . "</td>";
                    echo "<td><input class='not-full-width' type='number' name='quantity' min='1' max=$product->stock value=" . $product->quantity . "></td>";
                    echo "<input hidden type='text' name='idProduct' value=" . $product->id . ">";
                    echo "<td>
                        <button class='button-outlined' type='submit'>
                        <svg  xmlns='http://www.w3.org/2000/svg'  width='20'  height='20'  viewBox='0 0 24 24'  fill='none'  stroke='currentColor'  stroke-width='2'  stroke-linecap='round'  stroke-linejoin='round'><path stroke='none' d='M0 0h24v24H0z' fill='none'/><path d='M12 5l0 14' /><path d='M5 12l14 0' /></svg>
                        </button>
                        </td>";
                    echo "</form>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <div class="right-side form-center-item">
        <?php echo $_SESSION["userRole"] < 2 ? "<div>" : "<div hidden>"; ?>
        <div>
            <input checked form="cart" type="radio" name="type" id="incoming" value="incoming">
            <label for="incoming">Commande client</label>
        </div>
        <div>
            <input form="cart" type="radio" name="type" id="outgoing" value="outgoing">
            <label for="outgoing">Commande fournisseur</label>
        </div>

        <?php echo $_SESSION["userRole"] < 2 ? "<div>" : "<div hidden>"; ?>
        <div class='m-1'>
            <label for="provider">Fournisseur (Commande fournisseur uniquement)</label>
            <select form="cart" name="provider" id="provider">
                <?php
                if (isset ($data["providers"])) {
                    foreach ($data["providers"] as $provider) {
                        echo "<option value=$provider->id>$provider->name</option>";
                    }
                }
                ?>
            </select>
        </div>
    </div>
</div>

<div>
    <?php echo isset ($data["error"]) ? "<span class='text-error'>" . $data["error"] . "</span>" : ""; ?>
</div>

<span>Panier</span>
<div>
    <table>
        <thead>
            <tr>
                <th>Libellé</th>
                <th>Quantité</th>
                <th>Prix</th>
                <th>Catégorie</th>
                <th>Prix total €</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($data["cart"] as $addedProduct) {
                echo "<tr>";
                echo "<td>" . $addedProduct["name"] . "</td>";
                echo "<td>" . $addedProduct["quantity"] . "</td>";
                echo "<td>" . $addedProduct["price"] . "</td>";
                echo "<td>" . $addedProduct["category"]["name"] . "</td>";
                echo "<td>" . $addedProduct["totalPrice"] . "</td>";
                echo "<td>
                            <form action='http://localhost:8089/order/substract' method='GET'>
                                <input hidden type='text' name='id' value=" . $addedProduct["id"] . ">
                                <button class='button-outlined'>
                                <svg  xmlns='http://www.w3.org/2000/svg'  width=20  height=20  viewBox='0 0 24 24'  fill='none'  stroke='currentColor'  stroke-width='2'  stroke-linecap='round'  stroke-linejoin='round'><path stroke='none' d='M0 0h24v24H0z' fill='none'/><path d='M5 12l14 0' /></svg>
                                </button>
                            </form>
                            <form action='http://localhost:8089/order/remove' method='GET'>
                                <input hidden type='text' name='id' value=" . $addedProduct["id"] . ">
                                <button class='button-outlined' type='submit'>
                    <svg  xmlns='http://www.w3.org/2000/svg'  width='20'  height='20'  viewBox='0 0 24 24'  fill='none'  stroke='currentColor'  stroke-width='2'  stroke-linecap='round'  stroke-linejoin='round'><path stroke='none' d='M0 0h24v24H0z' fill='none'/><path d='M18 6l-12 12' /><path d='M6 6l12 12' /></svg>
                    </button>
                                </form>
                            </td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>
<form id='cart' action="http://localhost:8089/order/create" method="post">
    <button class="styled-button" type="submit">
        <span class="styled-span">Valider</span>
    </button>
</form>
<form action="http://localhost:8089/order/reset" method="POST">
    <button class="styled-button" type="submit">
        <span class="styled-span">Vider le panier</span>
    </button>
</form>
</div>
</div>
<?php require_once ("../app/views/footer-view.php"); ?>