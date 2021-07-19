/*  Appels AJAX pour récupèrer les résultats des requêtes PHP au format JSON et remplir dynamiquement les tableaux de relevés
    Params :
    * 'url' : url sur laquelle faire la requête POST
    * {} : données à envoyer dans la requête, ici 'typeOfRecords' : type de relevés demandés (personnels, équipe, à vérifier ou globaux) ; 'status' : portée de la demande (tous, validés, en attente, supprimés)
    * parseMultipleLinesRequest : fonction à appeler en cas de succès de la requête. Le contenu de la réponse est automatiquement passé en paramètre.
    * 'json' : format de données reçues par la requête AJAX
*/
function updatePersonalRecordsLog(typeOfRecords, status) {
    $.post('index.php?action=getPersonalRecordsLog', { 'typeOfRecords': typeOfRecords, 'status': status }, parseMultipleLinesRequest, 'json');
}

function updateTeamRecordsLog(typeOfRecords, status) {
    $.post('index.php?action=getTeamRecordsLog', { 'typeOfRecords': typeOfRecords, 'status': status }, parseMultipleLinesRequest, 'json');
}

function updateAllUsersRecordsLog(typeOfRecords, status) {
    $.post('index.php?action=getAllUsersRecordsLog', { 'typeOfRecords': typeOfRecords, 'status': status }, parseMultipleLinesRequest, 'json');
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
    * {} : données à envoyer dans la requête, ici 'typeOfRecords' : type de relevés demandés (personnels, équipe, à vérifier ou globaux) ; 'status' : portée de la demande (tous, validés, en attente, supprimés)
    * displayNumberOfRecordsTocheck : fonction à appeler en cas de succès de la requête. Le contenu de la réponse est automatiquement passé en paramètre.
    * 'json' : format de données reçues par la requête AJAX
*/
function getNumberOfRecordsToCheck(typeOfRecords, status) {
    $.post('index.php?action=getTeamRecordsLog', { 'typeOfRecords': typeOfRecords, 'status': status }, displayNumberOfRecordsTocheck, 'json');
}


/*  Appel AJAX pour récupèrer les résultats des requêtes PHP au format JSON et afficher dynamiquement les listes déroulantes de managers et de salariés
    Params :
    * 'url' : url sur laquelle faire la requête POST
    * {} : données à envoyer dans la requête, ici 'typeOfData' : le type d'utilisateurs ("managers" ou "users")
    * displayOptionsList : fonction à appeler en cas de succès de la requête. Le contenu de la réponse est automatiquement passé en paramètre.
    * 'json' : format de données reçues par la requête AJAX
*/
function getOptionsData(status, optionType, userId) {
    $.post('index.php?action=getOptionsData', { 'typeOfData': optionType, 'status': status, 'userId': userId }, displayOptionsList, 'json');
}

/*  Appel AJAX pour récupèrer les résultats des requêtes PHP au format JSON et afficher dynamiquement les rubriques de saisie des différents postes de travail
    Params :
    * 'url' : url sur laquelle faire la requête POST
    * {} : données à envoyer dans la requête
    * displayWorkCategories : fonction à appeler en cas de succès de la requête. Le contenu de la réponse est automatiquement passé en paramètre.
    * 'json' : format de données reçues par la requête AJAX
*/
function getWorkCategories() {
    $.post('index.php?action=getWorkCategories', {}, displayWorkCategories, 'json');
}

function getWorkSubCategories(workCategoryId) {
    $.post('index.php?action=getWorkSubCategories', { 'workCategoryId': workCategoryId }, displayWorkSubCategories/*, 'json'*/);
}

function getEventsFromPlanning(userId) {
    $.post('index.php?action=getEventsFromPlanning', { 'userId': userId }, displayEventsFromPlanning/*, 'json'*/);
}