
<div class="container-fluid">

    <form action="<?=($_POST['idRecord'] == 0 ? 'index.php?action=addNewRecord' : 'index.php?action=updateRecord')?>" method="POST">
    
        <div class="row mt-5 mb-3 justify-content-md-center">

            <div class="col-sm-4 mb-3">
                <span class="input-group-text" id="datetime_start_selector">Date et heure de début</span>
                <input type="datetime-local" name="datetime_start" id="datetime_start" class="form-control" aria-label="Sélectionnez une date et une heure de début" aria-describedby="datetime_start_selector" />
            </div>

            <div class="col-sm-4 mb-3">
                <span class="input-group-text" id="datetime_end_selector">Date et heure de fin</span>
                <input type="datetime-local" name="datetime_end" id="datetime_end" class="form-control" aria-label="Sélectionnez une date et une heure de fin" aria-describedby="datetime_end_selector" />
            </div>

        </div>

        <div class="row mb-3 justify-content-md-center">

            <div class="col-sm-8 mb-3">
                <span class="input-group-text" id="comment_section">Commentaire</span>
                <textarea autocapitalize="sentences" maxlength="255" name="comment" id="comment" class="form-control" aria-label="Commentaire" aria-describedby="comment_section"></textarea>
                <small class="form-text text-muted">255 caractères maximum</small>
            </div>

        </div>

        <div class="row mb-3 justify-content-md-center">
            
            <div class="col-sm-8 mb-5 text-center">
                <input type="hidden" value="<?=($_POST['idRecord'])?>" name="record_id"/>
                <input type="button" value="Annuler" class="btn btn-light"/>
                <input type="submit" value="Valider" class="btn btn-dark"/>
            </div>
            
        </div>
    </form>

</div>

<script src="public/js/update_records_log.js"></script>
<script>
    $(function() {
        getRecordData(<?=($_POST['idRecord']);?>);
    });
</script>