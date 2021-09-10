/**  Fonction qui permet d'ajouter un bouton "édition" qui déclenche l'ouverture d'une fenêtre modale
 * @param  {object} newEdit correspond à la cellule de la ligne du tableau dans laquelle ajouter le bouton
 * @param  {object} records contenu de la réponse à la requête AJAX
 * @param  {number} counter index du tour de boucle actuel qui permet de créer des id uniques sur les balises HTML créées
 */
function insertEditRecordButton(newEdit, records, counter) {
    var recordId = parseInt(records[counter].ID);
    var userUUID = parseInt(records[counter].id_login);

    var newEditText = [
        `<div class="text-center">
            <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#formModal" onclick="editRecord(${ recordId }, ${ userUUID })" data-bs-whatever="Editer">
                <i class="far fa-edit"></i>
            </button>
        </div>`
    ];
    newEdit.innerHTML += newEditText;
}


/**
 * @param  {object} newDelete correspond à la cellule de la ligne du tableau dans laquelle ajouter le bouton
 * @param  {object} records contenu de la réponse à la requête AJAX
 * @param  {number} counter index du tour de boucle actuel qui permet de créer des id uniques sur les balises HTML créées
 */
function insertDeleteRecordButton(newDelete, records, counter) {
    var recordId = parseInt(records[counter].ID);

    var newDeleteText = [
        `<div class="text-center">
            <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#formModal" onclick="displayDeleteConfirmation(${ recordId })">
                <i class="far fa-trash-alt"></i>
            </button>
        </div>`
    ];
    newDelete.innerHTML += newDeleteText;
}


/**
 * @param  {object} newIsValid correspond à la cellule de la ligne du tableau dans laquelle ajouter le bouton
 * @param  {object} records contenu de la réponse à la requête AJAX
 * @param  {number} counter index du tour de boucle actuel qui permet de créer des id uniques sur les balises HTML créées
 */
function insertSwitchButton(newIsValid, records, counter) {
    var recordId = parseInt(records[counter].ID);

    var html = [
        '<div class="form-check form-switch">',
            `<input class="form-check-input" type="checkbox" name="checkList[${counter}]" id="recordValidationCheck${counter}" value="${ recordId }"/>`,
            `<label class="form-check-label" for="recordValidationCheck${counter}">Sélectionner</label>`,
        '</div>'
    ].join('');
    newIsValid.innerHTML += html;
}

function insertViewButton(newView, records, counter) {
    var recordId = parseInt(records[counter].ID);
    var userUUID = parseInt(records[counter].id_login);

    var html = 
    `<div class="text-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16" data-bs-toggle="modal" data-bs-target="#formModal" onclick="viewRecord(${ recordId }, ${ userUUID })" data-bs-whatever="Visualiser">
            <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
            <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
        </svg>
    </div>`

    newView.innerHTML += html;
}