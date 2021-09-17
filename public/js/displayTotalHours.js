function getWeeklyCounters(userUUID, date) {
    let currentDate = new Date(date);
    Promise.all([getWeek(currentDate), getDaysOfWeek(currentDate)])
    .then((results) => {
        displayCounterFields(userUUID, results[0], results[1])
        .then((weekNumber) => {
            getUserDailyTotals(userUUID, weekNumber);
            getUserWeeklyTotal(userUUID, weekNumber);
        });
    })
}

/** Affiche le nombre total d'heures hebdomadaires effectuées par l'utilisateur.
 * @param {object} userWeeklyStats
 */
function displayWeeklyTotal(userWeeklyStats) {
    let weeklyTotal = document.getElementById("weeklyTotal");
    let total = convertTimeToHoursAndMinutes(userWeeklyStats.total);
    let hours = total.hours;
    let minutes = total.minutes;

    userWeeklyStats.total !== null ? weeklyTotal.innerHTML = hours + 'h' + minutes : weeklyTotal.innerHTML = '0h00';
}

/** Affiche le cumul des heures réalisées par l'utilisateur pour chaque jour de la semaine en cours.
 * @param {Array} parsedDatas
 */
function displayWeeklyDatas(parsedDatas) {
    parsedDatas.forEach(element => {
        let hours = convertTimeToHoursAndMinutes(element.total).hours;
        let minutes = convertTimeToHoursAndMinutes(element.total).minutes;

        element.textTag.innerHTML = hours + "h" + minutes;
        element.total >= 525 ? element.textTag.style = "fill: #007A36" : element.textTag.style = "fill: #C63527"; // 525 min = 8.75h/jour
    });
}

/** Fait correspondre les données issues de la requête Ajax et les balises <text> de la page "home". S'il y a des données, elles sont ajoutée, sinon on initialise à 0.
 * @param {any} userWeeklyDatas
 * @returns {Promise} un tableau de données associées à la balise <text> correspondante pour chaque jour de la semaine
 */
function parseWeeklyDatas(userWeeklyDatas) {
    return new Promise((resolve) => {
        let parsedDatas = [];
    
        // On cible toutes les balises <text>
        let textTagsCollection = document.getElementsByClassName("textTag");
        
        for (let item of textTagsCollection) {
            // On initialise un tableau de résultats et les variables à y insérer
            let data = [];
            let date = "";
            let day = "0";
            let total = "0";
    
            // On récupère l'attribut 'id' de la balise <text>
            let itemId = item.id;
    
            userWeeklyDatas.forEach(dailyData => {
                // S'il y a une correspondance entre la fin de l'id du text tag et la valeur associée à la clé "day", on remplace les variables avec les données du jour
                if(itemId.substring(itemId.lastIndexOf('_') + 1) === dailyData.day) {
                    date = dailyData.date;
                    day = dailyData.day;
                    total = dailyData.total;
                }
            });
            // On remplit le tableau de résultats
            data["textTag"] = item;
            data["date"] = date;
            data["day"] = day;
            data["total"] = total;
    
            // On pousse le tableau de résultats dans le tableau général
            parsedDatas.push(data);
        }
       resolve(parsedDatas);
    })
}

