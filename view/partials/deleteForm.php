<div class="container">
    <form action="index.php?action=deleteRecord" method="POST">
        
        <div class="row mt-5 mb-3 justify-content-md-center">

            <div class="col-sm-8 mb-3">
                <p class="lead text-center"><strong>Souhaitez-vous vraiment supprimer ce relevé ?</strong></p>
            </div>

        </div>

        <div class="row mb-3 justify-content-md-center">

            <div class="col-sm-8 mb-3">
                <span class="input-group-text" id="comment_section">Commentaire</span>
                <textarea autocapitalize="sentences" maxlength="255" name="comment" id="comment" class="form-control" aria-label="Commentaire" aria-describedby="comment_section" <?= $_SESSION['userGroup'] === $_SESSION['GroupAdmin'] || $_SESSION['userGroup'] === $_SESSION['GroupManager'] ? "required" : "" ;?>></textarea>
                <small class="form-text text-muted">255 caractères maximum</small>
            </div>

        </div>

        <div class="row mb-3 justify-content-md-center">
            
            <div class="col-sm-8 mb-5 text-center">
                <input type="hidden" value="<?= isset($_POST['recordId']) ? ($_POST['recordId']) : "" ?>" name="recordId"/>
                <input type="hidden" value="<?= $_SESSION['csrfToken'] ;?>" name="csrfToken"/>
                <input type="reset" value="Annuler" onclick="closeModal()" class="btn btn-light"/>
                <input type="submit" value="Confirmer" class="btn btn-dark"/>
            </div>
            
        </div>
    </form>

</div>