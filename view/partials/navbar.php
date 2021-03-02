<nav class="navbar navbar-expand-lg navbar-light bg-light">
           
                <div class="container-fluid d-flex align-items-center">
                    <a class="navbar-brand" href="#">
                        <img src="public/img/logo.png" alt="Logo" height="70vw"/>
                    </a>

                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarContent">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="index.php?action=showHomePage">Accueil</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="index.php?action=showNewRecordForm">Nouveau relevé</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="index.php?action=showRecordsToCheck">Validation</a>
                            </li>
                            <?php if($_SESSION['id_group'] == 1 || $_SESSION['id_group'] == 2){
                                echo '
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Historique</a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="index.php?action=showPersonnalRecordsLog">Historique personnel</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="index.php?action=showTeamRecordsLog">Historique des équipes</a></li>
                                    <li><a class="dropdown-item" href="index.php?action=showAllRecordsLog">Historique global</a></li>
                                </ul>
                            </li>';
                            } else {
                                echo ' <li class="nav-item">
                                <a class="nav-link" href="index.php?action=showPersonnalRecordsLog">Historique</a>
                            </li>';
                            } ?>
                        </ul>
                    </div>
                </div>
        </nav>