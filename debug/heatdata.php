<?php 

include '../dbconfig/connection.php';



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



 <!DOCTYPE html>

 <html>

 <head>

 	<title>Halaman Admin</title>

 	<style type="text/css">

		#map { height: 80vh;

		width: 100vh;

		}

	</style>

 		<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />

<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

 </head>

 <body>



 	<h1>Daftar Titik</h1>

 	

 	

 	

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



	 <script src="..\heat\Leaflet.heat-gh-pages\dist\leaflet-heat.js"></script>



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

 			 ?><br>	<?php 

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

 			





 		



 		



 </body>

 </html>