function displayCounterFields(userUUID, weekNumber, daysOfWeek) {
    return new Promise((resolve) => {
        let divUserStats = document.getElementById("divUserStats");
        let row = document.createElement("div");
        row.setAttribute("class", "row mb-5 text-center justify-content-evenly align-items-end");
    
        const options = { day: "2-digit", month: "2-digit", year: "numeric" };
        
        let html = [
            `<h2 class="display-6 mt-5 mb-5"><i class="bi bi-chevron-left pe-5 text-secondary" onclick="getPreviousWeek('${userUUID}', '${ daysOfWeek[0] }')"></i>Semaine ${ weekNumber } <i class="bi bi-chevron-right ps-5 text-secondary" onclick="getNextWeek('${userUUID}', '${ daysOfWeek[0] }')"></i></h2>
    
            <div class="col-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100">
                    <circle cx="50" cy="50" r="35" style="stroke: #F2E2DC; stroke-width: 2; fill: #fff" />
                    <text id="totalWeekDay_2" class="textTag" x="50" y="50" text-anchor="middle" font-size="1.2em" font-family="Arial" dy=".3em"></text>
                </svg>
                <p class="fs-5 text-center" id="weekDay_2" style="position: relative">Lun. ${ daysOfWeek[0].toLocaleString("fr-FR", options) }</p>
            </div> 
    
            <div class="col-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100">
                    <circle cx="50" cy="50" r="35" style="stroke: #F2E2DC; stroke-width: 2; fill: #fff" />
                    <text id="totalWeekDay_3" class="textTag" x="50" y="50" text-anchor="middle" font-size="1.2em" font-family="Arial" dy=".3em"></text>
                </svg>
                <p class="fs-5 text-center" id="weekDay_3" style="position: relative">Mar. ${ daysOfWeek[1].toLocaleString("fr-FR", options) }</p>
            </div>
    
            <div class="col-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100">
                    <circle cx="50" cy="50" r="35" style="stroke: #F2E2DC; stroke-width: 2; fill: #fff" />
                    <text id="totalWeekDay_4" class="textTag" x="50" y="50" text-anchor="middle" font-size="1.2em" font-family="Arial" dy=".3em"></text>
                </svg>
                <p class="fs-5 text-center" id="weekDay_4" style="position: relative">Mer. ${ daysOfWeek[2].toLocaleString("fr-FR", options) }</p>
            </div>
    
            <div class="col-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100">
                    <circle cx="50" cy="50" r="35" style="stroke: #F2E2DC; stroke-width: 2; fill: #fff" />
                    <text id="totalWeekDay_5" class="textTag" x="50" y="50" text-anchor="middle" font-size="1.2em" font-family="Arial" dy=".3em"></text>
                </svg>
                <p class="fs-5 text-center" id="weekDay_5" style="position: relative">Jeu. ${ daysOfWeek[3].toLocaleString("fr-FR", options) }</p>
            </div>
    
            <div class="col-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100">
                    <circle cx="50" cy="50" r="35" style="stroke: #F2E2DC; stroke-width: 2; fill: #fff" />
                    <text id="totalWeekDay_6" class="textTag" x="50" y="50" text-anchor="middle" font-size="1.2em" font-family="Arial" dy=".3em"></text>
                </svg>
                <p class="fs-5 text-center" id="weekDay_6" style="position: relative">Ven. ${ daysOfWeek[4].toLocaleString("fr-FR", options) }</p>
            </div>
    
            <div class="col-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100">
                    <circle cx="50" cy="50" r="35" fill="#C63527" />
                    <text id="weeklyTotal" x="50" y="50" text-anchor="middle" fill="white" font-size="1.2em" font-family="Arial" dy=".3em"></text>
                </svg>
                <p class="fs-5 text-center" id="currentWeek" style="position: relative">Total</p>
            </div>`
        ];
    
        row.innerHTML = html;
        divUserStats.appendChild(row);
        resolve(weekNumber);
    })
}

/** Renvoie le numéro de semaine (entre 0 et 52-53) pour une date passée en paramètres.
 * @param {Date} date
 * @returns {Promise}
 */
function getWeek(date) {
    return new Promise((resolve) => {
        let dayOfYear = getDayOfYear(date);
        let dayOfWeek = date.getDay();
    
        resolve(Math.trunc((10 + dayOfYear - dayOfWeek) / 7));
    })
}

/** Renvoie le numéro du jour (entre 1 et 365-366) pour la date passée en paramètre.
 * @param {Date} date
 * @returns {number}
 */
function getDayOfYear(date) {
    let year = date.getUTCFullYear();
    let firstDayOfYear = new Date(Date.UTC(year, 0, 1)); // 1er jour de l'année passée en paramètre
    
    let millisecondsSinceFirstDayOfYear = date - firstDayOfYear; // Nb de ms écoulées entre la date passée en paramètre et le premier jour de l'année  
    const millisecondsPerDay = 86400000; // Nombre de ms dans 24 heures

    return Math.trunc(millisecondsSinceFirstDayOfYear / millisecondsPerDay);
}

/** Renvoie un tableau contenant les dates de la semaine correspondant à la date passée en paramètre.
 * @param {Date} date
 * @returns {Promise}
 */
function getDaysOfWeek(date) {
    return new Promise((resolve) => {
        let year = date.getFullYear();
        let month = date.getMonth();
        let daysOfWeek = [];
    
        // On récupère la date passée en paramètre ainsi que le nombre correspondant au jour de la semaine (0 = dimanche, 1 = lundi, etc.)
        let currentDate = date.getDate();
        let dayOfWeek = date.getDay();
    
        // On calcule la date correspondant au lundi de la même semaine
        let firstDayOfWeek = currentDate - dayOfWeek + 1;
    
        // On crée un objet Date et on lui assigne la date du lundi
        let monday = new Date(year, month, firstDayOfWeek);
    
        // On ajoute le lundi au tableau, puis on boucle en décalant la date de +1 pour obtenir tous les jours de la semaine
        for (let i = 0; i < 7; i++) {
            daysOfWeek.push(new Date(monday));
            monday.setDate(monday.getDate()+1);
        }
    
        resolve(daysOfWeek);
    })
  }

  function getPreviousWeek(userUUID, date) {
    let currentWeekStart = new Date(date);
    let previousWeekStart = new Date(currentWeekStart.getFullYear(), currentWeekStart.getMonth(), currentWeekStart.getDate() - 7);
    clearUserStatsDiv();
    getWeeklyCounters(userUUID, previousWeekStart);
  }

  function getNextWeek(userUUID, date) {
    let currentWeekStart = new Date(date);
    let nextWeekStart = new Date(currentWeekStart.getFullYear(), currentWeekStart.getMonth(), currentWeekStart.getDate() + 7);
    clearUserStatsDiv();
    getWeeklyCounters(userUUID, nextWeekStart);
  }

  function clearUserStatsDiv() {
    let userStatsDiv = document.getElementById("divUserStats");
    userStatsDiv.replaceChildren();
  }