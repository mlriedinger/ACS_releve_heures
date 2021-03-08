<li class="nav-item ps-4">
    <a class="nav-link" href="index.php?action=showRecordsToCheck">Validation</a>
</li>

<li class="nav-item dropdown ps-4">
    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Historique</a>
    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
        <li><a class="dropdown-item" href="index.php?action=showPersonalRecordsLog">Historique personnel</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="index.php?action=showTeamRecordsLog">Historique des équipes</a></li> 
        <?= $_SESSION['id_group'] == 1 ? '<li><a class="dropdown-item" href="index.php?action=showAllRecordsLog">Historique global</a></li>' : ''; ?>
    </ul>
</li>