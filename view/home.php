<?php 

$title = "Accueil";
$id="homeBackGround";
?>

<?php ob_start(); ?>

    <h2 id="homeHeading">Bienvenue <?= $_SESSION['firstname'] . ' ' ?>!</h2>

    <div class="divider mt-5 mb-3"></div>

    <div class="row mb-5">
        <div class="col mt-3">
            <p class="fs-4 text-center" id="currentDate" style="position: relative">JJ/MM/AAAA</p>
        </div>
    </div>

    <div class="row mb-3" id="listOfEvents">
        
    </div>

<?php $content = ob_get_clean(); ?>

<?php 

$menuSelector = "#homeLink";
$iconSelector = "#homeIcon";
?>

<?php ob_start(); ?>
function getCurrentDate() {
const today = new Date();
const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };

currentDate = document.getElementById("currentDate");
currentDate.innerHTML = today.toLocaleDateString('fr-FR', options).substr(0,1).toUpperCase();
currentDate.innerHTML += today.toLocaleDateString('fr-FR', options).substr(1);
}

<?php $script = ob_get_clean(); ?>

<?php ob_start(); ?>
getCurrentDate();
getEventsFromPlanning('<?= $_SESSION['userUUID']?>');

<?php $additionalOnloadScript = ob_get_clean(); ?>

<?php require 'template.php'; ?>