<?php require_once("../app/views/header-view.php"); ?>
<div class="page-container">
    <div class="flex-col-container padding-one">
        <form class="padding-one" action="http://localhost:8089/user/search" method="GET">
            <label for=" searchbar">Rechercher le nom</label>
            <input type="text" name="search" id="searchbar">
            <input type="submit" value="🔍">
        </form>

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
                    echo "<td> $user->id </td>";
                    echo "<td> $user->enterprise </td>";
                    echo "<td>$user->lastname </td>";
                    echo "<td>$user->firstname </td>";
                    echo "<td>$user->email </td>";
                    echo "<td>$user->role </td>";
                    echo "<td>$user->levelAccess </td>";
                    echo "<td>$user->status </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once("../app/views/footer-view.php"); ?>