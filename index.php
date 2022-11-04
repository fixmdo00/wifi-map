<!doctype html>

<?php
session_start();
$_SESSION = [];
session_unset();
session_destroy();
include 'dbconfig/connection.php';

function query($query)
{
    global $con;
    $result = mysqli_query($con, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }

    return $rows;
}

if (isset($_POST["submit"])) {
    $ssid = $_POST["ssid"];
    $data = query("SELECT * FROM main_data WHERE ssid = '\"$ssid\"'  ");
    $map = query("SELECT max(lat) as lat, longitude FROM main_data WHERE ssid = '\"$ssid\"'  ");
    foreach ($map as $point) :

        $mlat = $point["lat"];
        $mlong = $point["longitude"];
    endforeach;
} else {
    $data = query("SELECT * FROM main_data");
    $map = query("SELECT lat, longitude FROM main_data WHERE lat = (SELECT max(lat) FROM main_data)");
    foreach ($map as $point) :

        $mlat = $point["lat"];
        $mlong = $point["longitude"];
    endforeach;
    //  echo $mlat;
    //  echo "<br>";
    //  echo $mlong;
}

$datassid = query("SELECT DISTINCT(REPLACE(ssid, '\"','')) as ssid FROM main_data");

?>

<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <title>Wifi Map</title>
    <style type="text/css">
        #map {
            height: 70vh;
            width: 74vw;
        }

        @media screen and (max-width: 600px) {
            #map {
                height: 50vh;
            }
        }

        .disclaimer {
            visibility: hidden !important;
        }
    </style>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="manifest" href="./manifest.webmanifest">
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
</head>

