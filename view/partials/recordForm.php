
<div class="container-fluid">

<form action="<?=($_POST['recordId'] == 0 ? 'index.php?action=addNewRecord' : 'index.php?action=updateRecord')?>" method="POST">

	<p class="text-end"><a href="javascript:void(0);" onclick="javascript:introJs().start();" style="color:black;"><i class="bi bi-info-circle"></i> Besoin d'aide ?</a></p>
    
    <!-- Sélection du chantier et de la date du relevé -->
    <div id="divWorksiteInput" class="row mt-5 mb-3 justify-content-md-center">

        <div class="col mb-3" style="flex-grow: 2;" data-step="1" data-intro="Sélectionnez une affaire pour laquelle vous souhaitez relever des heures.">
            <span class="input-group-text" id="worksite_selector">Affaire</span>
            <select class="form-select" name="worksiteId" id="selectWorksite" aria-label="Sélectionnez une affaire" aria-describedby="worksite_selector" required>
                <option value="" disabled selected>Sélectionnez une affaire</option>
            </select>
        </div>

        <?php 
        if($_SESSION['lengthMgmt'] == 1 || $_SESSION['lengthByCategoryMgmt'] == 1){ ?>
            <div class="col flex-shrink-1 mb-3" data-step="2" data-intro="Indiquez la date de réalisation des heures.">
                <span class="input-group-text" id="date_selector">Date</span>
                <input type="date" name="recordDate" id="recordDate" class="form-control" aria-label="Sélectionnez une date" aria-describedby="date_selector" required/>
            </div>
        <?php } ?>

    </div>


    <?php 
    if($_SESSION['dateTimeMgmt'] == 1){ ?>
        <!-- Champs pour un relevé avec date et heure de début / date et heure de fin -->
        <div id="divWorkDateTimeInput" class="row mb-3 justify-content-md-center">

            <p class="h6 text-center mb-3">Temps de travail</p>

            <div class="col mb-3">
                <span class="input-group-text" id="datetime_start_selector">Début</span>
                <input type="datetime-local" name="datetimeStart" id="datetime_start" class="form-control" aria-label="Sélectionnez une date et une heure de début" aria-describedby="datetime_start_selector" required/>
            </div>

            <div class="col mb-3">
                <span class="input-group-text" id="datetime_end_selector">Fin</span>
                <input type="datetime-local" name="datetimeEnd" id="datetime_end" class="form-control" aria-label="Sélectionnez une date et une heure de fin" aria-describedby="datetime_end_selector" required/>
            </div>

        </div>
    <?php } ?>
    

    <?php 
    if($_SESSION['lengthMgmt'] == 1) { ?>
        <!-- Champs pour un relevé avec seulement une durée -->
        <div id="divWorkLengthInput" class="row mb-3 justify-content-md-center">

            <p class="h6 mb-3">Temps de travail</p>

            <div class="col mb-3" data-step="3" data-intro="Indiquez le nombre d'heures de travail réalisées.">
                <span class="input-group-text" id="work_hours_indicator">Heures</span>
                <input type="number" min="-15" name="workLengthHours" id="workLengthHours" value="0" class="form-control" aria-label="Indiquez le nombre d'heures de trajet" aria-describedby="work_hours_indicator" required/>
            </div>

            <div class="col mb-3" data-step="4" data-intro="Au besoin, indiquez le nombre de minutes (palier de 15 minutes).">
                <span class="input-group-text" id="work_minutes_indicator">Minutes</span>
                <input type="number" min="-15" step="15" max="60" name="workLengthMinutes" value="0" id="workLengthMinutes" onchange="incrementHour(workLengthHours, workLengthMinutes)" class="form-control" aria-label="Indiquez le nombre de minutes de trajet" aria-describedby="work_minutes_indicator" required/>
            </div>

        </div>
    <?php } ?>

    <?php
    if($_SESSION['lengthByCategoryMgmt'] == 1 ) { ?>
        <div >
            <span class="input-group-text" id="work_hours_indicator">Temps de travail</span>
            
            <fieldset class="mb-4">
                <ul class="nav nav-pills justify-content-center mt-4">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Fabrication</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Pose</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Divers</a>
                    </li>
                </ul>

                <div class="mt-5 mb-5 ">

                    <div class="row mb-2 justify-content-center">
                        <label for="test1" class="col-sm-2 col-form-label">Fabrication</label>

                        <div class="col-3">
                            <div class="d-flex flex-row align-items-center">
                                <i class="bi bi-dash-circle-fill me-3"></i>
                                <input type="text" class="form-control" placeholder="Heures" aria-label="First name" id="test1">
                                <i class="bi bi-plus-circle-fill ms-3" style="color: #C63527"></i>
                            </div>
                        </div>

                        <div class="col-3">
                            <div class="d-flex flex-row align-items-center">
                                <i class="bi bi-dash-circle-fill me-3"></i>
                                <input type="text" class="form-control" placeholder="Minutes" aria-label="Last name">
                                <i class="bi bi-plus-circle-fill ms-3" style="color: #C63527"></i>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2 justify-content-center">
                        <label for="test2" class="col-sm-2 col-form-label">Peinture atelier</label>
                        <div class="col-3">
                            <div class="d-flex flex-row align-items-center">
                                <i class="bi bi-dash-circle-fill me-3"></i>
                                <input type="text" class="form-control" placeholder="Heures" aria-label="First name" id="test2">
                                <i class="bi bi-plus-circle-fill ms-3" style="color: #C63527"></i>
                            </div>
                        </div>

                        <div class="col-3">
                            <div class="d-flex flex-row align-items-center">
                                <i class="bi bi-dash-circle-fill me-3"></i>
                                <input type="text" class="form-control" placeholder="Minutes" aria-label="Last name">
                                <i class="bi bi-plus-circle-fill ms-3" style="color: #C63527"></i>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2 justify-content-center">
                        <label for="test3" class="col-sm-2 col-form-label">Livraison</label>
                        <div class="col-3">
                            <div class="d-flex flex-row align-items-center">
                                <i class="bi bi-dash-circle-fill me-3"></i>
                                <input type="text" class="form-control" placeholder="Heures" aria-label="First name" id="test3">
                                <i class="bi bi-plus-circle-fill ms-3" style="color: #C63527"></i>
                            </div>
                        </div>

                        <div class="col-3">
                            <div class="d-flex flex-row align-items-center">
                                <i class="bi bi-dash-circle-fill me-3"></i>
                                <input type="text" class="form-control" placeholder="Minutes" aria-label="Last name">
                                <i class="bi bi-plus-circle-fill ms-3" style="color: #C63527"></i>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2 justify-content-center">
                        <label for="test4" class="col-sm-2 col-form-label">Modification</label>
                        <div class="col-3">
                            <div class="d-flex flex-row align-items-center">
                                <i class="bi bi-dash-circle-fill me-3"></i>
                                <input type="text" class="form-control" placeholder="Heures" aria-label="First name" id="test4">
                                <i class="bi bi-plus-circle-fill ms-3" style="color: #C63527"></i>
                            </div>
                        </div>

                        <div class="col-3">
                            <div class="d-flex flex-row align-items-center">
                                <i class="bi bi-dash-circle-fill me-3"></i>
                                <input type="text" class="form-control" placeholder="Minutes" aria-label="Last name">
                                <i class="bi bi-plus-circle-fill ms-3" style="color: #C63527"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3 justify-content-center"  data-step="5" data-intro="Le total est calculé automatiquement !">

                    <label for="totalLengthHours" class="col-sm-2 col-form-label">Total</label>
                
                    <div class="col-3 mb-3">
                        <!-- <span class="input-group-text" id="total_hours_indicator">Heures</span> -->
                        <input type="number" min="-15" name="workLengthHours" id="totalLengthHours" value="0" class="form-control" aria-label="Indiquez le nombre d'heures de trajet" aria-describedby="total_hours_indicator" readonly/>
                    </div>
                    <div class="col-3 mb-3">
                        <!-- <span class="input-group-text" id="total_minutes_indicator">Minutes</span> -->
                        <input type="number" min="-15" step="15" max="60" name="workLengthMinutes" value="0" id="totalLengthMinutes" class="form-control" aria-label="Total des heures de la journée" aria-describedby="total_minutes_indicator" readonly/>
                    </div>
                        
                </div>

            </fieldset>
        </div>
        

        <!-- <div id="divWorkLengthByCategoryInputs" class="row mb-3 justify-content-md-center" data-step="3" data-intro="Indiquez le nombre d'heures de travail réalisées pour chaque poste.">
            <p class="h6 mb-3">Temps de travail</p> -->
            <!-- Insertion des catégories de postes -->
        <!-- </div> -->

    <?php } ?>

    <?php 
    if($_SESSION['breakMgmt'] == 1){ ?>
        <!-- Champs pour un relevé avec gestion du temps de pause -->
        <div id="divBreakTime" class="row mb-3 justify-content-md-center">

            <p class="h6 mb-3">Temps de pause</p>

                <div class="col mb-3">
                    <span class="input-group-text" id="break_hours_indicator">Heures</span>
                    <input type="number" min="-15" name="breakLengthHours" id="breakLengthHours" value="0" class="form-control" aria-label="Indiquez le nombre d'heures de trajet" aria-describedby="trip_hours_indicator" required/>
                </div>

                <div class="col mb-3">
                    <span class="input-group-text" id="break_minutes_indicator">Minutes</span>
                    <input type="number" min="-15" step="15" max="60" name="breakLengthMinutes" id="breakLengthMinutes" value="0" onchange="incrementHour(breakLengthHours, breakLengthMinutes)" class="form-control" aria-label="Indiquez le nombre de minutes de trajet" aria-describedby="trip_minutes_indicator" required/>
                </div>

        </div>
    <?php } ?>

    <?php 
    if($_SESSION['tripMgmt'] == 1) { ?>
        <!-- Champs pour un relevé avec gestion du temps de trajet -->
        <div id="divTripTimeInput"  data-step="6" data-intro="Au besoin, indiquez le nombre d'heures de trajet.">

            <span class="input-group-text">Temps de trajet</span>
            
            <fieldset class="mb-4">

                <div class="row mb-4 justify-content-center mt-4">
                    <div class="col-3">
                        <div class="d-flex flex-row align-items-center">
                            <i class="bi bi-dash-circle-fill me-3"></i>
                            <input type="text" class="form-control" placeholder="Heures" aria-label="First name" id="test3">
                            <i class="bi bi-plus-circle-fill ms-3" style="color: #C63527"></i>
                        </div>
                    </div>

                    <div class="col-3">
                        <div class="d-flex flex-row align-items-center">
                            <i class="bi bi-dash-circle-fill me-3"></i>
                            <input type="text" class="form-control" placeholder="Minutes" aria-label="Last name">
                            <i class="bi bi-plus-circle-fill ms-3" style="color: #C63527"></i>
                        </div>
                    </div>
                </div>
            </fieldset>
        </div>
    <?php } ?>

    <!-- Champs commentaire -->
    <div id="divCommentFieldInput">

        <div class="col mb-3"  data-step="7" data-intro="Une précision à apporter ? Laissez un commentaire !">
            <span class="input-group-text" id="comment_section">Commentaire (facultatif)</span>
            <textarea autocapitalize="sentences" maxlength="255" name="comment" id="recordComment" class="form-control" aria-label="Commentaire" aria-describedby="comment_section"></textarea>
            <small class="form-text text-muted">255 caractères maximum</small>
        </div>

    </div>

    <div class="row mb-3 justify-content-md-center">
        
        <div class="col mb-5 text-center">
            <input type="hidden" value="<?= isset($_POST['recordId']) ? ($_POST['recordId']) : "" ;?>" name="recordId"/>
            <input type="hidden" value="<?= $_SESSION['csrfToken'] ;?>" name="csrfToken"/>
            <input type="reset" value="Annuler" onclick="closeModal()" class="btn btn-light"/>
            <input type="submit" value="Valider" class="btn btn-dark"  data-step="7" data-intro="C'est votre dernier mot ?"/>
        </div>
        
    </div>
</form>

</div>

<script src="public/js/ajaxRequests.js"></script>

<script>
getOptionsData('add', 'worksites', <?= isset($_POST['userId']) ? ($_POST['userId']) : "" ;?>);
getRecordData(<?= isset($_POST['recordId']) ? $_POST['recordId'] : "";?>);
getWorkCategories();

function incrementHour(hoursInputId, minutesInputId){
    let minutesInput = document.getElementById(minutesInputId.id);
    let hourInput = document.getElementById(hoursInputId.id);

    if(minutesInput.value === '60'){
        hourInput.value ++;
        minutesInput.value = '0';
    }

    if(hourInput.value !== '0'){
        if(minutesInput.value === '-15'){
            hourInput.value --;
            minutesInput.value = '45';
        }
    }
    if(hourInput.value === '0' && minutesInput.value === '-15'){
        hourInput.value = '0';
        minutesInput.value = '0';
    }
}
</script>