/** Fonction qui permet d'afficher les événements récupérés du planning
 * @param  {object} data contenu de la réponse à la requête AJAX
 */
function displayEventsFromPlanning(data) {
    var divEvents = document.getElementById("listOfEvents");

    for(let i = 0 ; i < data.length ; i ++) {
        var newEvent = document.createElement("div");
        newEvent.setAttribute("class", "col-sm-3 mb-4");

        var worksiteUUID = data[i].id_document;
        var worksiteReference = data[i].Ref;
        var worksiteTitle = data[i].Ref_interne;
        var worksiteStartDate = data[i].DatePlanningDebut;
        var worksiteEndDate = data[i].DatePlanningFin;
        var eventType = data[i].Type;
        var uniqueIcon, firstIcon, secondIcon = "";

        switch(eventType) {
            case "Fabrication":
                uniqueIcon = "tools";
                break;

            case "Pose":
                uniqueIcon = "truck";
                break;
            
            case "Fab+Pose":
                firstIcon = "tools";
                secondIcon = "truck";
                break;
        }

        var divImgSimple = [
            `<div>
                <img src="public/img/icon_${ uniqueIcon }.svg" class=" mx-auto mt-4" alt="Icône de camion" style="height: 3rem;">
            </div>`
        ];

        var divImgDouble = [
            `<div>
                <img src="public/img/icon_${ firstIcon }.svg" class=" mx-auto mt-4" alt="Icône de camion" style="height: 3rem;">
                <img src="public/img/icon_${ secondIcon }.svg" class=" mx-auto mt-4" alt="Icône de camion" style="height: 3rem;">
            </div>`
        ];

        var html = [
            `<div class="card text-center">
                <div class="card-header ">
                    ${ eventType }
                </div>
                
                ${ eventType === 'Fab+Pose' ? divImgDouble : divImgSimple }

                <div class="card-body">
                    <h5 class="card-title">${ worksiteReference }</h5>
                    <p class="card-text">${ worksiteTitle }</p>
                    <p><small>Du ${ worksiteStartDate } au ${ worksiteEndDate }</small></p>
                </div>

                <div class="card-footer text-muted">
                    <a href="index.php?action=showNewRecordForm&worksiteUUID=${ worksiteUUID }&eventType=${ eventType }" class="btn btn-dark mt-2 mb-2">Saisir un relevé</a>
                </div>
            </div>`
        ];
        
        newEvent.innerHTML = html;

        // On ajoute le nouvel item à la liste existante
        divEvents.appendChild(newEvent);
    }
    console.log(data);
}