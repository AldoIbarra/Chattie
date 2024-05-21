// function getPosition() {
//     return new Promise((res, rej) => {
//         navigator.geolocation.getCurrentPosition(res, rej);
//     });
// }

// async function getCoords() {
//     var position = await getPosition();
//     return position.coords.latitude + ', ' + position.coords.longitude;
// }

function showMap(latitude, longitude, tagId){
    var coord = {lat:latitude ,lng: longitude};
    var map = new google.maps.Map(document.getElementById(tagId),{
      zoom: 10,
      center: coord,
      disableDefaultUI: true
    });
    var marker = new google.maps.Marker({
      position: coord,
      map: map
    });
}

function getPosition() {
    return new Promise((res, rej) => {
        navigator.geolocation.getCurrentPosition(res, rej);
    });
}

//showMap(25.649225 ,-100.32595, "map22")