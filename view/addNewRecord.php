<?php session_start(); ?>

<!DOCTYPE html>
<html lang="fr">

    <?php include('partials/head.php'); ?>

    <body>
    <?php include('partials/navbar.php'); ?>

        <div class="container">
            <h2 class="display-6 mt-5 mb-5 text-center">Nouveau relevé</h2>
            
            <?php include('partials/recordForm.php'); ?>

        </div>

        <?php 
            if($_SESSION['success'] === false) {
                include('partials/toastAlertError.php');
                unset($_SESSION['success']);
            }
        ?>

        <script id="mainScript" src="public/js/main.js"></script>
        <script>
            window.onload = function() {
                getOptionsData('add', 'worksites', <?= $_SESSION['userId']?>);
            }
        </script>

        <?php include('partials/footer.php'); ?>
        <?php include('partials/initalizeToastScript.php'); ?>
        <?php include('partials/recordFormScripts.php'); ?>
    </body>
</html>