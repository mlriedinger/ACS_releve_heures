/**
 * Ajoute les noms, prénoms et ID des utilisateurs à la liste déroulante #selectUser du formulaire d'ajout selon le tableau passé en paramètre.
 * @param {any} users
 * @returns {any}
 */
function addUsersToSelectTag(users) {
    for(let i = 0 ; i < users.length ; i++) {
        $('#selectUser').append(new Option(users[i].Nom + ' ' + users[i].Prenom, users[i].ID));
    }
}


/**
 * Ajoute les noms et ID des chantiers à la liste déroulante #selectWorksite du formulaire d'ajout selon le tableau passé en paramètre.
 * @param {any} worksites
 * @returns {any}
 */
function addWorksitesToSelectTag(worksites) {
    return new Promise((resolve) => {
        for(let i = 0 ; i < worksites.length ; i++) {
            $('#selectWorksite').append(new Option(worksites[i].Nom, worksites[i].ID));
        }
        resolve();
    })
}


/** Affiche les différentes catégories de postes de travail sous forme de boutons de navigation.
 * @param  {object} workCategories contenu de la réponse à la requête AJAX
 */
function displayWorkCategories(workCategories, eventType) {
    return new Promise((resolve) => {
        var workCategoriesNav = document.getElementById("workCategoriesNav");

        for(let i = 0 ; i < workCategories.length ; i ++) {
            // On crée un nouvel item de liste pour chaque catégorie trouvée
            var newListItem = document.createElement("li");
            newListItem.setAttribute("class", "nav-item");
            newListItem.setAttribute("role", "presentation");
    
            var categoryId = workCategories[i].ID;
            var categoryDescription = workCategories[i].libelle_poste;
    
            var html = [
                `<button type="button" class="nav-link" id="${ categoryDescription }_tab_${ categoryId }" role="tab" data-bs-toggle="pill" aria-current="page" onclick="hideUnrelatedSubCategories(${ categoryId })">${ categoryDescription }</button>`
            ];
            
            newListItem.innerHTML = html;
    
            // On ajoute le nouvel item à la liste existante
            workCategoriesNav.appendChild(newListItem);
        }
        resolve();
    })
}


/**
 * Ajoute un attribut "active" sur la catégorie correspondant au type d'événement passé en paramètre.
 * @param {any} eventType
 */
function addActiveAttribute(eventType) {
    let workCategories = document.getElementById("workCategoriesNav").children;
    let selector = 0 ;

    switch(eventType){
        case "Fabrication":
        case "Fab Pose":
            selector = 0;
            break;
        case "Pose":
            selector = 1;
            break;
    }
    workCategories[selector].firstElementChild.classList.add("active");
}


/**
 * Retourne l'ID de la catégorie active (càd, celle qui a un attribut "active").
 * @returns {Promise}
 */
function getFirstCategoryId() {
    return new Promise((resolve) => {
        let firstCategoryId = 0;
        // cibler les categories
        let workCategories = document.getElementById("workCategoriesNav").children;
        // rechercher celle avec un attribut "active"
        for (let i = 0 ; i < workCategories.length ; i ++) {
            if(workCategories[i].firstElementChild.classList.contains("active")) {
                let categoryId = workCategories[i].firstElementChild.id;
                // récupérer son id
                firstCategoryId = categoryId.substring(categoryId.lastIndexOf('_') + 1);
                firstCategoryId = parseInt(firstCategoryId);
            }
        };
        resolve(firstCategoryId);
    })
}


/** Affiche les sous-catégories de postes.
 * @param  {object} data contenu de la réponse à la requête AJAX
 */
