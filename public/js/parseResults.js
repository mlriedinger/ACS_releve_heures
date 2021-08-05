/** Traite les résultats renvoyés par un appel AJAX lorsque la requête renvoie plusieurs lignes et de les ajouter à un tableau existant.
 * @param  {object} data contenu de la réponse à la requête AJAX
 */
function parseMultipleLines(data) {
    //console.log(data);
    var tabData = data.records;
    var typeOfRecords = data.typeOfRecords;
    var currentUserId = data.currentUserId;

    // On vide le tableau
    clearTable(tabData);

    // Si la requête concerne une liste de relevés à valider, on insère les boutons de contrôle du formulaire de validation après le tableau
    if(tabData.length && typeOfRecords === "Check") {
        insertFormControlButtons();
    }

    // Si la requête a retourné des résultats, on boucle sur tabData pour récupérer chaque objet (relevé d'heure), puis on ajoute l'objet au tableau avec appendLine()
    if(tabData.length) {
        for (var i = 0; i < tabData.length; i++) {
            appendLine("records_log", tabData, typeOfRecords, currentUserId, i);
        }
    }
}


/** Traite les résultats renvoyés par un appel AJAX lorsque la requête renvoie une seule ligne, et les insère dans un tableau pour pré-remplir le formulaire lors de l'édition.
 * @param  {object} data
 */
function parseUniqueLine(data) {
    var recordData = [];

    $.each(data, function(key, value) {
        recordData.push(value);
    });
    
    displayRecordFormOptions(recordData);
}