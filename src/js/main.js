function preview(input, previewId) {
  var file = input.files[0];
  var reader = new FileReader();
  reader.onload = function (e) {
    var preview = document.getElementById(previewId);
    preview.src = e.target.result;
  };
  reader.readAsDataURL(file);
}
function formatTimestamp(timestamp) {
  var date = new Date(timestamp);
  var now = new Date();

  //calculate difference in current and sotre
  var diffDays = Math.floor((now - date) / (1000 * 60 * 60 * 24));

  if (diffDays === 0) {
    //check if it was today
    return (
      "Today at " +
      (("0" + date.getHours()).slice(-2) % 12) +
      ":" +
      ("0" + date.getMinutes()).slice(-2) +
      (date.getHours() >= 12 ? "PM" : "AM")
    );
  } else if (diffDays === 1) {
    //check if its 1 day before
    return (
      "Yesterday at " +
      ("0" + date.getHours()).slice(-2) +
      ":" +
      ("0" + date.getMinutes()).slice(-2) +
      (date.getHours() >= 12 ? "PM" : "AM")
    );
  } else {
    //send proper date
    return (
      date.getFullYear() +
      "-" +
      ("0" + (date.getMonth() + 1)).slice(-2) +
      "-" +
      ("0" + date.getDate()).slice(-2) +
      " " +
      ("0" + date.getHours()).slice(-2) +
      ":" +
      ("0" + date.getMinutes()).slice(-2) +
      ":" +
      ("0" + date.getSeconds()).slice(-2)
    );
  }
}
setTimeout(() => {
  const notificationTime = document.querySelectorAll(".n-time");
  notificationTime.forEach((time) => {
    console.log(time.innerHTML);
    time.innerHTML = formatTimestamp(parseInt(time.innerHTML));
  });
}, 1000);

///send request
function sendRequest(method, url, formData = null) {
  return new Promise((resolve, reject) => {
    var xhr = new XMLHttpRequest();

    // Construct the full URL
    var fullUrl = "http://localhost:3000/" + url;

    xhr.open(method, fullUrl);

    // Enable credential forwarding
    xhr.withCredentials = true;

    xhr.onload = function () {
      if (xhr.status >= 200 && xhr.status < 300) {
        resolve(xhr.responseText);
      } else {
        reject("Request failed with status:", xhr.status);
      }
    };

    xhr.onerror = function () {
      reject("Request failed");
    };

    if (formData) {
      xhr.send(formData);
    } else {
      xhr.send();
    }
  });
}
