/**  Fonction qui permet d'ajouter un bouton "édition" qui déclenche l'ouverture d'une fenêtre modale
 * @param  {object} newEdit correspond à la cellule de la ligne du tableau dans laquelle ajouter le bouton
 * @param  {object} data contenu de la réponse à la requête AJAX
 * @param  {number} counter index du tour de boucle actuel qui permet de créer des id uniques sur les balises HTML créées
 */
function insertEditRecordButton(newEdit, data, counter) {
    var recordId = parseInt(data[counter].ID);
    var userId = parseInt(data[counter].id_login);

    var newEditText = `<button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#formModal" onclick="displayRecordForm(${ recordId }, ${ userId })" data-bs-whatever="Editer"><i class="far fa-edit"></i></button>`;
    newEdit.innerHTML += newEditText;
}


/**
 * @param  {object} newDelete correspond à la cellule de la ligne du tableau dans laquelle ajouter le bouton
 * @param  {object} data contenu de la réponse à la requête AJAX
 * @param  {number} counter index du tour de boucle actuel qui permet de créer des id uniques sur les balises HTML créées
 */
function insertDeleteRecordButton(newDelete, data, counter) {
    var recordId = parseInt(data[counter].ID);

    var newDeleteText = `<button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#formModal" onclick="displayDeleteConfirmation(${ recordId })"><i class="far fa-trash-alt"></i></button>`;
    newDelete.innerHTML += newDeleteText;
}


/**
 * @param  {object} newIsValid correspond à la cellule de la ligne du tableau dans laquelle ajouter le bouton
 * @param  {object} data contenu de la réponse à la requête AJAX
 * @param  {number} counter index du tour de boucle actuel qui permet de créer des id uniques sur les balises HTML créées
 */
function insertSwitchButton(newIsValid, data, counter) {
    var recordId = parseInt(data[counter].ID);

    var html = [
        '<div class="form-check form-switch">',
            `<input class="form-check-input" type="checkbox" name="checkList[${counter}]" id="recordValidationCheck${counter}" value="${ recordId }"/>`,
            `<label class="form-check-label" for="recordValidationCheck${counter}">Sélectionner</label>`,
        '</div>'
    ].join('');
    newIsValid.innerHTML += html;
}


/* Fonction qui permet d'insérer des boutons de contrôle de formulaire 
*/
function insertFormControlButtons() {
    var formControlButtons = [
        '<div class="row mb-3 justify-content-md-center">',      
            '<div class="col-lg mb-5 text-end">',
                '<input type="reset" value="Annuler" class="btn btn-light me-3"/>',
                '<input type="submit" value="Valider" class="btn btn-dark"/>',
            '</div>',
        '</div>'
    ].join('');

    $(formControlButtons).insertAfter("#records_log");
}