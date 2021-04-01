<?php session_start(); ?>

<!DOCTYPE html>
<html lang="fr">

    <?php include('partials/head.php'); ?>

    <body>
    <?php include('partials/navbar.php');?>

        <div class="container">
        
            <h2 class="display-6 mt-5 mb-5 text-center">Validations en attente</h2>

            <div class="row mb-5">

                <!-- Tableau qui affiche les informations de la BDD-->
                <form name="validationForm" action="index.php?action=updateRecordStatus" method="POST">
                    <table class="table table-striped table-hover mt-4" id="records_log">
                        <thead>
                            <tr>
                                <th scope="col">Chantier</th>
                                <th scope="col">Prénom</th>
                                <th scope="col">Nom</th>
                                <th scope="col">Date et heure de début</th>
                                <th scope="col">Date et heure de fin</th>
                                <th scope="col">Commentaire</th>
                                <th scope="col">Modifié le</th>
                                <th scope="col">
                                    <button type="button" class="btn btn-dark btn-sm" onclick="selectAll();" id="selectAllButton">Sélectionner tout</button>
                                </th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr></tr>
                            <!-- Ici on insère dynamiquement les lignes du tableau avec Javascript-->
                        </tbody>
                    </table>

                </form>

                <p id="no_record_message" class="lead text-center mt-5" hidden>Aucun relevé à afficher.</p>

            </div>

            <?php include('partials/modal.php'); ?>

        </div>

        <?php include('partials/footer.php'); ?>

        <script id="mainScript" src="public/js/main.js"></script>

        <script>
            window.onload = function() {
                getNumberOfRecordsToCheck('Check', 'unchecked');
                updateTeamRecordsLog('Check', 'unchecked');
            };
        </script>

        <script>
            function selectAll(){
                for( let i = 0 ; i < document.validationForm.length ; i++) {
                    if(document.validationForm.elements[i].type == 'checkbox'){
                        document.validationForm.elements[i].checked = !document.validationForm.elements[i].checked;
                    }
                }
                document.getElementById('selectAllButton').innerHTML == 'Sélectionner tout' ? document.getElementById('selectAllButton').innerHTML = 'Désélectionner' : document.getElementById('selectAllButton').innerHTML = 'Sélectionner tout';
            }
        </script>
        
    </body>
</html>
