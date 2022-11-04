<!doctype html>

<?php
session_start();

include '../dbconfig/connection.php';

if (!isset($_SESSION["login"])) {
    echo "
				<script>
			alert('Silahkan Login Terlebih Dahulu!!');
            document.location.href = 'map.php';
		</script>
			";
}

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
$data2 = query("SELECT * FROM main_data");
$data = query("SELECT REPLACE(ssid, '\"','') as ssid, rssi, lat, longitude FROM main_data");
$datassid = query("SELECT DISTINCT(REPLACE(ssid, '\"','')) as ssid FROM main_data");
$datassid2 = query("SELECT DISTINCT ssid as ssid FROM main_data");
?>

<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>Wifi Map</title>
    <link rel="manifest" href="../manifest.webmanifest">
    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css" />
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
    <style type="text/css">
        .disclaimer {
            visibility: hidden !important;
        }
    </style>
</head>

<body class="bg-light">
    <!-- navbar -->
    <nav class="navbar sticky-top navbar-expand-lg navbar-dark bg-danger ">
        <div class="container">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <h3><a href="../" class="nav-link">UNSRAT</a></h3>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="../action/logout.php"><button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#Modal">
                            Logout
                        </button></a>
                </li>
            </ul>
        </div>
    </nav>

    <nav class="navbar navbar-expand navbar-dark 
    bg-danger fixed-bottom">
        <ul class="navbar-nav nav-justified w-100">
            <li class="nav-item">
                <a href="../" class="nav-link ">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house-fill" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="m8 3.293 6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293l6-6zm5-.793V6l-2-2V2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5z" />
                        <path fill-rule="evenodd" d="M7.293 1.5a1 1 0 0 1 1.414 0l6.647 6.646a.5.5 0 0 1-.708.708L8 2.207 1.354 8.854a.5.5 0 1 1-.708-.708L7.293 1.5z" />
                    </svg>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link active">Map</a>
            </li>
            <li class="nav-item">
                <a href="speed.html" class="nav-link">SpeedTest</a>
            </li>
        </ul>
    </nav>

    <!-- main content -->
    <!-- <div class="box bg-white mt-5"> -->
    <div class="p-3">
        <h2 class="title">Daftar Titik</h2>
    </div>

    <div class="p-3 mb-5">
        <table id="myTable" class="table table-striped" style="width:100%">
            <thead>
                <tr>
                    <th>Ssid</th>
                    <th>Rssi</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; ?>
                <?php foreach ($data as $row) : ?>
                    <tr>
                        <td><?php echo $row["ssid"]; ?></td>
                        <td><?php echo $row["rssi"]; ?></td>
                        <td><?php echo $row["lat"]; ?></td>
                        <td><?php echo $row["longitude"]; ?></td>
                        <td width="20%"><a href="../action/hapus_satuan.php?ssid=<?= $row["ssid"]; ?>&lat=<?= $row["lat"]; ?>&long=<?= $row["longitude"]; ?>"><button class="btn btn-danger">Hapus Data</button></a></td>
                    </tr>
                    <?php $i++; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>


    <div class="p-3 mb-5">
        <table id="mySsid" class="table table-striped" style="width:100%">
            <thead>
                <tr>
                    <th>Ssid</th>
                    <th width="20%"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($datassid as $row) : $ssid = $row["ssid"]; ?>
                    <tr>
                        <td><?php echo $ssid; ?></td>
                        <td width="20%"><a href="../action/hapus.php?ssid=<?php echo $ssid; ?>"><button class="btn btn-danger">Hapus Data</button></a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>




    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#mySsid').DataTable();
        });
    </script>
    <script src="/js/app.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js" integrity="sha256-eTyxS0rkjpLEo16uXTS0uVCS4815lc40K2iVpWDvdSY=" crossorigin="anonymous"></script>
</body>

</html>