<?php session_start(); ?>

<!DOCTYPE html>
<html lang="fr">

    <?php include('partials/head.php'); ?>

    <body>
    <?php include('partials/navbar.php');?>

        <div class="container">

            <h2 class="display-6 mt-5 mb-5 text-center">Historique global</h2>

            <div class="row mb-5">

                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="all_records-tab" onclick="updateAllUsersRecordsLog('All', 'all')" data-bs-toggle="tab" data-bs-target="#all_records" type="button" role="tab" aria-controls="all_records" aria-selected="true">Tous</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="valid_records-tab" onclick="updateAllUsersRecordsLog('All', 'valid')" data-bs-toggle="tab" data-bs-target="#valid_records" type="button" role="tab" aria-controls="valid_records" aria-selected="false">Validés</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="unchecked_records-tab" onclick="updateAllUsersRecordsLog('All', 'unchecked')" data-bs-toggle="tab" data-bs-target="#unchecked_records" type="button" role="tab" aria-controls="unchecked_records" aria-selected="false">En attente</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="deleted_records-tab" onclick="updateAllUsersRecordsLog('All', 'deleted')" data-bs-toggle="tab" data-bs-target="#deleted_records" type="button" role="tab" aria-controls="deleted_records" aria-selected="false">Supprimés</button>
                    </li>
                </ul>

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="all_records" role="tabpanel" aria-labelledby="all_records-tab">
                        <table class="table table-sm table-striped table-hover mt-4" id="records_log">
                            <thead>
                                <tr id="table-head">
                                    <th scope="col" id="worksite">Chantier</th>
                                    <th scope="col" id="manager">Manager</th>
                                    <th scope="col" id="employee">Salarié</th>
                                    <?= $_SESSION['dateTimeMgmt'] == 1 ? '<th scope="col" id="start">Début</th>' : ""; ?>
                                    <?= $_SESSION['dateTimeMgmt'] == 1 ? '<th scope="col" id="end">Fin</th>' : ""; ?>
                                    <?= $_SESSION['lengthMgmt'] == 1 ? '<th scope="col" id="date">Date</th>' : ""; ?>
                                    <?= $_SESSION['lengthMgmt'] == 1 ? '<th scope="col" id="workTime">Travail</th>' : ""; ?>
                                    <?= $_SESSION['breakMgmt'] == 1 ? '<th scope="col" id="breakTime">Pause</th>' : ""; ?>
                                    <?= $_SESSION['tripMgmt'] == 1 ? '<th scope="col" id="tripTime">Trajet</th>' : ""; ?>
                                    <th scope="col" id="comment">Commentaire</th>
                                    <th scope="col" id="status">Statut</th>
                                    <th scope="col" id="updateDate">Modifié le</th>
                                    <th scope="col" id="edit"></th>
                                    <th scope="col" id="delete"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr></tr>
                            </tbody>
                        </table> 

                        <p id="no_record_message" class="lead text-center mt-5" hidden>Aucun relevé à afficher.</p>
                    </div>
                </div> 

            </div>

            <?php include('partials/modal.php'); ?>

            <?php 
                if($_SESSION['success'] == 1) {
                    include('partials/toastAlert.php');
                    unset($_SESSION['success']);
                }
            ?>
            
        </div>

        <?php include('partials/footer.php'); ?>
        
        <script id="mainScript" src="public/js/main.js"></script>
        <script>
            window.onload = function() {
                getNumberOfRecordsToCheck('Check', 'unchecked');
                updateAllUsersRecordsLog('All', 'all');
            };
        </script>

        <?php include('partials/initalizeToastScript.php'); ?>
        
    </body>
</html>
