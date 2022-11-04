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



$data = query("SELECT * FROM main_data"); 





 ?>



 <!DOCTYPE html>

 <html>

 <head>

 	<title>Halaman Admin</title>

 </head>

 <body>



 	<h1>Daftar Mobil</h1>



 	<a href="tambah.php">Tambah data mahasiswa</a>

 	<a href="logout.php">Logout</a>

 	<br><br>



 	<form action="" method="post" >

 		<input type="text" name="keyword" size="30" autofocus="" placeholder="Masukan Keyword Pencarian. . ." autocomplete="off" >

 		<button type="submit" name="cari">Cari</button>

 	</form>

 	<br>



 	<table border="1" cellpadding="10" cellspacing="0">

 		<tr>



 			<th>ssid</th>

 			<th>rssi</th>

 			<th>latitude</th>

 			<th>longitude</th>

 		</tr>



 		<?php $i=1; ?>

 	

 		<?php foreach ($data as $row) : ?>

 		<tr>



 			<td><?php echo $row["ssid"]; ?></td>

 			<td><?php echo $row["rssi"]; ?></td>

 			<td><?php echo $row["lat"] ?></td>

 			<td><?php echo $row["longitude"] ?></td>

 		</tr>

 		<?php $i++; ?>

 		<?php endforeach; ?>

 		



 	</table>



 </body>

 </html>