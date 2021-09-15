function displayWeeklyTotal(userWeeklyStats) {
    console.log(userWeeklyStats);
    let weeklyTotal = document.getElementById("weeklyTotal");
    let total = convertTimeToHoursAndMinutes(userWeeklyStats.total);

    let hours = total.hours;
    let minutes = total.minutes;

    total !== null ? weeklyTotal.innerHTML = hours + 'h' + minutes : dailyTotal.innerHTML = '0';
}

function displayWeeklyDatas(parsedDatas){
    parsedDatas.forEach(element => {
        console.log(element);
        let hours = convertTimeToHoursAndMinutes(element.total).hours;
        let minutes = convertTimeToHoursAndMinutes(element.total).minutes;
        element.textTag.innerHTML = hours + "h" + minutes;
        element.total >= 525 ? element.textTag.style = "fill: #007A36" : element.textTag.style = "fill: #C63527";
    });
}

function parseWeeklyDatas(userWeeklyDatas) {
    return new Promise((resolve) => {
        let parsedDatas = [];
    
        // Cibler tous les text tags
        let textTagsCollection = document.getElementsByClassName("textTag");
        
        for (let item of textTagsCollection) {
            // Initialiser un tableau de data et les variables à insérer
            let data = [];
            let date = "";
            let day = "0";
            let total = "0";
    
            let itemId = item.id;
    
            userWeeklyDatas.forEach(dailyData => {
                // S'il y a une correspondance entre la fin de l'id du text tag et la valeur associée à la clé "day", on remplace les variables avec les données du jour
                if(itemId.substring(itemId.lastIndexOf('_') +1) === dailyData.day) {
                    date = dailyData.date;
                    day = dailyData.day;
                    total = dailyData.total;
                }
            });
            // On remplit le tableau de data avec les données
            data["textTag"] = item;
            data["date"] = date;
            data["day"] = day;
            data["total"] = total;
    
            // On pousse le tableau de data dans le tableau général
            parsedDatas.push(data);
        }
       resolve(parsedDatas);
    })
}

function displayCounterFields() {
    let divUserStats = document.getElementById("divUserStats");
    let row = document.createElement("div");
    row.setAttribute("class", "row mb-5 text-center justify-content-evenly align-items-end");
    let today = new Date();
    let week = getNumberOfWeek(today);
    let daysOfWeek = getDaysOfWeek(today);

    console.log(daysOfWeek);
    
    let html = [
        `<h2 class="display-6 mt-5 mb-5"><i class="bi bi-chevron-left pe-5 text-secondary"></i>Semaine ${ week } <i class="bi bi-chevron-right ps-5 text-secondary"></i></h2>

        <div class="col-sm">
            <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100">
                <circle cx="50" cy="50" r="35" style="stroke: #F2E2DC; stroke-width: 2; fill: #fff" />
                <text id="totalWeekDay_2" class="textTag" x="50" y="50" text-anchor="middle" font-size="1.2em" font-family="Arial" dy=".3em"></text>
            </svg>
            <p class="fs-5 text-center" id="weekDay_2" style="position: relative">Lun. ${ daysOfWeek[0] }</p>
        </div> 

        <div class="col-sm">
            <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100">
                <circle cx="50" cy="50" r="35" style="stroke: #F2E2DC; stroke-width: 2; fill: #fff" />
                <text id="totalWeekDay_3" class="textTag" x="50" y="50" text-anchor="middle" font-size="1.2em" font-family="Arial" dy=".3em"></text>
            </svg>
            <p class="fs-5 text-center" id="weekDay_3" style="position: relative">Mar. ${ daysOfWeek[1] }</p>
        </div>

        <div class="col-sm">
            <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100">
                <circle cx="50" cy="50" r="35" style="stroke: #F2E2DC; stroke-width: 2; fill: #fff" />
                <text id="totalWeekDay_4" class="textTag" x="50" y="50" text-anchor="middle" font-size="1.2em" font-family="Arial" dy=".3em"></text>
            </svg>
            <p class="fs-5 text-center" id="weekDay_4" style="position: relative">Mer. ${ daysOfWeek[2] }</p>
        </div>

        <div class="col-sm">
            <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100">
                <circle cx="50" cy="50" r="35" style="stroke: #F2E2DC; stroke-width: 2; fill: #fff" />
                <text id="totalWeekDay_5" class="textTag" x="50" y="50" text-anchor="middle" font-size="1.2em" font-family="Arial" dy=".3em"></text>
            </svg>
            <p class="fs-5 text-center" id="weekDay_5" style="position: relative">Jeu. ${ daysOfWeek[3] }</p>
        </div>

        <div class="col-sm">
            <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100">
                <circle cx="50" cy="50" r="35" style="stroke: #F2E2DC; stroke-width: 2; fill: #fff" />
                <text id="totalWeekDay_6" class="textTag" x="50" y="50" text-anchor="middle" font-size="1.2em" font-family="Arial" dy=".3em"></text>
            </svg>
            <p class="fs-5 text-center" id="weekDay_6" style="position: relative">Ven. ${ daysOfWeek[4] }</p>
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
}

function getNumberOfWeek(dt) {
    // const today = new Date();
    // const firstDayOfYear = new Date(today.getFullYear(), 0, 1);
    // const pastDaysOfYear = (today - firstDayOfYear) / 86400000;
    // return Math.ceil((pastDaysOfYear + firstDayOfYear.getDay() + 1) / 7);

    var tdt = new Date(dt.valueOf());
    var dayn = (dt.getDay() + 6) % 7;
    tdt.setDate(tdt.getDate() - dayn + 3);
    var firstThursday = tdt.valueOf();
    tdt.setMonth(0, 1);
    if (tdt.getDay() !== 4) {
        tdt.setMonth(0, 1 + ((4 - tdt.getDay()) + 7) % 7);
    }
    return 1 + Math.ceil((firstThursday - tdt) / 604800000);
}

function getDaysOfWeek(currentDate) {
    var week = [];
    // Starting Monday not Sunday 
    var first = currentDate.getDate() - currentDate.getDay() + 1;
    currentDate.setDate(first);
    for (var i = 0; i < 7; i++) {
    var options = {day: "2-digit", month:"2-digit"};
      week.push(new Date(+currentDate).toLocaleString('fr-FR', options));
      currentDate.setDate(currentDate.getDate()+1);
    }
    return week;
  }