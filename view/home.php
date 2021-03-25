<?php session_start(); ?>

<!DOCTYPE html>
<html lang="fr">

    <?php include('partials/head.php'); ?>

    <body>
    <?php include('partials/navbar.php'); ?>

        <div class="container">
            <h1 class="display-5 text-center mt-5 mb-5">Accueil</h1>
        </div>
       
        <?php include('partials/footer.php'); ?>
    </body>

    <script id="mainScript" src="public/js/main.js"></script>
    <script>
        $(function() {
            getNumberOfRecordsToCheck('Check', 'unchecked');
        });
    </script>
</html>