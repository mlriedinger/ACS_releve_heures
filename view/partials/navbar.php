<nav class="navbar navbar-expand-lg navbar-light bg-light">
           
    <div class="container-fluid d-flex align-items-center">
        <a class="navbar-brand" href="index.php?action=showHomePage">
            <img src="<?=$_SESSION['logo']?>" alt="Logo" height="70vw"/>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item ps-4">
                    <a class="nav-link active" aria-current="page" href="index.php?action=showHomePage">Accueil</a>
                </li>
                <li class="nav-item ps-4">
                    <a class="nav-link" href="index.php?action=showNewRecordForm">Nouveau relevé</a>
                </li>
                <?php if($_SESSION['userGroup'] == 1 || $_SESSION['userGroup'] == 2){
                    require('view/partials/menuManagerAdmin.php');
                } else {
                    echo ' 
                    <li class="nav-item ps-4">
                        <a class="nav-link" href="index.php?action=showPersonalRecordsLog">Historique</a>
                    </li>';
                } ?>
            </ul>
            
            <ul class="navbar-nav mb-2 mb-lg-0">
                <?= $_SESSION['userGroup'] == 1 ? '<li class="nav-item ps-4"><a class="nav-link" href="index.php?action=showSettingsForm"><i class="fas fa-cogs pe-3"></i>Paramètres</a></li>' : ''; ?>
            </ul>

            <div class="navbar-text ps-4">
                <i class="fas fa-user pe-3"></i><?= $_SESSION['firstname'] . ' ' . $_SESSION['name']?>
            </div>

            <ul class="navbar-nav ps-4 mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="index.php?action=logout">Déconnexion</a>    
                </li>
            </ul>
            
        </div>
    </div>
</nav>
