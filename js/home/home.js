//////////////////////////////////////// slider ///////////////////////////////

const arrows = document.getElementsByClassName("arrow");
const slides = document.getElementById("slides");

function slideRight() {
  slides.scrollTo({
    behavior: "smooth",
    left: slides.scrollLeft + slides.clientWidth,
  });
  setTimeout(() => {
    checkScroll();
  }, 500);
}

function slideLeft() {
  const slides = document.getElementById("slides");

  slides.scrollTo({
    behavior: "smooth",
    left: slides.scrollLeft - slides.clientWidth,
  });
  setTimeout(() => {
    checkScroll();
  }, 500);
}
function checkScroll() {
  const { scrollLeft, scrollWidth, clientWidth } = slides;
  const threshold = 100;

  console.log(scrollLeft, scrollWidth);

  arrows[1].classList.toggle(
    "disable",
    scrollLeft > scrollWidth - clientWidth - threshold
  );
  arrows[0].classList.toggle("disable", scrollLeft < threshold);
}
checkScroll();

//////////////////////////////////// animation ///////////////////////////
const fadeUpElements = document.querySelectorAll(".fade-up");
const fadeLeftElements = document.querySelectorAll(".fade-left");
const fadeRightElements = document.querySelectorAll(".fade-right");
//get element view position top
function isElementOutOfView(element) {
  return (
    element.getBoundingClientRect().top >
    (window.innerHeight - 100 || document.documentElement.clientHeight - 100)
  );
}
function displayFadeUpElement(element) {
  element.classList.add("fade-up-scrolled");
}
function hideFadeUpelement(element) {
  element.classList.remove("fade-up-scrolled");
}
function displayFadeLeftElement(element) {
  element.classList.add("fade-left-scrolled");
}
function hideFadeLeftelement(element) {
  element.classList.remove("fade-left-scrolled");
}
function displayFadeRightElement(element) {
  element.classList.add("fade-right-scrolled");
}
function hideFadeRightelement(element) {
  element.classList.remove("fade-right-scrolled");
}

function checkFadeAnimcation() {
  //fade up
  fadeUpElements.forEach((element) => {
    if (!isElementOutOfView(element)) {
      displayFadeUpElement(element);
    } else {
      hideFadeUpelement(element);
    }
  });
  //fade right
  fadeRightElements.forEach((element) => {
    if (!isElementOutOfView(element)) {
      displayFadeRightElement(element);
    } else {
      hideFadeRightelement(element);
    }
  });
  //fade left
  fadeLeftElements.forEach((element) => {
    if (!isElementOutOfView(element)) {
      displayFadeLeftElement(element);
    } else {
      hideFadeLeftelement(element);
    }
  });
}

document.addEventListener("scroll", (e) => {
  checkFadeAnimcation();
});

checkFadeAnimcation();
