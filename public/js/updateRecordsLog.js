/** Gère l'affichage du statut d'un relevé ainsi que l'affichage des boutons d'édition et de suppression.
 * @param  {object} newLines correspond à un objet contenant toutes les cellules d'une nouvelle ligne du tableau
 * @param  {object} records contenu de la réponse à la requête AJAX
 * @param  {number} currentUserUUID identifiant de l'utilisateur actuellement connecté
 * @param  {number} counter index du tour de boucle actuel qui permet de créer des id uniques sur les balises HTML créées
 */
function checkRecordValidationStatus(newLines, records, currentUserUUID, counter) {
    let validationStatus = records[counter].statut_validation;
    let deleteStatus = records[counter].supprimer;
	let userGroup = records[counter].id_groupe;

    let newValidationText = "";
    
    if ((validationStatus === "0" && deleteStatus === "0") || (userGroup === "1" && deleteStatus === "0")) {
        userGroup === "1" ? newValidationText = document.createTextNode("Auto-validé") : newValidationText = document.createTextNode("En attente");
        if (currentUserUUID === parseInt(records[counter].id_login)) {
            insertEditRecordButton(newLines.newEdit, records, counter);
            insertDeleteRecordButton(newLines.newDelete, records, counter);
        }
    } 
    else if (deleteStatus === "1") {
        newValidationText = document.createTextNode("Supprimé");
    }
    else {
        newValidationText = document.createTextNode("Validé");
        insertViewButton(newLines.newEdit, records, counter);
    }
    
    newLines.newStatus.appendChild(newValidationText);
}


/** Remplit les cellules de la nouvelle ligne du tableau avec les données de la requête AJAX.
 * @param  {object} newLines correspond à un objet contenant toutes les cellules d'une nouvelle ligne du tableau
 * @param  {object} records contenu de la réponse à la requête AJAX
 * @param  {number} counter index du tour de boucle actuel qui permet de créer des id uniques sur les balises HTML créées
 */
function fillRecordsTable(newLines, records, counter) {
    return new Promise((resolve) => {
        if(newLines.newWorkSite !== undefined) {
            let newText = document.createTextNode(records[counter].affaire);
            newLines.newWorkSite.appendChild(newText);
        }
    
        if(newLines.newEmployee !== undefined) {
            let newText = document.createTextNode(records[counter].prenom_salarie + ' ' + records[counter].nom_salarie);
            newLines.newEmployee.appendChild(newText);
        }
    
        if(newLines.newStartTime !== undefined) {
            let newText = document.createTextNode(records[counter].date_hrs_debut);
            newLines.newStartTime.appendChild(newText);
        }
    
        if(newLines.newEndTime !== undefined) {
            let newText = document.createTextNode(records[counter].date_hrs_fin);
            newLines.newEndTime.appendChild(newText);
        }
    
        if(newLines.newDate !== undefined) {
            let newText = document.createTextNode(records[counter].date_releve);
            newLines.newDate.appendChild(newText);
        }
    
        if(newLines.newWorkTime !== undefined) {
            let time = convertTimeToHoursAndMinutes(records[counter].tps_travail);
            let newText = document.createTextNode(time['hours'] + "h" + time['minutes']);
            newLines.newWorkTime.appendChild(newText);
        }
    
        if(newLines.newBreakTime !== undefined) {
            let time = convertTimeToHoursAndMinutes(records[counter].tps_pause);
            let newText = document.createTextNode(time['hours'] + "h" + time['minutes']);
            newLines.newBreakTime.appendChild(newText);
        }
    
        if(newLines.newTripTime !== undefined) {
            let time = convertTimeToHoursAndMinutes(records[counter].tps_trajet);
            let newText = document.createTextNode(time['hours'] + "h" + time['minutes']);
            newLines.newTripTime.appendChild(newText);
        }
    
        if(newLines.newComment !== undefined) {
            newLines.newComment.classList.add("records_log_comment");
            let newText = records[counter].commentaire;
            newLines.newComment.innerHTML = newText;
        }
    
        if(newLines.newUpdateDate !== undefined) {
            newLines.newUpdateDate.classList.add("records_log_last_modification");
            let newText = document.createTextNode(records[counter].date_hrs_modif);
            newLines.newUpdateDate.appendChild(newText);
        }
        resolve();
    })
}


/** Ajoute des cellules dans la ligne en cours d'ajout.
 * @param  {object} newRow
 */
