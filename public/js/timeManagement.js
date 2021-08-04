/** Fonction qui permet d'incrémenter ou de décrémenter les heures lorsqu'un palier de minutes est atteint
 * @param  {string} hoursInputId
 * @param  {string} minutesInputId
 */
 function incrementHour(hoursInputId, minutesInputId) {
    var minutesInput = document.getElementById(minutesInputId.id);
    var hourInput = document.getElementById(hoursInputId.id);

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

function increment(timeUnit, hoursInputId, minutesInputId) {
    var minutesInput = document.getElementById(minutesInputId.id);
    var hourInput = document.getElementById(hoursInputId.id);

    if(timeUnit == 'hour') {
        hourInput.valueAsNumber ++;
    }
    else if(timeUnit == 'minutes') {
        minutesInput.valueAsNumber += 15;
        incrementHour(hoursInputId, minutesInputId);
    }
    triggerOnchangeEvent(hourInput, minutesInput);
}

function decrement(timeUnit, hoursInputId, minutesInputId) {
    var minutesInput = document.getElementById(minutesInputId.id);
    var hourInput = document.getElementById(hoursInputId.id);

    if(timeUnit == 'hour' && hourInput.valueAsNumber > 0) {
        hourInput.valueAsNumber --;
    }
    else if(timeUnit == 'minutes') {
        minutesInput.valueAsNumber -= 15;
        incrementHour(hoursInputId, minutesInputId);
    }
    triggerOnchangeEvent(hourInput, minutesInput);
}

function triggerOnchangeEvent(hourInput, minutesInput) {
    // Si la fonction a été appelée par un bouton associé à une sous-catégorie de poste, on déclenche un événement "change" pour recalculer le total
    if($(hourInput).attr('name').includes('workstation') || $(minutesInput).attr('name').includes('workstation')) {
        console.log("trigger onchange event");
        $('.col-3 .timeInput').trigger('change');
    }
}

/* Fonction qui permet d'ajouter un événement pour détecter les modifications dans les champs contenant la classe "timeInput", càd les champs "heures" et "minutes"
*/
function addEventCalculateTotalWorkingHours() {
    $('.col-3').on('change', '.timeInput', getTotalWorkingHours);
}


/* Fonction qui permet de calculer le total des heures effectuées
*/
function getTotalWorkingHours() {
    var sum = 0;

    $('.col-3 .timeInput').each(function() {
        if($(this).attr('name').includes('Hours')) {
            let inputValue = $(this).val();

            if($.isNumeric(inputValue)) {
                sum += parseFloat(inputValue) * 60;
            }
        }
        else if($(this).attr('name').includes('Minutes')) {
            let inputValue = $(this).val();
            if($.isNumeric(inputValue)) {
                sum += parseFloat(inputValue);
            }
        }
    });
    
    sum = convertTimeToHoursAndMinutes(sum)
    $('#totalLengthHours').val(sum.hours);
    $('#totalLengthMinutes').val(sum.minutes);
}


/** Fonction qui permet de convertir un temps en minutes au format heures + minutes.
 * @param  {number} timeToConvert
 */
function convertTimeToHoursAndMinutes(timeToConvert) {
    var convertedTime = [];

    convertedTime['hours'] = Math.floor(timeToConvert / 60);
    convertedTime['minutes'] = timeToConvert % 60;

    if(convertedTime['minutes'] === 0) convertedTime['minutes'] = "00";

    return convertedTime;
}