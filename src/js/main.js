/////////////////// navigation /////////////////////////
//select naviagation
if (location.pathname == "/index.html") {
  document.getElementById("homeNav").classList.add("navSelected");
}

let activeNav = false;
document.addEventListener("scroll", (e) => {
  if (document.scrollingElement.scrollTop > 50 && !activeNav) {
    document.getElementById("navbar").classList.add("whitenav");
    activeNav = true;
  } else if (document.scrollingElement.scrollTop < 50 && activeNav) {
    document.getElementById("navbar").classList.remove("whitenav");
    activeNav = false;
  }
});

const movileNavBar = document.getElementsByClassName("mobileNavbar")[0];
function toggleNavBar() {
  movileNavBar.classList.toggle("hideNavBar");
}
function preview(input, previewId) {
  var file = input.files[0];
  var reader = new FileReader();
  reader.onload = function (e) {
    var preview = document.getElementById(previewId);
    preview.src = e.target.result;
  };
  reader.readAsDataURL(file);
}