function displayWorkSubCategories(data) {
    return new Promise((resolve) => {
        //var firstCategoryId = getFirstCategoryId();
        var divWorkLengthBySubCategoryInputs = document.getElementById("divWorkLengthBySubCategoryInputs");
    
        // Pour chaque sous-catégorie, on crée une div avec des champs "heures" et "minutes"
        for(let i = 0; i < data.length ; i++) {
            // On transforme la casse du nom du poste : 1ère lettre en majuscule, les suivantes en minuscules
            var subCategoryName = data[i].code_poste[0].toUpperCase() + data[i].code_poste.substr(1).toLowerCase();
            var parentCategoryId = data[i].ID_categorie;
            var subCategoryId = data[i].ID;
            var subCategoryCode = data[i].code_poste.toLowerCase();

            // On s'assure qu'il n'y a pas d'espaces ou de slash dans le nom de code de la catégorie
            subCategoryCode = subCategoryCode.replace(/\s+/g, '');
            subCategoryCode = subCategoryCode.replace("/", "_");
    
            // On crée l'élément <div> qui va contenir les champs "heures" et "minutes"
            var newDivSubCategory = document.createElement("div");
            newDivSubCategory.setAttribute("id", "div" + subCategoryName + "_" + subCategoryId + "_" + parentCategoryId);
            newDivSubCategory.setAttribute("class", "row mb-2 justify-content-center subCategory");
    
            // On ajoute l'input, le libellé et les boutons [+] et [-]
            var html = [
                `<label for="${ subCategoryCode }LengthHours" class="col-sm-2 col-form-label">${ data[i].libelle_poste }</label>
                
                <div class="col-3 me-5 me-5">
                    <div class="d-flex flex-row align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-dash-circle-fill me-3" viewBox="0 0 16 16" onclick="decrement('hour', ${ subCategoryCode }LengthHours, ${ subCategoryCode }LengthMinutes)">
                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM4.5 7.5a.5.5 0 0 0 0 1h7a.5.5 0 0 0 0-1h-7z"/>
                        </svg>
    
                        <input type="number" min="0" class="form-control timeInput" value="0" name="workstationLengthHours[${ subCategoryId }]" id="${ subCategoryCode }LengthHours">
                        
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#C63527" class="bi bi-dash-circle-fill ms-3" viewBox="0 0 16 16" onclick="increment('hour', ${ subCategoryCode }LengthHours, ${ subCategoryCode }LengthMinutes)">
                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z"/>
                        </svg>
                    </div>
                </div>
    
                <div class="col-3 me-5">
                    <div class="d-flex flex-row align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-dash-circle-fill me-3" viewBox="0 0 16 16" onclick="decrement('minutes', ${ subCategoryCode }LengthHours, ${ subCategoryCode }LengthMinutes)">
                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM4.5 7.5a.5.5 0 0 0 0 1h7a.5.5 0 0 0 0-1h-7z"/>
                        </svg>
    
                        <input type="number" min="-15" step="15" max="60" class="form-control timeInput" value="0" name="workstationLengthMinutes[${ subCategoryId }]" id="${ subCategoryCode }LengthMinutes" onchange="updateHoursInput(${ subCategoryCode }LengthHours, ${ subCategoryCode }LengthMinutes)">
                        
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#C63527" class="bi bi-dash-circle-fill ms-3" viewBox="0 0 16 16" onclick="increment('minutes', ${ subCategoryCode }LengthHours, ${ subCategoryCode }LengthMinutes)">
                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z"/>
                        </svg>
                    </div>
                </div>`
            ];
    
            // On remplit la div avec le contenu HTML, puis on a insère la div ainsi créée dans le DOM
            newDivSubCategory.innerHTML = html;
            divWorkLengthBySubCategoryInputs.appendChild(newDivSubCategory);
        }
        // On résoud la Promise en renvyant l'id de la catégorie active
        resolve();
    })
}


/** Masque ou affiche les sous-catégories pertinentes selon la catégorie qui a été sélectionnée.
 * @param  {number} categoryId
 */
function hideUnrelatedSubCategories(categoryId) {
    $('.subCategory').each(function() {
        // On récupère le dernier caractère de l'attribut id de la balise (qui contient l'id de la catégorie parente)
        let parentCategoryId = parseFloat($(this).attr('id').substr(-1, 1));
        parentCategoryId !== categoryId ? $(this).attr("hidden", true) : $(this).attr("hidden", false);
    });
}

/**
 * Ajoute un attribut "readonly" sur les inputs de formulaire.
 */
function addReadOnlyAttributes() {
    $('.form-control').each(function() {
        //console.log($(this));
        $(this).attr("readonly", true);
    });
    $('svg').each(function() {
        $(this).attr("onclick", "");
    })
}

/**
 * Masque les boutons de contrôle de formulaire.
 */
function hideFormButtons() {
    document.getElementById("formButtons").style = "display:none";
}