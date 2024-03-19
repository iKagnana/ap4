<?php require_once ("../app/views/header-view.php"); ?>
<div class="page-container">
    <div class="flex-col-container">
        <?php echo "<h1>Bonjour, " . $data["lastname"] . " " . $data["firstname"] . "</h1>"; ?>
        <div class="flex-row-container">
            <?php
            if (isset ($_SESSION["userRole"]) && $_SESSION["userRole"] < 2) {
                echo "<a class='dashboard-item' href='http://localhost:8089/product'>";
                echo "<div>
                    Stocks
                </div>
            </a>";
            }
            ?>
            <a class="dashboard-item" href="http://localhost:8089/order">
                <div>
                    Commandes
                </div>
            </a>
            <?php
            if (isset ($_SESSION["userRole"]) && $_SESSION["userRole"] == 0) {
                echo "<a class='dashboard-item' href='http://localhost:8089/user'>";
                echo "<div>
                    Utilisateur
                </div>
            </a>";

                echo "<a class='dashboard-item' href='http://localhost:8089/provider'>";
                echo "<div>
                    Fournisseur
                </div>
            </a>";
            }
            ?>
        </div>
    </div>
</div>
<?php require_once ("../app/views/footer-view.php"); ?>