<body class="bg-light">
    <nav class="navbar sticky-top navbar-expand-lg navbar-dark bg-danger ">
        <div class="container">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <h3><a href="#" class="nav-link active">UNSRAT</a></h3>
                </li>
            </ul>
        </div>
    </nav>
    <nav class="navbar navbar-expand navbar-dark 
    bg-danger fixed-bottom">
        <ul class="navbar-nav nav-justified w-100">
            <li class="nav-item">
                <a href="#" class="nav-link active">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house-fill" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="m8 3.293 6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293l6-6zm5-.793V6l-2-2V2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5z" />
                        <path fill-rule="evenodd" d="M7.293 1.5a1 1 0 0 1 1.414 0l6.647 6.646a.5.5 0 0 1-.708.708L8 2.207 1.354 8.854a.5.5 0 1 1-.708-.708L7.293 1.5z" />
                    </svg>
                </a>
            </li>
            <li class="nav-item">
                <a href="pages/map.php" class="nav-link">Map</a>
            </li>
            <li class="nav-item">
                <a href="pages/speed.html" class="nav-link">SpeedTest</a>
            </li>
        </ul>
    </nav>
    <div class="main ">
        <div class="container">
            <div class="box bg-white mt-5 p-5">
                <h2>Halo,</h2>
                <h5>Selamat datang di tampilan peta jaringan wifi Unsrat</h5>
            </div>

            <div class="box bg-white mt-5 p-5">
                <a href="pages/map.php" style="text-decoration:none">
                    <h2 class="title">Heat Map</h2>
                </a>
                <form action="" method="post">
                    <label for="ssid">Pilih SSID : </label>
                    <select name="ssid" id="ssid">
                        <?php foreach ($datassid as $row) : ?>
                            <option value="<?= $row["ssid"] ?>"><?= $row["ssid"] ?></option>
                        <?php endforeach ?>
                    </select>
                    <br>
                    <input type="submit" value="submit" name="submit">
                </form>
                <br>
                <a href="pages/map.php" style="text-decoration:none">
                    <div id="map"></div>
                </a>
                <script src="..\heat\Leaflet.heat-gh-pages\dist\leaflet-heat.js"></script>
                <br>



                <script>
                    var mymap = L.map('map', {
                        minZoom: 2,
                        maxZoom: 20
                    });
                    var Thunderforest_Pioneer = L.tileLayer('https://{s}.tile.thunderforest.com/pioneer/{z}/{x}/{y}.png?apikey={apikey}', {
                        attribution: '&copy; <a href="http://www.thunderforest.com/">Thunderforest</a>, &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                        apikey: 'b9e825c93fa74e28a6cb17efed9828b9',
                        maxZoom: 22
                    });

                    var Esri_WorldImagery = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                        attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
                    });

                    var OpenStreetMap_France = L.tileLayer('https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', {
                        maxZoom: 22,
                        attribution: '&copy; OpenStreetMap France | &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                    });

                    OpenStreetMap_France.addTo(mymap);
                    mymap.setView([<?= $mlat ?>, <?= $mlong ?>], 18);

                    mymap.createPane('circle1');
                    mymap.getPane('circle1').style.zIndex = 410;
                    mymap.getPane('circle1').style.opacity = 1;

                    mymap.createPane('circle2');
                    mymap.getPane('circle2').style.zIndex = 409;
                    mymap.getPane('circle2').style.opacity = 1;

                    mymap.createPane('circle3');
                    mymap.getPane('circle3').style.zIndex = 408;
                    mymap.getPane('circle3').style.opacity = 1;

                    mymap.createPane('circle4');
                    mymap.getPane('circle4').style.zIndex = 407;
                    mymap.getPane('circle4').style.opacity = 1;

                    mymap.createPane('circle5');
                    mymap.getPane('circle5').style.zIndex = 406;
                    mymap.getPane('circle5').style.opacity = 1;

                    mymap.createPane('circle6');
                    mymap.getPane('circle6').style.zIndex = 405;
                    mymap.getPane('circle6').style.opacity = 1;

                    mymap.createPane('circle7');
                    mymap.getPane('circle7').style.zIndex = 404;
                    mymap.getPane('circle7').style.opacity = 1;

                    mymap.createPane('circle8');
                    mymap.getPane('circle8').style.zIndex = 403;
                    mymap.getPane('circle8').style.opacity = 1;

                    mymap.createPane('circle9');
                    mymap.getPane('circle9').style.zIndex = 402;
                    mymap.getPane('circle9').style.opacity = 1;

                    mymap.createPane('circle10');
                    mymap.getPane('circle10').style.zIndex = 401;
                    mymap.getPane('circle10').style.opacity = 1;


                    <?php
                    $pane = "";
                    $color = "";

                    foreach ($data as $row) :
                        if (
                            $row["rssi"] == '-40' || $row["rssi"] == '-39' || $row["rssi"] == '-38'
                            || $row["rssi"] == '-37' || $row["rssi"] == '-36' || $row["rssi"] == '-35' || $row["rssi"] == '-34' || $row["rssi"] == '-33' || $row["rssi"] == '-32' || $row["rssi"] == '-32' || $row["rssi"] == '-31'
                        ) {
                            $color = "#f6e622";
                            $pane = "1";
                        }

                        if (
                            $row["rssi"] == '-50' || $row["rssi"] == '-49' || $row["rssi"] == '-48'
                            || $row["rssi"] == '-47' || $row["rssi"] == '-46' || $row["rssi"] == '-45' || $row["rssi"] == '-44' || $row["rssi"] == '-43' || $row["rssi"] == '-42' || $row["rssi"] == '-42' || $row["rssi"] == '-41'
                        ) {
                            $color = "#addc2f";
                            $pane = "2";
                        }


                        if (
                            $row["rssi"] == '-60' || $row["rssi"] == '-59' || $row["rssi"] == '-58'
                            || $row["rssi"] == '-57' || $row["rssi"] == '-56' || $row["rssi"] == '-55' || $row["rssi"] == '-54' || $row["rssi"] == '-53' || $row["rssi"] == '-52' || $row["rssi"] == '-52' || $row["rssi"] == '-51'
                        ) {
                            $color = "#68cc5c";
                            $pane = "3";
                        } else if (
                            $row["rssi"] == '-70' || $row["rssi"] == '-69' || $row["rssi"] == '-68'
                            || $row["rssi"] == '-67' || $row["rssi"] == '-66' || $row["rssi"] == '-65' || $row["rssi"] == '-64' || $row["rssi"] == '-63' || $row["rssi"] == '-62' || $row["rssi"] == '-62' || $row["rssi"] == '-61'
                        ) {
                            $color = "#32b47a";
                            $pane = "4";
                        } else if (
                            $row["rssi"] == '-80' || $row["rssi"] == '-79' || $row["rssi"] == '-78'
                            || $row["rssi"] == '-77' || $row["rssi"] == '-76' || $row["rssi"] == '-75' || $row["rssi"] == '-74' || $row["rssi"] == '-73' || $row["rssi"] == '-72' || $row["rssi"] == '-72' || $row["rssi"] == '-71'
                        ) {
                            $color = "#1f9c89";
                            $pane = "5";
                        } else if (
                            $row["rssi"] == '-90' || $row["rssi"] == '-89' || $row["rssi"] == '-88'
                            || $row["rssi"] == '-87' || $row["rssi"] == '-86' || $row["rssi"] == '-85' || $row["rssi"] == '-84' || $row["rssi"] == '-83' || $row["rssi"] == '-82' || $row["rssi"] == '-82' || $row["rssi"] == '-81'
                        ) {
                            $color = "#26838e";
                            $pane = "6";
                        }

                        if (
                            $row["rssi"] == '-100' || $row["rssi"] == '-99' || $row["rssi"] == '-98'
                            || $row["rssi"] == '-97' || $row["rssi"] == '-96' || $row["rssi"] == '-95' || $row["rssi"] == '-94' || $row["rssi"] == '-93' || $row["rssi"] == '-92' || $row["rssi"] == '-92' || $row["rssi"] == '-91'
                        ) {
                            $color = "#31678d";
                            $pane = "7";
                        }

                        if (
                            $row["rssi"] == '-110' || $row["rssi"] == '-109' || $row["rssi"] == '-108'
                            || $row["rssi"] == '-107' || $row["rssi"] == '-106' || $row["rssi"] == '-105' || $row["rssi"] == '-104' || $row["rssi"] == '-103' || $row["rssi"] == '-102' || $row["rssi"] == '-102' || $row["rssi"] == '-101'
                        ) {
                            $color = "#3e4b89";
                            $pane = "8";
                        }

                        if (
                            $row["rssi"] == '-120' || $row["rssi"] == '-119' || $row["rssi"] == '-118'
                            || $row["rssi"] == '-117' || $row["rssi"] == '-116' || $row["rssi"] == '-115' || $row["rssi"] == '-114' || $row["rssi"] == '-113' || $row["rssi"] == '-112' || $row["rssi"] == '-112' || $row["rssi"] == '-111'
                        ) {
                            $color = "#472b79";
                            $pane = "9";
                        }

                        if (
                            $row["rssi"] == '-130' || $row["rssi"] == '-129' || $row["rssi"] == '-128'
                            || $row["rssi"] == '-127' || $row["rssi"] == '-126' || $row["rssi"] == '-125' || $row["rssi"] == '-124' || $row["rssi"] == '-123' || $row["rssi"] == '-122' || $row["rssi"] == '-122' || $row["rssi"] == '-121'
                        ) {
                            $color = "#440458";
                            $pane = "10";
                        } else {
                            // $row["rssi"] = "blue"; 
                            // $pane = "2";
                        }
                    ?>
                        var circle = L.circle([<?= $row["lat"] ?>, <?= $row["longitude"] ?>], {
                            stroke: false,
                            colorOpacity: 1,
                            fillColor: '<?= $color ?>',
                            fillOpacity: 1,
                            radius: 2,
                            pane: 'circle<?= $pane ?>'
                        }).addTo(mymap);
                        circle.bindPopup("<?= $row["rssi"] ?> dan pane <?= $pane ?> <?= $row["lat"] ?>, <?= $row["longitude"] ?>");
                    <?php
                    endforeach;
                    ?>
                    mymap.getPanes('circle10', 'circle6').style.opacity = 0.5;
                </script>
            </div>

            <div class="box bg-white mt-5 p-5">
                <h2>Bagian Live Traffic</h2>
                <a href="pages/testspeed.html">Speed Test</a>
                <p>
                    Visual Studio Code comes with Emmet preinstalled. Emmet is a plugin that helps you write HTML and CSS easier using shortcuts.

                    Thanks to Emmet it is really easy to generate lorem ipsum. You no longer have to search for a lorem ipsum online generator.
                    Visual Studio Code comes with Emmet preinstalled. Emmet is a plugin that helps you write HTML and CSS easier using shortcuts.

                    Thanks to Emmet it is really easy to generate lorem ipsum. You no longer have to search for a lorem ipsum online generator.Visual Studio Code comes with Emmet preinstalled. Emmet is a plugin that helps you write HTML and CSS easier using shortcuts.

                    Thanks to Emmet it is really easy to generate lorem ipsum. You no longer have to search for a lorem ipsum online generator.Visual Studio Code comes with Emmet preinstalled. Emmet is a plugin that helps you write HTML and CSS easier using shortcuts.

                    Thanks to Emmet it is really easy to generate lorem ipsum. You no longer have to search for a lorem ipsum online generator.Visual Studio Code comes with Emmet preinstalled. Emmet is a plugin that helps you write HTML and CSS easier using shortcuts.

                    Thanks to Emmet it is really easy to generate lorem ipsum. You no longer have to search for a lorem ipsum online generator.Visual Studio Code comes with Emmet preinstalled. Emmet is a plugin that helps you write HTML and CSS easier using shortcuts.

                    Thanks to Emmet it is really easy to generate lorem ipsum. You no longer have to search for a lorem ipsum online generator.
                </p>
            </div>
        </div>
    </div>

    <script src="/dbconfig/db.js"></script>
    <script src="/js/app.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>

</body>

</html>