<?php session_start(); ?>

<!DOCTYPE html>
<html lang="fr">

    <?php include('partials/head.php'); ?>

    <body>
    <?php include('partials/navbar.php');?>
    
        <div class="container">
    
            <h2 class="display-6 mt-5 mb-5 text-center">Historique personnel</h2>

            <div class="row mb-5">

            <!-- Tableau qui affiche les informations de la BDD-->
                <table class="table table-striped table-hover" id="records_log">
                    <thead>
                        <tr>
                            <th scope="col">Chantier</th>
                            <th scope="col">Date et heure de début</th>
                            <th scope="col">Date et heure de fin</th>
                            <th scope="col">Commentaire</th>
                            <th scope="col">Statut</th>
                            <th scope="col">Modifié le</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr></tr>
                        <!-- Ici on insère dynamiquement les lignes du tableau avec Javascript-->
                    </tbody>
                </table> 

            </div>

            <!-- Fenêtre modale -->
            <div class="modal fade" id="formModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">

                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title" id="formModalLabel">Editer un relevé</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                        <!-- Ici on insère le contenu de la fenêtre modale -->
                        </div>

                    </div>

                </div>
            </div> 

        </div>

        <?php include('partials/footer.php'); ?>

        <script src="public/js/update_records_log.js"></script>
        <script>
            $(function() {
                updatePersonnalRecordsLog('Personal');
            });
        </script>

        <script>
            function display_record_form(id_record){
                $.post('index.php?action=getRecordForm', { 'idRecord': id_record }, function(content){
                    $(".modal-body").html(content);
                })
            }
        </script>

    </body>
</html>