/* Fonction qui ajoute une nouvelle ligne à un tableau cible
    Params :
        * tableID : id associé à la balise <table>
        * data : un tableau de données
        * typeOfRecord : type de relevés demandés (personnels, en attente, équipe ou globaux)
        * counter : index du tour de boucle actuel qui permet de créer des id uniques sur les balises HTML créées
*/

function appendLine(tableID, data, typeOfRecord, counter){
    console.log(data);
    console.log(typeOfRecord);
    // On vise la balise HTML dont l'id correspond à celui passé en paramètre
    var table = document.getElementById(tableID);

    // On crée une nouvelle ligne à la fin du tableau existant
    var newRow = table.insertRow(-1);

    var newWorkSite = newRow.insertCell(0);
    newWorkSite.innerHTML += data[0];


    // Si on demande l'historique personnel
    if(typeOfRecord == 'Personal'){
        // On crée autant de nouvelles colonnes qu'il y a de champs dans le tableau
        var newStartTime = newRow.insertCell(1);
        var newEndTime = newRow.insertCell(2);
        var newComment = newRow.insertCell(3);
        var newStatus = newRow.insertCell(4);
        var newUpdateDate = newRow.insertCell(5);
        var newEdit = newRow.insertCell(6);
        var newDelete = newRow.insertCell(7);

        // On ajoute du contenu à chaque colonne créée : ici, les données du tableau passé en paramètre
        newStartTime.innerHTML += data[1];
        newEndTime.innerHTML += data[2];
        newComment.innerHTML += data[3];
        if(data[4] == 0){
            newStatus.innerHTML += "En attente";
            if(data[8] == 0){
                // Dans la dernière colonne, on insère un bouton avec une icône, qui commande l'affichage de la fenêtre modale et qui, au clic, appelle la fonction pour charger le formulaire en lui passant l'id du relevé 
                newEdit.innerHTML += '<button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#formModal" onclick="displayRecordForm(' + data[7] + ')" data-bs-whatever="Editer"><i class="far fa-edit"></i></button>';
                newDelete.innerHTML += '<button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#formModal" onclick="displayDeleteConfirmation(' + data[7] + ')"><i class="far fa-trash-alt"></i></button>';
            }
        } else newStatus.innerHTML += "Validé";
        newUpdateDate.innerHTML += data[6];
    } 

    // Si on demande les relevés en attente de validation
    else if (typeOfRecord == 'Check'){        
        // On crée autant de nouvelles colonnes qu'il y a de champs dans le tableau
        var newFirstName = newRow.insertCell(1);
        var newLastName = newRow.insertCell(2);
        var newStartTime = newRow.insertCell(3);
        var newEndTime = newRow.insertCell(4);
        var newComment = newRow.insertCell(5);
        var newUpdateDate = newRow.insertCell(6);
        var newIsValid = newRow.insertCell(7);
        var newDelete = newRow.insertCell(8);
        
        // On ajoute du contenu à chaque colonne créée : ici, les données du tableau passé en paramètre
        newFirstName.innerHTML += data[2];
        newLastName.innerHTML += data[1];
        newStartTime.innerHTML += data[3];
        newEndTime.innerHTML += data[4];
        newComment.innerHTML += data[5];
        newUpdateDate.innerHTML += data[7];

        var html = [
            '<div class="form-check form-switch">',
                '<input class="form-check-input" type="checkbox" name="check_list[' + counter +']" id="recordValidationCheck' + counter +'" value="' + data[8] +'"/>',
                '<label class="form-check-label" for="recordValidationCheck' + counter +'">Sélectionner</label>',
            '</div>'
        ].join('');

        newIsValid.innerHTML += html;
        newDelete.innerHTML += '<button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#formModal" onclick="displayDeleteConfirmation(' + data[8] + ')"><i class="far fa-trash-alt"></i></button>';
    } 

    // Si on demande les relevés d'équipe ou l'intégralité des relevés
    else {
        // On crée autant de nouvelles colonnes qu'il y a de champs dans le tableau
        var newFirstName = newRow.insertCell(1);
        var newLastName = newRow.insertCell(2);
        var newStartTime = newRow.insertCell(3);
        var newEndTime = newRow.insertCell(4);
        var newComment = newRow.insertCell(5);
        var newStatus = newRow.insertCell(6);
        var newUpdateDate = newRow.insertCell(7);
        var newEdit = newRow.insertCell(8);
        var newDelete = newRow.insertCell(9);

        // On ajoute du contenu à chaque colonne créée : ici, les données du tableau passé en paramètre
        newFirstName.innerHTML += data[2];
        newLastName.innerHTML += data[1];
        newStartTime.innerHTML += data[3];
        newEndTime.innerHTML += data[4];
        newComment.innerHTML += data[5];

        if(data[6] == '0'){
            newStatus.innerHTML += "En attente";
            // Dans la dernière colonne, on insère un bouton avec une icône, qui commande l'affichage de la fenêtre modale et qui, au clic, appelle la fonction pour charger le formulaire en lui passant l'id du relevé 
            newEdit.innerHTML += '<button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#formModal" onclick="displayRecordForm(' + data[9] + ')" data-bs-whatever="Editer"><i class="far fa-edit"></i></button>';
            newDelete.innerHTML += '<button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#formModal" onclick="displayDeleteConfirmation(' + data[9] + ')"><i class="far fa-trash-alt"></i></button>';
        } else newStatus.innerHTML += "Validé";

        newUpdateDate.innerHTML += data[8];
    }
}


/* Fonction qui permet de mettre à jour les champs du formulaire dans la fenêtre modale d'édition d'un relevé
    Param : 
    * data : un tableau de données
*/

