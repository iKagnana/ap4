<?php require_once ("../app/views/header-view.php");
?>
<div class="page-container">
    <div class="flex-col-container">
        <div>
            <?php echo isset ($data["error"]) ? "<span class='text-error'>" . $data["error"] . "</span>" : ""; ?>
        </div>
        <?php echo "<form class='form-center-item' action=$host/user/create method='post' autocomplete='off'>"; ?>
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
            <?php echo isset ($data["form"]->enterprise) ? "<input type='text' name='enterprise' id='enterprise' value=" . $data["form"]->enterprise . ">" : "<input type='text' name='enterprise' id='enterprise'>" ?>
        </div>


        <div class="half-width">
            <label for="lastname">Nom</label>
            <?php echo isset ($data["form"]->lastname) ? "<input type='text' name='lastname' id='lastname' value=" . $data["form"]->lastname . ">" : "<input type='text' name='lastname' id='lastname'>" ?>
        </div>

        <div class="half-width">
            <label for="firstname">Prénom</label>
            <?php echo isset ($data["form"]->firstname) ? "<input type='text' name='firstname' id='firstname' value=" . $data["form"]->firstname . ">" : "<input type='text' name='firstname' id='firstname'>" ?>
        </div>

        <div class="half-width">
            <label for="email">Email</label>
            <?php echo isset ($data["form"]->email) ? "<input type='text' name='email' id='email' value=" . $data["form"]->email . ">" : "<input type='text' name='email' id='email'>" ?>
        </div>

        <div class="half-width">
            <label for="password">Mot de passe</label>
            <?php echo isset ($data["form"]->password) ? "<input type='password' name='password' id='password' value=" . $data["form"]->password . ">" : "<input type='password' name='password' id='password'>" ?>
        </div>

        <div class="half-width">
            <label for="level-access">Niveau d'accès maximum</label>
            <select name="levelAccess" id="level-access">``
                <?php echo isset ($data["form"]->levelAccess) && $data["form"]->levelAccess == 1 ? "<option selected value=1>1</option>" : "<option value=1>1</option>" ?>
                <?php echo isset ($data["form"]->levelAccess) && $data["form"]->levelAccess == 2 ? "<option selected value=2>2</option>" : "<option value=2>2</option>" ?>
                <?php echo isset ($data["form"]->levelAccess) && $data["form"]->levelAccess == 3 ? "<option selected value=3>3</option>" : "<option value=3>3</option>" ?>
            </select>
        </div>


        <button class="styled-button" type="submit">
            <span class="styled-span">Valider</span>
        </button>
        </form>
    </div>
</div>
<?php require_once ("../app/views/footer-view.php"); ?>