/* Fonction qui ajoute une nouvelle ligne au tableau
    Paramètres :
        * tableID : id associé à la balise <table>
        * data : un tableau de données
*/

function appendLine(tableID, data, typeOfRecord){
    console.log(typeOfRecord);
    // On vise la balise HTML dont l'id correspond à celui passé en paramètre
    var table = document.getElementById(tableID);

    // On crée une nouvelle ligne à la fin du tableau existant
    var newRow = table.insertRow(-1);

    var newWorkSite = newRow.insertCell(0);
    newWorkSite.innerHTML += data[0];

    if(typeOfRecord == 'Personal'){
        // On crée autant de nouvelles colonnes qu'il y a de champs dans le tableau
        var newStartTime = newRow.insertCell(1);
        var newEndTime = newRow.insertCell(2);
        var newComment = newRow.insertCell(3);
        var newStatus = newRow.insertCell(4);
        var newUpdateDate = newRow.insertCell(5);

        // On ajoute du contenu à chaque colonne créée : ici, les données du tableau passé en paramètre
        newStartTime.innerHTML += data[1];
        newEndTime.innerHTML += data[2];
        newComment.innerHTML += data[3];
        data[4] == 0 ? newStatus.innerHTML += "En attente" : newStatus.innerHTML += "Validé";
        newUpdateDate.innerHTML += data[6];
    } else {
        // On crée autant de nouvelles colonnes qu'il y a de champs dans le tableau
        var newFirstName = newRow.insertCell(1);
        var newLastName = newRow.insertCell(2);
        var newStartTime = newRow.insertCell(3);
        var newEndTime = newRow.insertCell(4);
        var newComment = newRow.insertCell(5);
        var newStatus = newRow.insertCell(6);
        var newUpdateDate = newRow.insertCell(7);

        // On ajoute du contenu à chaque colonne créée : ici, les données du tableau passé en paramètre
        newFirstName.innerHTML += data[2];
        newLastName.innerHTML += data[1];
        newStartTime.innerHTML += data[3];
        newEndTime.innerHTML += data[4];
        newComment.innerHTML += data[5];
        data[6] == 0 ? newStatus.innerHTML += "En attente" : newStatus.innerHTML += "Validé";
        newUpdateDate.innerHTML += data[8];
    }
}


/* Fonction qui permet de traiter les données reçues de PHP et de les insérer dans le tableau 
    Paramètre :
    * data : contenu de la réponse à la requête POST
*/

function getDataFromPhp(data){
    var tab_data = data.records;
    var typeOfRecords = data.typeOfRecords;

    // On récupère la ligne vide du tableau
    var tr_empty = $("#records_log tbody tr:first-child");
    // On ré-insère la ligne vide. 
    // Résultat : Le tableau est vidé à chaque fois que la fonction est appelée
    $("#records_log").children("tbody").html(tr_empty);

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
        // On appelle la fonction qui permet d'ajouter des lignes au tableau et on lui passe le tableau en paramètre
        appendLine('records_log', recordData, typeOfRecords);
    }
}


/*  Appel AJAX pour récupèrer en JS le résultat de la requête PHP  
    Paramètres :
    * 'url' : url sur laquelle faire la requête POST
    * {} : données à envoyer dans la requête (ici, aucune)
    * getDataFromPhp : fonction à appeler en cas de succès de la requête. Le contenu de la réponse est automatiquement passé en paramètre.
    * 'json' : format de données reçues
*/

function updatePersonnalRecordsLog(typeOfRecords) {
    $.post('index.php?action=getPersonnalRecordsLog', { 'typeOfRecords': typeOfRecords }, getDataFromPhp, 'json');
;}

function updateAllUsersRecordsLog(typeOfRecords) {
    $.post('index.php?action=getAllUsersRecordsLog', { 'typeOfRecords': typeOfRecords }, getDataFromPhp, 'json');
}

function updateTeamRecordsLog(typeOfRecords) {
    $.post('index.php?action=getTeamRecordsLog', { 'typeOfRecords': typeOfRecords }, getDataFromPhp, 'json');
}
