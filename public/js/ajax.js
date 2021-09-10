/**
 * Description
 * @param {any} scope "user" ou "global" : correspond au périmètre des relevés demandés (personnels ou globaux)
 * @param {any} status "all", "approved, "pending" ou "deleted" : correspond au statut des relevés demandés (tous, validés, en attente ou supprimés)
 * @returns {any}
 */
function updateRecordsLog(scope, status) {
    $.ajax({
        type: "POST",
        url: "index.php?action=getRecords",
        data: {
            "scope": scope, 
            "status": status
        },
        //dataType: "json"
    })
    .done((result) => {
        console.log(result);
        clearTable("records_log")
        .then(() => {
            parseMultipleLines(result)
            .then((onfulfilled) => {
                hideNoRecordMessage();
                let records = result.records;
                for(let i = 0 ; i < records.length ; i++) {
                    appendLine("records_log", result, i);
                }
            }, (onrejected) => {
                displayNoRecordMessage();
            })
        })
    })
    .fail((error) => {
        alert("Un problème est survenu.");
    });
}

function viewRecord(recordId, userUUID) {
    $.ajax({
        type: "POST",
        url: "index.php?action=getForm",
        data: {
            "recordId": recordId,
            "formFile": "recordForm"
        }
    })
    .done((content) => {
        addModalContent(content, "view")
        .then(() => {
            console.log(userUUID);
            getWorksites(userUUID);
            getWorkCategories();
            getWorkSubCategories();
        })
        .then(() => {
            getRecord(recordId, "view");
        })
    })
}


/** Appel AJAX pour récupérer le contenu à insérer dans la fenêtre modale d'édition d'un relevé
 * @param  {number} recordId identifiant du relevé à modifier
 * @param  {number} userUUID identifiant de l'utilisateur
 */
function editRecord(recordId, userUUID) {
    $.ajax({
        type: "POST",
        url: "index.php?action=getForm",
        data: {
            "recordId": recordId,
            "userUUID": userUUID,
            "formFile": "recordForm"
        }
    })
    .done((content) => {
        addModalContent(content, "edit")
        .then(()=> {
            getWorksites(userUUID);
            getWorkCategories();
            getWorkSubCategories();
        })
        .then(() => {
            getRecord(recordId);
        })
    });
}


/** Appel AJAX pour récupérer le contenu à insérer dans la fenêtre modale de suppression d'un relevé
 * @param  {number} recordId identifiant du relevé à supprimer
 */
function displayDeleteConfirmation(recordId) {
    $.ajax({
        type: "POST",
        url: "index.php?action=getForm",
        data: {
            "recordId": recordId,
            "formFile": "deleteForm"
        }
    })
    .done((content) => {
        addModalContent(content, "delete");
    });
}


/** Appel AJAX pour récupérer les données d'un relevé
 * @param  {number} recordId identifiant du relevé dont on souhaite récupérer les informations
 */
function getRecord(recordId, action="") {
    $.ajax({
        type: "POST",
        url: "index.php?action=getRecord",
        data: {
            "recordId": recordId
        },
        dataType: "json"
    })
    .done((response) => {
        prefillRecordData(response);
        if(action === "view") {
            addReadOnlyAttributes();
            hideFormButtons();
        }
    })
}


/** Appel AJAX pour récupérer les relevés en attente de validation
 * @param  {string} scope type de relevés demandés (personnels, équipe, à vérifier ou globaux)
 * @param  {string} status portée de la demande (tous, validés, en attente, supprimés)
 */
function updateValidationBadge(scope, status) {
    $.ajax({
        type: "POST",
        url: "index.php?action=getRecords",
        data: {
            "scope": scope,
            "status": status
        },
        dataType: "json"
    })
    .done((response) => {
        displayNumberOfPendingRecords(response);
    })
}


/** Appel AJAX pour récupérer la liste des salariés
 */
function getUsers() {
    $.ajax({
        type: "POST",
        url: "index.php?action=getUsers",
        dataType: "json"
    })
    .done((response) => {
        addUsersToSelectTag(response);
    })
}

/** Appel AJAX pour récupérer la liste des catégories de postes de travail
 */
function getWorksites(userUUID, worksiteUUID) {
    $.ajax({
        type: "POST",
        url: "index.php?action=getWorksites",
        data: {
            "userUUID": userUUID,
            "worksiteUUID": worksiteUUID
        },
        dataType: "json"
    })
    .done((response) => {
        addWorksitesToSelectTag(response);
        if(worksiteUUID != undefined) {
            selectWorksite(worksiteUUID);
        }
    })
}


/** Appel AJAX pour récupérer la liste des catégories de postes de travail
 */
 function getWorkCategories() {
    $.ajax({
        type: "POST",
        url: "index.php?action=getWorkCategories",
        dataType: "json"
    })
    .done((response) => {
        displayWorkCategories(response);
    })
}


/** Appel AJAX pour récupérer la liste des sous-catégories de postes de travail
 */
 function getWorkSubCategories() {
    $.ajax({
        type: "POST",
        url: "index.php?action=getWorkSubCategories",
        dataType: "json"
    })
    .done((response) => {
        displayWorkSubCategories(response)
        .then((firstCategoryId) => {
            hideUnrelatedSubCategories(firstCategoryId);
            addEventCalculateTotalWorkingHours();
        });
    })
}


/** Appel AJAX pour récupérer la liste des événements du planning
*/
function getEventsFromPlanning(userUUID) {
    $.ajax({
        type: "POST",
        url: "index.php?action=getEventsFromPlanning",
        data: {
            "userUUID": userUUID
        },
        dataType: "json"
    })
    .done((response) => {
        displayEventsFromPlanning(response);
    })
}