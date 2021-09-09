<?php session_start(); ?>

<!DOCTYPE html>
<html lang="fr" class="h-100">

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
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
        <link rel="preconnect" href="https://fonts.gstatic.com">
		
		<!-- Chargement des feuilles de style du tour guide -->
        <link href="node_modules/intro.js/introjs.css" rel="stylesheet" />
        
        <!-- Toujours mettre la feuille de style en dernière position ! -->
        <link rel="stylesheet" href="public/css/style.css" />

        <!-- CDN pour charger jQuery -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    </head>

    <body class="d-flex flex-column h-100">
        <header><?php include 'partials/navbar.php'; ?></header>
    
        <main class="flex-shrink-0" id="<?= $id ?>">
            <div class="container">
                <h2 class="display-6 mt-5 mb-5 text-center"> <?= $heading ?> </h2>
                
                <?= $content ?>

                <?php 
                    include 'partials/toastAlertSuccess.php';
                    include 'partials/toastAlertError.php';
                ?>

            </div>
        </main>

        <?php include('partials/footer.php'); ?>

        <script id="mainScript" src="public/js/main.js"></script>
        <script>
            window.onload = function() {
                let selectors = new Array("<?= $menuSelector ?>", "<?= $iconSelector ?>");
                updateValidationBadge('team', 'pending');
                updateNavBarActiveAttribute(selectors);
                <?= $additionalOnloadScript ?>
            }
            
            <?= $script ?>
        </script>
		
		<!-- Script de fonctionnment du tour guide -->
		<script src="node_modules/intro.js/intro.js"></script>
        

        <?php include('partials/toastInitializationScript.php'); ?>
    </body>
</html>