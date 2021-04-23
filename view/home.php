<?php 

$title = "Accueil";

?>

<?php ob_start(); ?>

    <h1 class="display-5 text-center mt-5 mb-5">Accueil</h1>

<?php $content = ob_get_clean(); ?>

<?php ob_start(); ?>
    <script>
        window.onload = function() {
            getNumberOfRecordsToCheck('Check', 'unchecked');
    }
    </script>

<?php $script = ob_get_clean(); ?>

<?php require 'template.php'; ?>