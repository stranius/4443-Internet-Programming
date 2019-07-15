window.addEventListener("load", function() { //Calls main when window has been loaded
    main();
})

/**
* Input: Some string hex value: #AA00FF
* Returns: string [black,white]
* 
* You could alter the function to return: #000000 or #FFFFFF*/

function getContrastYIQ(hexcolor){ // Get's the hex color
    hexcolor = hexcolor.replace("#", "");
    var r = parseInt(hexcolor.substr(0,2),16);
    var g = parseInt(hexcolor.substr(2,2),16);
    var b = parseInt(hexcolor.substr(4,2),16);
    var yiq = ((r*299)+(g*587)+(b*114))/1000;
    return (yiq >= 128) ? 'black' : 'white';
}

function getJSON(url, callback) { //Function for getting JSON files
    var hreq = new XMLHttpRequest();
    hreq.overrideMimeType("application/json");
    hreq.open('GET', url, true); 
    hreq.onreadystatechange = function () {
        if (hreq.readyState == 4 && hreq.status == "200") {
            callback(hreq.responseText);
        }
    }
    hreq.send(null); 
}

async function main() { // Main function
    var div = document.getElementById('container'); // The container of each color item
    // Get the JSON file and then create an item for each color in it
    getJSON('https://raw.githubusercontent.com/rugbyprof/4443-Internet-Programming/master/Resources/R01-Json/colors.json', function(response) {
        var file = JSON.parse(response);
        file.forEach(function(color) {
            let background_color = color.html;
            let name = color.name;
            let font_color = getContrastYIQ(background_color);
            console.log(font_color);
            div.innerHTML += "<div class='grid_item' style='background-color: " + background_color + "; color: " + font_color + "'> <p>" + name + "</p> <p style='background-color: " + background_color + "; font-size: 15px; font-weight: 500;'>" + background_color + "</p> </div>";
        })
    })
}