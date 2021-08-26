/** Gère l'affichage du statut d'un relevé ainsi que l'affichage des boutons d'édition et de suppression.
 * @param  {object} newLines correspond à un objet contenant toutes les cellules d'une nouvelle ligne du tableau
 * @param  {object} records contenu de la réponse à la requête AJAX
 * @param  {number} currentUserId identifiant de l'utilisateur actuellement connecté
 * @param  {number} counter index du tour de boucle actuel qui permet de créer des id uniques sur les balises HTML créées
 */
function checkRecordValidationStatus(newLines, records, currentUserId, counter) {
    let validationStatus = records[counter].statut_validation;
    let deleteStatus = records[counter].supprimer;
	let userGroup = records[counter].id_groupe;

    let newValidationText = "";
    
    if ((validationStatus === "0" && deleteStatus === "0") || (userGroup === "1" && deleteStatus === "0")) {
        userGroup === "1" ? newValidationText = document.createTextNode("Auto-validé") : newValidationText = document.createTextNode("En attente");
        if (currentUserId === parseInt(records[counter].id_login)) {
            insertEditRecordButton(newLines.newEdit, records, counter);
            insertDeleteRecordButton(newLines.newDelete, records, counter);
        }
    } 
    else if (validationStatus === "0" && deleteStatus === "1") {
        newValidationText = document.createTextNode("Supprimé");
    }
    else {
        newValidationText = document.createTextNode("Validé");
    }
    
    newLines.newStatus.appendChild(newValidationText);
}


/** Remplit les cellules de la nouvelle ligne du tableau avec les données de la requête AJAX.
 * @param  {object} newLines correspond à un objet contenant toutes les cellules d'une nouvelle ligne du tableau
 * @param  {object} records contenu de la réponse à la requête AJAX
 * @param  {number} counter index du tour de boucle actuel qui permet de créer des id uniques sur les balises HTML créées
 */
function fillRecordsTable(newLines, records, counter) {
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
}


/** Ajoute des cellules dans la ligne en cours d'ajout.
 * @param  {object} newRow
 */
function createNewLines(newRow) {
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
        }
    }

    return newLines;
}


/** Ajoute une nouvelle ligne à un tableau cible.
 * @param  {string} tableId
 * @param  {object} result
 * @param  {number} counter
 */
function appendLine(tableId, result, counter) {
    var records = result.records;
    var currentUserId = result.currentUserId;
    var scope = result.scope;
    var status = result.status;

    // On vise la balise HTML dont l'id correspond à celui passé en paramètre
    var table = document.getElementById(tableId);

    // On crée une nouvelle ligne à la fin du tableau existant
    var newRow = table.lastElementChild.insertRow(-1);

    // En fonction du type de relevés, on crée et on remplit les autres colonnes du tableau
    let newLines = createNewLines(newRow);
    fillRecordsTable(newLines, records, counter);

    if(scope === "user" || (scope === "global" && status != "pending")) {
        checkRecordValidationStatus(newLines, records, currentUserId, counter);
    } 
    else {
        insertSwitchButton(newLines.newIsValid, records, counter);
    } 
}


/** Vide le tableau et affiche un message s'il n'y a aucun résultat.
 * @param  {array} records
 */
function clearTable(tableId, records) {
    table = document.getElementById(tableId);
    table.lastElementChild.innerHTML="";

    // S'il n'y a pas de relevés, on affiche un message sous le tableau, sinon on le cache
    displayNoRecordMessage(records);
    
}

function displayNoRecordMessage(records) {
    if(!records.length) {
        document.getElementById("no_record_message").hidden = false;
    } else {
        document.getElementById("no_record_message").hidden = true;
    }
}