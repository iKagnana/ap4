<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>GSB - connexion </title>
</head>

<body>
    <div class="page-container">
        <div class="left-side">
            <img class="cover" src="https://cdn.pixabay.com/photo/2014/08/02/11/40/high-bay-408222_1280.jpg"
                alt="image entrepôt">
        </div>
        <div class="right-side">
            <form class="align-center" action="index.php?controller=login&action=askLogin" method="post">
                <div class="textfield">
                    <span>Email</span>
                    <input class="textfield-input" type="text" name="email" id="">
                </div>
                <br>
                <div class="textfield">
                    <span>Mot de passe</span>
                    <input class="textfield-input" type="text" name="password" id="">
                </div>
                <br>
                <input type="submit" value="Valider">
            </form>
        </div>
    </div>
</body>

</html>