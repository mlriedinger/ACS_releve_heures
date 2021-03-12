<!-- Tableau qui affiche les informations de la BDD-->
<table class="table table-striped table-hover mt-4" id="records_log">
    <thead>
        <tr>
            <th scope="col">Chantier</th>
            <th scope="col">Date et heure de début</th>
            <th scope="col">Date et heure de fin</th>
            <th scope="col" class="records_log_comment">Commentaire</th>
            <th scope="col">Statut</th>
            <th scope="col" class="records_log_last_modification">Modifié le</th>
            <th scope="col"></th>
            <th scope="col"></th>
        </tr>
    </thead>
    <tbody>
        <tr></tr>
        <!-- Ici on insère dynamiquement les lignes du tableau avec Javascript-->
    </tbody>
</table> 

<p id="no_record_message" class="lead text-center mt-5" hidden>Aucun relevé à afficher.</p>