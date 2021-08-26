<?php 

$title = "Validation";
$heading = "Validations en attente";

?>

<?php ob_start(); ?>

    <div class="row mb-5">

        <!-- Tableau qui affiche les informations de la BDD-->
        <form name="validationForm" action="index.php?action=updateRecordStatus" method="POST">
            <table class="table table-striped table-hover mt-4" id="records_log">
                <thead>
                    <tr id="table-head">
                        <th scope="col" id="worksite">Affaire</th>
                        <th scope="col" id="employee">Salarié</th>
                        <?= $_SESSION['dateTimeMgmt'] == 1 ? '<th scope="col" id="start">Début</th>' : ""; ?>
                        <?= $_SESSION['dateTimeMgmt'] == 1 ? '<th scope="col" id="end">Fin</th>' : ""; ?>
                        <?= ($_SESSION['lengthMgmt'] == 1 || $_SESSION['lengthByCategoryMgmt'] == 1) ? '<th scope="col" id="date">Date</th>' : ""; ?>
                        <?= ($_SESSION['lengthMgmt'] == 1 || $_SESSION['lengthByCategoryMgmt'] == 1) ? '<th scope="col" id="workTime">Travail</th>' : ""; ?>
                        <?= $_SESSION['breakMgmt'] == 1 ? '<th scope="col" id="breakTime">Pause</th>' : ""; ?>
                        <?= $_SESSION['tripMgmt'] == 1 ? '<th scope="col" id="tripTime">Trajet</th>' : ""; ?>
                        <th scope="col" class="records_log_comment" id="comment">Commentaire</th>
                        <th scope="col" class="records_log_last_modification" id="updateDate">Modifié le</th>
                        <th scope="col" id="select">
                            <button type="button" class="btn btn-dark btn-sm" onclick="selectAll();" id="selectAllButton">Sélectionner tout</button>
                        </th>
                        <th scope="col" id="delete"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr></tr>
                    <!-- Ici on insère dynamiquement les lignes du tableau avec Javascript-->
                </tbody>
            </table>
            
            <input type="hidden" value="<?= $_SESSION['csrfToken'] ;?>" name="csrfToken"/>
            <div class="row mb-3 justify-content-md-center">      
                <div class="col-lg mb-5 text-end">
                    <input type="reset" value="Annuler" class="btn btn-light me-3"/>
                    <input type="submit" value="Valider" class="btn btn-dark"/>
                </div>
            </div>
        </form>

        <p id="no_record_message" class="lead text-center mt-5" hidden>Aucun relevé à afficher.</p>

    </div>

    <?php include('partials/modal.php'); ?>

<?php $content = ob_get_clean(); ?>

<?php 

$menuSelector = "#validationLink";
$iconSelector = "#validationIcon";
?>

<?php ob_start(); ?>
function selectAll(){
    for( let i = 0 ; i < document.validationForm.length ; i++) {
        if(document.validationForm.elements[i].type == 'checkbox'){
            document.validationForm.elements[i].checked = !document.validationForm.elements[i].checked;
        }
    }
    document.getElementById('selectAllButton').innerHTML == 'Sélectionner tout' ? document.getElementById('selectAllButton').innerHTML = 'Désélectionner' : document.getElementById('selectAllButton').innerHTML = 'Sélectionner tout';
}

<?php $script = ob_get_clean(); ?>

<?php ob_start(); ?>
updateRecordsLog('global', 'pending');

<?php $additionalOnloadScript = ob_get_clean(); ?>

<?php require 'template.php'; ?>     