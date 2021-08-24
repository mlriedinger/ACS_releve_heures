<?php 

$title = "Equipe";
$heading = "Historique de l'équipe";

?>

<?php ob_start(); ?>

    <div class="row mb-5">

        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="all_records-tab" onclick="updateTeamRecordsLog('Team', 'all')" data-bs-toggle="tab" data-bs-target="#all_records" type="button" role="tab" aria-controls="all_records-tab" aria-selected="true">Tous</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="valid_records-tab" onclick="updateTeamRecordsLog('Team', 'valid')" data-bs-toggle="tab" data-bs-target="#valid_records" type="button" role="tab" aria-controls="valid_records-tab" aria-selected="false">Validés</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="deleted_records-tab" onclick="updateTeamRecordsLog('Team', 'deleted')" data-bs-toggle="tab" data-bs-target="#deleted_records" type="button" role="tab" aria-controls="deleted_records-tab" aria-selected="false">Supprimés</button>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="all_records" role="tabpanel" aria-labelledby="all_records-tab">
                <table class="table table-striped table-hover mt-4" id="records_log">
                    <thead>
                        <tr id="table-head">
                            <th scope="col" id="worksite">Affaire</th>
                            <th scope="col" id="employee">Salarié</th>
                            <?= $_SESSION['dateTimeMgmt'] == 1 ? '<th scope="col" id="start">Début</th>' : ""; ?>
                            <?= $_SESSION['dateTimeMgmt'] == 1 ? '<th scope="col" id="end">Fin</th>' : ""; ?>
                            <?= ($_SESSION['lengthMgmt'] == 1 || $_SESSION['lengthByCategoryMgmt'] == 1) ? '<th scope="col" id="workTime">Temps de travail</th>' : ""; ?>
                            <?= $_SESSION['breakMgmt'] == 1 ? '<th scope="col" id="breakTime">Temps de pause</th>' : ""; ?>
                            <?= $_SESSION['tripMgmt'] == 1 ? '<th scope="col" id="tripTime">Temps de trajet</th>' : ""; ?>
                            <th scope="col" class="records_log_comment" id="comment">Commentaire</th>
                            <th scope="col" id="status">Statut</th>
                            <th scope="col" class="records_log_last_modification" id="updateDate">Modifié le</th>
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

<?php $content = ob_get_clean(); ?>

<?php 

$menuSelector = "#historyLink";
$iconSelector = "#historyIcon";
?>

<?php ob_start(); ?>
updateTeamRecordsLog('Team', 'all');

<?php $additionalOnloadScript = ob_get_clean(); ?>

<?php include('partials/recordFormScripts.php'); ?>

<?php require 'template.php'; ?> 