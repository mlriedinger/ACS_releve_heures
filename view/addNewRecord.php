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

        <?php include('partials/footer.php'); ?>
        <?php include('partials/recordFormScripts.php'); ?>
    </body>
</html>