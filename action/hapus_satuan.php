<?php 
require 'function.php';

$ssid = $_GET["ssid"];
$lat = $_GET["lat"];
$long = $_GET["long"];
$row_affected = hapus_satuan($ssid, $lat, $long);

if ($row_affected > 0) {
	echo "
	<script>
		alert('Data $ssid berhasil dihapus!! $row_affected baris data dihapus');
		document.location.href = '../pages/datalist.php';
	</script>
		";
	}
else{
	echo "
	<script>
		alert('$ssid Data GAGAL dihapus!!');
	</script>
	";
    echo $ssid;
}

 ?>