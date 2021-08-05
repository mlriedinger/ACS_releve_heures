/** Incrémente ou décrémente les heures lorsqu'un palier de minutes est atteint (0 ou 60).
 * @param  {string} hoursInputSelector
 * @param  {string} minutesInputSelector
 */
 function updateHoursInput(hoursInputSelector, minutesInputSelector) {
    var minutesInput = document.getElementById(minutesInputSelector.id);
    var hourInput = document.getElementById(hoursInputSelector.id);

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


/** Incrémente les champs "heures" et "minutes" à l'aide des boutons [+].
 * @param  {string} timeUnit unité de temps ('hour' ou 'minutes')
 * @param  {string} hoursInputSelector
 * @param  {string} minutesInputSelector
 */
function increment(timeUnit, hoursInputSelector, minutesInputSelector) {
    var minutesInput = document.getElementById(minutesInputSelector.id);
    var hourInput = document.getElementById(hoursInputSelector.id);

    if(timeUnit == 'hour') {
        hourInput.valueAsNumber ++;
    }
    else if(timeUnit == 'minutes') {
        minutesInput.valueAsNumber += 15;
        updateHoursInput(hoursInputSelector, minutesInputSelector);
    }
    triggerOnchangeEvent(hourInput, minutesInput);
}


/** Décrémente les champs "heures" et "minutes" à l'aide des boutons [-].
 * @param  {string} timeUnit unité de temps ('hour' ou 'minutes')
 * @param  {string} hoursInputSelector
 * @param  {string} minutesInputSelector
 */
function decrement(timeUnit, hoursInputSelector, minutesInputSelector) {
    var minutesInput = document.getElementById(minutesInputSelector.id);
    var hourInput = document.getElementById(hoursInputSelector.id);

    if(timeUnit == 'hour' && hourInput.valueAsNumber > 0) {
        hourInput.valueAsNumber --;
    }
    else if(timeUnit == 'minutes') {
        minutesInput.valueAsNumber -= 15;
        updateHoursInput(hoursInputSelector, minutesInputSelector);
    }
    triggerOnchangeEvent(hourInput, minutesInput);
}


/** Déclenche un événement "onchange" si le bouton utilisé pour incrémenter ou décrémenter est associé à une sous-catégorie de poste.
 * @param  {} hourInput
 * @param  {} minutesInput
 */
function triggerOnchangeEvent(hourInput, minutesInput) {
    if($(hourInput).attr('name').includes('workstation') || $(minutesInput).attr('name').includes('workstation')) {
        $('.col-3 .timeInput').trigger('change');
    }
}

/** Ajoute un event handler (délégué) lorsqu'un événement "onchange" est déclenché sur l'un des champs "heures" et "minutes" de la partie temps de travail.
*/
function addEventCalculateTotalWorkingHours() {
    $('.col-3').on('change', '.timeInput', getTotalWorkingHours);
}


/** Calcule la somme totale des heures de travail déclarées.
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


/** Convertit un temps en minutes au format heures + minutes.
 * @param  {number} timeToConvert
 */
function convertTimeToHoursAndMinutes(timeToConvert) {
    var convertedTime = [];

    convertedTime['hours'] = Math.floor(timeToConvert / 60);
    convertedTime['minutes'] = timeToConvert % 60;

    if(convertedTime['minutes'] === 0) convertedTime['minutes'] = "00";

    return convertedTime;
}