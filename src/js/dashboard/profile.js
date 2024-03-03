function setLocation() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPosition);
  } else {
    alert("Geolocation is not supported by this browser.");
  }
  document.getElementById("locationbtn").innerHTML = "Loading...";
  document.getElementById("locationbtn").disabled = true;
}

function showPosition(p) {
  document.getElementById("latitude").value = p.coords.latitude;
  document.getElementById("longitude").value = p.coords.longitude;
  document.getElementById("locationbtn").innerHTML = "Update Success";
  setTimeout(() => {
    document.getElementById("locationbtn").innerHTML = "Set Current Location";
    document.getElementById("locationbtn").disabled = false;
  }, 3000);
}
let map;

async function initMap(lat, lng) {
  // The location of Uluru
  const position = { lat, lng };
  // Request needed libraries.
  //@ts-ignore
  const { Map } = await google.maps.importLibrary("maps");
  const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");

  // The map, centered at Uluru
  map = new Map(document.getElementById("map"), {
    zoom: 15,
    center: position,
    mapId: "DEMO_MAP_ID",
  });

  // The marker, positioned at Uluru
  const marker = new AdvancedMarkerElement({
    map: map,
    position: position,
    title: "Uluru",
  });
}

function viewMap() {
  const latitude = document.getElementById("latitude");
  const longitude = document.getElementById("longitude");
  if (latitude.value && longitude.value) {
    document.getElementById("map").style.display = "block";
    initMap(parseFloat(latitude.value), parseFloat(longitude.value));
  } else {
    alert("Please set live location first!");
  }
}
