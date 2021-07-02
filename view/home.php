<?php 

$title = "Accueil";
$id="homeBackGround";
?>

<?php ob_start(); ?>

    <h2 id="homeHeading">Bienvenue <?= $_SESSION['firstname'] . ' ' ?>!</div>

    <div class="divider mt-5 mb-3"></div>

    <div class="row mb-5">
        <div class="col mt-3">
            <p class="fs-4 text-center" id="currentDate" style="position: relative">JJ/MM/AAAA</p>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col text-center" id="listOfEvents">
            
        </div>
    </div>

<?php $content = ob_get_clean(); ?>

<?php ob_start(); ?>
    <script>
        window.onload = function() {
            getNumberOfRecordsToCheck('Check', 'unchecked');
            getCurrentDate();
            getEventsFromPlanning(<?= $_SESSION['userId']?>);
            
            var menuItemSelector = "#navbarContent > ul.navbar-nav.me-auto.mb-2.mb-lg-0 > li:nth-child(1) > div > a";
            updateNavBarActiveAttribute(menuItemSelector);
			var iconSelector = "#navbarContent > ul.navbar-nav.me-auto.mb-2.mb-lg-0 > li:nth-child(1) > div > i";
			updateNavBarActiveAttribute(iconSelector);
    }

        function getCurrentDate() {
            const today = new Date();
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };

            currentDate = document.getElementById("currentDate");
            currentDate.innerHTML = today.toLocaleDateString('fr-FR', options).substr(0,1).toUpperCase();
            currentDate.innerHTML += today.toLocaleDateString('fr-FR', options).substr(1);
        }

    </script>

<?php $script = ob_get_clean(); ?>

<?php require 'template.php'; ?>