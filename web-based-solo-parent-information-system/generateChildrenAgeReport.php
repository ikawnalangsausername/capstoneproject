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
    <title>Solo Parent Report (Age of Children) <?=date("d M Y")?></title>
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
                    <h1 class="fs-2 fw-bold">Solo Parents' Report (Age of Children)</h1>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col mb-3">
                    <div class="card reportBody">
                        <div class="card-body">
                            <h5 class="card-title text-secondary fw-bolder">Solo Parents' Population Distribution (Based on Age of Children) </h5>
                            <canvas id="myChart" style="max-height: calc(8.5in/2);"></canvas>
                            <h5 class="card-title text-secondary fw-bolder mt-5">Solo Parents' Age of Children Distribution</h5>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th class="fw-bold text-center">Age of Children</th>
                                        <th class="fw-bold text-center">Population</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $childAgePopulations = getPopulationOfEveryChildAgeGroup();?>
                                    <tr>
                                        <td>Infant (Ages 3 Below)</td>
                                        <td><?=$childAgePopulations[0]?></td>
                                    </tr>
                                    <tr>
                                        <td>Child (Ages 4 - 12)</td>
                                        <td><?=$childAgePopulations[1]?></td>
                                    </tr>
                                    <tr>
                                        <td>Adolescent (Ages 12 - 19)</td>
                                        <td><?=$childAgePopulations[2]?></td>
                                    </tr>
                                    <tr>
                                        <th class="fw-bold">Total</th>
                                        <th class="fw-bold"><?=getTotalChildBelow20();?></th>
                                    </tr>
                                </tbody>
                            </table>
                            <h5 class="card-title text-secondary fw-bolder mt-5">Findings</h5>
                            <p>The report concluded that majority of the solo parents's children are <b class="fw-bold"><?php 
                                $maxVal = max($childAgePopulations);
                                $maxKey = array_search($maxVal, $childAgePopulations);

                                switch($maxKey) {
                                    case 0: 
                                        echo "INFANT (Ages 3 Below)";
                                    break;
                                    case 1: 
                                        echo "CHILD (Ages 4 - 12)";
                                    break;
                                    case 2: 
                                        echo "ADOLESCENT (Ages 12 - 19)";
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
                'Infant (Ages 3 Below)', 'Child (Ages 4 - 12)', 'Adolescent (Ages 12 - 19)'
            ],
			datasets: [{
				label: 'Population',
				data: [
                    <?php
                        $childAgePopulations = getPopulationOfEveryChildAgeGroup();
                        print_r(implode(', ', $childAgePopulations));
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