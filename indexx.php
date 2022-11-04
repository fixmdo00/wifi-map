<!doctype html>
<?php 
include 'dbconfig/connection.php';

function query($query) {
	global $con;
	$result = mysqli_query($con, $query);
	$rows =[];
	while ( $row = mysqli_fetch_assoc($result)) {
		$rows[] = $row;
	}

	return $rows;
}

if (isset($_POST["submit"])){
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
    echo $mlat;
    echo "<br>";
    echo $mlong;
}

$datassid = query("SELECT DISTINCT(REPLACE(ssid, '\"','')) as ssid FROM main_data"); 

 ?>
<html lang="en">

<head>
  <title>Hello, world!</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
  <!-- Material Icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
  <!-- Material Kit CSS -->
  <link href="assets/css/material-kit.css?v=3.0.0" rel="stylesheet" />
  <link rel="manifest" href="./manifest.webmanifest">
  <style type="text/css">
		#map { height: 80vh;
		width: 80vw;
		}
	</style>
 		<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
</head>

<body>
  <!-- Navbar Transparent -->
  <nav class="navbar navbar-expand-lg position-absolute top-0 z-index-3 w-100 shadow-none my-3 navbar-transparent">
    <div class="container">
      <a class="navbar-brand  text-white " href="https://demos.creative-tim.com/material-kit/presentation" rel="tooltip" title="Designed and Coded by Creative Tim" data-placement="bottom" target="_blank">
        Universitas Sam Ratulangi
      </a>
      <button class="navbar-toggler shadow-none ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon mt-2">
          <span class="navbar-toggler-bar bar1"></span>
          <span class="navbar-toggler-bar bar2"></span>
          <span class="navbar-toggler-bar bar3"></span>
        </span>
      </button>
      <div class="collapse navbar-collapse w-100 pt-3 pb-2 py-lg-0 ms-lg-12 ps-lg-5" id="navigation">
        <ul class="navbar-nav navbar-nav-hover ms-auto">
          <li class="nav-item dropdown dropdown-hover mx-2 ms-lg-6">
            <a class="nav-link ps-2 d-flex cursor-pointer align-items-center" id="dropdownMenuPages2" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="material-icons opacity-6 me-2 text-md">dashboard</i>
              Pages
              <img src="./assets/img/down-arrow-white.svg" alt="down-arrow" class="arrow ms-auto ms-md-2 d-lg-block d-none">
              <img src="./assets/img/down-arrow-dark.svg" alt="down-arrow" class="arrow ms-auto ms-md-2 d-lg-none d-block">
            </a>
          </li>

          <li class="nav-item dropdown dropdown-hover mx-2">
            <a class="nav-link ps-2 d-flex cursor-pointer align-items-center" id="dropdownMenuBlocks" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="material-icons opacity-6 me-2 text-md">view_day</i>
              Sections
              <img src="./assets/img/down-arrow-white.svg" alt="down-arrow" class="arrow ms-auto ms-md-2 d-lg-block d-none">
              <img src="./assets/img/down-arrow-dark.svg" alt="down-arrow" class="arrow ms-auto ms-md-2 d-lg-none d-block">
            </a>
          </li>

          <li class="nav-item dropdown dropdown-hover mx-2">
            <a class="nav-link ps-2 d-flex cursor-pointer align-items-center" id="dropdownMenuDocs" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="material-icons opacity-6 me-2 text-md">article</i>
              Docs
              <img src="./assets/img/down-arrow-white.svg" alt="down-arrow" class="arrow ms-auto ms-md-2 d-lg-block d-none">
              <img src="./assets/img/down-arrow-dark.svg" alt="down-arrow" class="arrow ms-auto ms-md-2 d-lg-none d-block">
            </a>
          </li>
          <li class="nav-item ms-lg-auto my-auto ms-3 ms-lg-0 mt-2 mt-lg-0">
            <a href="https://www.creative-tim.com/product/material-kit" class="btn btn-sm  bg-gradient-primary mb-0 me-1 mt-2 mt-md-0">Free Download</a></li>
        </ul>
      </div>
    </div>
  </nav>
  <!-- End Navbar -->


  <div class="page-header min-vh-80" style="background-image: url('assets/img/10830527_955470174472353_7264131730228488413_o.jpg')">
    <span class="mask bg-gradient-dark opacity-6"></span>
    <div class="container">
      <div class="row">
        <div class="col-md-8 mx-auto">
          <div class="text-center">
            <h1 class="text-white">Wifi NetMap</h1>
            <p class="lead text-white mt-3">Peta Kekatan jaringan WiFI di Universitas Sam Ratulangi</p>
          </div>
        </div>
      </div>
    </div>
  </div>

