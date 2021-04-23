<?php 

$title = "Export";
$heading = "Exporter des relevés";

?>

<?php ob_start(); ?>

    <form action="index.php?action=exportRecords&typeOfRecords=export" method="POST">

        <div class="row mt-5 mb-3 justify-content-md-center">   

            <div class="col-sm-6">

                <p class="fs-5 text fw-light mb-3">Quel(s) type(s) de relevés souhaitez-vous exporter ?</p>

                <div class="input-group mb-2">
                    <div class="input-group-text">
                        <input class="form-check-input mt-0" type="radio" name="scope" value="all" aria-label="Bouton radio permettant de sélectionner tous les relevés" checked />
                    </div>
                    <input type="text" class="form-control" aria-label="Sélectionner tous les relevés" value="Tous les relevés (par défaut)" readonly/>
                </div>

                <div class="input-group mb-2">
                    <div class="input-group-text">
                        <input class="form-check-input mt-0" type="radio" name="scope" value="valid" aria-label="Bouton radio permettant de sélectionner uniquement les relevés validés" />
                    </div>
                    <input type="text" class="form-control" aria-label="Sélectionner tous les relevés" value="Uniquement les relevés &#34validés&#34" readonly/>
                </div>

                <div class="input-group mb-2">
                    <div class="input-group-text">
                        <input class="form-check-input mt-0" type="radio" name="scope" value="unchecked" aria-label="Bouton radio permettant de sélectionner uniquement les relevés en attente" />
                    </div>
                    <input type="text" class="form-control" aria-label="Sélectionner tous les relevés" value="Uniquement les relevés &#34en attente&#34" readonly/>
                </div>

                <div class="input-group mb-2">
                    <div class="input-group-text">
                        <input class="form-check-input mt-0" type="radio" name="scope" value="deleted" aria-label="Bouton radio permettant de sélectionner uniquement les relevés supprimés" />
                    </div>
                    <input type="text" class="form-control" aria-label="Sélectionner tous les relevés" value="Uniquement les relevés supprimés" />
                </div>

            </div>
        
        </div>

        <div class="row mt-5 mb-3 justify-content-md-center">

            <div class="col-sm-6">

                <div class="accordion" id="exportFormAccordion">
                    <div class="accordion-item">

                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseOne">
                            Souhaitez-vous sélectionner d'autres options ? (facultatif)
                            </button>
                        </h2>

                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#exportFormAccordion">

                            <div class="accordion-body">
                                <label for="periodStart_selector">Sélectionnez une période :</label>
                                <div class="input-group mt-4 mb-3">
                                    <span class="input-group-text" id="periodStart_selector">Du</span>
                                    <input type="date" name="periodStart" id="periodStart" class="form-control" aria-label="Sélectionnez une date de début" aria-describedby="periodStart_selector"/>
                                </div>
                                
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="periodEnd_selector">Au</span>
                                    <input type="date" name="periodEnd" id="periodEnd" class="form-control" aria-label="Sélectionnez une date de fin" aria-describedby="periodEnd_selector"/>
                                </div>

                                <label for="selectManager" class="mt-4 mb-2">Exporter les relevés de tous les salariés liés à un manager </label>
                                <select name="manager" id="selectManager" class="form-select mb-3" aria-label="Default select example">
                                    <option value ="" selected>Sélectionnez un manager</option>
                                </select>

                                <label for="selectUser" class="mt-4 mb-2">Exporter les relevés d'un salarié en particulier </label>
                                <select name="user" id="selectUser" class="form-select mb-3" aria-label="Default select example">
                                    <option value ="" selected>Sélectionnez un salarié</option>
                                </select>
                            </div>

                        </div>
                    </div>

                </div>

            </div> 

        </div>

        <div class="row mb-3 mt-5 justify-content-md-center">
            
            <div class="col-sm-8 mb-5 text-center">
                <input type="reset" value="Annuler" class="btn btn-light"/>
                <input type="submit" value="Exporter" class="btn btn-dark"/>
            </div>
            
        </div>

    </form>

<?php $content = ob_get_clean(); ?>

<?php ob_start() ; ?>
    <script>
        window.onload = function(){
            getOptionsData('export', 'managers');
            getOptionsData('export', 'users');
    }
    </script>
<?php $script = ob_get_clean(); ?>

<?php require 'template.php'; ?>