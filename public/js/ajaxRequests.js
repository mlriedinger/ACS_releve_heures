/* Fonction qui permet de traiter les données reçues de PHP, lorsque la requête renvoie plusieurs lignes et de les insérer dans le tableau 
    Param :
    * data : correspond au tableau contenant les résultats de la requête AJAX
*/

function parseMultipleLinesRequest(data) {
    console.log("parseMultipleLinesRequest : ");
    console.log(data);
    var tabData = data.records;
    var typeOfRecords = data.typeOfRecords;

    // On vide le tableau
    clearTable(tabData);

    // Si la requête concerne une liste de relevés à valider, on insère les boutons de contrôle du formulaire de validation après le tableau
    if(tabData.length && typeOfRecords === "Check") {
        insertFormControlButtons();
    }

    // Si la requête a retourné des résultats, on boucle sur tabData pour récupérer chaque objet (relevé d'heure), puis on ajoute l'objet au tableau avec appendLine()
    if(tabData.length) {
        for (var i = 0; i < tabData.length; i++) {
            appendLine("records_log", tabData, typeOfRecords, i);
        }
    }
}


/* Fonction qui permet de traiter les données reçues de PHP, lorsque la requête renvoie une seule ligne, et de les insérer dans le tableau
    Param :
    * data : contenu de la réponse à la requête AJAX
*/

function parseUniqueLineRequest(data) {
    console.log(data);
    var recordData = [];

    $.each(data, function(key, value) {
        recordData.push(value);
    });
    
    updateFormInputs(recordData);
}


/*  Appels AJAX pour récupèrer les résultats des requêtes PHP au format JSON et remplir dynamiquement les tableaux de relevés
    Params :
    * 'url' : url sur laquelle faire la requête POST
    * {} : données à envoyer dans la requête, ici 'typeOfRecords' : type de relevés demandés (personnels, équipe, à vérifier ou globaux) ; 'scope' : portée de la demande (tous, validés, en attente, supprimés)
    * parseMultipleLinesRequest : fonction à appeler en cas de succès de la requête. Le contenu de la réponse est automatiquement passé en paramètre.
    * 'json' : format de données reçues par la requête AJAX
*/

function updatePersonalRecordsLog(typeOfRecords, scope) {
    $.post('index.php?action=getPersonalRecordsLog', { 'typeOfRecords': typeOfRecords, 'scope': scope }, parseMultipleLinesRequest, 'json');
}

function updateTeamRecordsLog(typeOfRecords, scope) {
    $.post('index.php?action=getTeamRecordsLog', { 'typeOfRecords': typeOfRecords, 'scope': scope }, parseMultipleLinesRequest, 'json');
}

function updateAllUsersRecordsLog(typeOfRecords, scope) {
    $.post('index.php?action=getAllUsersRecordsLog', { 'typeOfRecords': typeOfRecords, 'scope': scope }, parseMultipleLinesRequest, 'json');
}


/* Appels AJAX pour récupérer le contenu qui va être inséré dans le corps de la fenêtre modale (édition ou suppression d'un relevé)
    Params :
    * 'url' : url sur laquelle faire la requête POST
    * {} : données à envoyer dans la requête, ici 'recordId' : identifiant du relevé à modifier ou à supprimer
*/

function displayRecordForm(recordId, userId) {
    $.post('index.php?action=getRecordForm', { 'recordId': recordId, 'userId': userId }, function(content) {
        $(".modal-title").html("Editer un relevé");
        $(".modal-body").html(content);
    });
}

function displayDeleteConfirmation(recordId) {
    $.post('index.php?action=getDeleteConfirmationForm', { 'recordId': recordId }, function(content) {
        $(".modal-title").html("Confirmation de suppression");
        $(".modal-body").html(content);
    });
}


/*  Appel AJAX pour récupèrer les résultats des requêtes PHP au format JSON et remplir dynamiquement le contenu de la modale d'édition d'un relevé
    Params :
    * 'url' : url sur laquelle faire la requête POST
    * {} : données à envoyer dans la requête, ici 'recordID' : l'ID du relevé dont on souhaite récupérer les informations
    * parseUniqueLineRequest : fonction à appeler en cas de succès de la requête. Le contenu de la réponse est automatiquement passé en paramètre.
    * 'json' : format de données reçues par la requête AJAX
*/

function getRecordData(recordId) {
    $.post('index.php?action=getRecordData', { 'recordId': recordId }, updateFormInputs, 'json');
}


/*  Appel AJAX pour récupèrer les résultats des requêtes PHP au format JSON et afficher dynamiquement le nombre de relevés en attente de validation
    Params :
    * 'url' : url sur laquelle faire la requête POST
    * {} : données à envoyer dans la requête, ici 'typeOfRecords' : type de relevés demandés (personnels, équipe, à vérifier ou globaux) ; 'scope' : portée de la demande (tous, validés, en attente, supprimés)
    * displayNumberOfRecordsTocheck : fonction à appeler en cas de succès de la requête. Le contenu de la réponse est automatiquement passé en paramètre.
    * 'json' : format de données reçues par la requête AJAX
*/

function getNumberOfRecordsToCheck(typeOfRecords, scope) {
    $.post('index.php?action=getTeamRecordsLog', { 'typeOfRecords': typeOfRecords, 'scope': scope }, displayNumberOfRecordsTocheck, 'json');
}


/*  Appel AJAX pour récupèrer les résultats des requêtes PHP au format JSON et afficher dynamiquement les listes déroulantes de managers et de salariés
    Params :
    * 'url' : url sur laquelle faire la requête POST
    * {} : données à envoyer dans la requête, ici 'typeOfData' : le type d'utilisateurs ("managers" ou "users")
    * displayOptionsList : fonction à appeler en cas de succès de la requête. Le contenu de la réponse est automatiquement passé en paramètre.
    * 'json' : format de données reçues par la requête AJAX
*/

function getOptionsData(scope, optionType, userId) {
    $.post('index.php?action=getOptionsData', { 'typeOfData': optionType, 'scope': scope, 'userId': userId }, displayOptionsList, 'json');
}