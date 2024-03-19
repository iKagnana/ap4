<?php require_once ("../app/views/header-view.php"); ?>
<div class="page-container align-top">
    <div class="flex-col-container padding-one">
        <div class="flex-row-container">
            <form class="flex-row" action="http://localhost:8089/provider/search" method="get">
                <input class="not-full-width" type="text" name="search" id="searchbar" placeholder="Rechercher...">
                <button class="button-outlined" type="submit" form="filter">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                        <path d="M21 21l-6 -6" />
                    </svg>
                </button>
            </form>
        </div>
        <div>
            <?php echo isset ($data["error"]) ? "<span class='text-error'>" . $data["error"] . "</span>" : ""; ?>
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
                    if (isset ($data["selected"]) && $data["selected"] == $provider->id) {
                        echo "<tr>";
                        echo "<td>$provider->id</td>";
                        echo "<td><input class='not-full-width' type='text' name='name' value=$provider->name></td>";
                        echo "<td><input class='not-full-width' type='text' name='email' value=$provider->email></td>";
                        echo "<td>
                            <form action='http://localhost:8089/provider/update' method='POST'>
                                <button class='button-outlined' type='submit'>Modifier</button>
                                <input hidden type='text' name='id' value=$provider->id>
                            </form>
                            <form action='http://localhost:8089/provider/delete' method='POST'>
                                <button class='button-outlined red' type='submit'>Supprimer</button>
                                <input hidden type='text' name='id' value=$provider->id>
                            </form>
                            <form action='http://localhost:8089/provider'>
                                <button class='button-outlined' type='submit'>
                    <svg  xmlns='http://www.w3.org/2000/svg'  width='20'  height='20'  viewBox='0 0 24 24'  fill='none'  stroke='currentColor'  stroke-width='2'  stroke-linecap='round'  stroke-linejoin='round'><path stroke='none' d='M0 0h24v24H0z' fill='none'/><path d='M18 6l-12 12' /><path d='M6 6l12 12' /></svg>
                    </button>
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
                                <button class='button-outlined' name='id' value=$provider->id>
                                <input hidden type='text' name='id' value=$provider->id>
                                <div><svg xmlns='http://www.w3.org/2000/svg'  width='20'  height='20'  viewBox='0 0 24 24'  fill='none'  stroke='currentColor'  stroke-width='2'  stroke-linecap='round'  stroke-linejoin='round'><path stroke='none' d='M0 0h24v24H0z' fill='none'/><path d='M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4' /><path d='M13.5 6.5l4 4' /></svg></div>
                                </button>
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
<?php require_once ("../app/views/footer-view.php"); ?>