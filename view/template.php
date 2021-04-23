<?php session_start(); ?>

<!DOCTYPE html>
<html lang="fr">

    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no"/>
        
        <title> <?= $title ?> </title>
        
        <!-- Chargement des fichiers Bootstrap en local -->
        <link rel="stylesheet" href="public/css/bootstrap/bootstrap.min.css"/>
        <!-- CDN pour charger Bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous"/>
        
        <!-- CDN pour charger Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
        
        <!-- Toujours mettre la feuille de style en dernière position ! -->
        <link rel="stylesheet" href="public/css/style.css" />
    </head>

    <body>
    <?php include 'partials/navbar.php'; ?>

        <div class="container">
            <h2 class="display-6 mt-5 mb-5 text-center"> <?= $heading ?> </h2>
            
            <?= $content ?>

            <?php 
                include 'partials/toastAlertSuccess.php';
                include 'partials/toastAlertError.php';
            ?>

        </div>

        <script id="mainScript" src="public/js/main.js"></script>
        <?= $script ?>

        <?php include('partials/initalizeToastScript.php'); ?>
        <?php include('partials/footer.php'); ?>
    </body>
</html>