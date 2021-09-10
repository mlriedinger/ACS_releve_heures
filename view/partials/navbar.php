<nav class="navbar navbar-expand-lg navbar-light bg-light">
           
    <div class="container-fluid d-flex align-items-center mt-3 mb-3">
        <a class="navbar-brand" href="index.php?action=showHomePage">
            <img src="<?=$_SESSION['imgFilePath'] . $_SESSION['logo']?>" alt="Logo" height="50"/>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item ps-5 mb-3 mb-lg-0">
                    <div class="d-flex flex-column align-items-center navbar-div">
                        <i class="bi bi-house text-dark" id="homeIcon"></i>
                        <a class="nav-link text-center text-center" id="homeLink" aria-current="page" href="index.php?action=showHomePage">Accueil</a>
                    </div>
                </li>
                <li class="nav-item ps-5 mb-3 mb-lg-0">
                    <div class="d-flex flex-column align-items-center navbar-div">
                        <i class="bi bi-clock-history text-dark" id="newRecordIcon"></i>
                        <a class="nav-link text-center text-center" id="newRecordLink" aria-current="page" href="index.php?action=showNewRecordForm">Nouveau relevé</a>
                    </div>
                </li>

                <?php 
                if($_SESSION['userGroup'] === $_SESSION['GroupAdmin'] || $_SESSION['userGroup'] === $_SESSION['GroupManager']){ 
                    ?>
                    <li class="nav-item ps-5 mb-3 mb-lg-0">
                        <div class="d-flex flex-column align-items-center navbar-div">
                            <i class="bi bi-journal-check text-dark" id="validationIcon"></i>
                            <a class="nav-link text-center" id="validationLink" aria-current="page" href="index.php?action=showPendingRecordsLog">Validation<span id="notificationIcon" class="badge rounded-pill bg-danger ms-2"></span></a>
                        </div>
                    </li>

                    <li class="nav-item dropdown ps-5 mb-3 mb-lg-0">
                        <div class="d-flex flex-column align-items-center navbar-div">
                            <i class="bi bi-journal-text text-dark" id="historyIcon"></i>    
                            <a class="nav-link text-center dropdown-toggle" aria-current="page" href="index.php?action=showPersonalRecordsLog" id="historyLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">Historique</a>
                            <ul class="dropdown-menu" aria-labelledby="historyLink">
                                <li><a class="dropdown-item" href="index.php?action=showPersonalRecordsLog">Historique personnel</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <?php 
                                if($_SESSION['userGroup'] === $_SESSION['GroupAdmin']) {
                                    ?>
                                    <li><a class="dropdown-item" href="index.php?action=showAllRecordsLog">Historique global</a></li>
                                <?php
                                } ?>
                            </ul>
                        </div>
                    </li>
                <?php 
                } else { 
                    ?>
                    <li class="nav-item ps-5 mb-3 mb-lg-0">
                        <div class="d-flex flex-column align-items-center navbar-div">
                            <i class="bi bi-journal-bookmark-fill text-dark" id="historyIcon"></i>
                            <a class="nav-link text-center" id="historyLink" href="index.php?action=showPersonalRecordsLog">Historique</a>
                        </div>
                    </li>
                <?php 
                } ?>

                <?php 
                if($_SESSION['userGroup'] === $_SESSION['GroupManager']){ 
                    ?>
                    <li class="nav-item ps-5 mb-3 mb-lg-0">
                        <div class="d-flex flex-column align-items-center navbar-div">
                            <i class="bi bi-download text-dark" id="exportIcon"></i>
                            <a class="nav-link text-center text-center" id="exportLink" aria-current="page" href="index.php?action=showExportForm">Export</a>
                        </div>
                    </li>
                <?php 
                } ?>
            </ul> 
            
            <ul class="navbar-nav mb-2 mb-lg-0">
            <?php 
            if($_SESSION['userGroup'] === $_SESSION['GroupAdmin']){ 
                ?>
                <li class="nav-item dropdown ps-5 mb-3 mb-lg-0">
                    <div class="d-flex flex-column align-items-center navbar-div">
                        <i class="bi bi-sliders text-dark" id="adminIcon"></i>    
                        <a class="nav-link text-center dropdown-toggle" aria-current="page" id="adminLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">Administration</a>
                        <ul class="dropdown-menu" aria-labelledby="adminLink">
                            <li><a class="dropdown-item" href="index.php?action=showExportForm">Export</a></li>
                            <li><a class="dropdown-item" href="index.php?action=showSettingsForm">Paramètres</a></li> 
                            </ul>
                    </div>
                </li>
            <?php 
            } ?>

                <li class="nav-item ps-5 mb-3 mb-lg-0">
                    <div class="d-flex flex-column align-items-center navbar-div">
                        <i class="bi bi-person text-dark"></i>
                        <span class="nav-link text-center"><?= $_SESSION['firstname'] . ' ' . $_SESSION['name']?></span>
                    </div>
                </li>

                
                <li class="nav-item ps-5 mb-3 mb-lg-0">
                    <div class="d-flex flex-column align-items-center navbar-div">
                        <i class="bi bi-door-open text-dark"></i>
                        <a class="nav-link text-center" aria-current="page" href="index.php?action=logout">Déconnexion</a>    
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>