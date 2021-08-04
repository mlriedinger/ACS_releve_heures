/** Ajoute un attribut "selected" à une option de liste déroulante.
 * @param  {object} data
 */
function addSelectedAttribute(data) {
    var worksitesCollection = document.getElementById("selectWorksite").children;

    for (let item of worksitesCollection) {
        if (item.value === data['id_affaire']) {
            item.setAttribute("selected", "");
        }
    }
}


/** Met à jour les champs du formulaire dans la fenêtre modale d'édition d'un relevé.
 * @param  {object} data contenu de la réponse à la requête AJAX
 */
 function displayRecordFormOptions(data) {    
    // On récupère les chantiers associés à l'utilisateur et on ajoute un attribut "selected" sur le chantier correspondant au relevé en cours d'édition
    addSelectedAttribute(data);
    
    // On pointe sur les inputs de formulaire à modifier
    var inputDate = document.getElementById("recordDate");
    var inputDateTimeStart = document.getElementById("datetime_start");
    var inputDateTimeEnd = document.getElementById("datetime_end");
    var inputWorkLengthHours = document.getElementById("workLengthHours");
    var inputWorkLengthMinutes = document.getElementById("workLengthMinutes");
    var inputBreakLengthHours = document.getElementById("breakLengthHours");
    var inputBreakLengthMinutes = document.getElementById("breakLengthMinutes");
    var inputTripLengthHours = document.getElementById("tripLengthHours");
    var inputTripLengthMinutes = document.getElementById("tripLengthMinutes");
    var inputComment = document.getElementById("recordComment");

    // On remplace le caractère d'espace par un "T" pour correspondre au format de date attendu par datetime-locale
    var startTime = data['date_hrs_debut'].replace(" ", "T");
    var endTime = data['date_hrs_fin'].replace(" ", "T");

    // On transforme les temps de travail, trajet et pause récupérés en minutes en heures + minutes pour l'affichage
    var workTime = convertTimeToHoursAndMinutes(data['tps_travail']);
    var tripTime = convertTimeToHoursAndMinutes(data['tps_trajet']);
    var breakTime = convertTimeToHoursAndMinutes(data['tps_pause']);

    // On insère les données dans le formulaire
    if(inputDate !== null) {
        inputDate.setAttribute("value", data["date_releve"]);
    }

    if(inputDateTimeStart !== null && inputDateTimeEnd !== null ){
        inputDateTimeStart.setAttribute("value", startTime);
        inputDateTimeEnd.setAttribute("value", endTime);
    }

    if(inputWorkLengthHours !== null && inputWorkLengthMinutes !== null){
        inputWorkLengthHours.setAttribute("value", workTime['hours']);
        inputWorkLengthMinutes.setAttribute("value", workTime['minutes']);
    }

    if(inputBreakLengthHours !== null && inputBreakLengthMinutes !== null){
        inputBreakLengthHours.setAttribute("value", breakTime['hours']);
        inputBreakLengthMinutes.setAttribute("value", breakTime['minutes']);
    }

    if(inputTripLengthHours !== null && inputTripLengthMinutes !== null){
        inputTripLengthHours.setAttribute("value", tripTime['hours']);
        inputTripLengthMinutes.setAttribute("value", tripTime['minutes']);
    }

    inputComment.innerHTML += data['commentaire'];
}
