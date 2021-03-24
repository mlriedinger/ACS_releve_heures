function fillPersonalRecordsTable(newRow, data, counter){
    // On crée autant de nouvelles colonnes qu'il y a de champs dans le tableau
    var newStartTime = newRow.insertCell(1);
    var newEndTime = newRow.insertCell(2);
    var newComment = newRow.insertCell(3);
    var newStatus = newRow.insertCell(4);
    var newUpdateDate = newRow.insertCell(5);
    var newEdit = newRow.insertCell(6);
    var newDelete = newRow.insertCell(7);

    // On ajoute du contenu à chaque colonne créée : ici, les données du tableau passé en paramètre
    newStartTime.innerHTML += data[counter].date_hrs_debut;
    newEndTime.innerHTML += data[counter].date_hrs_fin;

    newComment.classList.add("records_log_comment");
    newComment.innerHTML += data[counter].commentaire;

    if(data[counter].statut_validation == 0){
        newStatus.innerHTML += "En attente";
        if(data[counter].supprimer == 0){
            // Dans la dernière colonne, on insère un bouton avec une icône, qui commande l'affichage de la fenêtre modale et qui, au clic, appelle la fonction pour charger le formulaire en lui passant l'id du relevé 
            newEdit.innerHTML += '<button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#formModal" onclick="displayRecordForm(' + data[counter].ID + ')" data-bs-whatever="Editer"><i class="far fa-edit"></i></button>';
            newDelete.innerHTML += '<button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#formModal" onclick="displayDeleteConfirmation(' + data[counter].ID + ')"><i class="far fa-trash-alt"></i></button>';
        }
    } else newStatus.innerHTML += "Validé";
    
    newUpdateDate.classList.add("records_log_last_modification");
    newUpdateDate.innerHTML += data[counter].date_hrs_modif;
}

function fillTeamRecordsToCheckTable(newRow, data, counter){
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
    newFirstName.innerHTML += data[counter].Prenom;
    newLastName.innerHTML += data[counter].Nom;
    newStartTime.innerHTML += data[counter].date_hrs_debut;
    newEndTime.innerHTML += data[counter].date_hrs_fin;
    newComment.innerHTML += data[counter].commentaire;
    newUpdateDate.innerHTML += data[counter].date_hrs_modif;

    var html = [
        '<div class="form-check form-switch">',
            '<input class="form-check-input" type="checkbox" name="checkList[' + counter +']" id="recordValidationCheck' + counter +'" value="' + data[counter].ID +'"/>',
            '<label class="form-check-label" for="recordValidationCheck' + counter +'">Sélectionner</label>',
        '</div>'
    ].join('');

    newIsValid.innerHTML += html;
    newDelete.innerHTML += '<button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#formModal" onclick="displayDeleteConfirmation(' + data[counter].ID + ')"><i class="far fa-trash-alt"></i></button>';
} 


function fillTeamAndAllUsersRecordsTable(newRow, data, counter){
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
    newFirstName.innerHTML += data[counter].Prenom;
    newLastName.innerHTML += data[counter].Nom;
    newStartTime.innerHTML += data[counter].date_hrs_debut;
    newEndTime.innerHTML += data[counter].date_hrs_fin;
    newComment.innerHTML += data[counter].commentaire;

    if(data[counter].statut_validation == '0'){
        newStatus.innerHTML += "En attente";
        if(data[counter].supprimer == 0){
            // Dans la dernière colonne, on insère un bouton avec une icône, qui commande l'affichage de la fenêtre modale et qui, au clic, appelle la fonction pour charger le formulaire en lui passant l'id du relevé 
            newEdit.innerHTML += '<button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#formModal" onclick="displayRecordForm(' + data[counter].ID + ')" data-bs-whatever="Editer"><i class="far fa-edit"></i></button>';
            newDelete.innerHTML += '<button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#formModal" onclick="displayDeleteConfirmation(' + data[counter].ID + ')"><i class="far fa-trash-alt"></i></button>';
        }
    } else newStatus.innerHTML += "Validé";

    newUpdateDate.innerHTML += data[counter].date_hrs_modif;
}

/* Fonction qui ajoute une nouvelle ligne à un tableau cible
    Params :
        * tableID : id associé à la balise <table>
        * data : un tableau de données
        * typeOfRecord : type de relevés demandés (personnels, en attente, équipe ou globaux)
        * counter : index du tour de boucle actuel qui permet de créer des id uniques sur les balises HTML créées
*/

function appendLine(tableID, data, typeOfRecord, counter){
    // On vise la balise HTML dont l'id correspond à celui passé en paramètre
    var table = document.getElementById(tableID);

    // On crée une nouvelle ligne à la fin du tableau existant
    var newRow = table.insertRow(-1);

    // On crée une nouvelle colonne 'chantiers'
    var newWorkSite = newRow.insertCell(0);
    newWorkSite.innerHTML += data[counter].id_of;

    // En fonction du type de relevés, on crée et on remplit les autres colonnes du tableau
    if(typeOfRecord == 'Personal') fillPersonalRecordsTable(newRow, data, counter);
    else if (typeOfRecord == 'Check') fillTeamRecordsToCheckTable(newRow, data, counter);
    else fillTeamAndAllUsersRecordsTable(newRow, data, counter);
}


/* Fonction qui permet de vider le tableau et d'afficher un message s'il n'y a aucun résultat à afficher
    Param :
    * tab_data : tableau contenant les données de la requête AJAX
*/

function clearTable(tab_data){
    // On récupère la ligne vide du tableau
    var tr_empty = $("#records_log tbody tr:first-child");

    // On ré-insère la ligne vide. Résultat : Le tableau est vidé à chaque fois que la fonction est appelée
    $("#records_log").children("tbody").html(tr_empty);

    // Si la requête n'a retourné aucun résultat, on affiche un message sous le tableau, sinon on le cache
    if(!tab_data.length) document.getElementById("no_record_message").hidden = false;
    else document.getElementById("no_record_message").hidden = true;
}


