<?php require_once("../app/views/header-view.php"); ?>
<div class="page-container">
    <div class="left-side">
        <div class="flex-col-container">
            <form action="http://localhost:8089/order/search" method="post">
                <label for="searchName">Rechercher :</label>
                <input type="text" name="search" id="searchName">
                <button type="submit">Valider</button>
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
                    echo "<td>" . $product->category . "</td>";
                    echo "<td>" . $product->stock . "</td>";
                    echo "<td><input type='number' name='quantity' min='1' max=$product->stock value=" . $product->quantity . "></td>";
                    echo "<input hidden type='text' name='idProduct' value=" . $product->id . ">";
                    echo "<td>
                        <input type='submit' value='+'>
                        </td>";
                    echo "</form>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <div class="right-side">
        <?php echo $_SESSION["userRole"] < 2 ? "<div>" : "<div hidden>"; ?>
        <div>
            <input checked form="cart" type="radio" name="type" id="incoming">
            <label for="incoming">Commande client</label>
        </div>
        <div>
            <input form="cart" type="radio" name="type" id="outgoing">
            <label for="outgoing">Commande fournisseur</label>
        </div>
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
                    echo "<td>" . $addedProduct["category"] . "</td>";
                    echo "<td>" . $addedProduct["totalPrice"] . "</td>";
                    echo "<td>
                            <form id='cart' action='http://localhost:8089/order/substract' method='GET'>
                                <input hidden type='text' name='id' value=" . $addedProduct["id"] . ">
                                <input type='submit' value='-'>
                            </form>
                            <form action='http://localhost:8089/order/remove' method='GET'>
                                <input hidden type='text' name='id' value=" . $addedProduct["id"] . ">
                                <input type='submit' value='X'>
                                </form>
                            </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <form action="http://localhost:8089/order/create" method="post">
        <input type="submit" value="Valider">
    </form>
</div>
</div>
<?php require_once("../app/views/footer-view.php"); ?>