function updateFormInputs(data){
    var inputDateTimeStart = document.getElementById('datetime_start');
    var inputDateTimeEnd = document.getElementById('datetime_end');
    var inputComment = document.getElementById('comment');

    // On remplace le caractère d'espace par un "T" pour correspondre au format de date attendu par l'input datetime-locale
    var startTime = data[3].replace(" ", "T");
    var endTime = data[4].replace(" ", "T");

    inputDateTimeStart.setAttribute("value", startTime);
    inputDateTimeEnd.setAttribute("value", endTime);
    inputComment.innerHTML += data[6];
}


/* Fonction qui permet de traiter les données reçues de PHP, lorsque la requête renvoie plusieurs lignes et de les insérer dans le tableau 
    Param :
    * data : contenu de la réponse à la requête AJAX
*/

function parseMultipleLinesRequest(data){
    // console.log(data.records);
    var tab_data = data.records;
    //console.log(tab_data);
    var typeOfRecords = data.typeOfRecords;

    // On récupère la ligne vide du tableau
    var tr_empty = $("#records_log tbody tr:first-child");
    // On ré-insère la ligne vide. 
    // Résultat : Le tableau est vidé à chaque fois que la fonction est appelée
    $("#records_log").children("tbody").html(tr_empty);

    // Si la requête n'a retourné aucun résultat, on affiche un message sous le tableau
    if(!tab_data.length) $('<p class="lead text-center mt-5">Aucun relevé à afficher.</p>').insertAfter("#records_log");

    // Si la requête concerne une liste de relevés à valider, on insère les boutons de contrôledu formulaire de validation après le tableau
    if(tab_data.length && typeOfRecords == 'Check') {
        var formControlButtons = [
            '<div class="row mb-3 justify-content-md-center">',      
                '<div class="col-lg mb-5 text-end">',
                    '<input type="button" value="Annuler" class="btn btn-light p-3"/>',
                    '<input type="submit" value="Valider" class="btn btn-dark"/>',
                '</div>',
            '</div>'
        ].join('');
    
        $(formControlButtons).insertAfter("#records_log");
    }

    // On boucle sur tab_data pour récupérer chaque objet (relevé d'heure)
    for (var i = 0; i < tab_data.length; i++) {
        // On initialise un tableau vide
        var recordData = [];

         // On itère sur chaque objet (relevé d'heure) pour récupérer toutes les clés (noms de colonne BDD) /valeurs
         $.each(tab_data[i], function(key, value) {
            // console.log(key, value);

            // On pousse uniquement les valeurs dans le tableau
            recordData.push(value);
        });
        // console.log(recordData);
        // On appelle la fonction qui permet d'ajouter des lignes au tableau et on lui passe le tableau en paramètre
        appendLine('records_log', recordData, typeOfRecords, i);
    }
}


/* Fonction qui permet de traiter les données reçues de PHP, lorsque la requête renvoie une seule ligne, et de les insérer dans le tableau
    Param :
    * data : contenu de la réponse à la requête AJAX
*/

function parseUniqueLineRequest(data){
    var recordData = [];

    $.each(data, function(key, value) {
        recordData.push(value);
    });
    
    updateFormInputs(recordData);
}


/*  Appels AJAX pour récupèrer les résultats des requêtes PHP au format JSON
    Params :
    * 'url' : url sur laquelle faire la requête POST
    * {} : données à envoyer dans la requête, ici 'typeOfRecords' : type de relevés demandés (personnels, équipe, à vérifier ou globaux)
    * parseMultipleLinesRequest ou parseUniqueLineRequest : fonction à appeler en cas de succès de la requête. Le contenu de la réponse est automatiquement passé en paramètre.
    * 'json' : format de données reçues par la requête AJAX
*/

function updatePersonalRecordsLog(typeOfRecords, scope) {
    $.post('index.php?action=getPersonalRecordsLog', { 'typeOfRecords': typeOfRecords, 'scope': scope }, parseMultipleLinesRequest/*, 'json'*/);
;}

function updateAllUsersRecordsLog(typeOfRecords) {
    $.post('index.php?action=getAllUsersRecordsLog', { 'typeOfRecords': typeOfRecords, 'scope': scope }, parseMultipleLinesRequest, 'json');
}

function updateTeamRecordsLog(typeOfRecords) {
    $.post('index.php?action=getTeamRecordsLog', { 'typeOfRecords': typeOfRecords, 'scope': scope }, parseMultipleLinesRequest, 'json');
}

function displayRecordsToCheck(typeOfRecords) {
    $.post('index.php?action=getRecordsToCheck', { 'typeOfRecords': typeOfRecords }, parseMultipleLinesRequest, 'json');
}

function getRecordData(recordId) {
    $.post('index.php?action=getRecordData', { 'recordID': recordId }, parseUniqueLineRequest, 'json');
}


/* Appels AJAX pour récupérer le contenu qui va être inséré dans le corps de la fenêtre modale (édition ou suppression d'un relevé)
    Params :
    * 'url' : url sur laquelle faire la requête POST
    * {} : données à envoyer dans la requête, ici 'idRecord' : identifiant du relevé à modifier ou à supprimer
*/

function displayRecordForm(id_record){
    $.post('index.php?action=getRecordForm', { 'idRecord': id_record }, function(content){
        $(".modal-body").html(content);
    });
}

function displayDeleteConfirmation(id_record){
    $.post('index.php?action=getDeleteConfirmationForm', { 'idRecord': id_record }, function(content){
        $(".modal-title").html("Confirmation de suppression");
        $(".modal-body").html(content);
    });
}
