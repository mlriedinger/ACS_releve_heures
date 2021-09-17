<?php 

$title = "Accueil";
$id="homeBackGround";
?>

<?php ob_start(); ?>

    <h4 class="mt-4">Mes heures</h4>
    <hr class="mb-5">

    <fieldset class="mb-5" id="divUserStats">

    </fieldset>

    <h4 class="mt-4">Mes chantiers</h4>
    <hr class="mb-5">

    <div class="row mb-3 justify-content-between" id="listOfEvents">
        <!-- Liste des événements -->
    </div>

<?php $content = ob_get_clean(); ?>

<?php 

$menuSelector = "#homeLink";
$iconSelector = "#homeIcon";
?>

<?php ob_start(); ?>


<?php $script = ob_get_clean(); ?>

<?php ob_start(); ?>
getEventsFromPlanning('<?= $_SESSION['userUUID']?>');
getWeeklyCounters('<?= $_SESSION['userUUID'] ?>', '<?= date("Y/m/d H:i:s") ?>');

<?php $additionalOnloadScript = ob_get_clean(); ?>

<?php require 'template.php'; ?>