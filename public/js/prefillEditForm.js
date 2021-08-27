/** 
 * Ajoute un attribut "selected" à une option de liste déroulante.
 * @param  {object} data
 */
function selectWorksite(worksiteId) {
    var worksitesCollection = document.getElementById("selectWorksite").children;

    for (let item of worksitesCollection) {
        if (parseInt(item.value) === worksiteId) {
            item.setAttribute("selected", "");
        }
    }
}


/** 
 * Met à jour les champs du formulaire dans la fenêtre modale d'édition d'un relevé.
 * @param  {object} data contenu de la réponse à la requête AJAX
 */
 function prefillRecordData(data) {  
    var basis = data.recordBasis;
    var details = data.recordDetails;

    selectWorksite(parseInt(basis['id_chantier']));
    prefillDate(basis);
    prefillWorkLength(basis);
    
    var aggregatedDetails = concatInputsInfosAndRecordDetails(details);
    prefillSubCategories(aggregatedDetails);
    
    prefillTripLength(basis);
    prefillBreakLength(basis);
    prefillComment(basis);
}


/** 
 * Pré-remplit les champs "Date" ou "Date de début"/"Date de fin" du formulaire
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


/** 
 * Pré-remplit les champs "Temps de travail" ou "Temps de travail total" du formulaire
 * @param  {object} record contient les données de base d'un relevé
 */
function prefillWorkLength(record) {
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


/** 
 * Pré-remplit les champs "heures" et "minutes" des sous-catégories du formulaire
 * @param  {object} aggregatedDetails tableau de correspondance entre les inputs et les détails d'un relevé
 */
function prefillSubCategories(aggregatedDetails) {
    for(let i = 0 ; i < aggregatedDetails.length ; i++) {
        let input = document.getElementsByName(aggregatedDetails[i].name);        
        input[0].value = aggregatedDetails[i].duration;
    }
}


/**
 * Crée un tableau recensant les informations des inputs associés aux sous-catégories de postes.
 * @returns {object} un tableau contenant le name, l'id de la sous-catégorie, la valeur de l'input et son type (champ heures ou minutes)
 */
function parseInputsData() {
    var data = [];

    // On récupère tous les inputs des sous-catégories
    var subCategoriesInputs = document.getElementsByClassName("timeInput");
    
    // On définit les regex pour déterminer s'il s'agit d'un champ "heures" ou "minutes"
    var regexHours = /workstationLengthHours\[\d+\]/g;
    var regexMinutes = /workstationLengthMinutes\[\d+\]/g;

    for(let input of subCategoriesInputs) {
        let inputInfos = {};

        let name = input.name;
        let subCategoryId = parseInt(name.substring(name.indexOf('[') + 1, name.lastIndexOf(']')));

        inputInfos["subCategoryId"] = subCategoryId;

        if(regexHours.test(input.name)) {
            inputInfos["type"] = "hours";
        } else if(regexMinutes.test(input.name)) {
            inputInfos["type"] = "minutes";
        }

        inputInfos["name"] = name;
        inputInfos["value"] = input.value;     

        data.push(inputInfos);
    }

    return data;

}


/**
 * Fait correspondre les détails d'un relevé avec les données des inputs de sous-catégories
 * @param {object} inputsData tableau recensant les informations des inputs associés aux sous-catégories de postes
 * @param {object} recordDetails tableau contenant des détails d'un relevé d'heures
 * @returns {object} un tableau associant les détails d'un relevé aux inputs dans lesquels ils devront être affichés
 */
function addRecordDetails(inputsData, recordDetails) {

    recordDetails.forEach(detail => {
        let time = convertTimeToHoursAndMinutes(detail.duree);

        for(let i = 0 ; i < inputsData.length ; i++) {
            if(parseInt(detail.id_poste) === inputsData[i].subCategoryId) {
                if(inputsData[i].type === "hours") {
                    inputsData[i]["duration"] = time["hours"];
                } else {
                    inputsData[i]["duration"] = time["minutes"];
                } 
            }
        }
    })

    return inputsData;
}

/**
 * Crée un tableau de correspondance entre les inputs des sous-catégories et les détails d'un relevé d'heures 
 * @param {object} recordDetails recensant les informations des inputs associés aux sous-catégories de postes
 * @returns {object} un tableau de correspondance
 */
function concatInputsInfosAndRecordDetails(recordDetails) {
    var aggregatedData = parseInputsData();
    aggregatedData = addRecordDetails(aggregatedData, recordDetails);

    return aggregatedData;
}


/** 
 * Pré-remplit les champs "Temps de trajet" du formulaire
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


/** 
 * Pré-remplit les champs "Temps de pause" du formulaire
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


/** 
 * Pré-remplit le champ "Commentaire" du formulaire
 * @param  {object} record contient les données de base d'un relevé
 */
function prefillComment(record){
    var inputComment = document.getElementById("recordComment");

    inputComment.innerHTML += record['commentaire'];
}
