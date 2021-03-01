<?php session_start(); ?>

<!DOCTYPE html>
<html lang="fr">

    <?php include('partials/head.php'); ?>

    <body>
    <?php include('partials/navbar.php'); ?>

        <div class="bd-cheatsheet container-fluid bg-body">
            <h2 class="display-6 mt-5 mb-5 text-center">Nouveau relevé</h2>

            <form action="index.php?action=addNewRecord" method="POST">

                <div class="row mb-3 justify-content-md-center">

                    <div class="col-lg-3 mb-3">
                        <span class="input-group-text" id="datetime_start_selector">Date et heure de début</span>
                        <input type="datetime-local" name="datetime_start" id="datetime_start" class="form-control" aria-label="Sélectionnez une date et une heure de début" aria-describedby="datetime_start_selector" />
                    </div>

                    <div class="col-lg-3 mb-3">
                        <span class="input-group-text" id="datetime_end_selector">Date et heure de fin</span>
                        <input type="datetime-local" name="datetime_end" id="datetime_end" class="form-control" aria-label="Sélectionnez une date et une heure de fin" aria-describedby="datetime_end_selector" />
                    </div>

                </div>

                <div class="row mb-3 justify-content-md-center">

                    <div class="col-lg-6 mb-3">
                        <span class="input-group-text" id="comment_section">Commentaire</span>
                        <textarea autocapitalize="sentences" maxlength="255" name="comment" id="comment" class="form-control" aria-label="Commentaire" aria-describedby="comment_section"></textarea>
                        <small class="form-text text-muted">255 caractères maximum</small>
                    </div>

                </div>

                <div class="row mb-3 justify-content-md-center">
                    
                    <div class="col-lg-6 mb-5 text-center">
                        <input type="hidden" value="<?php echo $_SESSION['id']?>" name="user_id"/>
                        <input type="button" value="Annuler" class="btn btn-light"/>
                        <input type="submit" value="Valider" class="btn btn-dark"/>
                    </div>
                    
                </div>
            </form>

        </div>

        <?php include('partials/footer.php'); ?>
    </body>
</html>