function createNewLines(newRow) {
    return new Promise((resolve, reject) => {
        // On crée un objet newLines qui contiendra les cellules de chaque nouvelle ligne
        var newLines = {};

        // On cible la ligne du tableau qui contient les en-têtes de colonnes
        var tableHead = document.getElementById('table-head');
        var numberOfColumnHeaders = tableHead.childElementCount;

        // On boucle autant de fois qu'il y a d'en-têtes de colonnes dans le tableau
        for(i = 0 ; i < numberOfColumnHeaders ; i++){

            // On vérifie l'id de chaque balise <th>
            // S'il correspond à l'une des options, on crée une nouvelle cellule dans la ligne (newRow) avec la méthode insertCell()
            // Pour s'assurer que le contenu ira dans la bonne colonne du tableau, on passe à insertCell() l'index de la colonne dans laquelle on a trouvé une correspondance avec l'id
            switch(tableHead.children[i].attributes.id.value){
                case "worksite":
                    newLines['newWorkSite'] = newRow.insertCell(tableHead.children[i].cellIndex);
                    break;

                case "employee":
                    newLines['newEmployee'] = newRow.insertCell(tableHead.children[i].cellIndex);
                    break;

                case "start":
                    newLines['newStartTime'] = newRow.insertCell(tableHead.children[i].cellIndex);
                    break;

                case "end":
                    newLines['newEndTime'] = newRow.insertCell(tableHead.children[i].cellIndex);
                    break;

                case "date":
                    newLines['newDate'] = newRow.insertCell(tableHead.children[i].cellIndex);
                    break;

                case "workTime":
                    newLines['newWorkTime'] = newRow.insertCell(tableHead.children[i].cellIndex);
                    break;

                case "breakTime":
                    newLines['newBreakTime'] = newRow.insertCell(tableHead.children[i].cellIndex);
                    break;

                case "tripTime":
                    newLines['newTripTime'] = newRow.insertCell(tableHead.children[i].cellIndex);
                    break;

                case "comment":
                    newLines['newComment'] = newRow.insertCell(tableHead.children[i].cellIndex);
                    break;

                case "status":
                    newLines['newStatus'] = newRow.insertCell(tableHead.children[i].cellIndex);
                    break;

                case "updateDate":
                    newLines['newUpdateDate'] = newRow.insertCell(tableHead.children[i].cellIndex);
                    break;

                case "edit":
                    newLines['newEdit'] = newRow.insertCell(tableHead.children[i].cellIndex);
                    break;

                case "delete":
                    newLines['newDelete'] = newRow.insertCell(tableHead.children[i].cellIndex);
                    break;

                case "select":
                    newLines['newIsValid'] = newRow.insertCell(tableHead.children[i].cellIndex);
                    break; 

                case "view":
                    newLines['newView'] = newRow.insertCell(tableHead.children[i].cellIndex);
                    break;
            }
        }
        if(Object.keys(newLines).length != 0) {
            resolve(newLines);
        }
        else {
            reject("Something went wrong.");
        }
    })
}


/** Ajoute une nouvelle ligne à un tableau cible.
 * @param  {string} tableId
 * @param  {object} result
 * @param  {number} counter
 */
async function appendLine(tableId, result, counter) {
    var records = result.records;
    var currentUserUUID = result.currentUserUUID;
    var scope = result.scope;
    var status = result.status;

    // On crée une nouvelle ligne à la fin du tableau existant
    var newRow = document.getElementById(tableId).lastElementChild.insertRow(-1);

    await createNewLines(newRow)
    .then((newLines) => {
        fillRecordsTable(newLines, records, counter)
        .then(() => {
            if(newLines.newStatus != undefined) {
                checkRecordValidationStatus(newLines, records, currentUserUUID, counter);
            }
            if(scope === "global" && status === "pending" && newLines.newIsValid != undefined) {
                insertViewButton(newLines.newView, records, counter);
                insertSwitchButton(newLines.newIsValid, records, counter);
            }
        });
    })
}


/** Vide le tableau et affiche un message s'il n'y a aucun résultat.
 * @param  {number} tableId
 */
async function clearTable(tableId) {
    return new Promise((resolve) => {
        document.getElementById(tableId).lastElementChild.innerHTML="";
        resolve();
    });    
}

function displayNoRecordMessage() {
    document.getElementById("no_record_message").hidden = false;
}

function hideNoRecordMessage() {
    document.getElementById("no_record_message").hidden = true;
}

function addModalContent(content, action) {
    return new Promise((resolve) => {
        let modalTitle = "";
        if(action === "edit") {
            modalTitle = "Editer un relevé";
        } else if(action === "view") {
            modalTitle = "Détails d'un relevé";
        } else {
            modalTitle = "Confirmation de suppression";
        }
        
        $(".modal-title").html(modalTitle);
        $(".modal-body").html(content);
        resolve();
    })
}