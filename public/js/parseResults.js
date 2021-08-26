/** Traite les résultats renvoyés par un appel AJAX lorsque la requête renvoie plusieurs lignes et de les ajouter à un tableau existant.
 * @param  {object} result contenu de la réponse à la requête AJAX
 */
function parseMultipleLines(result) {
    
    var records = result.records;

    // On vide le tableau
    clearTable("records_log", records);

    // Si la requête a retourné des résultats, on boucle sur records pour récupérer chaque objet (relevé d'heure), puis on ajoute l'objet au tableau avec appendLine()
    if(records.length) {
        for (var i = 0; i < records.length; i++) {
            appendLine("records_log", result, i);
        }
    }
}


/** Traite les résultats renvoyés par un appel AJAX lorsque la requête renvoie une seule ligne, et les insère dans un tableau pour pré-remplir le formulaire lors de l'édition.
 * @param  {object} result
 */
function parseUniqueLine(result) {
    var recordData = [];

    $.each(result, function(key, value) {
        recordData.push(value);
    });
    
    prefillRecordData(recordData);
}