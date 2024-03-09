<?php require_once("../app/views/header-view.php"); ?>
<div class="page-container">
    <div class="flex-col-container padding-one">
        <div class="flex-row-container">
            <form class=" padding-one" action="http://localhost:8089/user/search" method="GET">
                <label for=" searchbar">Rechercher le nom</label>
                <input type="text" name="search" id="searchbar">
                <input type="submit" value="🔍">
            </form>
            <form class=" padding-one" id="filter" action="http://localhost:8089/user/filter" method="GET">
                <label for="filterBy">Trier par utilisateurs :</label>
                <button type="submit">
                    <select class="select-filterBy" name="filter" id="filterBy">
                        <?php
                        echo "<option value='all'>Tous</option>";
                        echo isset($data["filter"]) && $data["filter"] == "waiting" ? "<option value='waiting' selected>En attente</option>" : "<option value='waiting' >En attente</option>";
                        echo isset($data["filter"]) && $data["filter"] == "valid" ? "<option value='valid' selected>Validé</option>" : "<option value='valid' >Validé</option>";
                        echo isset($data["filter"]) && $data["filter"] == "refused" ? "<option value='refused' selected>Refusé</option>" : "<option value='refused' >Refusé</option>";
                        ?>
                    </select>
                </button>
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
<?php require_once("../app/views/footer-view.php"); ?>