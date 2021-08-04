/** Fonction qui permet d'ajouter des fichiers de script à la suite du script principal.
 * @param  {string} file chemin d'accès au fichier
 * @param  {string} integrity (facultatif)
 * @param  {string} crossorigin (facultatif)
 */
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
include("public/js/timeManagement.js");
include("public/js/updateNavbar.js");
include("https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js", "sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0", "anonymous");