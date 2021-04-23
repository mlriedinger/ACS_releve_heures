<?php 

$title = "Historique personnel";
$heading = "Historique personnel";

?>

<?php ob_start(); ?>

    <div class="row mb-5">

        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="all_records-tab" onclick="updatePersonalRecordsLog('Personal', 'all')" data-bs-toggle="tab" data-bs-target="#all_records" type="button" role="tab" aria-controls="all_records" aria-selected="true">Tous</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="valid_records-tab" onclick="updatePersonalRecordsLog('Personal', 'valid')" data-bs-toggle="tab" data-bs-target="#valid_records" type="button" role="tab" aria-controls="valid_records" aria-selected="false">Validés</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="unchecked_records-tab" onclick="updatePersonalRecordsLog('Personal', 'unchecked')" data-bs-toggle="tab" data-bs-target="#unchecked_records" type="button" role="tab" aria-controls="unchecked_records" aria-selected="false" <?= $_SESSION['userGroup'] == 1 ? "hidden" : "" ?>>En attente</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="deleted_records-tab" onclick="updatePersonalRecordsLog('Personal', 'deleted')" data-bs-toggle="tab" data-bs-target="#deleted_records" type="button" role="tab" aria-controls="deleted_records" aria-selected="false">Supprimés</button>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="all_records" role="tabpanel" aria-labelledby="all_records-tab">
                <table class="table table-striped table-hover mt-4" id="records_log">
                    <thead>
                        <tr id="table-head">
                            <th scope="col" id="worksite">Chantier</th>
                            <?= $_SESSION['dateTimeMgmt'] == 1 ? '<th scope="col" id="start">Début</th>' : ""; ?>
                            <?= $_SESSION['dateTimeMgmt'] == 1 ? '<th scope="col" id="end">Fin</th>' : ""; ?>
                            <?= $_SESSION['lengthMgmt'] == 1 ? '<th scope="col" id="date">Date</th>' : ""; ?>
                            <?= $_SESSION['lengthMgmt'] == 1 ? '<th scope="col" id="workTime">Travail</th>' : ""; ?>
                            <?= $_SESSION['breakMgmt'] == 1 ? '<th scope="col" id="breakTime">Pause</th>' : ""; ?>
                            <?= $_SESSION['tripMgmt'] == 1 ? '<th scope="col" id="tripTime">Trajet</th>' : ""; ?>
                            <th scope="col" id="comment">Commentaire</th>
                            <th scope="col" id="status">Statut</th>
                            <th scope="col" id="updateDate" class="records_log_last_modification">Modifié le</th>
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

<?php ob_start(); ?>          
    <script>
        window.onload = function(){
            getNumberOfRecordsToCheck('Check', 'unchecked');
            updatePersonalRecordsLog('Personal', 'all');
        }
    </script>
    <?php include('partials/recordFormScripts.php'); ?>
<?php $script = ob_get_clean(); ?>

<?php require 'template.php'; ?>
