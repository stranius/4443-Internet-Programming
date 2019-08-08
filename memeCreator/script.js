window.onload = function() {
    // Get the input that the user will upload their own image into
    let imgInput = document.getElementById('imgInput');
    let hideStart = document.getElementById('hideButton');
    let topInput = document.getElementById('topInput');
    let bottomInput = document.getElementById('bottomInput');
    let chooseSample = document.getElementById('chooseSample');
    let chooseSampleButton = document.getElementById('chooseSampleButton');
    let usersCreations = document.getElementById('usersCreations');
    let usersCreationsButton = document.getElementById('usersCreationsButton');

    let jpgDownload = document.getElementById('jpgD');
    jpgDownload.addEventListener('click', function() {
        downloadImage('jpg');
    })
    let pngDownload = document.getElementById('pngD');
    pngDownload.addEventListener('click', function () {
        //downloadImage('png');
    })

    let image = new Image();
    let numberOfImages = 15;
    let userImages = null;

    image.onload = function() {
        console.log("loaded");
        drawImage();
    }

    let topText = "";
    let bottomText = "";

    // Get the canvas and set it up for drawing the text overlay
    var canvas = document.getElementById('canvas'), ctx = canvas.getContext('2d');
    canvas.width = 400;
    canvas.crossOrigin = "Anonymous";
    canvas.height = 400;
    if(image != null)
        ctx.drawImage(image, 0, 0);
    ctx.font = "36pt Impact";
    ctx.lineWidth = 2;

    for(var i = 1; i <= numberOfImages; i++) {
        let img = "<img onclick='imageClick()' class='sampleImage' id=image'" + i + "' src='sampleImages/" + i + ".jpg'>";
        //chooseSample.innerHTML += img;
        var elem = document.createElement('img'); 
        elem.src = "sampleImages/" + i + ".jpg";
        elem.className = 'sampleImage';
        elem.onclick = function(e) {
            image.src = e.path[0].src;
            drawImage();
            chooseSample.style = "display: none;"
        }
        chooseSample.appendChild(elem);
    }

    // Get collection of images that other users have created
    // $.get("http://159.65.231.216/memeCreator/api?route=image?data=").done(function (data) {
    //     let json = data.data; //json data that is returned from the get request
    //     console.log(json);
    //     json.forEach(function (ele) { //Loop through each one and add an element to the searchResults div
    //         console.log(ele);
    //         //let img = '<img id="' + ele.id + '" src="' + ele.original_image_url + '" class="pure-u-1 pure-u-md-1-2">';
    //         //let name = '<p class="candyName">' + ele.title + '</p>';
    //         //let price = '<p class="candyPrice">' + ele.price + '</p>';
    //         //$('#serachResults').append('<div class="candyHolder"> <div class="pure-u-1 pure-u-md-1-4 pure-g">' + img + '<div class="pure-u-1 pure-u-md-1-3">' + name + price + '</div> </div>'); //Add the element
    //     });
    // });

    // Add an event listener to listen for changes in the imgInput
    imgInput.addEventListener('change', function (e) {
        var file = imgInput.files[0]; //sames as here
        var reader = new FileReader();

        reader.onloadend = function () {
            image.src = reader.result;
            drawImage();
        }

        if (file) {
            reader.readAsDataURL(file); //reads the data as a URL
        } else {
            preview.src = "";
        }
    })


    topInput.addEventListener('input', function(e) {
        topText = topInput.value;
        drawImage();
    })

    bottomInput.addEventListener('input', function (e) {
        bottomText = bottomInput.value;
        drawImage();
    })

    function drawImage() {
        // Redraw the image
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.drawImage(image, 0, 0, 400, 400);
        // Draw the top text
        ctx.fillStyle = "white";
        ctx.fillText(topText, 40, 80);
        ctx.strokeText(topText, 40, 80);
        // Draw the bottom text
        ctx.fillStyle = "white";
        ctx.fillText(bottomText, 40, 350);
        ctx.strokeText(bottomText, 40, 350);
    }

    function downloadImage(type) {
        var link = document.createElement('a');
        var data = document.getElementById('canvas').toDataURL();

        link.download = 'filename.' + type;
        link.href = document.getElementById('canvas').toDataURL();
        link.click();
        console.log(data)

        data = {
            imageData: data,
            name: "filename" + type
        }

        //Post the file onto the 
        $.post("http://159.65.231.216/memeCreator/api?route=image?data=" + data).done(function (d) {
            console.log(d);
            let json = d.data; //json data that is returned from the get request
            console.log(json);
        });
    }

    // $('button').click(function () {
    //     console.log(ctx.getImageData(50, 50, 100, 100));
    // });

    hideStart.addEventListener('click', function() {
        let getStarted = document.getElementById('getStarted');
        let creatorMenu = document.getElementById('creatorScreen');

        getStarted.style = "display: none;";
        creatorMenu.style = "background: #2d2d2d; display: table; height: 100vh; width: 100%;";
    })

    usersCreationsButton.addEventListener('click', function () {
        usersCreations.style = "display: auto;";
    })

    chooseSampleButton.addEventListener('click', function () {
        chooseSample.style = "display: auto;";
    })
}