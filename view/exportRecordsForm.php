<?php session_start(); ?>

<!DOCTYPE html>
<html lang="fr">

    <?php include('partials/head.php'); ?>

    <body>
    <?php include('partials/navbar.php'); ?>

        <div class="container">
            <h2 class="display-6 mt-5 mb-5 text-center">Exporter des relevés</h2>

                <form action="index.php?action=exportRecords&typeOfRecords=export&scope=all" method="POST">

                    <div class="row mt-5 mb-3 justify-content-md-center">   

                        <div class="col-sm-6">

                            <p class="fs-5 text fw-light mb-3">Quel(s) type(s) de relevés souhaitez-vous exporter ?</p>

                            <div class="input-group mb-1">
                                <div class="input-group-text">
                                    <input class="form-check-input mt-0" type="radio" name="scope" value="all" aria-label="Bouton radio permettant de sélectionner tous les relevés" checked />
                                </div>
                                <input type="text" class="form-control" aria-label="Sélectionner tous les relevés" value="Tous les relevés (par défaut)"/>
                            </div>

                            <div class="input-group mb-1">
                                <div class="input-group-text">
                                    <input class="form-check-input mt-0" type="radio" name="scope" value="valid" aria-label="Bouton radio permettant de sélectionner tous les relevés" />
                                </div>
                                <input type="text" class="form-control" aria-label="Sélectionner tous les relevés" value="Uniquement les relevés &#34validés&#34"/>
                            </div>

                            <div class="input-group mb-1">
                                <div class="input-group-text">
                                    <input class="form-check-input mt-0" type="radio" name="scope" value="unchecked" aria-label="Bouton radio permettant de sélectionner tous les relevés" />
                                </div>
                                <input type="text" class="form-control" aria-label="Sélectionner tous les relevés" value="Uniquement les relevés &#34en attente&#34"/>
                            </div>

                            <div class="input-group mb-1">
                                <div class="input-group-text">
                                    <input class="form-check-input mt-0" type="radio" name="scope" value="deleted" aria-label="Bouton radio permettant de sélectionner tous les relevés" />
                                </div>
                                <input type="text" class="form-control" aria-label="Sélectionner tous les relevés" value="Uniquement les relevés supprimés"/>
                            </div>

                        </div>
                    
                    </div>


                    <div class="row mt-5 mb-3 justify-content-md-center">   

                        <div class="col-sm-6">

                            <p class="fs-5 text fw-light mb-3">Souhaitez-vous sélectionner une période ?</p>

                            <div class="accordion" id="accordionExample">
                                <div class="accordion-item">

                                    <h2 class="accordion-header" id="headingOne">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                            Cliquez ici pour sélectionner une période.
                                        </button>
                                    </h2>

                                    <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">

                                        <div class="accordion-body">
                                            <div class="input-group mb-3">
                                                <span class="input-group-text" id="period_start_selector">Du</span>
                                                <input type="date" name="period_start" id="period_start" class="form-control" aria-label="Sélectionnez une date de début" aria-describedby="period_start_selector" required/>
                                            </div>

                                            <div class="input-group mb-3">
                                                    <span class="input-group-text" id="period_end_selector">Au</span>
                                                    <input type="date" name="period_end" id="period_end" class="form-control" aria-label="Sélectionnez une date de fin" aria-describedby="period_end_selector" required/>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>

                        </div>
                    
                    </div>
        
                    <div class="row mt-5 mb-3 justify-content-md-center">

                        <div class="col-sm-6">

                            <p class="fs-5 text fw-light mb-3">Souhaitez-vous sélectionner d'autres options ?</p>

                            <div class="accordion" id="accordionExample">
                                <div class="accordion-item">

                                    <h2 class="accordion-header" id="headingOne">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseOne">
                                            Cliquez ici pour sélectionner un manager, un utilisateur et/ou un chantier.
                                        </button>
                                    </h2>

                                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">

                                        <div class="accordion-body">
                                            <select class="form-select mb-3" aria-label="Default select example">
                                                <option selected>Open this select menu</option>
                                                <option value="1">One</option>
                                                <option value="2">Two</option>
                                                <option value="3">Three</option>
                                            </select>

                                            <select class="form-select mb-3" aria-label="Default select example">
                                                <option selected>Open this select menu</option>
                                                <option value="1">One</option>
                                                <option value="2">Two</option>
                                                <option value="3">Three</option>
                                            </select>

                                            <select class="form-select mb-3" aria-label="Default select example">
                                                <option selected>Open this select menu</option>
                                                <option value="1">One</option>
                                                <option value="2">Two</option>
                                                <option value="3">Three</option>
                                            </select>
                                        </div>

                                    </div>
                                </div>

                            </div>

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

        <?php include('partials/footer.php'); ?>
    </body>
</html>