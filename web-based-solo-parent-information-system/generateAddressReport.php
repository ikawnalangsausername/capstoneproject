<!-- Include database connection and functions -->
<?php include 'includes/connection.php'; ?>
<?php include 'includes/functions.php'; ?>

<!-- Page PHP Code -->
<?php 
if (!isSomeoneIsLoggedIn()) {
    header("Location: login.php");
}
?>

<!-- Include page header -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solo Parent Report (Barangay) <?=date("d M Y")?></title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- Roboto Font -->
    <link rel="stylesheet" href="css/robotoFont.css">
    <!-- CSS Reset -->
    <link rel="stylesheet" href="css/styleReset.css">
    <!-- CSS -->
    <link rel="stylesheet" href="css/baseStyle.css">
    <!-- Font Awesome -->
    <script src="js/fontawesome.js" crossorigin="anonymous"></script>
    <!-- Chart.js -->
    <script src="js/chart.js"></script>
    <!-- DataTables -->
    <link rel="stylesheet" href="css/dataTables.bootstrap5.min.css">
    <style>
        .bondPaper {
            width: 8.5in;
            height: 11in;
            background-image: none !important;
        }
        canvas {
            max-width: 7in;
        }
        .reportBody {
            min-height: 10in;
        }
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }
    </style>
