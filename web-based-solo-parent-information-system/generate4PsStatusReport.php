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
    <title>Solo Parent Report (4Ps Status) <?=date("d M Y")?></title>
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
                    <h1 class="fs-2 fw-bold">Solo Parents' Report (4Ps Status)</h1>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col mb-3">
                    <div class="card reportBody">
                        <div class="card-body">
                            <h5 class="card-title text-secondary fw-bolder">Solo Parents' Population Distribution (Based on 4P's Status)</h5>
                            <canvas id="myChart" style="max-height: calc(8.5in/2); max-width: 7.5in;"></canvas>
                            <h5 class="card-title text-secondary fw-bolder mt-5">Solo Parents' 4Ps Status Distribution</h5>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-center fw-bold">4Ps Membership Status</th>
                                        <th class="text-center fw-bold">Number of Solo Parents</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>4P's Member</td>
                                        <td><?=getTotalSoloParentsWith4Ps()?></td>
                                    </tr>
                                    <tr>
                                        <td>Not 4P's Member</td>
                                        <td><?=getTotalSoloParentsWithout4Ps()?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">TOTAL</td>
                                        <td class="fw-bold"><?=getTotalPopulationOfSoloParents()?></td>
                                    </tr>
                                </tbody>
                            </table>
                            <h5 class="card-title text-secondary fw-bolder mt-5">Findings</h5>
                            <p>The report concluded that majority of solo parents' population are <b class="fw-bold"><?php if (getTotalSoloParentsWith4Ps()>getTotalSoloParentsWithout4Ps()) {
                                echo '4Ps MEMBEMBERS';
                            } else {
                                echo 'NOT 4Ps MEMBERS';
                            }?>.</b></p>
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
			labels: ['4Ps Member', 'Not 4Ps Member'],
			datasets: [{
				label: 'Population',
				data: [
                    <?= getTotalSoloParentsWith4Ps()?>, <?= getTotalSoloParentsWithout4Ps()?>
				],
                backgroundColor: [
                    '#33a8c7',
                    '#52e3e1',
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