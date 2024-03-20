<?php require_once ("../app/views/header-view.php"); ?>
<div class="page-container">
    <div class="flex-col-container">
        <?php echo "<form class='m-1' action=$host/user>" ?>
        <button class="styled-button" type="submit">
            <div class="styled-span">
                <svg xmlns="http://www.w3.org/2000/svg" width=15 viewBox="0 0 24 24" stroke="currentColor" fill="none"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M5 12l14 0" />
                    <path d="M5 12l6 6" />
                    <path d="M5 12l6 -6" />
                </svg>
                <span>Retour</span>
            </div>
            </svg>
        </button>
        </form>

        <div>
            <?php echo isset ($data["error"]) ? "<span class='text-error'>" . $data["error"] . "</span>" : ""; ?>
        </div>

        <?php echo "<form class='form-center-item' action=$host/user/update method=post>"; ?>
        <?php
        $user = $data["selected"];
        echo "<input hidden type='text' name='id' value=$user->id>";

        echo "<div class='half-width'>";
        echo "<label for='role'>Rôle</label>";
        echo "<select name='role' id='role'>";
        echo $user->role == 2 ? "<option value='2' selected>Client</option>" : "<option value='2'>Client</option>";
        echo $user->role == 1 ? "<option value='1' selected>Employé</option>" : "<option value='1'>Employé</option>";
        echo $user->role == 0 ? "<option value='0' selected>Administrateur</option>" : "<option value='0'>Administrateur</option>";
        echo "</select>";
        echo "</div>";

        echo "<div class='half-width'>
                <label for='enterprise'>Nom de l'entreprise (Client uniquement)</label>
                <input type='text' name='enterprise' id='enterprise' value=$user->enterprise>
            </div>
            
            <div class='half-width'>
                <label for='lastname'>Nom</label>
                <input type='text' name='lastname' id='lastname' value=$user->lastname>
            </div>

            <div class='half-width'>
                <label for='firstname'>Prénom</label>
                <input type='text' name='firstname' id='firstname' value=$user->firstname>
            </div>

            <div class='half-width'>
                <label for='email'>Email</label>
                <input type='text' name='email' id='email' value=$user->email>
            </div>";

        echo "<div class='half-width'>";
        echo "<label for='level-access'>Niveau d'accès maximum</label>";
        echo "<select name='levelAccess' id='level-access'>";
        echo $user->levelAccess == 1 ? "<option value='1' selected>1</option>" : "<option value='1'>1</option>";
        echo $user->levelAccess == 2 ? "<option value='2' selected>2</option>" : "<option value='2'>2</option>";
        echo $user->levelAccess == 3 ? "<option value='3' selected>3</option>" : "<option value='3'>3</option>";
        echo "</select>
            </div>

            <div class='half-width'>
                <label for='status'>Status</label>";
        echo "<select name='status' id='status'>";
        echo $user->status == "Validé" ? "<option value='Validé' selected>Validé</option>" : "<option value='Validé'>Validé</option>";
        echo $user->status == "Refusé" ? "<option value='Refusé' selected>Refusé</option>" : "<option value='Refusé'>Refusé</option>";
        echo "</select>
            </div>";
        ?>
        <button class="styled-button" type="submit">
            <span class="styled-span">Valider</span>
        </button>
        </form>

        <?php echo "<form class='padding-one' action=$host/user/delete>"; ?>
        <?php
        $user = $data["selected"];
        echo "<input hidden type='text' name='id' value=$user->id>";
        ?>
        <button class="styled-button-red" type="submit">
            <span class="styled-span">Supprimer</span>
            </svg>
        </button>
        </form>
    </div>
</div>
<?php require_once ("../app/views/footer-view.php"); ?>