<?php require_once ("../app/views/header-view.php"); ?>
<div class="page-container">
    <div class="flex-col-container">
        <?php echo "<h1>Bonjour, " . $data["lastname"] . " " . $data["firstname"] . "</h1>"; ?>
        <div class="flex-row-container">
            <?php
            if (isset ($_SESSION["userRole"]) && $_SESSION["userRole"] < 2) {
                echo "<a class='dashboard-item' href='$host/product'>";
                echo "<div>
                    Stocks
                </div>
            </a>";
            }
            ?>
            <?php echo "<a class='dashboard-item' href='$host/order'>"; ?>
            <div>
                Commandes
            </div>
            </a>
            <?php
            if (isset ($_SESSION["userRole"]) && $_SESSION["userRole"] == 0) {
                echo "<a class='dashboard-item' href='$host/user'>";
                echo "<div>
                    Utilisateur
                </div>
            </a>";

                echo "<a class='dashboard-item' href='$host/provider'>";
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