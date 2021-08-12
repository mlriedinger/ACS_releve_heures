/** Fonction qui permet d'afficher les événements récupérés du planning
 * @param  {object} data contenu de la réponse à la requête AJAX
 */
function displayEventsFromPlanning(data) {
    var divEvents = document.getElementById("listOfEvents");

    for(let i = 0 ; i < data.length ; i ++) {
        var newEvent = document.createElement("div");
        newEvent.setAttribute("class", "col-sm-4 mb-3");

        var worksiteId = data[i].id_chantier;
        var worksiteName = data[i].Nom;
        var worksiteStartDate = data[i].DatePlanningDebut;
        var worksiteEndDate = data[i].DatePlanningFin;
        var typeOfEvent = data[i].Type;

        var html = [
            `<div class="card text-center" >
                <div class="card-header ">
                    ${ typeOfEvent }
                </div>

                <div class="card-body">
                    <h5 class="card-title">${ worksiteName }</h5>
                    <a href="#" class="btn btn-danger">Saisir un relevé</a>
                </div>

                <div class="card-footer text-muted">
                    <p><small>Chantier du ${ worksiteStartDate } au ${ worksiteEndDate }</small></p>
                </div>
            </div>`
        ];
        
        newEvent.innerHTML = html;

        // On ajoute le nouvel item à la liste existante
        divEvents.appendChild(newEvent);
    }
    console.log(data);
}