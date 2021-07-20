function include(file, integrity="", crossorigin="") {
    var script = document.createElement('script');
    script.src = file;
    script.type = "text/javascript";
    if(integrity !== "") {
        script.integrity = integrity;
    }
    if(crossorigin !== "") {
        script.crossOrigin = crossorigin;
    }

    document.getElementById('mainScript').after(script);
}

include("public/js/ajaxRequests.js");
include("public/js/parseRequestResults.js");
include("public/js/updateRecordsLog.js");
include("public/js/updateFormInputs.js");
include("public/js/buttonManagement.js");
include("public/js/updateListOfEvents.js");
include("https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js", "sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0", "anonymous");


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


/** Fonction qui permet d'afficher le nombre de relevés en attente de validation dans un badge rouge à côté du menu "Validation"
 * @param  {object} data contenu de la réponse à la requête AJAX
 */
function displayNumberOfRecordsTocheck(data) {
    var tabData = data.records;

    if(tabData.length) {
        document.getElementById("notificationIcon").innerHTML = tabData.length;
    } else {
        document.getElementById("notificationIcon").hidden = true;
    }
}


/** Fonction qui permet d'ajouter une classe "active" sur un élément de la navbar passé en paramètre.
 * @param  {string} selector
 */
function updateNavBarActiveAttribute(selector) {
    var navBarItem = document.querySelector(selector);
    navBarItem.classList.add("active");
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