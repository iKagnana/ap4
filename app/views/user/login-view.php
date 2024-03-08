<?php require_once("../app/views/header-view.php"); ?>
<div class="page-container">
    <div class="plain-left-side">
        <img class="cover" src="https://cdn.pixabay.com/photo/2014/08/02/11/40/high-bay-408222_1280.jpg"
            alt="image entrepôt">
    </div>
    <div class="plain-right-side login-container">
        <form class="align-center" action="http://localhost:8089/login/askLogin" method="post">
            <div class="textfield">
                <label for="email">Email</label>
                <input class="textfield-input" type="email" name="email" id="email">
            </div>
            <br>
            <div class="textfield">
                <label for="password">Mot de passe</label>
                <input class="textfield-input" type="password" name="password" id="password">
            </div>
            <br>
            <input type="submit" value="Valider">
        </form>

        <form action="" method="POST">
        </form>
    </div>
</div>
<?php require_once("../app/views/footer-view.php"); ?>