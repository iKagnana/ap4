<?php require_once ("../app/views/header-view.php"); ?>
<div class="page-container">
    <div class="flex-col-container">
        <div>
            <?php echo isset ($data["error"]) ? "<span class='text-error'>" . $data["error"] . "</span>" : ""; ?>
        </div>
        <form class="form-create-account" action="http://localhost:8089/user/create" method="post" autocomplete="off">
            <div>
                <div>
                    <input type="radio" value="1" name="type" id="employee">
                    <label for="employee">Employé</label>
                </div>

                <div>
                    <input type="radio" value="2" name="type" id="client">
                    <label for="client">Client</label>
                </div>
            </div>

            <div>
                <label for="enterprise">Nom de l'entreprise (Client uniquement)</label>
                <input type="text" name="enterprise" id="enterprise">
            </div>

            <div>
                <label for="lastname">Nom</label>
                <input type="text" name="lastname" id="lastname">
            </div>

            <div>
                <label for="firstname">Prénom</label>
                <input type="text" name="firstname" id="firstname">
            </div>

            <div>
                <label for="email">Email</label>
                <input type="text" name="email" id="email">
            </div>

            <div>
                <label for="password">Mot de passe</label>
                <input type="text" name="password" id="password">
            </div>

            <input hidden type="text" name="origin" value="user">
            <button type="submit">Valdier</button>
        </form>
    </div>
</div>
<?php require_once ("../app/views/footer-view.php"); ?>