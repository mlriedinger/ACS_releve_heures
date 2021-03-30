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


/* Fonction qui permet de remplir les cellules de la nouvelle ligne du tableau avec les données de la requête AJAX 
    Params :
        * newLines : correspond à un objet contenant toutes les cellules d'une nouvelle ligne du tableau
        * data : correspond au tableau contenant les résultats de la requête AJAX
        * counter : index du tour de boucle actuel qui permet de créer des id uniques sur les balises HTML créées
*/

function fillRecordsTable(newLines, data, counter) {
    if(newLines.newStartTime !== undefined) {
        let newText = document.createTextNode(data[counter].date_hrs_debut);
        newLines.newStartTime.appendChild(newText);
    }

    if(newLines.newEndTime !== undefined) {
        let newText = document.createTextNode(data[counter].date_hrs_fin);
        newLines.newEndTime.appendChild(newText);
    }

    if(newLines.newComment !== undefined) {
        newLines.newComment.classList.add("records_log_comment");
        let newText = document.createTextNode(data[counter].commentaire);
        newLines.newComment.appendChild(newText);
    }

    if(newLines.newUpdateDate !== undefined) {
        newLines.newUpdateDate.classList.add("records_log_last_modification");
        let newText = document.createTextNode(data[counter].date_hrs_modif);
        newLines.newUpdateDate.appendChild(newText);
    }

    if(newLines.newFirstName !== undefined) {
        let newText = document.createTextNode(data[counter].Prenom);
        newLines.newFirstName.appendChild(newText);
    }

    if(newLines.newLastName !== undefined) {
        let newText = document.createTextNode(data[counter].Nom);
        newLines.newLastName.appendChild(newText);
    }
}


/* Fonction qui permet de créer les cellules d'une nouvelle ligne d'un tableau d'historique personnel 
    Params :
        * newRow : correspond à la nouvelle ligne du tableau
*/

function createNewLinesInPersonalRecordsTable(newRow) {
    var newLines = {
        newStartTime: newRow.insertCell(1),
        newEndTime : newRow.insertCell(2),
        newComment : newRow.insertCell(3),
        newStatus : newRow.insertCell(4),
        newUpdateDate : newRow.insertCell(5),
        newEdit : newRow.insertCell(6),
        newDelete : newRow.insertCell(7)
    }
    return newLines;
}


/* Fonction qui permet de créer les cellules d'une nouvelle ligne d'un tableau de relevés en attente de validation 
    Params :
        * newRow : correspond à la nouvelle ligne du tableau
*/

function createNewLinesInTeamRecordsToCheckTable(newRow) {
    var newLines = {
        newFirstName : newRow.insertCell(1),
        newLastName : newRow.insertCell(2),
        newStartTime : newRow.insertCell(3),
        newEndTime : newRow.insertCell(4),
        newComment : newRow.insertCell(5),
        newUpdateDate : newRow.insertCell(6),
        newIsValid : newRow.insertCell(7),
        newDelete : newRow.insertCell(8)
    }
    return newLines;
} 


/* Fonction qui permet de créer les cellules d'une nouvelle ligne d'un tableau d'historique équipe ou global 
    Params :
        * newRow : correspond à la nouvelle ligne du tableau
*/

function createTeamAndAllUsersRecordsTable(newRow) {
    var newLines = {
        newFirstName : newRow.insertCell(1),
        newLastName : newRow.insertCell(2),
        newStartTime : newRow.insertCell(3),
        newEndTime : newRow.insertCell(4),
        newComment : newRow.insertCell(5),
        newStatus : newRow.insertCell(6),
        newUpdateDate : newRow.insertCell(7),
        newEdit : newRow.insertCell(8),
        newDelete : newRow.insertCell(9)
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

    // On crée une nouvelle colonne 'chantiers'
    var newWorkSite = newRow.insertCell(0);
    var newText = document.createTextNode(data[counter].id_of);
    newWorkSite.appendChild(newText);

    // En fonction du type de relevés, on crée et on remplit les autres colonnes du tableau
    if(typeOfRecord === "Personal") {
        let newLines = createNewLinesInPersonalRecordsTable(newRow);
        fillRecordsTable(newLines, data, counter);
        checkRecordValidationStatus(newLines, data, counter);
    } 
    else if (typeOfRecord === "Check") {
        let newLines = createNewLinesInTeamRecordsToCheckTable(newRow, data, counter);
        fillRecordsTable(newLines, data, counter);
        insertSwitchButton(newLines.newIsValid, data, counter);
        insertDeleteRecordButton(newLines.newDelete, data, counter);
    } 
    else { 
        let newLines = createTeamAndAllUsersRecordsTable(newRow);
        fillRecordsTable(newLines, data, counter);
        checkRecordValidationStatus(newLines, data, counter);
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
    var inputDateTimeStart = document.getElementById("datetime_start");
    var inputDateTimeEnd = document.getElementById("datetime_end");
    var inputComment = document.getElementById("comment");

    // On remplace le caractère d'espace par un "T" pour correspondre au format de date attendu par datetime-locale
    var startTime = data[3].replace(" ", "T");
    var endTime = data[4].replace(" ", "T");

    inputDateTimeStart.setAttribute("value", startTime);
    inputDateTimeEnd.setAttribute("value", endTime);
    inputComment.innerHTML += data[6];
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
    console.log(data);
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
