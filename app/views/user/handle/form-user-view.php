<?php require_once ("../app/views/header-view.php"); ?>
<div class="page-container">
    <div class="flex-col-container">
        <div>
            <?php echo isset ($data["error"]) ? "<span class='text-error'>" . $data["error"] . "</span>" : ""; ?>
        </div>
        <form class="form-center-item" action="http://localhost:8089/user/create" method="post" autocomplete="off">
            <div class="half-width">
                <label for="role">Rôle</label>
                <select name="role" id="">
                    <option value="2">Client</option>
                    <option value="1">Employé</option>
                    <option value="0">Administrateur</option>
                </select>
            </div>

            <div class="half-width">
                <label for="enterprise">Nom de l'entreprise (Client uniquement)</label>
                <input type="text" name="enterprise" id="enterprise">
            </div>


            <div class="half-width">
                <label for="lastname">Nom</label>
                <input type="text" name="lastname" id="lastname">
            </div>

            <div class="half-width">
                <label for="firstname">Prénom</label>
                <input type="text" name="firstname" id="firstname">
            </div>

            <div class="half-width">
                <label for="email">Email</label>
                <input type="text" name="email" id="email" autocomplete="off">
            </div>

            <div class="half-width">
                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password" autocomplete="off">
            </div>

            <div class="half-width">
                <label for="level-access">Niveau d'accès maximum</label>
                <select name="levelAccess" id="level-access">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                </select>
            </div>


            <button class="styled-button" type="submit">
                <span class="styled-span">Valider</span>
            </button>
        </form>
    </div>
</div>
<?php require_once ("../app/views/footer-view.php"); ?>