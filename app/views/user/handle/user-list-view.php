<?php require_once ("../app/views/header-view.php"); ?>
<div class="page-container align-top">
    <div class="flex-col-container padding-one">
        <div class="filter-wrapper">
            <div class="filter-header">
                <label for="">Filtres :</label>
                <button class="button-outlined" type="submit" form="filter">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                        <path d="M21 21l-6 -6" />
                    </svg>
                </button>
            </div>
            <?php echo "<form class='form-filter-user padding-one' id='filter' action=$host/user/filter method='GET'>" ?>


            <div class="textfield-label">
                <label for=" searchbar">Rechercher</label>
                <?php echo isset($data["searchName"]) ? "<input type='text' name='search' id='searchbar' value=" . $data["searchName"] . ">" : "<input type='text' name='search' id='searchbar'>"; ?>
            </div>
            <div class="textfield-label">
                <label for="filterBy">Status</label>

                <select name="filter" id="filterBy">
                    <?php
                    echo "<option value='all'>Tous</option>";
                    echo isset($data["filter"]) && $data["filter"] == "En attente de validation" ? "<option value='En attente de validation' selected>En attente</option>" : "<option value='En attente de validation' >En attente</option>";
                    echo isset($data["filter"]) && $data["filter"] == "Validé" ? "<option value='Validé' selected>Validé</option>" : "<option value='Validé' >Validé</option>";
                    echo isset($data["filter"]) && $data["filter"] == "Refusé" ? "<option value='Refusé' selected>Refusé</option>" : "<option value='Refusé' >Refusé</option>";
                    ?>
                </select>
            </div>
            </form>
            <?php echo "<form class='filter-footer' action=$host/user method='GET'>" ?>
            <button class="button-outlined" type="submit">Réinitialiser les filtres</button>
            </form>
        </div>

        <div>
            <?php echo isset($data["error"]) ? "<span class='text-error'>" . $data["error"] . "</span>" : ""; ?>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nom</th>
                    <th>Prenom</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Niveau d'accès</th>
                    <th>Status</th>
                    <th>Métier</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($data["particuliers"] as $user) {
                    echo "<tr>";
                    echo "<td>$user->id </td>";
                    echo "<td>$user->lastname </td>";
                    echo "<td>$user->firstname </td>";
                    echo "<td>$user->email </td>";
                    echo "<td>$user->role </td>";
                    echo "<td>$user->levelAccess </td>";
                    echo "<td>$user->status </td>";
                    echo "<td>$user->metier </td>";
                    echo "<td>";
                    echo "<form action='$host/user/details'>";
                    echo "<button class='button-outlined' name='id' value=$user->id>";
                    echo "<div><svg xmlns='http://www.w3.org/2000/svg'  width='20'  height='20'  viewBox='0 0 24 24'  fill='none'  stroke='currentColor'  stroke-width='2'  stroke-linecap='round'  stroke-linejoin='round'><path stroke='none' d='M0 0h24v24H0z' fill='none'/><path d='M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4' /><path d='M13.5 6.5l4 4' /></svg></div>";
                    echo "</button>";
                    echo "</form>";
                    echo "</td>";
                }
                ?>
            </tbody>
        </table>

        <table>
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nom</th>
                    <th>Prenom</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Niveau d'accès</th>
                    <th>Status</th>
                    <th>Numéro Siret</th>
                    <th>Code APE</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($data["entreprises"] as $user) {
                    echo "<tr>";
                    echo "<td>$user->id </td>";
                    echo "<td>$user->lastname </td>";
                    echo "<td>$user->firstname </td>";
                    echo "<td>$user->email </td>";
                    echo "<td>$user->role </td>";
                    echo "<td>$user->levelAccess </td>";
                    echo "<td>$user->status </td>";
                    echo "<td>$user->n_siret </td>";
                    echo "<td>$user->codeAPE </td>";
                    echo "<td>";
                    echo "<form action='$host/user/details'>";
                    echo "<button class='button-outlined' name='id' value=$user->id>";
                    echo "<div><svg xmlns='http://www.w3.org/2000/svg'  width='20'  height='20'  viewBox='0 0 24 24'  fill='none'  stroke='currentColor'  stroke-width='2'  stroke-linecap='round'  stroke-linejoin='round'><path stroke='none' d='M0 0h24v24H0z' fill='none'/><path d='M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4' /><path d='M13.5 6.5l4 4' /></svg></div>";
                    echo "</button>";
                    echo "</form>";
                    echo "</td>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<script>
</script>
<?php require_once ("../app/views/footer-view.php"); ?>