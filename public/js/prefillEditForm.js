/** Ajoute un attribut "selected" à une option de liste déroulante.
 * @param  {object} data
 */
function selectWorksite(data) {
    // console.log(data);
    var worksitesCollection = document.getElementById("selectWorksite").children;
    // console.log(worksitesCollection.value);

    for (let item of worksitesCollection) {
        if (item.value === data['id_chantier']) {
            item.setAttribute("selected", "");
        }
    }
}


/** Met à jour les champs du formulaire dans la fenêtre modale d'édition d'un relevé.
 * @param  {object} data contenu de la réponse à la requête AJAX
 */
 function prefillRecordData(data) {  
    var basis = data.recordBasis;
    var details = data.recordDetails;

    selectWorksite(basis);
    prefillDate(basis);
    prefillWorkLength(basis);
    prefillSubCategories(details);
    prefillTripLength(basis);
    prefillBreakLength(basis);
    prefillComment(basis);
}


/** Pré-remplit les champs "Date" ou "Date de début"/"Date de fin" du formulaire
 * @param  {object} record contient les données de base d'un relevé
 */
function prefillDate(record) {
    var inputDate = document.getElementById("recordDate");
    var inputDateTimeStart = document.getElementById("datetime_start");
    var inputDateTimeEnd = document.getElementById("datetime_end");

    if(inputDate !== null) {
        inputDate.setAttribute("value", record["date_releve"]);
    }

    if(inputDateTimeStart !== null && inputDateTimeEnd !== null ){
        // On remplace le caractère d'espace par un "T" pour correspondre au format de date attendu par datetime-locale
        var startTime = record['date_hrs_debut'].replace(" ", "T");
        var endTime = record['date_hrs_fin'].replace(" ", "T");
        inputDateTimeStart.setAttribute("value", startTime);
        inputDateTimeEnd.setAttribute("value", endTime);
    }
}


/** Pré-remplit les champs "Temps de travail" ou "Temps de travail total" du formulaire
 * @param  {object} record contient les données de base d'un relevé
 */
function prefillWorkLength(record) {
    console.log(record);
    var inputWorkLengthHours = document.getElementById("workLengthHours");
    var inputWorkLengthMinutes = document.getElementById("workLengthMinutes");
    var inputTotalWorkLengthHours = document.getElementById("totalLengthHours");
    var inputTotalLengthMinutes = document.getElementById("totalLengthMinutes");

    var workTime = convertTimeToHoursAndMinutes(record['tps_travail']);

    if(inputWorkLengthHours !== null && inputWorkLengthMinutes !== null){
        inputWorkLengthHours.setAttribute("value", workTime['hours']);
        inputWorkLengthMinutes.setAttribute("value", workTime['minutes']);
    }

    if(inputTotalWorkLengthHours !== null && inputTotalLengthMinutes !== null){
        inputTotalWorkLengthHours.setAttribute("value", workTime['hours']);
        inputTotalLengthMinutes.setAttribute("value", workTime['minutes']);
    }
}


/** Pré-remplit les champs "heures" et "minutes" des sous-catégories du formulaire
 * @param  {object} recordDetails contient les détails d'un relevé
 */
function prefillSubCategories(recordDetails) {
    // On récupère tous les inputs des sous-catégories
    var subCategoriesInputs = document.getElementsByClassName("timeInput");

    // On définit les regex pour déterminer s'il s'agit d'un champ "heures" ou "minutes"
    var regexHours = /workstationLengthHours\[\w+\]/g;
    var regexMinutes = /workstationLengthMinutes\[\d+\]/g;

    for(let subCategory of subCategoriesInputs) {
        // On récupère le name de l'input
        let subCategoryName = subCategory.name;

        // On récupère l'id de la sous-catégorie dans le name
        let subCategoryId = parseInt(subCategoryName.substring(subCategoryName.indexOf('[') + 1, subCategoryName.lastIndexOf(']')));

        recordDetails.forEach(detail => {
            if(subCategoryId === parseInt(detail.id_poste)){
                let time = convertTimeToHoursAndMinutes(detail.duree);
                if (regexHours.test(subCategoryName)) {
                    subCategory.value = parseInt(time["hours"]);
                } 
                else if (regexMinutes.test(subCategoryName)) {
                    subCategory.value = parseInt(time["minutes"]);
                }
            }
        });
    } 
}


/** Pré-remplit les champs "Temps de trajet" du formulaire
 * @param  {object} record contient les données de base d'un relevé
 */
function prefillTripLength(record) {
    var inputTripLengthHours = document.getElementById("tripLengthHours");
    var inputTripLengthMinutes = document.getElementById("tripLengthMinutes");

    var tripTime = convertTimeToHoursAndMinutes(record['tps_trajet']);
    
    if(inputTripLengthHours !== null && inputTripLengthMinutes !== null){
        inputTripLengthHours.setAttribute("value", tripTime['hours']);
        inputTripLengthMinutes.setAttribute("value", tripTime['minutes']);
    }
}


/** Pré-remplit les champs "Temps de pause" du formulaire
 * @param  {object} record contient les données de base d'un relevé
 */
function prefillBreakLength(record) {
    var inputBreakLengthHours = document.getElementById("breakLengthHours");
    var inputBreakLengthMinutes = document.getElementById("breakLengthMinutes");

    var breakTime = convertTimeToHoursAndMinutes(record['tps_pause']);

    if(inputBreakLengthHours !== null && inputBreakLengthMinutes !== null){
        inputBreakLengthHours.setAttribute("value", breakTime['hours']);
        inputBreakLengthMinutes.setAttribute("value", breakTime['minutes']);
    }
}


/** Pré-remplit le champ "Commentaire" du formulaire
 * @param  {object} record contient les données de base d'un relevé
 */
function prefillComment(record){
    var inputComment = document.getElementById("recordComment");

    inputComment.innerHTML += record['commentaire'];
}
