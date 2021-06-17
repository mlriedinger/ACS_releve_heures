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
include("https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js", "sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0", "anonymous");


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

function updateNavBarActiveAttribute(selector) {
    var menuItem = document.querySelector(selector);
    menuItem.classList.add("active");
}