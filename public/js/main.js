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


/* Fonction qui permet de convertir un temps en minutes au format heures + minutes.
	Param :
	* timeToConvert : temps en minutes à convertir
*/
function convertTimeToHoursAndMinutes(timeToConvert) {
    let convertedTime = [];
    convertedTime['hours'] = Math.floor(timeToConvert / 60);
    convertedTime['minutes'] = timeToConvert % 60;
    if(convertedTime['minutes'] === 0) convertedTime['minutes'] = "00";

    return convertedTime;
}


/* Fonction qui permet d'afficher le nombre de relevés en attente de validation dans un badge rouge à côté du menu "Validation"
    Param :
    * data : contenu de la réponse à la requête AJAX
*/
function displayNumberOfRecordsTocheck(data) {
    var tabData = data.records;

    if(tabData.length) {
        document.getElementById("notificationIcon").innerHTML = tabData.length;
    } else {
        document.getElementById("notificationIcon").hidden = true;
    }
}


/* Fonction qui permet d'ajouter une classe "active" sur un élément de la navbar passé en paramètre.
*/
function updateNavBarActiveAttribute(selector) {
    var navBarItem = document.querySelector(selector);
    navBarItem.classList.add("active");
}


function addEventCalculateTotalWorkingHours() {
    // console.log("addEventCalculateTotalWokringHours");
    $('.col').on('change', '.timeInput', getTotalWorkingHours);
}

function getTotalWorkingHours() {
    let sum = 0;
    // console.log("getTotalWorkingHours");
    // console.log(sum);

    $('.col .timeInput').each(function() {
        if($(this).attr('name').includes('Hours')) {
            var inputValue = $(this).val();

            if($.isNumeric(inputValue)) {
                sum += parseFloat(inputValue) * 60;
            }
        }
        else if($(this).attr('name').includes('Minutes')) {
            var inputValue = $(this).val();
            if($.isNumeric(inputValue)) {
                sum += parseFloat(inputValue);
            }
        }
        /*var inputValue = $(this).val();
        if($.isNumeric(inputValue)) {
            
            sum += parseFloat(inputValue);
        }*/
    });
    sum = convertTimeToHoursAndMinutes(sum)
    $('#totalLengthHours').val(sum.hours);
    $('#totalLengthMinutes').val(sum.minutes);
}