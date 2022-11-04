<?php
include '../dbconfig/connection.php';
global $con;

function hapus($ssid) {
	global $con;
	$query = "DELETE FROM main_data WHERE ssid = '$ssid'";
	mysqli_query($con, $query);
	return mysqli_affected_rows($con);
	}

function hapus_satuan($ssid, $lat, $long){
	global $con;
	$query = "DELETE FROM main_data WHERE ssid LIKE '%$ssid%' AND lat LIKE '%$lat%' AND longitude LIKE '%$long%'";
	mysqli_query($con, $query);
	return mysqli_affected_rows($con);
}




?>