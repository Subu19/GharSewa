<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Ghar Sewa</title>
    <link rel="icon" type="image/x-icon" href="/assets/svgs/logo.ico" />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="src/css/navbar.css">
    <link rel="stylesheet" href="/src/css/main.css">
    <link rel="stylesheet" href="/src/css/location/location.css">
    <?php include "src/views/components/mapApi.php" ?>


</head>

<body>
    <img class="wave1" src="./assets/svgs/wave1.svg" alt="" />
    <!-- include navbar  -->
    <?php include "src/views/components/navbar.php" ?>
    <!-- //landing page  -->
    <div class="locationPage">
        <div class="wrapper">
            <div class="heading textcenter">Are we around you?</div>
            <div class=" text wrapper textcenter">We are geradually expanding our locations around Nepal. Lets grow together.</div>
            <div class="containner">
                <img class="image" src="/assets/svgs/geolocation.svg" alt="">
            </div>
            <div class="wrapper">
                <div id="map"></div>
            </div>
        </div>
    </div>
    <script>
        initMap();
        async function initMap() {
            // The location of Uluru
            const position = {
                lat: 27.72959096441606,
                lng: 85.2956671220969
            };
            const position2 = {
                lat: 27.675301,
                lng: 85.282045
            };
            // Request needed libraries.
            //@ts-ignore
            const {
                Map
            } = await google.maps.importLibrary("maps");
            const {
                AdvancedMarkerElement
            } = await google.maps.importLibrary("marker");

            // The map, centered at Uluru
            map = new Map(document.getElementById("map"), {
                zoom: 13,
                center: position,
                mapId: "DEMO_MAP_ID",
            });

            // The marker, positioned at Uluru
            const marker = new AdvancedMarkerElement({
                map: map,
                position: position,
                title: "Subash",
            });
            const marker2 = new AdvancedMarkerElement({
                map: map,
                position: position2,
                title: "Sagar",
            });
            document.getElementById("map").style.display = "block";
        }
    </script>

    <script src="src/js/main.js"></script>
    <script src="src/js/navbar.js"></script>

</body>

</html>