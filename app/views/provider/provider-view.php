<?php require_once("../app/views/header-view.php"); ?>
<div class="page-container">
    <div class="flex-col-container padding-one">
        <div class="flex-row-container">
            <form class=" padding-one" action="http://localhost:8089/provider/search" method="get">
                <label for=" searchbar">Rechercher le nom</label>
                <input type="text" name="search" id="searchbar">
                <input type="submit" value="🔍">
            </form>
        </div>
        <div>
            <?php echo isset($data["error"]) ? "<span class='text-error'>" . $data["error"] . "</span>" : ""; ?>
        </div>
        <table>
            <thead>
                <tr>
                    <td>Id</td>
                    <td>Nom</td>
                    <td>Email</td>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($data["all"] as $provider) {
                    if (isset($data["selected"]) && $data["selected"] == $provider->id) {
                        echo "<tr>";
                        echo "<td>$provider->id</td>";
                        echo "<td><input type='text' name='name' value=$provider->name></td>";
                        echo "<td><input type='text' name='email' value=$provider->email></td>";
                        echo "<td>
                            <form action='http://localhost:8089/provider/delete' method='POST'>
                                <button type='submit'>Supprimer</button>
                                <input hidden type='text' name='id' value=$provider->id>
                            </form>
                            <form action='http://localhost:8089/provider'>
                                <button type='submit'>Quitter</button>
                            </form>
                            </td>";
                        echo "</tr>";
                    } else {
                        echo "<tr>";
                        echo "<td>$provider->id</td>";
                        echo "<td>$provider->name</td>";
                        echo "<td>$provider->email</td>";
                        echo "<td>
                            <form action='http://localhost:8089/provider/details' method='GET'>
                                <button type='submit'>✏️</button>
                                <input hidden type='text' name='id' value=$provider->id>
                            </form>
                            </td>";
                        echo "</tr>";
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once("../app/views/footer-view.php"); ?>