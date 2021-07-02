/* Fonction qui permet d'ajout un attribut "selected" à une option de liste déroulante.
*/
function addSelectedAttribute(data) {
    var worksitesCollection = document.getElementById("selectWorksite").children;

    for (let item of worksitesCollection) {
        if(item.value === data['id_affaire']){
            item.setAttribute("selected", "");
        }
    }
}


/* Fonction qui permet de mettre à jour les champs du formulaire dans la fenêtre modale d'édition d'un relevé
    Param : 
    * data : correspond au tableau contenant les résultats de la requête AJAX
*/
function updateFormInputs(data) {    
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


/* Fonction qui permet d'afficher une liste déroulante dans le formulaire d'export 1/ avec les noms et prénoms des managers , 2/ avec les noms et prénoms des utilisateurs
    Param :
    * data : contenu de la réponse à la requête AJAX
*/
function displayOptionsList(data) {
    //console.log(data);
    var typeOfData = data.typeOfData;
    var tabData = data.records;
    //console.log(data.records);

    var selector = "";
    if(typeOfData === "users") {
        selector = "#selectUser";
    }
    if(typeOfData === "managers") {
        selector = "#selectManager";
    }
    if(typeOfData === "worksites") {
        selector = "#selectWorksite";
    }

    if(typeOfData === "users" || typeOfData === "managers"){
        for(let i = 0 ; i < tabData.length ; i ++) {
            $(selector).append(new Option(tabData[i].Nom + ' ' + tabData[i]. Prenom, tabData[i].ID));
        }
    }
    else {
        for(let i = 0 ; i < tabData.length ; i ++) {
            $(selector).append(new Option(tabData[i].Nom, tabData[i].ID));
        }
    }
}


/*
*/
function displayWorkCategories(data) {
    //console.log(data);
    var divWorkLengthByCategoryInputs = document.getElementById("divWorkLengthByCategoryInputs");

    for(let i = 0; i < data.length ; i++) {
        // On transforme la casse du nom du poste : 1ère lettre en majuscule, les suivantes en minuscules
        var categoryName = data[i].code_poste[0].toUpperCase() + data[i].code_poste.substr(1).toLowerCase();

        var newDivCategory = document.createElement("div");
        newDivCategory.setAttribute("id", "div"+ categoryName);
        newDivCategory.setAttribute("class", "col-4 flex-grow-1 mb-3");

        var categoryCode = data[i].code_poste.toLowerCase().replace(/\s+/g, '');
        categoryCode = categoryCode.replace("/", "_");
        var categoryId = data[i].ID;
        var categoryDescription = data[i].libelle_poste.toLowerCase().replace(/\s+/g, '');
        
        var html = [
            `<div class="card">
                <div class="card-body">
                    <p class="card-text text-center mb-3">${ data[i].libelle_poste }</p>
                    <div class="row">
                        <div class="col mb-3">
                            <span class="input-group-text text-center" id="${ categoryCode }_hours_indicator_${ categoryId }">Heures</span>
                            <input type="number" min="0" name="workstationLengthHours[${ categoryId }]" id="${ categoryCode }LengthHours" value="0" class="form-control timeInput" aria-label="Indiquez le nombre d'heures pour le poste ${ categoryDescription }" aria-describedby="${ categoryCode }_hours_indicator" required/>
                        </div>
                        <div class="col mb-3">
                            <span class="input-group-text text-center" id="${ categoryCode }_minutes_indicator_${ categoryId }">Minutes</span>
                            <input type="number" min="-15" step="15" max="60" name="workstationLengthMinutes[${ categoryId }]" value="0" id="${ categoryCode }LengthMinutes" onchange="incrementHour(${ categoryCode }LengthHours, ${ categoryCode }LengthMinutes)" class="form-control timeInput" aria-label="Indiquez le nombre de minutes pour le poste ${ categoryDescription }" aria-describedby="${ categoryCode }_minutes_indicator" required/>
                        </div>
                    </div>
                </div>
            </div>`
        ]
        newDivCategory.innerHTML = html;
        divWorkLengthByCategoryInputs.appendChild(newDivCategory);

        addEventCalculateTotalWorkingHours();
    }
}