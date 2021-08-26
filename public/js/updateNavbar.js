/** Fonction qui permet d'afficher le nombre de relevés en attente de validation dans un badge rouge à côté du menu "Validation"
 * @param  {object} result contenu de la réponse à la requête AJAX
 */
 function displayNumberOfPendingRecords(result) {
    var records = result.records;

    if(records.length) {
        document.getElementById("notificationIcon").innerHTML = records.length;
    } else {
        document.getElementById("notificationIcon").hidden = true;
    }
}


/** Fonction qui permet d'ajouter une classe "active" sur un élément de la navbar passé en paramètre.
 * @param  {string} selector
 */
function updateNavBarActiveAttribute(selectors) {
    selectors.forEach(selector => {
        var navBarItem = document.querySelector(selector);
        navBarItem.classList.add("active");
    });
}