/* Fonction qui permet de gérer l'affichage du statut du relevé 
    Params :
        * newLines : correspond à un objet contenant toutes les cellules d'une nouvelle ligne du tableau
        * data : correspond au tableau contenant les résultats de la requête AJAX
        * counter : index du tour de boucle actuel qui permet de créer des id uniques sur les balises HTML créées
*/

function checkRecordValidationStatus(newLines, data, counter) {
    let validationStatus = data[counter].statut_validation;
    let deleteStatus = data[counter].supprimer;

    let newValidationText = "";
    
    if(validationStatus === "0" && deleteStatus === "0") {
        newValidationText = document.createTextNode("En attente");
        insertEditRecordButton(newLines.newEdit, data, counter);
        insertDeleteRecordButton(newLines.newDelete, data, counter);  
    } 
    else if (validationStatus === "0" && deleteStatus === "1") {
        newValidationText = document.createTextNode("Supprimé");
    }
    else {
        newValidationText = document.createTextNode("Validé");
    }
    
    newLines.newStatus.appendChild(newValidationText);
}


function convertTimeToHoursAndMinutes(timeToConvert) {
    let convertedTime = [];
    convertedTime['hours'] = Math.floor(timeToConvert / 60);
    convertedTime['minutes'] = timeToConvert % 60;
    if(convertedTime['minutes'] === 0) convertedTime['minutes'] = "00";

    return convertedTime;
}


/* Fonction qui permet de remplir les cellules de la nouvelle ligne du tableau avec les données de la requête AJAX 
    Params :
        * newLines : correspond à un objet contenant toutes les cellules d'une nouvelle ligne du tableau
        * data : correspond au tableau contenant les résultats de la requête AJAX
        * counter : index du tour de boucle actuel qui permet de créer des id uniques sur les balises HTML créées
*/

function fillRecordsTable(newLines, data, counter) {
    if(newLines.newWorkSite !== undefined) {
        let newText = document.createTextNode(data[counter].chantier);
        newLines.newWorkSite.appendChild(newText);
    }

    if(newLines.newManager !== undefined) {
        let newText = document.createTextNode(data[counter].prenom_manager + ' ' + data[counter].nom_manager);
        newLines.newManager.appendChild(newText);
    }

    if(newLines.newEmployee !== undefined) {
        let newText = document.createTextNode(data[counter].prenom_salarie + ' ' + data[counter].nom_salarie);
        newLines.newEmployee.appendChild(newText);
    }

    if(newLines.newStartTime !== undefined) {
        let newText = document.createTextNode(data[counter].date_hrs_debut);
        newLines.newStartTime.appendChild(newText);
    }

    if(newLines.newEndTime !== undefined) {
        let newText = document.createTextNode(data[counter].date_hrs_fin);
        newLines.newEndTime.appendChild(newText);
    }

    if(newLines.newDate !== undefined) {
        let newText = document.createTextNode(data[counter].date_releve);
        newLines.newDate.appendChild(newText);
    }

    if(newLines.newWorkTime !== undefined) {
        let time = convertTimeToHoursAndMinutes(data[counter].tps_travail);
        let newText = document.createTextNode(time['hours'] + "h" + time['minutes']);
        newLines.newWorkTime.appendChild(newText);
    }

    if(newLines.newBreakTime !== undefined) {
        let time = convertTimeToHoursAndMinutes(data[counter].tps_pause);
        let newText = document.createTextNode(time['hours'] + "h" + time['minutes']);
        newLines.newBreakTime.appendChild(newText);
    }

    if(newLines.newTripTime !== undefined) {
        let time = convertTimeToHoursAndMinutes(data[counter].tps_trajet);
        let newText = document.createTextNode(time['hours'] + "h" + time['minutes']);
        newLines.newTripTime.appendChild(newText);
    }

    if(newLines.newComment !== undefined) {
        newLines.newComment.classList.add("records_log_comment");
        let newText = data[counter].commentaire;
        newLines.newComment.innerHTML = newText;
    }

    if(newLines.newUpdateDate !== undefined) {
        newLines.newUpdateDate.classList.add("records_log_last_modification");
        let newText = document.createTextNode(data[counter].date_hrs_modif);
        newLines.newUpdateDate.appendChild(newText);
    }

    

    
}


/* 
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

            case "manager":
                newLines['newManager'] = newRow.insertCell(tableHead.children[i].cellIndex);
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


/* Fonction qui ajoute une nouvelle ligne à un tableau cible
    Params :
        * tableID : id associé à la balise <table>
        * data : correspond au tableau contenant les résultats de la requête AJAX
        * typeOfRecord : type de relevés demandés (personnels, en attente, équipe ou globaux)
        * counter : index du tour de boucle actuel qui permet de créer des id uniques sur les balises HTML créées
*/

