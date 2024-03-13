<?php require_once("../app/views/header-view.php"); ?>
<div class="page-container">
    <div class="flex-col-container padding-one">
        <div class="filter-wrapper">
            <div class="filter-header">
                <label for="">Filter :</label>
                <input form="filter" type="submit" value="🔍">
            </div>
            <form class="form-filter-user padding-one" id="filter" action="http://localhost:8089/user/filter"
                method="GET">

                <div class="textfield-label">
                    <label for=" searchbar">Rechercher</label>
                    <input type="text" name="search" id="searchbar">
                </div>
                <div class="textfield-label">
                    <label for="filterBy">Status</label>

                    <select name="filter" id="filterBy">
                        <?php
                        echo "<option value='all'>Tous</option>";
                        echo isset($data["filter"]) && $data["filter"] == "waiting" ? "<option value='waiting' selected>En attente</option>" : "<option value='waiting' >En attente</option>";
                        echo isset($data["filter"]) && $data["filter"] == "valid" ? "<option value='valid' selected>Validé</option>" : "<option value='valid' >Validé</option>";
                        echo isset($data["filter"]) && $data["filter"] == "refused" ? "<option value='refused' selected>Refusé</option>" : "<option value='refused' >Refusé</option>";
                        ?>
                    </select>
                </div>
            </form>
            <form class="filter-footer" action="http://localhost:8089/user" method="GET">
                <button type="submit">Réinitialiser les filtres</button>
            </form>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Entreprise</th>
                    <th>Nom</th>
                    <th>Prenom</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Niveau d'accès</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($data["users"] as $user) {
                    echo "<tr>";
                    echo "<td>$user->id </td>";
                    echo "<td>$user->enterprise </td>";
                    echo "<td>$user->lastname </td>";
                    echo "<td>$user->firstname </td>";
                    echo "<td>$user->email </td>";
                    echo "<td>$user->role </td>";
                    echo "<td>$user->levelAccess </td>";
                    echo "<td>$user->status </td>";
                    echo "<td>
                        <form action='http://localhost:8089/user/details'>
                            <button type='submit'>✏️</button>
                            <input hidden name='id' type='text' value=$user->id>
                        </form>
                        </td>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<script>
</script>
<?php require_once("../app/views/footer-view.php"); ?>