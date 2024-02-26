function toggleDropDown(e) {
  var drops = e.nextElementSibling;
  var icon = e.getElementsByClassName("material-icons")[0];
  icon.classList.toggle("toggleDropdownIcon");
  drops.classList.toggle("hide");
}
