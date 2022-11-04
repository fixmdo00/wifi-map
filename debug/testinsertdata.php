<?php 

require_once '../dbconfig/connection.php';



function insert($data) {



$respon = array();

global $con;

if ($con) {

	$ssid = $data['ssid'];

	$rssi = $data['rssi'];

	$lat = $data['lat'];

	$longitude = $data['longitude'];



	$query = "INSERT INTO main_data (ssid,rssi,lat,longitude) 

	VALUES ('$ssid','$rssi','$lat','$longitude')";



	$result = mysqli_query($con,$query);



	if ( $ssid != "" && $rssi != "" && $lat != "" && $longitude != "") {

		array_push($respon, array('status' => 'OK'));

	} 

	else {

		array_push($respon, array('status' => 'FAILED'));

	}

	echo $result;

}



else {

	array_push($respon, array('status' => 'FAILED'));

}



echo json_encode(array("server_response" => $respon));

mysqli_close($con);

}

 ?>