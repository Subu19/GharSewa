function showMap(lat, lon) {
  const mapContainner = document.getElementById("mapContainner");
  mapContainner.classList.add("show");
  initMap(lat, lon);
}
function closeMap() {
  const mapContainner = document.getElementById("mapContainner");
  mapContainner.classList.remove("show");
}
