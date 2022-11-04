<?php 

require_once '../dbconfig/connection.php';

include 'testinsertdata.php';



if( isset($_POST["login"])) {

	insert($_POST);

}





 ?>



 <!DOCTYPE html>

<html>

<head>

	<title>Masukan Data</title>

	<style>

		label {

			display: block;

		}

	</style>

</head>

<body>

	<h3>Masukan Data</h3>



	<form action="" method="post">

		<ul>

			<li>

				<label for="ssid" id="ssid">ssid</label>

				<input type="text" name="ssid">

			</li>

			<li>

				<label for="rssi" id="rssi">rssi</label>

				<input type="rssi" name="rssi">

			</li>

			<li>

				<label for="lat" id="lat">lat</label>

				<input type="lat" name="lat">

			</li>

			<li>

				<label for="longitude" id="longitude">longitude</label>

				<input type="longitude" name="longitude">

			</li>

			

			<li>

				<button type="submit" name="login">Login</button>

			</li>

		</ul>







	</form>

</body>

</html>