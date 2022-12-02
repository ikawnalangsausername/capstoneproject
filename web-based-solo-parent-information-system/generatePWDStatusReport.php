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
    <title>Solo Parent Report (PWD Status) <?=date("d M Y")?></title>
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
                    <h1 class="fs-2 fw-bold">Solo Parents' Report (PWD Status)</h1>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col mb-3">
                    <div class="card reportBody">
                        <div class="card-body">
                            <h5 class="card-title text-secondary fw-bolder">Solo Parents' Population Distribution (Based on PWD Status) </h5>
                            <canvas id="myChart" style="max-height: calc(8.5in/2);"></canvas>
                            <h5 class="card-title text-secondary fw-bolder mt-4">Solo Parents' PWD Status Distribution</h5>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th class="fw-bold text-center">PWD Category</th>
                                        <th class="fw-bold text-center">Population</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $populationOfEverySoloParentsPWDStatus = getPopulationOfEverySoloParentsPWDStatus();?>
                                    <tr>
                                        <td>Person with psychosocial disability</td>
                                        <td><?=$populationOfEverySoloParentsPWDStatus[0]?></td>
                                    </tr>
                                    <tr>
                                        <td>Person that is disabled due to chronic illness</td>
                                        <td><?=$populationOfEverySoloParentsPWDStatus[1]?></td>
                                    </tr>
                                    <tr>
                                        <td>Person with learning disability</td>
                                        <td><?=$populationOfEverySoloParentsPWDStatus[2]?></td>
                                    </tr>
                                    <tr>
                                        <td>Person with mental disability</td>
                                        <td><?=$populationOfEverySoloParentsPWDStatus[3]?></td>
                                    </tr>
                                    <tr>
                                        <td>Person with visual disability</td>
                                        <td><?=$populationOfEverySoloParentsPWDStatus[4]?></td>
                                    </tr>
                                    <tr>
                                        <td>Person with orthopedic disability</td>
                                        <td><?=$populationOfEverySoloParentsPWDStatus[5]?></td>
                                    </tr>
                                    <tr>
                                        <td>Person with communication disability</td>
                                        <td><?=$populationOfEverySoloParentsPWDStatus[6]?></td>
                                    </tr>
                                    <tr>
                                        <td>Not PWD</td>
                                        <td><?=$populationOfEverySoloParentsPWDStatus[7]?></td>
                                    </tr>
                                    <tr>
                                        <th class="fw-bold">Total</th>
                                        <th class="fw-bold"><?=getTotalPopulationOfSoloParents();?></th>
                                    </tr>
                                </tbody>
                            </table>
                            <h5 class="card-title text-secondary fw-bolder mt-4">Findings</h5>
                            <p>The report concluded that majority of the solo parents's children are <b class="fw-bold"><?php 
                                $maxVal = max($populationOfEverySoloParentsPWDStatus);
                                $maxKey = array_search($maxVal, $populationOfEverySoloParentsPWDStatus);

                                switch($maxKey) {
                                    case 0: 
                                        echo strtoupper("Person with psychosocial disability");
                                    break;
                                    case 1: 
                                        echo strtoupper("Person that is disabled due to chronic illness");
                                    break;
                                    case 2: 
                                        echo strtoupper("Person with learning disability");
                                    break;
                                    case 3: 
                                        echo strtoupper("Person with mental disability");
                                    break;
                                    case 4: 
                                        echo strtoupper("Person with visual disability");
                                    break;
                                    case 5: 
                                        echo strtoupper("Person with orthopedic disability");
                                    break;
                                    case 6: 
                                        echo strtoupper("Person with communication disability");
                                    break;
                                    case 7: 
                                        echo strtoupper("Not PWD");
                                    break;
                                }
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
			labels: [
                'Person with psychosocial disability',
                'Person that is disabled due to chronic illness',
                'Person with learning disability',
                'Person with mental disability',
                'Person with visual disability',
                'Person with orthopedic disability',
                'Person with communication disability',
                'Not PWD',
            ],
			datasets: [{
				label: 'Population',
				data: [
                    <?php
                        $populationOfEverySoloParentsPWDStatus = getPopulationOfEverySoloParentsPWDStatus();
                        print_r(implode(', ', $populationOfEverySoloParentsPWDStatus));
                    ?>
				],
                backgroundColor: [
                    '#33a8c7',
                    '#52e3e1',
                    '#a0e426',
                    '#fdf148',
                    '#ffab00',
                    '#f77976',
                    '#f050ae',
                    '#d883ff',
                    '#9336fd',
                    ],
				},
			],
		},
        options: {},
	});
</script>
<script>
    window.onload = function(){
        setTimeout(function(){
        window.print();
    }, 900);
    };
    window.onafterprint = function(){
        location.replace("generateSoloParentReport.php?generationSuccessful");
    }
</script>
<!-- Include page footer
<?php include 'footer.php'; ?>