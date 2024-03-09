<?php require_once("../app/views/header-view.php"); ?>
<div class="page-container">
    <div class="form-detail-user">
        <form class="padding-one" action="http://localhost:8089/user">
            <button type='submit'>Retour</button>
        </form>

        <form class="form-detail-user" action="http://localhost:8089/user/update" method="post">
            <?php
            $user = $data["selected"];
            echo "<input hidden type='text' name='id' value=$user->id>";
            echo "<select class='form-user-label-text' name='role' id='role'>";
            echo $user->role == 2 ? "<option value='2' selected>Client</option>" : "<option value='2'>Client</option>";
            echo $user->role == 1 ? "<option value='1' selected>Employé</option>" : "<option value='1'>Employé</option>";
            echo $user->role == 0 ? "<option value='0' selected>Administrateur</option>" : "<option value='0'>Administrateur</option>";
            echo "</select>";

            echo "<div class='form-user-label-text'>
                <label for='enterprise'>Nom de l'entreprise (Client uniquement)</label>
                <input type='text' name='enterprise' id='enterprise' value=$user->enterprise>
            </div>
            
            <div class='form-user-label-text'>
                <label for='lastname'>Nom</label>
                <input type='text' name='lastname' id='lastname' value=$user->lastname>
            </div>

            <div class='form-user-label-text'>
                <label for='firstname'>Prénom</label>
                <input type='text' name='firstname' id='firstname' value=$user->firstname>
            </div>

            <div class='form-user-label-text'>
                <label for='email'>Email</label>
                <input type='text' name='email' id='email' value=$user->email>
            </div>";

            echo "<div class='form-user-label-text'>";
                echo "<label for='level-access'>Niveau d'accès maximum</label>";
                echo "<select name='levelAccess' id='level-access'>";
                    echo $user->levelAccess == 1 ? "<option value='1' selected>1</option>" : "<option value='1'>1</option>";
                    echo $user->levelAccess == 2 ? "<option value='2' selected>2</option>" : "<option value='2'>2</option>";
                    echo $user->levelAccess == 3 ? "<option value='3' selected>3</option>" : "<option value='3'>3</option>";
                echo "</select>
            </div>

            <div class='form-user-label-text'>
                <label for='status'>Status</label>";
                echo "<select name='status' id='status'>";
                    echo $user->status == "Validé" ? "<option value='Validé' selected>Validé</option>" : "<option value='Validé'>Validé</option>";
                    echo $user->status == "Refusé" ? "<option value='Refusé' selected>Refusé</option>" : "<option value='Refusé'>Refusé</option>";
                echo "</select>
            </div>";
            ?>
            <button type="submit">Valider</button>
        </form>

        <form class="padding-one" action="http://localhost:8089/user/delete">
            <?php
            $user = $data["selected"];
            echo "<input hidden type='text' name='id' value=$user->id>";
            ?>
            <button type="submit">Supprimer</button>
        </form>
    </div>
</div>
<?php require_once("../app/views/footer-view.php"); ?>