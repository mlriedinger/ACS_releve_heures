/** Traite les résultats renvoyés par un appel AJAX lorsque la requête renvoie plusieurs lignes et de les ajouter à un tableau existant.
 * @param  {object} data contenu de la réponse à la requête AJAX
 */
function parseMultipleLines(data) {
    console.log(data);
    var records = data.records;
    var scope = data.scope;
    var status = data.status;
    var currentUserId = data.currentUserId;

    // On vide le tableau
    clearTable("records_log", records);

    // Si la requête a retourné des résultats, on boucle sur records pour récupérer chaque objet (relevé d'heure), puis on ajoute l'objet au tableau avec appendLine()
    if(records.length) {
        for (var i = 0; i < records.length; i++) {
            appendLine("records_log", records, scope, currentUserId, i);
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