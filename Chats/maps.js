// function getPosition() {
//     return new Promise((res, rej) => {
//         navigator.geolocation.getCurrentPosition(res, rej);
//     });
// }

// async function getCoords() {
//     var position = await getPosition();
//     return position.coords.latitude + ', ' + position.coords.longitude;
// }

function showMap(latitud, longitud){
    var coord = {lat: latitud, lng: longitud};
    var map = new google.maps.Map

}

function getPosition() {
    return new Promise((res, rej) => {
        navigator.geolocation.getCurrentPosition(res, rej);
    });
}

function getCoords() {
    getPosition().then(function(value){
        var coords = value.coords.latitude + ', ' + value.coords.longitude;
        return coords;
    });
}