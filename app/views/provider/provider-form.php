<?php require_once ("../app/views/header-view.php"); ?>
<div class="page-container">
    <form class="align-center padding-one" action="http://localhost:8089/provider/create" method="post">
        <div class="m-1">
            <label for="name">Nom de l'entreprise fournisseuse</label>
            <input type="text" name="name" id="name">
        </div>

        <div class="m-1">
            <label for="email">Email de l'entreprise fournisseuse</label>
            <input type="text" name="email" id="email">
        </div>

        <button>Valider</button>
    </form>
</div>
<?php require_once ("../app/views/footer-view.php"); ?>