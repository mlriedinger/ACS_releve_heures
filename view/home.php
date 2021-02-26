<?php session_start(); ?>

<!DOCTYPE html>
<html lang="fr">

    <?php include('partials/head.php'); ?>

    <body>
    <?php include('partials/navbar.php'); ?>

        <div class="bd-cheatsheet container-fluid bg-body">
            <h1>Bienvenue <?= $userData['Prenom'] . ' ' . $userData['Nom'] . ' ';?>!</h1>
        </div>
       
            
        
        
        <?php include('partials/footer.php'); ?>
    </body>
</html>