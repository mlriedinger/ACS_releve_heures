/** Traite les résultats renvoyés par un appel AJAX lorsque la requête renvoie plusieurs lignes et de les ajouter à un tableau existant.
 * @param  {object} result contenu de la réponse à la requête AJAX
 */
function parseMultipleLines(result) {
    return new Promise((resolve, reject) => {
        let records = result.records;
        if(records.length) {
            resolve(result);
        }
        else {
            reject();
        }
    })
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