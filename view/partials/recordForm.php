
<div class="container-fluid">

<form action="<?=($_POST['recordId'] == 0 ? 'index.php?action=addNewRecord' : 'index.php?action=updateRecord')?>" method="POST">
    
    <!-- Sélection du chantier et de la date du relevé -->
    <div id="divWorksiteInput" class="row mt-5 mb-3 d-flex justify-content-md-center">

        <div class="col mb-3" style="flex-grow: 2;">
            <span class="input-group-text" id="worksite_selector">Chantier</span>
            <select class="form-select" name="worksiteId" id="selectWorksite" aria-label="Sélectionnez un chantier" aria-describedby="worksite_selector" required>
                <option value="" disabled selected>Sélectionnez un chantier</option>
            </select>
        </div>

        <div class="col flex-shrink-1 mb-3" <?= $_SESSION['dateTimeMgmt'] == 1 ? "hidden" : ""; ?> >
            <span class="input-group-text" id="worksite_selector">Date</span>
            <input type="date" name="recordDate" id="recordDate" class="form-control" aria-label="Sélectionnez une date" aria-describedby="date_selector" />
        </div>

    </div>


    <!-- Champs pour un relevé avec date et heure de début / date et heure de fin -->
    <div id="divWorkDateTimeInput" class="row mb-3 justify-content-md-center" <?= $_SESSION['dateTimeMgmt'] == 0 ? "hidden" : ""; ?> >

        <p class="h6 text-center mb-3">Temps de travail</p>

        <div class="col mb-3">
            <span class="input-group-text" id="datetime_start_selector">Début</span>
            <input type="datetime-local" name="datetimeStart" id="datetime_start" class="form-control" aria-label="Sélectionnez une date et une heure de début" aria-describedby="datetime_start_selector" />
        </div>

        <div class="col mb-3">
            <span class="input-group-text" id="datetime_end_selector">Fin</span>
            <input type="datetime-local" name="datetimeEnd" id="datetime_end" class="form-control" aria-label="Sélectionnez une date et une heure de fin" aria-describedby="datetime_end_selector" />
        </div>

    </div>

    <!-- Champs pour un relevé avec seulement une durée -->
    <div id="divWorkLengthInput" class="row mb-3 justify-content-md-center" <?= $_SESSION['lengthMgmt'] == 0 ? "hidden" : ""; ?> >

        <p class="h6 text-center mb-3">Temps de travail</p>

        <div class="col mb-3">
            <span class="input-group-text" id="work_hours_indicator">Heures</span>
            <input type="number" min="-15" name="workLengthHours" id="workLengthHours" value="0" class="form-control" aria-label="Indiquez le nombre d'heures de trajet" aria-describedby="work_hours_indicator" />
        </div>

        <div class="col mb-3">
            <span class="input-group-text" id="work_minutes_indicator">Minutes</span>
            <input type="number" min="-15" step="15" max="60" name="workLengthMinutes" value="0" id="workLengthMinutes" onclick="incrementHour(workLengthHours, workLengthMinutes)" class="form-control" aria-label="Indiquez le nombre de minutes de trajet" aria-describedby="work_minutes_indicator" />
        </div>

    </div>

    <!-- Champs pour un relevé avec gestion du temps de pause -->
    <div id="divBreakTime" class="row mb-3 justify-content-md-center" <?= $_SESSION['breakMgmt'] == 0 ? "hidden" : ""; ?> >

        <p class="h6 text-center mb-3">Temps de pause</p>

            <div class="col mb-3">
                <span class="input-group-text" id="trip_hours_indicator">Heures</span>
                <input type="number" min="-15" name="breakLengthHours" id="breakLengthHours" value="0" class="form-control" aria-label="Indiquez le nombre d'heures de trajet" aria-describedby="trip_hours_indicator" />
            </div>

            <div class="col mb-3">
                <span class="input-group-text" id="trip_minutes_indicator">Minutes</span>
                <input type="number" min="-15" step="15" max="60" name="breakLengthMinutes" id="breakLengthMinutes" value="0" onclick="incrementHour(breakLengthHours, breakLengthMinutes)" class="form-control" aria-label="Indiquez le nombre de minutes de trajet" aria-describedby="trip_minutes_indicator" />
            </div>


    </div>

    <!-- Champs pour un relevé avec gestion du temps de trajet -->
    <div id="divTripTimeInput" class="row mb-3 justify-content-md-center" <?= $_SESSION['tripMgmt'] == 0 ? "hidden" : ""; ?> >

        <p class="h6 text-center mb-3">Temps de trajet</p>

        <div class="col mb-3">
            <span class="input-group-text" id="trip_hours_indicator">Heures</span>
            <input type="number" min="-15" name="tripLengthHours" id="tripLengthHours" value="0" class="form-control" aria-label="Indiquez le nombre d'heures de trajet" aria-describedby="trip_hours_indicator" />
        </div>

        <div class="col mb-3">
            <span class="input-group-text" id="trip_minutes_indicator">Minutes</span>
            <input type="number" min="-15" step="15" max="60" name="tripLengthMinutes" id="tripLengthMinutes" value="0" onclick="incrementHour(tripLengthHours, tripLengthMinutes)" class="form-control" aria-label="Indiquez le nombre de minutes de trajet" aria-describedby="trip_minutes_indicator" />
        </div>

    </div>

    <!-- Champs commentaire -->
    <div id="divCommentFieldInput" class="row mb-3 justify-content-md-center">

        <div class="col mb-3">
            <span class="input-group-text" id="comment_section">Commentaire (facultatif)</span>
            <textarea autocapitalize="sentences" maxlength="255" name="comment" id="comment" class="form-control" aria-label="Commentaire" aria-describedby="comment_section"></textarea>
            <small class="form-text text-muted">255 caractères maximum</small>
        </div>

    </div>

    <div class="row mb-3 justify-content-md-center">
        
        <div class="col mb-5 text-center">
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
