<?php 

    $title = "Nouveau relevé";
    $heading = "Nouveau relevé";

    ob_start(); ?>
    <?php include 'partials/recordForm.php'; ?>

    <?php $content = ob_get_clean(); ?>

    <?php ob_start(); ?>
        <script>
            window.onload = function() {
                getOptionsData('add', 'worksites', <?= $_SESSION['userId']?>);
            }
        </script>
    <?php $script = ob_get_clean(); ?>
    
    <?php require 'template.php'; ?>