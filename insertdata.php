<?php
require_once 'dbconfig/connection.php';

$respon = array();

if ($con) {
    if ($_POST['lat'] == '1') {
        $ssid = rtrim($_POST['ssid'], ",");
        $query = "INSERT INTO main_data (ssid,rssi,lat,longitude) 
	    VALUES $ssid";
    } else {
        $ssid = $_POST['ssid'];
        $rssi = $_POST['rssi'];
        $lat = $_POST['lat'];
        $longitude = $_POST['longitude'];
        $query = "INSERT INTO main_data (ssid,rssi,lat,longitude) 
	    VALUES ('$ssid','$rssi','$lat','$longitude')";
    }
    $result = mysqli_query($con, $query);

    array_push($respon, array('status' => 'OK'));
} else {
    array_push($respon, array('status' => 'FAILED2'));
}


$res = array("server" => $respon);

echo json_encode($res);
echo " " . mysqli_affected_rows($con) . " baris Ditambahkan";

mysqli_close($con);
