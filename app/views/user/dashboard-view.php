<?php require_once("../app/views/header-view.php"); ?>
<div class="page-container">
    <div class="flex-col-container">
        <?php echo "<h1>Bonjour, " . $data["lastname"] . " " . $data["firstname"] . "</h1>"; ?>
        <div class="flex-row-container">
            <a class="dashboard-item" href="http://localhost:8089/product">
                <div>
                    Stocks
                </div>
            </a>
            <div class="dashboard-item">
                Commandes
            </div>
        </div>
    </div>
</div>
<?php require_once("../app/views/footer-view.php"); ?>