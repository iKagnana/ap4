<?php require_once ("../app/views/header-view.php"); ?>
<div class="page-container">
    <div class="flex-col-container">
        <div>
            <?php echo isset ($data["error"]) ? "<span class='text-error'>" . $data["error"] . "</span>" : ""; ?>
        </div>
        <form class="form-center-item" action="http://localhost:8089/provider/create" method="post">
            <div class="m-1">
                <label for="name">Nom de l'entreprise fournisseuse</label>
                <?php echo isset ($data["form"]->name) ? "<input type='text' name='name' id='name' value=" . $data["form"]->name . ">" : "<input type='text' name='name' id='name'>" ?>
            </div>

            <div class="m-1">
                <label for="email">Email de l'entreprise fournisseuse</label>
                <?php echo isset ($data["form"]->email) ? "<input type='text' name='email' id='email' value=" . $data["form"]->email . ">" : "<input type='text' name='email' id='email'>" ?>
            </div>

            <button class="styled-button" type="submit">
                <span class="styled-span">Valider</span>
            </button>
        </form>
    </div>
</div>
<?php require_once ("../app/views/footer-view.php"); ?>