/* Fonction qui permet d'insérer des boutons de contrôle de formulaire */

function insertFormControlButtons(){
    var formControlButtons = [
        '<div class="row mb-3 justify-content-md-center">',      
            '<div class="col-lg mb-5 text-end">',
                '<input type="reset" value="Annuler" class="btn btn-light p-3"/>',
                '<input type="submit" value="Valider" class="btn btn-dark"/>',
            '</div>',
        '</div>'
    ].join('');

    $(formControlButtons).insertAfter("#records_log");
}


/* Fonction qui permet de traiter les données reçues de PHP, lorsque la requête renvoie plusieurs lignes et de les insérer dans le tableau 
    Param :
    * data : contenu de la réponse à la requête AJAX
*/

function parseMultipleLinesRequest(data){
    console.log(data);
    var tab_data = data.records;
    var typeOfRecords = data.typeOfRecords;

    // On vide le tableau
    clearTable(tab_data);

    // Si la requête concerne une liste de relevés à valider, on insère les boutons de contrôle du formulaire de validation après le tableau
    if(tab_data.length && typeOfRecords == 'Check') insertFormControlButtons();

    // Si la requête a retourné des résultats, on boucle sur tab_data pour récupérer chaque objet (relevé d'heure), puis on ajoute l'objet au tableau avec appendLine()
    if(tab_data.length){
        for (var i = 0; i < tab_data.length; i++) {
            appendLine('records_log', tab_data, typeOfRecords, i);
        }
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

    // On remplace le caractère d'espace par un "T" pour correspondre au format de date attendu par datetime-locale
    var startTime = data[3].replace(" ", "T");
    var endTime = data[4].replace(" ", "T");

    inputDateTimeStart.setAttribute("value", startTime);
    inputDateTimeEnd.setAttribute("value", endTime);
    inputComment.innerHTML += data[6];
}


/* Fonction qui permet de traiter les données reçues de PHP, lorsque la requête renvoie une seule ligne, et de les insérer dans le tableau
    Param :
    * data : contenu de la réponse à la requête AJAX
*/

function parseUniqueLineRequest(data){
    console.log(data);
    var recordData = [];

    $.each(data, function(key, value) {
        recordData.push(value);
    });
    
    updateFormInputs(recordData);
}


/* Fonction qui permet d'afficher le nombre de relevés en attente de validation dans un badge rouge à côté du menu "Validation"
    Param :
    * data : contenu de la réponse à la requête AJAX
*/

function displayNumberOfRecordsTocheck(data){
    var tab_data = data.records;

    if(tab_data.length) document.getElementById("notificationIcon").innerHTML = tab_data.length;
    else document.getElementById("notificationIcon").hidden = true;
}


/* Fonction qui permet d'afficher une liste déroulante dans le formulaire d'export 1/ avec les noms et prénoms des managers , 2/ avec les noms et prénoms des utilisateurs
    Param :
    * data : contenu de la réponse à la requête AJAX
*/

function displayOptionsList(data){
    console.log(data);
    var typeOfData = data.typeOfData;
    var tab_data = data.records;

    var selector = "";
    if(typeOfData === "users") selector = "#selectUser";
    if(typeOfData === "managers") selector = "#selectManager";
    

    for(let i = 0 ; i < tab_data.length ; i ++){
        $(selector).append(new Option(tab_data[i].Nom + ' ' + tab_data[i]. Prenom, tab_data[i].ID));
    }
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
;}

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

function displayRecordForm(recordId){
    $.post('index.php?action=getRecordForm', { 'recordId': recordId }, function(content){
        $(".modal-body").html(content);
    });
}

function displayDeleteConfirmation(recordId){
    $.post('index.php?action=getDeleteConfirmationForm', { 'recordId': recordId }, function(content){
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
    $.post('index.php?action=getRecordData', { 'recordId': recordId }, parseUniqueLineRequest/*, 'json'*/);
}


/*  Appel AJAX pour récupèrer les résultats des requêtes PHP au format JSON et afficher dynamiquement le nombre de relevés en attente de validation
    Params :
    * 'url' : url sur laquelle faire la requête POST
    * {} : données à envoyer dans la requête, ici 'typeOfRecords' : type de relevés demandés (personnels, équipe, à vérifier ou globaux) ; 'scope' : portée de la demande (tous, validés, en attente, supprimés)
    * displayNumberOfRecordsTocheck : fonction à appeler en cas de succès de la requête. Le contenu de la réponse est automatiquement passé en paramètre.
    * 'json' : format de données reçues par la requête AJAX
*/

function getNumberOfRecordsToCheck(typeOfRecords, scope){
    $.post('index.php?action=getTeamRecordsLog', { 'typeOfRecords': typeOfRecords, 'scope': scope }, displayNumberOfRecordsTocheck, 'json');
}


/*  Appel AJAX pour récupèrer les résultats des requêtes PHP au format JSON et afficher dynamiquement les listes déroulantes de managers et de salariés
    Params :
    * 'url' : url sur laquelle faire la requête POST
    * {} : données à envoyer dans la requête, ici 'typeOfData' : le type d'utilisateurs ("managers" ou "users")
    * displayOptionsList : fonction à appeler en cas de succès de la requête. Le contenu de la réponse est automatiquement passé en paramètre.
    * 'json' : format de données reçues par la requête AJAX
*/

function getOptionsData(optionType){
    $.post('index.php?action=getOptionsData', { 'typeOfData': optionType }, displayOptionsList, 'json');
}
