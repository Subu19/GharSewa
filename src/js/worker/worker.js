// Function to show a specific slide
function showSlide(slideIndex) {
  // Get all slides
  var slides = document.querySelectorAll(".slide");
  // Get all control buttons
  var controls = document.querySelectorAll(".control");

  // Remove 'selected' class from all control buttons
  controls.forEach(function (control) {
    control.classList.remove("selected");
  });

  // Add 'selected' class to the clicked control button
  controls[slideIndex].classList.add("selected");

  // Scroll to the selected slide
  slides.forEach(function (slide) {
    slide.style.transform = `translateX(calc(${-slideIndex} * 100%))`;
  });
}