<!-- main section start -->
  <div class="card card-body shadow-xl mx-3 mx-md-4 mt-n6">
    <div class="container">
      <div class="section text-center">
        <h2 class="title">Daftar Titik</h2>
        <form action="" method="post">
          <label for="ssid">Pilih SSID : </label>
            <select name="ssid" id="ssid">
              <?php foreach ($datassid as $row) : ?>
                <option value="<?=$row["ssid"]?>"><?=$row["ssid"]?></option>
              <?php endforeach ?>
            </select>
          <br>
          <input type="submit" value="submit" name="submit">
        </form>
        <br>

        <div id="map"></div>
        <script src="heat\Leaflet.heat-gh-pages\dist\leaflet-heat.js"></script>
        <br>

        <?php 
          $heatmapArray = array();
          foreach ($data as $row) : 
              if ($row["rssi"] == '-60' || $row["rssi"] == '-59' || $row["rssi"] == '-58'
              || $row["rssi"] == '-57' || $row["rssi"] == '-56' || $row["rssi"] == '-55' || $row["rssi"] == '-54'|| $row["rssi"] == '-53'|| $row["rssi"] == '-52'|| $row["rssi"] == '-52'|| $row["rssi"] == '-51') {
                  $row["rssi"] = 0.8;
              }
              
              else if ($row["rssi"] == '-70' || $row["rssi"] == '-69' || $row["rssi"] == '-68'
              || $row["rssi"] == '-67' || $row["rssi"] == '-66' || $row["rssi"] == '-65' || $row["rssi"] == '-64'|| $row["rssi"] == '-63'|| $row["rssi"] == '-62'|| $row["rssi"] == '-62'|| $row["rssi"] == '-61') {
                  $row["rssi"] = 0.6;
              }
              
              else if ($row["rssi"] == '-80' || $row["rssi"] == '-79' || $row["rssi"] == '-78'
              || $row["rssi"] == '-77' || $row["rssi"] == '-76' || $row["rssi"] == '-75' || $row["rssi"] == '-74'|| $row["rssi"] == '-73'|| $row["rssi"] == '-72'|| $row["rssi"] == '-72'|| $row["rssi"] == '-71') {
                  $row["rssi"] = 0.4;
              }
              
              else if ($row["rssi"] == '-90' || $row["rssi"] == '-89' || $row["rssi"] == '-88'
              || $row["rssi"] == '-87' || $row["rssi"] == '-86' || $row["rssi"] == '-85' || $row["rssi"] == '-84'|| $row["rssi"] == '-83'|| $row["rssi"] == '-82'|| $row["rssi"] == '-82'|| $row["rssi"] == '-81') {
                  $row["rssi"] = 0.2;
              }
              else{
                $row["rssi"] = 1; 
              }
            $heatmpaArray[]="[".$row["lat"].", ".$row["longitude"].", ".$row["rssi"]."]";
        ?>
        <br>	
        <?php 
          endforeach;
          $heatmapData = implode(",", $heatmpaArray);
        ?>
    
        <script>
          var mymap = L.map('map', {
            minZoom: 2,
            maxZoom: 22
          });

          // 	 	L.tileLayer('https://api.maptiler.com/maps/basic/{z}/{x}/{y}@2x.png?key=24LlyCQkTYvmhHv6tzwj', {
          //   	 	attribution: '<a href="https://www.maptiler.com/copyright/" target="_blank">&copy; MapTiler</a> <a href="https://www.openstreetmap.org/copyright" target="_blank">&copy; OpenStreetMap contributors</a>',
          // 		}).addTo(mymap);
              
          var Thunderforest_Pioneer = L.tileLayer('https://{s}.tile.thunderforest.com/pioneer/{z}/{x}/{y}.png?apikey={apikey}', {
            attribution: '&copy; <a href="http://www.thunderforest.com/">Thunderforest</a>, &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            apikey: 'b9e825c93fa74e28a6cb17efed9828b9',
            maxZoom: 22
          });
          Thunderforest_Pioneer.addTo(mymap);
          mymap.setView([ <?=$mlat?>, <?=$mlong?>  ], 20);
          var heat = L.heatLayer([<?=$heatmapData?>], {radius: 30}).addTo(mymap);
        </script>             
      </div>
    </div>
  </div>