<body>
    <div class="container d-flex justify-content-evenly bondPaper">
        <div class="main-content px-5 pt-4">
            <div class="row mb-3">
                <div class="col border-bottom border-danger pb-2">
                    <h1 class="fs-2 fw-bold">Solo Parents' Report (Barangay)</h1>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col mb-3">
                    <div class="card reportBody">
                        <div class="card-body">
                            <h5 class="card-title text-secondary fw-bolder">Solo Parents' Population Distribution (Based on Barangay)</h5>
                            <canvas id="myChart" style="max-height: calc(8.5in/2); max-width: 7.5in;"></canvas>
                            <h5 class="card-title text-secondary fw-bolder mt-5">Solo Parents' Barangay Population Distribution</h5>
                            <table class="table table-striped">
                                <?php
                                    $populationPerBarangay = [];
                                    for ($i = 0; $i < 18; $i++) {
                                        $id = $i + 1;
                                        $sql = "SELECT COUNT(soloparents.BarangayID)
                                        FROM soloparents 
                                        WHERE BarangayID = $id";
                                        $result = mysqli_query($connection, $sql);
                                        $barangayPopulation = mysqli_fetch_row($result);

                                        array_push($populationPerBarangay, $barangayPopulation[0]);
                                    }
                                    
                                ?>
                                <thead>
                                    <tr>
                                        <th class="text-center fw-bold">Barangay</th>
                                        <th class="text-center fw-bold">Number of Solo Parents</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="w-50">Baclaran</td>
                                        <td><?=$populationPerBarangay[0]?></td>
                                    </tr>
                                    <tr>
                                        <td>Banay Banay</td>
                                        <td><?=$populationPerBarangay[1]?></td>
                                    </tr>
                                    <tr>
                                        <td>Banlic</td>
                                        <td><?=$populationPerBarangay[2]?></td>
                                    </tr>
                                    <tr>
                                        <td>Barangay Dos</td>
                                        <td><?=$populationPerBarangay[3]?></td>
                                    </tr>
                                    <tr>
                                        <td>Barangay Tres</td>
                                        <td><?=$populationPerBarangay[4]?></td>
                                    </tr>
                                    <tr>
                                        <td>Barangay Uno</td>
                                        <td><?=$populationPerBarangay[5]?></td>
                                    </tr>
                                    <tr>
                                        <td>Bigaa</td>
                                        <td><?=$populationPerBarangay[6]?></td>
                                    </tr>
                                    <tr>
                                        <td>Butong</td>
                                        <td><?=$populationPerBarangay[7]?></td>
                                    </tr>
                                    <tr>
                                        <td>Casile</td>
                                        <td><?=$populationPerBarangay[8]?></td>
                                    </tr>
                                    <tr>
                                        <td>Diezmo</td>
                                        <td><?=$populationPerBarangay[9]?></td>
                                    </tr>
                                </tbody>
                            </table>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </div>
    <div class="container d-flex justify-content-evenly bondPaper">
        <div class="main-content px-5 pt-4">
            <div class="row mb-3">
                <div class="col border-bottom border-danger pb-2">
                    <h1 class="fs-2 fw-bold">Solo Parents' Report (Barangay)</h1>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col mb-3">
                    <div class="card reportBody">
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-center fw-bold">Barangay</th>
                                        <th class="text-center fw-bold">Number of Solo Parents</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="w-50">Gulod</td>
                                        <td><?=$populationPerBarangay[10]?></td>
                                    </tr>
                                    <tr>
                                        <td>Mamatid</td>
                                        <td><?=$populationPerBarangay[11]?></td>
                                    </tr>
                                    <tr>
                                        <td>Marinig</td>
                                        <td><?=$populationPerBarangay[12]?></td>
                                    </tr>
                                    <tr>
                                        <td>Niugan</td>
                                        <td><?=$populationPerBarangay[13]?></td>
                                    </tr>
                                    <tr>
                                        <td>Pittland</td>
                                        <td><?=$populationPerBarangay[14]?></td>
                                    </tr>
                                    <tr>
                                        <td>Pulo</td>
                                        <td><?=$populationPerBarangay[15]?></td>
                                    </tr>
                                    <tr>
                                        <td>Sala</td>
                                        <td><?=$populationPerBarangay[16]?></td>
                                    </tr>
                                    <tr>
                                        <td>San Isidro</td>
                                        <td><?=$populationPerBarangay[17]?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">TOTAL</td>
                                        <td class="fw-bold"><?=getTotalPopulationOfSoloParents()?></td>
                                    </tr>
                                </tbody>
                            </table>
                            <h5 class="card-title text-secondary fw-bolder mt-5">Findings</h5>
                            <p>The report concluded that majority of solo parents' population are from <b class="fw-bold">BRGY. <?php
                                $maxVal = max($populationPerBarangay);
                                $maxKey = array_search($maxVal, $populationPerBarangay);
                                $barangay = '';
                                switch($maxKey) {
                                    case 0: 
                                        $barangay = 'baclaran';
                                    break;
                                    case 1: 
                                        $barangay = 'banay banay';
                                    break;
                                    case 2: 
                                        $barangay = 'banlic';
                                    break;
                                    case 3: 
                                        $barangay = 'barangay dos';
                                    break;
                                    case 4: 
                                        $barangay = 'barangay tres';
                                    break;
                                    case 5: 
                                        $barangay = 'barangay uno';
                                    break;
                                    case 6: 
                                        $barangay = 'bigaa';
                                    break;
                                    case 7: 
                                        $barangay = 'butong';
                                    break;
                                    case 8: 
                                        $barangay = 'casile';
                                    break;
                                    case 9: 
                                        $barangay = 'diezmo';
                                    break;
                                    case 10: 
                                        $barangay = 'gulod';
                                    break;
                                    case 11: 
                                        $barangay = 'mamatid';
                                    break;
                                    case 12: 
                                        $barangay = 'marinig';
                                    break;
                                    case 13: 
                                        $barangay = 'niugan';
                                    break;
                                    case 14: 
                                        $barangay = 'pittland';
                                    break;
                                    case 15: 
                                        $barangay = 'pulo';
                                    break;
                                    case 16: 
                                        $barangay = 'sala';
                                    break;
                                    case 17: 
                                        $barangay = 'san isidro';
                                    break;
                                }
                                echo strtoupper($barangay);
                            ?>.</b></p>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </div>
<script>
	const ctx = document.getElementById('myChart').getContext('2d');
	new Chart(ctx, {
		type: 'pie',
		data: {
			labels: [<?= getBarangayNames()?>],
			datasets: [{
				label: 'Population',
				data: [
                    <?= getPopulationPerBarangay();?>
				],
                backgroundColor: [
                    '#FF7000',
                    '#54BAB9',
                    '#C8FFD4',
                    '#90C8AC',
                    '#5F7161',
                    '#FCF9BE',
                    '#DBA39A',
                    '#AD8B73',
                    '#FAAB78',
                    '#FFB9B9',
                    '#EF4B4B',
                    '#874C62',
                    '#FF8DC7',
                    '#BA94D1',
                    '#11324D',
                    '#D1D1D1',
                    '#41444B',
                    ],
				},
			],
		},
        options: {},
	});
</script>
<script>
    setTimeout(function(){
        window.print();
    }, 900);
    window.onafterprint = function(){
        location.replace("generateSoloParentReport.php?generationSuccessful");
    }
</script>
<!-- Include page footer
<?php include 'footer.php'; ?>