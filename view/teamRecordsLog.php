<?php session_start(); ?>

<!DOCTYPE html>
<html lang="fr">

    <?php include('partials/head.php'); ?>

    <body>
    <?php include('partials/navbar.php');?>

        <div class="container">
        
            <h2 class="display-6 mt-5 mb-5 text-center">Historique de l'équipe</h2>

            <div class="row mb-5">

                <!-- Tableau qui affiche les informations de la BDD-->
                <table class="table table-striped table-hover" id="records_log">
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
                        </tr>
                    </thead>
                    <tbody>
                        <tr></tr>
                        <!-- Ici on insère dynamiquement les lignes du tableau avec Javascript-->
                    </tbody>
                </table> 

            </div>

        </div>

        <?php include('partials/footer.php'); ?>

        <script src="public/js/update_records_log.js"></script>
        <script>
            $(function() {
                updateTeamRecordsLog('Team');
            });
        </script>
        
    </body>
</html>
