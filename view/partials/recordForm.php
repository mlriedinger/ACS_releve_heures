
<div class="container-fluid">

<form action="<?=($_POST['recordId'] == 0 ? 'index.php?action=addNewRecord' : 'index.php?action=updateRecord')?>" method="POST">

	<p class="text-end"><a href="javascript:void(0);" onclick="javascript:introJs().start();" style="color:black;"><i class="bi bi-info-circle"></i> Besoin d'aide ?</a></p>
    
    <!-- Sélection du chantier et de la date du relevé -->
    <div id="divWorksiteInput" class="row mt-5 mb-3 d-flex justify-content-md-center">

        <div class="col mb-3" style="flex-grow: 2;" data-step="1" data-intro="Sélectionnez un projet pour lequel vous souhaitez relever des heures.">
            <span class="input-group-text" id="worksite_selector">Projet</span>
            <select class="form-select" name="worksiteId" id="selectWorksite" aria-label="Sélectionnez un projet" aria-describedby="worksite_selector" required>
                <option value="" disabled selected>Sélectionnez un projet</option>
            </select>
        </div>

        <?php 
        if($_SESSION['lengthMgmt'] == 1){ ?>
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

            <p class="h6 text-center mb-3">Temps de travail</p>

            <div class="col mb-3" data-step="3" data-intro="Indiquez le nombre d'heures de travail réalisées.">
                <span class="input-group-text" id="work_hours_indicator">Heures</span>
                <input type="number" min="-15" name="workLengthHours" id="workLengthHours" value="0" class="form-control" aria-label="Indiquez le nombre d'heures de trajet" aria-describedby="work_hours_indicator" required/>
            </div>

            <div class="col mb-3" data-step="4" data-intro="Au besoin, indiquez le nombre de minutes (palier de 15 minutes).">
                <span class="input-group-text" id="work_minutes_indicator">Minutes</span>
                <input type="number" min="-15" step="15" max="60" name="workLengthMinutes" value="0" id="workLengthMinutes" onchange="updateHoursInput(workLengthHours, workLengthMinutes)" class="form-control" aria-label="Indiquez le nombre de minutes de trajet" aria-describedby="work_minutes_indicator" required/>
            </div>

        </div>
    <?php } ?>

    <?php 
    if($_SESSION['breakMgmt'] == 1){ ?>
        <!-- Champs pour un relevé avec gestion du temps de pause -->
        <div id="divBreakTime" class="row mb-3 justify-content-md-center">

            <p class="h6 text-center mb-3">Temps de pause</p>

                <div class="col mb-3">
                    <span class="input-group-text" id="break_hours_indicator">Heures</span>
                    <input type="number" min="-15" name="breakLengthHours" id="breakLengthHours" value="0" class="form-control" aria-label="Indiquez le nombre d'heures de trajet" aria-describedby="trip_hours_indicator" required/>
                </div>

                <div class="col mb-3">
                    <span class="input-group-text" id="break_minutes_indicator">Minutes</span>
                    <input type="number" min="-15" step="15" max="60" name="breakLengthMinutes" id="breakLengthMinutes" value="0" onchange="updateHoursInput(breakLengthHours, breakLengthMinutes)" class="form-control" aria-label="Indiquez le nombre de minutes de trajet" aria-describedby="trip_minutes_indicator" required/>
                </div>

        </div>
    <?php } ?>

    <?php 
    if($_SESSION['tripMgmt'] == 1) { ?>
        <!-- Champs pour un relevé avec gestion du temps de trajet -->
        <div id="divTripTimeInput" class="row mb-3 justify-content-md-center" <?= $_SESSION['tripMgmt'] == 0 ? "hidden" : ""; ?> data-step="5" data-intro="De la même manière, indiquez le nombre d'heures de trajet.">

            <p class="h6 text-center mb-3">Temps de trajet</p>

            <div class="col mb-3">
                <span class="input-group-text" id="trip_hours_indicator">Heures</span>
                <input type="number" min="-15" name="tripLengthHours" id="tripLengthHours" value="0" class="form-control" aria-label="Indiquez le nombre d'heures de trajet" aria-describedby="trip_hours_indicator" />
            </div>

            <div class="col mb-3">
                <span class="input-group-text" id="trip_minutes_indicator">Minutes</span>
                <input type="number" min="-15" step="15" max="60" name="tripLengthMinutes" id="tripLengthMinutes" value="0" onchange="updateHoursInput(tripLengthHours, tripLengthMinutes)" class="form-control" aria-label="Indiquez le nombre de minutes de trajet" aria-describedby="trip_minutes_indicator" />
            </div>

        </div>
    <?php } ?>

    <!-- Champs commentaire -->
    <div id="divCommentFieldInput" class="row mb-3 justify-content-md-center">

        <div class="col mb-3"  data-step="6" data-intro="Une précision à apporter ? Laissez un commentaire !">
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

<script src="public/js/ajax.js"></script>

<script>
getOptionsData('add', 'worksites', <?= isset($_POST['userId']) ? ($_POST['userId']) : "" ;?>);
getRecordData(<?= isset($_POST['recordId']) ? $_POST['recordId'] : "";?>);

</script>