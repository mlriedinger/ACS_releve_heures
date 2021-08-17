/** Appel AJAX pour récupérer les relevés personnels
 * @param  {string} typeOfRecords type de relevés demandés (personnels, équipe, à vérifier ou globaux)
 * @param  {string} status portée de la demande (tous, validés, en attente, supprimés)
 */
function updatePersonalRecordsLog(typeOfRecords, status) {
    $.post('index.php?action=getPersonalRecordsLog', { 'typeOfRecords': typeOfRecords, 'status': status }, parseMultipleLines, 'json');
}


/** Appel AJAX pour récupérer les relevés des membres de l'équipe
 * @param  {string} typeOfRecords type de relevés demandés (personnels, équipe, à vérifier ou globaux)
 * @param  {string} status portée de la demande (tous, validés, en attente, supprimés)
 */
function updateTeamRecordsLog(typeOfRecords, status) {
    $.post('index.php?action=getTeamRecordsLog', { 'typeOfRecords': typeOfRecords, 'status': status }, parseMultipleLines, 'json');
}


/** Appel AJAX pour récupérer tous les relevés
 * @param  {string} typeOfRecords type de relevés demandés (personnels, équipe, à vérifier ou globaux)
 * @param  {string} status portée de la demande (tous, validés, en attente, supprimés)
 */
function updateAllUsersRecordsLog(typeOfRecords, status) {
    $.post('index.php?action=getAllUsersRecordsLog', { 'typeOfRecords': typeOfRecords, 'status': status }, parseMultipleLines, 'json');
}


/** Appel AJAX pour récupérer le contenu à insérer dans la fenêtre modale d'édition d'un relevé
 * @param  {number} recordId identifiant du relevé à modifier
 * @param  {number} userId identifiant de l'utilisateur
 */
function displayRecordForm(recordId, userId) {
    $.post('index.php?action=getRecordForm', { 'recordId': recordId, 'userId': userId }, function(content) {
        $(".modal-title").html("Editer un relevé");
        $(".modal-body").html(content);
    });
}


/** Appel AJAX pour récupérer le contenu à insérer dans la fenêtre modale de suppression d'un relevé
 * @param  {number} recordId identifiant du relevé à supprimer
 */
function displayDeleteConfirmation(recordId) {
    $.post('index.php?action=getDeleteConfirmationForm', { 'recordId': recordId }, function(content) {
        $(".modal-title").html("Confirmation de suppression");
        $(".modal-body").html(content);
    });
}


/** Appel AJAX pour récupérer les données d'un relevé
 * @param  {number} recordId identifiant du relevé dont on souhaite récupérer les informations
 */
function getRecordData(recordId) {
    $.post('index.php?action=getRecordData', { 'recordId': recordId }, prefillRecordData, 'json');
}


/** Appel AJAX pour récupérer les relevés en attente de validation
 * @param  {string} typeOfRecords type de relevés demandés (personnels, équipe, à vérifier ou globaux)
 * @param  {string} status portée de la demande (tous, validés, en attente, supprimés)
 */
function getNumberOfRecordsToCheck(typeOfRecords, status) {
    $.post('index.php?action=getTeamRecordsLog', { 'typeOfRecords': typeOfRecords, 'status': status }, displayNumberOfRecordsTocheck, 'json');
}


/** Appel AJAX pour récupérer la liste des managers ou des salariés
 * @param  {string} scope périmère de la demande ("export" : formulaire d'export ou "add" : formulaire d'ajout)
 * @param  {string} optionType le type d'utilisateurs ("managers" ou "users")
 * @param  {number} userId identifiant de l'utilisateur
 */
function getOptionsData(scope, optionType, userId, worksiteId) {
    $.post('index.php?action=getOptionsData', { 'typeOfData': optionType, 'scope': scope, 'userId': userId, 'worksiteId': worksiteId }, addOptionsToSelectTag/*, 'json'*/);
}


/** Appel AJAX pour récupérer la liste des catégories de postes de travail
 */
function getWorkCategories() {
    $.post('index.php?action=getWorkCategories', {}, displayWorkCategories, 'json');
}


/** Appel AJAX pour récupérer la liste des sous-catégories de postes de travail
 */
function getWorkSubCategories() {
    $.post('index.php?action=getWorkSubCategories', {}, displayWorkSubCategories, 'json');
}


/** Appel AJAX pour récupérer la liste des événements du planning
*/
function getEventsFromPlanning(userId) {
    $.post('index.php?action=getEventsFromPlanning', { 'userId': userId }, displayEventsFromPlanning/*, 'json'*/);
}