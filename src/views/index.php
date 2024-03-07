<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Ghar Sewa</title>
  <link rel="icon" type="image/x-icon" href="/assets/svgs/logo.ico" />
  <link rel="stylesheet" href="src/css/home/main.css" />
  <link rel="stylesheet" href="src/css/navbar.css">
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet" />
</head>

<body>
  <img class="wave1" src="./assets/svgs/wave1.svg" alt="" />
  <!-- include navbar  -->
  <?php include "src/views/components/navbar.php" ?>
  <!-- //landing page  -->
  <div class="landingPage">
    <div class="wrapper">
      <div class="left">
        <div class="prefix fade-right">Get your own</div>
        <div class="heading fade-right">Handy Man</div>
        <div class="surfix fade-right">
          Your comfort is our job and responsiblity
        </div>
        <button class="fade-right">Get Now!</button>
      </div>
      <div class="right">
        <img src="./assets/svgs/landingImage.svg" class="landingImage" alt="" />
      </div>
    </div>
  </div>

  <!-- secondpage  -->
  <div class="secondPage">
    <div class="wrapper">
      <div class="left">
        <img src="./assets/svgs/particles.svg" class="particles" alt="" />
        <div class="cards">
          <div class="card">
            <img src="./assets/svgs/secondPage2.svg" alt="" />
          </div>
          <div class="card">
            <img src="./assets/svgs/secondPage1.svg" alt="" />
          </div>
          <div class="card">
            <img src="./assets/svgs/secondPage3.svg" alt="" />
          </div>
        </div>
      </div>
      <div class="right">
        <div class="title textright fade-up">What we do?</div>
        <p class="text textright fade-up">
          We offer essential <strong class="green">Online</strong> Home
          Services, allowing you to hire your preferred handy professional.
        </p>
      </div>
    </div>
  </div>

  <div class="thirdPage">
    <div class="wrapper">
      <div class="left fade-up">
        <div class="text">Handy Man</div>
        <div class="heading count" id="handyman-count">50</div>
      </div>
      <div class="right fade-up">
        <div class="text">Customers</div>
        <div class="heading count" id="customer-count">2,469</div>
      </div>
    </div>
    <img src="./assets/svgs/blueslope.svg" class="bluewave" alt="" />
  </div>
  <div class="services-page">
    <div class="wrapper">
      <div class="heading textcenter serviceText">Our Services</div>
      <div class="services" id="services">
        <div class="service fade-up">
          <h3>Plumbing</h3>
          <div class="text">
            Hire a plumber to fix your pipeline that is not working for days!
          </div>
          <div class="service-icon">
            <img class="icon" src="./assets/icons/plumber.png" alt="" />
          </div>
        </div>

        <div class="service fade-up">
          <h3>Electrician</h3>
          <div class="text">
            Hire a plumber to fix your pipeline that is not working for days!
          </div>
          <div class="service-icon">
            <img class="icon" src="./assets/icons/electrician.png" alt="" />
          </div>
        </div>
        <div class="service fade-up">
          <h3>Painter</h3>
          <div class="text">
            Hire a plumber to fix your pipeline that is not working for days!
          </div>
          <div class="service-icon">
            <img class="icon" src="./assets/icons/painter.png" alt="" />
          </div>
        </div>

        <div class="service fade-up">
          <h3>Cook</h3>
          <div class="text">
            Hire a plumber to fix your pipeline that is not working for days!
          </div>
          <div class="service-icon">
            <img class="icon" src="./assets/icons/cooking.png" alt="" />
          </div>
        </div>

        <div class="service fade-up">
          <h3>Cleaning</h3>
          <div class="text">
            Hire a plumber to fix your pipeline that is not working for days!
          </div>
          <div class="service-icon">
            <img class="icon" src="./assets/icons/cleaning.png" alt="" />
          </div>
        </div>

        <div class="service fade-up">
          <h3>Health Care</h3>
          <div class="text">
            Hire a plumber to fix your pipeline that is not working for days!
          </div>
          <div class="service-icon">
            <img class="icon" src="./assets/icons/nurse.png" alt="" />
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="forthPage">
    <div class="wrapper">
      <div class="left">
        <div class="title">
          What our customers <br />
          say about us
        </div>
        <img src="./assets/svgs/customer.svg" alt="" />
      </div>
      <div class="right fade-up">
        <div class="slider">
          <div class="slides" id="slides">
            <div class="slide">
              <div class="text">
                "He did an amazing work with the plumbing. That's waht madhu
                said. She said Pipe was rusty but he did a great job."
              </div>
              <div class="customer">
                <img src="./assets/images/default.png" alt="" />
                <div class="vertical">
                  <div class="title">Rajesh Hamal</div>
                  <div class="ratings">
                    <span class="star rate">⭐</span>
                    <span class="star rate">⭐</span>
                    <span class="star rate">⭐</span>
                    <span class="star rate">⭐</span>
                    <span class="star">⭐</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="slide">
              <div class="text">
                "He did an amazing work with the plumbing. That's waht madhu
                said. She said Pipe was rusty but he did a great job."
              </div>
              <div class="customer">
                <img src="./assets/images/default.png" alt="" />
                <div class="vertical">
                  <div class="title">Rajesh Hamal</div>
                  <div class="ratings">
                    <span class="star rate">⭐</span>
                    <span class="star rate">⭐</span>
                    <span class="star rate">⭐</span>
                    <span class="star rate">⭐</span>
                    <span class="star">⭐</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="slide">
              <div class="text">
                "He did an amazing work with the plumbing. That's waht madhu
                said. She said Pipe was rusty but he did a great job."
              </div>
              <div class="customer">
                <img src="./assets/images/default.png" alt="" />
                <div class="vertical">
                  <div class="title">Rajesh Hamal</div>
                  <div class="ratings">
                    <span class="star rate">⭐</span>
                    <span class="star rate">⭐</span>
                    <span class="star rate">⭐</span>
                    <span class="star rate">⭐</span>
                    <span class="star">⭐</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="indexes">
            <div class="index"></div>
            <div class="arrows">
              <img src="./assets/svgs/arrowLeft.svg" alt="" class="arrow" onclick="slideLeft()" />
              <img src="./assets/svgs/arrowRight.svg" alt="" class="arrow" onclick="slideRight()" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <footer></footer>
  <script src="src/js/main.js"></script>
  <script src="src/js/navbar.js"></script>

  <script src="src/js/home/home.js"></script>
</body>

</html>