<!-- main section end -->

  <footer class="footer pt-5 mt-5">
    <div class="container">
      <div class=" row">
        <div class="col-md-3 mb-4 ms-auto">
          <div>
            <a href="https://www.creative-tim.com/product/material-kit">
              <img src="./assets/img/logo-ct-dark.png" class="mb-3 footer-logo" alt="main_logo">
            </a>
            <h6 class="font-weight-bolder mb-4">Material Kit 2</h6>
          </div>
          <div>
            <ul class="d-flex flex-row ms-n3 nav">
              <li class="nav-item">
                <a class="nav-link pe-1" href="https://www.facebook.com/CreativeTim/" target="_blank">
                  <i class="fab fa-facebook text-lg opacity-8"></i>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link pe-1" href="https://twitter.com/creativetim" target="_blank">
                  <i class="fab fa-twitter text-lg opacity-8"></i>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link pe-1" href="https://dribbble.com/creativetim" target="_blank">
                  <i class="fab fa-dribbble text-lg opacity-8"></i>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link pe-1" href="https://github.com/creativetimofficial" target="_blank">
                  <i class="fab fa-github text-lg opacity-8"></i>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link pe-1" href="https://www.youtube.com/channel/UCVyTG4sCw-rOvB9oHkzZD1w" target="_blank">
                  <i class="fab fa-youtube text-lg opacity-8"></i>
                </a>
              </li>
            </ul>
          </div>
        </div>
        <div class="col-md-2 col-sm-6 col-6 mb-4">
          <div>
            <h6 class="text-sm">Company</h6>
            <ul class="flex-column ms-n3 nav">
              <li class="nav-item">
                <a class="nav-link" href="https://www.creative-tim.com/presentation" target="_blank">
                  About Us
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="https://www.creative-tim.com/templates/free" target="_blank">
                  Freebies
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="https://www.creative-tim.com/templates/premium" target="_blank">
                  Premium Tools
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="https://www.creative-tim.com/blog" target="_blank">
                  Blog
                </a>
              </li>
            </ul>
          </div>
        </div>
        <div class="col-md-2 col-sm-6 col-6 mb-4">
          <div>
            <h6 class="text-sm">Resources</h6>
            <ul class="flex-column ms-n3 nav">
              <li class="nav-item">
                <a class="nav-link" href="https://iradesign.io/" target="_blank">
                  Illustrations
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="https://www.creative-tim.com/bits" target="_blank">
                  Bits & Snippets
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="https://www.creative-tim.com/affiliates/new" target="_blank">
                  Affiliate Program
                </a>
              </li>
            </ul>
          </div>
        </div>
        <div class="col-md-2 col-sm-6 col-6 mb-4">
          <div>
            <h6 class="text-sm">Help & Support</h6>
            <ul class="flex-column ms-n3 nav">
              <li class="nav-item">
                <a class="nav-link" href="https://www.creative-tim.com/contact-us" target="_blank">
                  Contact Us
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="https://www.creative-tim.com/knowledge-center" target="_blank">
                  Knowledge Center
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="https://services.creative-tim.com/?ref=ct-mk2-footer" target="_blank">
                  Custom Development
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="https://www.creative-tim.com/sponsorships" target="_blank">
                  Sponsorships
                </a>
              </li>
            </ul>
          </div>
        </div>
        <div class="col-md-2 col-sm-6 col-6 mb-4 me-auto">
          <div>
            <h6 class="text-sm">Legal</h6>
            <ul class="flex-column ms-n3 nav">
              <li class="nav-item">
                <a class="nav-link" href="https://www.creative-tim.com/knowledge-center/terms-of-service/" target="_blank">
                  Terms & Conditions
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="https://www.creative-tim.com/knowledge-center/privacy-policy/" target="_blank">
                  Privacy Policy
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="https://www.creative-tim.com/license" target="_blank">
                  Licenses (EULA)
                </a>
              </li>
            </ul>
          </div>
        </div>
        <div class="col-12">
          <div class="text-center">
            <p class="text-dark my-4 text-sm font-weight-normal">
              All rights reserved. Copyright © <script>
                document.write(new Date().getFullYear())
              </script> Material Kit by <a href="https://www.creative-tim.com" target="_blank">Creative Tim</a>.
            </p>
          </div>
        </div>
      </div>
    </div>
  </footer>
</body>

</html>
