<?php session_start(); ?>

<!DOCTYPE html>
<html lang="fr">

    <?php include('partials/head.php'); ?>

    <body>
    <?php include('partials/navbar.php');?>
    
        <div class="container">
    
            <h2 class="display-6 mt-5 mb-5 text-center">Historique personnel</h2>

            <div class="row mb-5">

                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="all_records-tab" onclick="updatePersonalRecordsLog('Personal', 'all')" data-bs-toggle="tab" data-bs-target="#all_records" type="button" role="tab" aria-controls="all_records" aria-selected="true">Tous</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="valid_records-tab" onclick="updatePersonalRecordsLog('Personal', 'valid')" data-bs-toggle="tab" data-bs-target="#valid_records" type="button" role="tab" aria-controls="valid_records" aria-selected="false">Validés</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="unchecked_records-tab" onclick="updatePersonalRecordsLog('Personal', 'unchecked')" data-bs-toggle="tab" data-bs-target="#unchecked_records" type="button" role="tab" aria-controls="unchecked_records" aria-selected="false" <?= $_SESSION['id_group'] == 1 ? "hidden" : "" ?>>En attente</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="deleted_records-tab" onclick="updatePersonalRecordsLog('Personal', 'deleted')" data-bs-toggle="tab" data-bs-target="#deleted_records" type="button" role="tab" aria-controls="deleted_records" aria-selected="false">Supprimés</button>
                    </li>
                </ul>

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="all_records" role="tabpanel" aria-labelledby="all_records-tab"><?php include('partials/personalRecordsTable.php'); ?></div>
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

        <script src="public/js/update_records_log.js"></script>
  
        <script>
            $(function() {
                getNumberOfRecordsToCheck('Check', 'unchecked');
                updatePersonalRecordsLog('Personal', 'all');
            });
        </script>

        <?php include('partials/initalizeToastScript.php'); ?>

    </body>
</html>