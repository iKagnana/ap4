<?php require_once ("../app/views/header-view.php"); ?>
<div class="page-container">
    <div class="banner-black flex-col-container">
        <h1>Crée un compte</h1>
        <span>Et profitez des fonctionnalités de notre site</span>
    </div>
    <div class="flex-col-container">
        <div>
            <?php echo isset ($data["error"]) ? "<span class='text-error'>" . $data["error"] . "</span>" : ""; ?>
        </div>
        <form class="form-center-item" action="http://localhost:8089/user/create" method="post" autocomplete="off">
            <div class="radio-container">
                <span>Votre status</span>
                <div class="radio-group">
                    <div>
                        <input type="radio" value="1" name="type" id="employee">
                        <label for="employee">Employé</label>
                    </div>

                    <div>
                        <input type="radio" value="2" name="type" id="client">
                        <label for="client">Client</label>
                    </div>
                </div>
            </div>

            <div class="not-full-width">
                <label for="enterprise">Nom de l'entreprise (Client uniquement)</label>
                <input type="text" name="enterprise" id="enterprise">
            </div>

            <div class="not-full-width">
                <label for="lastname">Nom</label>
                <input type="text" name="lastname" id="lastname">
            </div>

            <div class="not-full-width">
                <label for="firstname">Prénom</label>
                <input type="text" name="firstname" id="firstname">
            </div>

            <div class="not-full-width">
                <label for="email">Email</label>
                <input type="text" name="email" id="email">
            </div>

            <div class="not-full-width">
                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password">
            </div>

            <input hidden type="text" name="origin" value="user">
            <button class="styled-button" type="submit">
                <span class="styled-span">Valider</span>
                </svg>
            </button>
        </form>

        <form action="http://localhost:8089">
            <button class="styled-button" type="submit">
                <div class="styled-span">
                    <svg xmlns="http://www.w3.org/2000/svg" width=15 viewBox="0 0 24 24" stroke="currentColor"
                        fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
    </div>
</div>
<?php require_once ("../app/views/footer-view.php"); ?>