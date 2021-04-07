
<div class="container-fluid">

    <form action="<?=($_POST['recordId'] == 0 ? 'index.php?action=addNewRecord' : 'index.php?action=updateRecord')?>" method="POST">
        
        <div class="row mt-5 mb-3 justify-content-md-center">

            <div class="col-sm-8 mb-3">
                <span class="input-group-text" id="worksite_selector">Chantier</span>
                <select class="form-select" name="worksiteId" id="selectWorksite" aria-label="Sélectionnez un chantier" aria-describedby="worksite_selector" required>
                    <option value="">Sélectionnez un chantier</option>
                </select>
            </div>

        </div>

        <div class="row mb-3 justify-content-md-center">

            <div class="col-sm-4 mb-3">
                <span class="input-group-text" id="datetime_start_selector">Début</span>
                <input type="datetime-local" name="datetimeStart" id="datetime_start" class="form-control" aria-label="Sélectionnez une date et une heure de début" aria-describedby="datetime_start_selector" required/>
            </div>

            <div class="col-sm-4 mb-3">
                <span class="input-group-text" id="datetime_end_selector">Fin</span>
                <input type="datetime-local" name="datetimeEnd" id="datetime_end" class="form-control" aria-label="Sélectionnez une date et une heure de fin" aria-describedby="datetime_end_selector" required/>
            </div>

        </div>

        <div class="row mb-3 justify-content-md-center">

            <div class="col-sm-8 mb-3">
                <span class="input-group-text" id="comment_section">Commentaire (facultatif)</span>
                <textarea autocapitalize="sentences" maxlength="255" name="comment" id="comment" class="form-control" aria-label="Commentaire" aria-describedby="comment_section"></textarea>
                <small class="form-text text-muted">255 caractères maximum</small>
            </div>

        </div>

        <div class="row mb-3 justify-content-md-center">
            
            <div class="col-sm-8 mb-5 text-center">
                <input type="hidden" value="<?= isset($_POST['recordId']) ? ($_POST['recordId']) : "" ;?>" name="recordId"/>
                <input type="reset" value="Annuler" onclick="closeModal()" class="btn btn-light"/>
                <input type="submit" value="Valider" class="btn btn-dark"/>
            </div>
            
        </div>
    </form>

</div>

<script src="public/js/ajaxRequests.js"></script>

<script>
    getOptionsData('add', 'worksites', <?= isset($_POST['userId']) ? ($_POST['userId']) : "" ;?>);
    getRecordData(<?= isset($_POST['recordId']) ? $_POST['recordId'] : "";?>);
</script>
