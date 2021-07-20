/** Fonction qui permet d'ajout un attribut "selected" à une option de liste déroulante.
 * @param  {Object} data
 */
function addSelectedAttribute(data) {
    var worksitesCollection = document.getElementById("selectWorksite").children;

    for (let item of worksitesCollection) {
        if(item.value === data['id_affaire']){
            item.setAttribute("selected", "");
        }
    }
}


/** Fonction qui permet de mettre à jour les champs du formulaire dans la fenêtre modale d'édition d'un relevé
 * @param  {Object} data contenu de la réponse à la requête AJAX
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


/** Fonction qui permet d'afficher une liste déroulante dans le formulaire d'export 
 * 1/ avec les noms et prénoms des managers
 * 2/ avec les noms et prénoms des utilisateurs
 * @param  {Object} data contenu de la réponse à la requête AJAX
 */
function displayOptionsList(data) {
    var typeOfData = data.typeOfData;
    var tabData = data.records;

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


/** Fonction qui permet d'afficher les différentes catégories de postes de travail sous forme de boutons de navigation
 * @param  {Object} data contenu de la réponse à la requête AJAX
 */
function displayWorkCategories(data) {
    var workCategoriesNav = document.getElementById("workCategoriesNav");

    for(let i = 0 ; i < data.length ; i ++) {
        // On crée un nouvel item de liste pour chaque catégorie trouvée
        var newListItem = document.createElement("li");
        newListItem.setAttribute("class", "nav-item");
        newListItem.setAttribute("role", "presentation");

        var categoryId = data[i].ID;
        var categoryDescription = data[i].libelle_poste;

        var html = [
            `<button type="button" class="nav-link" id="${ categoryDescription }_tab" role="tab" data-bs-toggle="pill" aria-current="page" onclick="hideUnrelatedSubCategories(${ categoryId })">${ categoryDescription }</button>`
        ];
        
        newListItem.innerHTML = html;

        // On ajoute le nouvel item à la liste existante
        workCategoriesNav.appendChild(newListItem);
    }
    // On rend actif le premier bouton par défaut
    workCategoriesNav.firstElementChild.firstElementChild.classList.add("active");
}


/** Fonction qui permet d'afficher par défaut uniquement les sous-catégories de postes liées à la premières catégorie trouvée et de masquer les autres
 * @param  {Object} data contenu de la réponse à la requête AJAX
 */
function displayWorkSubCategories(data) {
    var firstCategoryId = 0;
    var divWorkLengthBySubCategoryInputs = document.getElementById("divWorkLengthBySubCategoryInputs");

    // Pour chaque sous-catégorie, on crée une div avec des champs "heures" et "minutes"
    for(let i = 0; i < data.length ; i++) {
        // On transforme la casse du nom du poste : 1ère lettre en majuscule, les suivantes en minuscules
        var subCategoryName = data[i].code_poste[0].toUpperCase() + data[i].code_poste.substr(1).toLowerCase();
        var subCategoryParentId = data[i].ID_categorie;
        var subCategoryId = data[i].ID;

        // Pour la première sous-catégorie trouvée, on stocke l'ID de sa catégorie parente
        if(i == 0) {
            firstCategoryId = parseInt(subCategoryParentId);
        } 

        var subCategoryCode = data[i].code_poste.toLowerCase();
        // On s'assure qu'il n'y a pas d'espaces ou de slash dans le nom de code de la catégorie
        subCategoryCode = subCategoryCode.replace(/\s+/g, '');
        subCategoryCode = subCategoryCode.replace("/", "_");

        // On crée l'élément <div> qui va contenir les champs "heures" et "minutes"
        var newDivSubCategory = document.createElement("div");
        newDivSubCategory.setAttribute("id", "div" + subCategoryName + "_" + subCategoryParentId);
        newDivSubCategory.setAttribute("class", "row mb-2 justify-content-center subCategory");

        var html = [
            `<label for="${ subCategoryCode }LengthHours" class="col-sm-2 col-form-label">${ data[i].libelle_poste }</label>
            
            <div class="col-3 me-5 me-5">
                <div class="d-flex flex-row align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-dash-circle-fill me-3" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM4.5 7.5a.5.5 0 0 0 0 1h7a.5.5 0 0 0 0-1h-7z"/>
                    </svg>

                    <input type="number" min="0" class="form-control timeInput" placeholder="Heures" name="workstationLengthHours[${ subCategoryId }]" id="${ subCategoryCode }LengthHours">
                    
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#C63527" class="bi bi-dash-circle-fill ms-3" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z"/>
                    </svg>
                </div>
            </div>

            <div class="col-3 me-5">
                <div class="d-flex flex-row align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-dash-circle-fill me-3" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM4.5 7.5a.5.5 0 0 0 0 1h7a.5.5 0 0 0 0-1h-7z"/>
                    </svg>

                    <input type="number" min="-15" step="15" max="60" class="form-control timeInput" placeholder="Minutes" name="workstationLengthMinutes[${ subCategoryId }]" id="${ subCategoryCode }LengthMinutes" onchange="incrementHour(${ subCategoryCode }LengthHours, ${ subCategoryCode }LengthMinutes)">
                    
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#C63527" class="bi bi-dash-circle-fill ms-3" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z"/>
                    </svg>
                </div>
            </div>`
        ];

        // On remplit la div avec le contenu HTML, puis on a insère la div ainsi créée dans le DOM
        newDivSubCategory.innerHTML = html;
        divWorkLengthBySubCategoryInputs.appendChild(newDivSubCategory);
    }
    // On masque les sous-catégories qui n'appartiennent pas à la première catégorie trouvée dans les enregistrements pour qu'elle n'apparaissent pas au chargement de la page
    hideUnrelatedSubCategories(firstCategoryId);

    // On ajoute un événement qui permet de détecter les modifications dans les inputs "heures" et "minutes"
    addEventCalculateTotalWorkingHours();
}


/** Fonction qui permet de masquer ou d'afficher les sous-catégories selon que leur catégorie parente est différente de celle passée en paramètre ou non
 * @param  {number} categoryId
 */
function hideUnrelatedSubCategories(categoryId) {
    $('.subCategory').each(function() {
        // On récupère le dernier caractère de l'attribut id de la balise (qui contient l'id de la catégorie parente)
        let subCategoryParentId = parseFloat($(this).attr('id').substr(-1, 1));

        subCategoryParentId !== categoryId ? $(this).attr("hidden", true) : $(this).attr("hidden", false);
    });
}