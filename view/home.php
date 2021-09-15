<?php 

$title = "Accueil";
$id="homeBackGround";
?>

<?php ob_start(); ?>

<?php 
    $firstDayOfWeek = date("Y-m-d", strtotime(sprintf("%4dW%02d", strftime('%G'), strftime('%V'))));
    $monday = new DateTime($firstDayOfWeek);
?>
    <h3 class="mt-4">Mes heures</h3>
    <hr class="mb-5">

    <fieldset class="mb-5">
        <div class="row mb-5 text-center justify-content-evenly align-items-end" id="divUserStats">
 
            <h2 class="display-6 mt-5 mb-5"><i class="bi bi-chevron-left pe-5 text-secondary"></i><?= "Semaine " . strftime('%V');?><i class="bi bi-chevron-right ps-5 text-secondary"></i></h2>

            <div class="col-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100">
                    <circle cx="50" cy="50" r="35" style="stroke: #F2E2DC; stroke-width: 2; fill: #fff" />
                    <text id="totalWeekDay_2" class="textTag" x="50" y="50" text-anchor="middle" font-size="1.2em" font-family="Arial" dy=".3em"></text>
                </svg>
                <p class="fs-5 text-center" id="weekDay_2" style="position: relative">Lun. <?= $monday->format('d/m') ?></p>
            </div> 

            <div class="col-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100">
                    <circle cx="50" cy="50" r="35" style="stroke: #F2E2DC; stroke-width: 2; fill: #fff" />
                    <text id="totalWeekDay_3" class="textTag" x="50" y="50" text-anchor="middle" font-size="1.2em" font-family="Arial" dy=".3em"></text>
                </svg>
                <p class="fs-5 text-center" id="weekDay_3" style="position: relative">Mar. <?php $tuesday = $monday->modify('+1 day'); echo $tuesday->format('d/m')?></p>
            </div>

            <div class="col-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100">
                    <circle cx="50" cy="50" r="35" style="stroke: #F2E2DC; stroke-width: 2; fill: #fff" />
                    <text id="totalWeekDay_4" class="textTag" x="50" y="50" text-anchor="middle" font-size="1.2em" font-family="Arial" dy=".3em"></text>
                </svg>
                <p class="fs-5 text-center" id="weekDay_4" style="position: relative">Mer. <?php $wednesday = $monday->modify('+1 day'); echo $wednesday->format('d/m')?></p>
            </div>

            <div class="col-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100">
                    <circle cx="50" cy="50" r="35" style="stroke: #F2E2DC; stroke-width: 2; fill: #fff" />
                    <text id="totalWeekDay_5" class="textTag" x="50" y="50" text-anchor="middle" font-size="1.2em" font-family="Arial" dy=".3em"></text>
                </svg>
                <p class="fs-5 text-center" id="weekDay_5" style="position: relative">Jeu. <?php $thursday = $monday->modify('+1 day'); echo $thursday->format('d/m')?></p>
            </div>

            <div class="col-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100">
                    <circle cx="50" cy="50" r="35" style="stroke: #F2E2DC; stroke-width: 2; fill: #fff" />
                    <text id="totalWeekDay_6" class="textTag" x="50" y="50" text-anchor="middle" font-size="1.2em" font-family="Arial" dy=".3em"></text>
                </svg>
                <p class="fs-5 text-center" id="weekDay_6" style="position: relative">Ven. <?php $friday = $monday->modify('+1 day'); echo $friday->format('d/m')?></p>
            </div>

            <div class="col-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100">
                    <circle cx="50" cy="50" r="35" fill="#C63527" />
                    <text id="weeklyTotal" x="50" y="50" text-anchor="middle" fill="white" font-size="1.2em" font-family="Arial" dy=".3em"></text>
                </svg>
                <p class="fs-5 text-center" id="currentWeek" style="position: relative">Total</p>
            </div>

        </fieldset>

    <h3 class="mt-4">Mes chantiers</h3>
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
getUserDataForCurrentWeek('<?= $_SESSION['userUUID'] ?>');
getUserWeeklyTotal('<?= $_SESSION['userUUID']?>');

<?php $additionalOnloadScript = ob_get_clean(); ?>

<?php require 'template.php'; ?>