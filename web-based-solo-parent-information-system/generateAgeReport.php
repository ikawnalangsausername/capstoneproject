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
    <title>Solo Parent Report (Age) <?=date("d M Y")?></title>
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
                    <h1 class="fs-2 fw-bold">Solo Parents' Report (Age)</h1>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col mb-3">
                    <div class="card reportBody">
                        <div class="card-body">
                            <h5 class="card-title text-secondary fw-bolder">Solo Parents' Population Distribution (Based on Age) </h5>
                            <canvas id="myChart" style="max-height: calc(8.5in/2);"></canvas>
                            <h5 class="card-title text-secondary fw-bolder mt-5">Solo Parents' Age Distribution</h5>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th class="fw-bold text-center">Age Groups</th>
                                        <th class="fw-bold text-center">Number of Solo Parents</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Ages below 18</td>
                                        <td><?=getPopulationOfMinorSoloParents()?></td>
                                    </tr>
                                    <tr>
                                        <td>Ages 18-30</td>
                                        <td><?=getPopulationOfYoungAdultSoloParents()?></td>
                                    </tr>
                                    <tr>
                                        <td>Ages 31-50</td>
                                        <td><?=getPopulationOfMiddleAgedAdultSoloParents()?></td>
                                    </tr>
                                    <tr>
                                        <td>Ages 51 and above</td>
                                        <td><?= getPopulationOfOldAgedAdultSoloParents()?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">TOTAL</td>
                                        <td class="fw-bold"><?=getTotalPopulationOfSoloParents()?></td>
                                    </tr>
                                </tbody>
                            </table>
                            <h5 class="card-title text-secondary fw-bolder mt-5">Findings</h5>
                            <p>The report concluded that majority of solo parents' population are <b class="fw-bold"><?=getMajorityOfSoloParentsByAge()?>.</b></p>
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
			labels: ['Ages below 18', 'Ages 18-30', 'Ages 31-50','Ages 51 and above'],
			datasets: [{
				label: 'Population',
				data: [
                    <?=getPopulationOfMinorSoloParents()?>,<?=getPopulationOfYoungAdultSoloParents()?>,<?=getPopulationOfMiddleAgedAdultSoloParents()?>,<?= getPopulationOfOldAgedAdultSoloParents()?>
				],
                backgroundColor: [
                    '#33a8c7',
                    '#52e3e1',
                    '#a0e426',
                    '#fdf148',
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