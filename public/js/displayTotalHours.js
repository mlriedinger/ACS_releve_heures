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