function appendLine(tableID, data, typeOfRecord, counter) {
    // On vise la balise HTML dont l'id correspond à celui passé en paramètre
    var table = document.getElementById(tableID);

    // On crée une nouvelle ligne à la fin du tableau existant
    var newRow = table.insertRow(-1);

    // En fonction du type de relevés, on crée et on remplit les autres colonnes du tableau
    let newLines = createNewLines(newRow);
    fillRecordsTable(newLines, data, counter);

    if(typeOfRecord === "Personal" || typeOfRecord === "Team" || typeOfRecord === "All") {
        checkRecordValidationStatus(newLines, data, counter);
    } 
    else {
        insertSwitchButton(newLines.newIsValid, data, counter);
        insertDeleteRecordButton(newLines.newDelete, data, counter);
    } 
}


/* Fonction qui permet de vider le tableau et d'afficher un message s'il n'y a aucun résultat à afficher
    Param :
    * tabData : tableau contenant les données de la requête AJAX
*/

function clearTable(tabData) {
    // On récupère la ligne vide du tableau
    var trEmpty = $("#records_log tbody tr:first-child");

    // On ré-insère la ligne vide. Résultat : Le tableau est vidé à chaque fois que la fonction est appelée
    $("#records_log").children("tbody").html(trEmpty);

    // Si la requête n'a retourné aucun résultat, on affiche un message sous le tableau, sinon on le cache
    if(!tabData.length) {
        document.getElementById("no_record_message").hidden = false;
    } else {
        document.getElementById("no_record_message").hidden = true;
    }
}


/* Fonction qui permet de mettre à jour les champs du formulaire dans la fenêtre modale d'édition d'un relevé
    Param : 
    * data : correspond au tableau contenant les résultats de la requête AJAX
*/

function updateFormInputs(data) {
    console.log("updateFormInputs :");
    console.log(data);
    
    // On récupère les chantiers associés à l'utilisateur et on ajoute un attribut "selected" sur le chantier correspondant au relevé en cours d'édition
    var worksitesCollection = document.getElementById("selectWorksite").children;
    for (let item of worksitesCollection) {
        if(item.value === data['id_chantier']){
            item.setAttribute("selected", "");
        }
    }
    
    // On pointe sur les inputs de formulaire à modifier
    var inputDateTimeStart = document.getElementById("datetime_start");
    var inputDateTimeEnd = document.getElementById("datetime_end");
    var inputBreakTime = document.getElementById("breakLengthMinutes");
    var inputTripLengthHours = document.getElementById("tripLengthHours");
    var inputTripLengthMinutes = document.getElementById("tripLengthMinutes");
    var inputComment = document.getElementById("comment");

    // On remplace le caractère d'espace par un "T" pour correspondre au format de date attendu par datetime-locale
    var startTime = data['date_hrs_debut'].replace(" ", "T");
    var endTime = data['date_hrs_fin'].replace(" ", "T");

    // On transforme le temps de trajet récupéré en minutes en heures + minutes pour l'affichage
    var tripTimeHours = Math.floor(data['tps_trajet'] / 60);
    var tripTimeMinutes = data['tps_trajet'] % 60;

    // On insère les données dans le formulaire
    inputDateTimeStart.setAttribute("value", startTime);
    inputDateTimeEnd.setAttribute("value", endTime);
    inputBreakTime.setAttribute("value", data['tps_pause']);
    inputTripLengthHours.setAttribute("value", tripTimeHours);
    inputTripLengthMinutes.setAttribute("value", tripTimeMinutes);
    inputComment.innerHTML += data['commentaire'];
}


/* Fonction qui permet d'afficher le nombre de relevés en attente de validation dans un badge rouge à côté du menu "Validation"
    Param :
    * data : contenu de la réponse à la requête AJAX
*/

function displayNumberOfRecordsTocheck(data) {
    var tabData = data.records;

    if(tabData.length) {
        document.getElementById("notificationIcon").innerHTML = tabData.length;
    } else {
        document.getElementById("notificationIcon").hidden = true;
    }
}


/* Fonction qui permet d'afficher une liste déroulante dans le formulaire d'export 1/ avec les noms et prénoms des managers , 2/ avec les noms et prénoms des utilisateurs
    Param :
    * data : contenu de la réponse à la requête AJAX
*/

function displayOptionsList(data) {
    // console.log(data);
    var typeOfData = data.typeOfData;
    var tabData = data.records;

    var selector = "";
    if(typeOfData === "users") {
        selector = "#selectUser";
    }
    if(typeOfData === "managers") {
        selector = "#selectManager";
    }
    if(typeOfData === "worksites") {
        selector = "#selectWorksite";
    }

    if(typeOfData === "users" || typeOfData === "managers"){
        for(let i = 0 ; i < tabData.length ; i ++) {
            $(selector).append(new Option(tabData[i].Nom + ' ' + tabData[i]. Prenom, tabData[i].ID));
        }
    }
    else {
        for(let i = 0 ; i < tabData.length ; i ++) {
            $(selector).append(new Option(tabData[i].Nom, tabData[i].ID));
        }
    }
}
