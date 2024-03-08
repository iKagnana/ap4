<?php require_once("../app/views/header-view.php"); ?>
<div class="page-container">
    <form class="form-create-account" action="http://localhost:8089/user/create" method="post">
        <div>
            <div>
                <input type="radio" value="employee" name="type" id="employee">
                <label for="employee">Employé</label>
            </div>

            <div>
                <input type="radio" value="client" name="type" id="client">
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


        <button type="submit">Valdier</button>
    </form>
</div>
<?php require_once("../app/views/footer-view.php"); ?>