function toggleDropDown(e) {
  var drops = e.nextElementSibling;
  var icon = e.getElementsByClassName("material-icons")[0];
  icon.classList.toggle("toggleDropdownIcon");
  drops.classList.toggle("hide");
}

var shownWorker = [];

const workerContainner = document.getElementById("workers");
var filterurl = new URL(document.location);
filterurl.pathname = "/api/getworkerlist";
var searchQuery = new URLSearchParams();
var formdata = new FormData();

//update api filter and fetch it
async function updateFilter(e, search) {
  if (!search) {
    if (e.checked) {
      formdata.set(e.value, "1");
    } else {
      formdata.delete(e.value);
    }
  } else {
    searchQuery.set("search", e.value);
    filterurl.search = searchQuery;
  }

  fetchWorkers()
    .then((res) => {
      updateWorkers();
    })
    .catch((err) => console.log(err));
}

//fetch all workers through api
function fetchWorkers() {
  return new Promise((resolve, reject) => {
    sendRequest("POST", filterurl, formdata)
      .then((res) => {
        const workers = JSON.parse(res);
        shownWorker = workers;
        resolve(workers);
      })
      .catch((err) => {
        reject(err);
      });
  });
}

// update visible workers
function updateWorkers() {
  workerContainner.innerHTML = "";

  for (var w of shownWorker) {
    workerContainner.innerHTML += `
        <a class="card" href="/worker?id=${w.worker_id}">
            <img src="/${w.cover_image}" alt="" class="card-img">
            <div class="card-title">${w.first_name} ${w.last_name}</div>
            <div class="card-details">
                <div class="card-tag">${w.service_type}</div>
                <div class="card-rating">
                    <div class="rating2">
                        <input value="5" type="radio" ${
                          w.average_rating >= 5 ? "checked" : ""
                        }>
                        <label></label>
                        <input value="4" type="radio" ${
                          w.average_rating >= 4 ? "checked" : ""
                        }>
                        <label></label>
                        <input value="3" type="radio" ${
                          w.average_rating >= 3 ? "checked" : ""
                        }>
                        <label></label>
                        <input value="2" type="radio" ${
                          w.average_rating >= 2 ? "checked" : ""
                        }>
                        <label></label>
                        <input value="1" type="radio" ${
                          w.average_rating >= 1 ? "checked" : ""
                        }>
                        <label></label>
                    </div>
                    (${w.total_reviews})
                </div>
            </div>
            <div class="card-footer">
                <img src="assets/svgs/greenLocation.svg" alt="" class="card-smallicon">
                ${w.distance} km
            </div>
        </a>
    `;
  }
}

//sort by best
async function sortByBestRating() {
  if (!shownWorker.length > 0) {
    await fetchWorkers();
  }
  shownWorker.sort((a, b) => b.average_rating - a.average_rating);
  updateWorkers();
}
//sort by distance
async function sortByDistance() {
  if (!shownWorker.length > 0) {
    await fetchWorkers();
  }
  shownWorker.sort((a, b) => {
    if (a.distance === "unknown" && b.distance === "unknown") {
      return 0;
    } else if (a.distance === "unknown") {
      return 1;
    } else if (b.distance === "unknown") {
      return -1;
    } else {
      return a.distance - b.distance;
    }
  });

  shownWorker.sort((a, b) => {
    if (a.distance !== "unknown" && b.distance !== "unknown") {
      return a.distance - b.distance;
    } else {
      return a.distance === "unknown" ? 1 : -1;
    }
  });
  updateWorkers();
}
