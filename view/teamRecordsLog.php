<?php session_start(); ?>

<!DOCTYPE html>
<html lang="fr">

    <?php include('partials/head.php'); ?>

    <body>
    <?php include('partials/navbar.php');?>

        <div class="container">
        
            <h2 class="display-6 mt-5 mb-5 text-center">Historique de l'équipe</h2>

            <div class="row mb-5">

                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="all_records-tab" onclick="updateTeamRecordsLog('Team', 'all')" data-bs-toggle="tab" data-bs-target="#all_records" type="button" role="tab" aria-controls="all_records" aria-selected="true">Tous</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="valid_records-tab" onclick="updateTeamRecordsLog('Team', 'valid')" data-bs-toggle="tab" data-bs-target="#valid_records" type="button" role="tab" aria-controls="valid_records" aria-selected="false">Validés</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="deleted_records-tab" onclick="updateTeamRecordsLog('Team', 'deleted')" data-bs-toggle="tab" data-bs-target="#deleted_records" type="button" role="tab" aria-controls="deleted_records" aria-selected="false">Supprimés</button>
                    </li>
                </ul>

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="all_records" role="tabpanel" aria-labelledby="all_records-tab">
                        <table class="table table-striped table-hover mt-4" id="records_log">
                            <thead>
                                <tr>
                                    <th scope="col">Chantier</th>
                                    <th scope="col">Prénom</th>
                                    <th scope="col">Nom</th>
                                    <th scope="col">Date et heure de début</th>
                                    <th scope="col">Date et heure de fin</th>
                                    <th scope="col">Commentaire</th>
                                    <th scope="col">Statut</th>
                                    <th scope="col">Modifié le</th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
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
        <?php include('partials/recordFormScripts.php'); ?>

        <script id="mainScript" src="public/js/main.js"></script>
        
        <script>
            window.onload = function(){
                getNumberOfRecordsToCheck('Check', 'unchecked');
                updateTeamRecordsLog('Team', 'all');
            }
        </script>

        <?php include('partials/initalizeToastScript.php'); ?>
        
    </body>
</html>
