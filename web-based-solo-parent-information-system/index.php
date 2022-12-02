<!-- Include database connection and functions -->
<?php include 'includes/connection.php'; ?>
<?php include 'includes/functions.php'; ?>

<!-- Page PHP Code -->
<?php 
if (!isSomeoneIsLoggedIn()) {
    header("Location: login.php");
}
    if (!isset($_COOKIE['currentYear'])) {
        setCurrentYear();
        setTodaysYear();
        updateOfficials('FE EVELYN L. PARALA, RSW', 'DENNIS FELIPE C. HAIN', 'ESTELA J. AQUIPEL, RSW');
    } else {
        setTodaysYear();
    }
    updateMembershipStatus($_COOKIE['currentYear'], $_COOKIE['todaysYear']);
    
    $i = getNewSoloParentsCountToday();
    $j = getRenewedSoloParentsCountToday();
?>

<!-- Include page header -->
<?php include 'header.php'; ?>

<!-- Page HTML -->
<div class="container d-flex">
    <div class="sidebar" id="side_nav">
        <div class="px-3 pt-3 pb-2">
            <h1 class="fs-4 text-white text-center fw-bolder">Solo Parent Information System</h1>
        </div>
        <div class="px-3 pb-3">
            <h1 class="fs-lg text-white text-center fw-bold" id="dateTime"><?php date_default_timezone_set('Asia/Manila'); echo date("D, d M Y | h:i A"); ?></h1>
        </div>
        <div class="px-3 pt-3 pb-4">
            <h1 class="fs-5 text-white text-center fw-bold"><?=$_SESSION['loggedAccount']['FirstName'] . " " . $_SESSION['loggedAccount']['LastName'] ?></h1>
        </div>
        <ul class="px-2">
            <li class="px-2 py-3 active"><a href="index.php" class="text-decoration-none d-block text-white"><i class="fa-solid fa-house"></i>Dashboard</a></li>
            <li class="px-2 py-3"><a href="manageSoloParent.php" class="text-decoration-none d-block text-white"><i class="fa-solid fa-people-roof"></i>Manage Solo Parent Data</a></li>
            <li class="px-2 py-3"><a href="generateSoloParentID.php" class="text-decoration-none d-block text-white"><i class="fa-solid fa-id-card"></i>Generate Solo Parent ID</a></li>
            <li class="px-2 py-3"><a href="generateSoloParentReport.php" class="text-decoration-none d-block text-white"><i class="fa-solid fa-chart-simple"></i>Generate Solo Parent Report</a></li>
            <li class="px-2 py-3"><a href="archiveInformativeMaterial.php" class="text-decoration-none d-block text-white"><i class="fa-solid fa-box-archive"></i>Archive Informative Materials</a></li>
        </ul>
        <?php 
        if($_SESSION['loggedAccount']['AccountID'] == 1) {
            echo "<hr class='h-color mx-2'>
                <ul class='px-2'>
                    <li class='px-2 py-3'><a href='employeeAccountManagement.php' class='text-decoration-none d-block text-white'><i class='fa-solid fa-people-group'></i>Manage Employees Account</a></li>
                </ul>";
        }
        ?>
        <hr class="h-color mx-2">
        <ul class="px-2">
            <li class="px-2 py-3"><a href="accountSettings.php" class="text-decoration-none d-block text-white"><i class="fa-solid fa-gear"></i>Account Settings</a></li>
            <li class="px-2 py-3"><a href="logout.php" class="text-decoration-none d-block text-white"><i class="fa-solid fa-right-from-bracket"></i>Log Out</a></li>
        </ul>
    </div>
    <div class="content d-flex justify-content-center">
        <div class="main-content px-5 pt-4">
            <div class="row mb-3">
                <div class="col border-bottom border-danger pb-2">
                    <h1 class="fs-2 fw-bold">Dashboard</h1>
                </div>
            </div>
            <div class="row mb-3 gy-2">
                <div class="col-lg-4">
                    <div class="card rounded-2">
                        <div class="card-body">
                            <h5 class="card-title text-secondary fw-bolder">TOTAL POPULATION OF SOLO PARENTS</h5>
                            <p class="card-text fs-5 fw-bold text-danger"><?= getTotalPopulationOfSoloParents()?></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4"> 
                    <div class="card rounded-2">
                        <div class="card-body">
                            <h5 class="card-title text-secondary fw-bolder">TOTAL NEW SOLO PARENTS TODAY</h5>
                            <p class="card-text fs-5 fw-bold text-danger"><?=$i?></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card rounded-2">
                        <div class="card-body">
                            <h5 class="card-title text-secondary fw-bolder">TOTAL RENEWED SOLO PARENTS TODAY</h5>
                            <p class="card-text fs-5 fw-bold text-danger"><?= $j;?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-3" >
                <div class="col-8">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title text-secondary fw-bolder">POPULATION OF SOLO PARENTS PER BARANGAY</h5>
                            <canvas id="myChart" style="max-height: 400px;"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card pb-4">
                        <div class="card-body">
                            <h5 class="card-title text-secondary fw-bolder">SOLO PARENTS MEMBERSHIP STATUS</h5>
                            <canvas id="myChart2" style="max-height: 400px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row gy-2">
                <div class="col-lg-4">
                    <div class="card rounded-2">
                        <div class="card-body">
                            <h5 class="card-title text-secondary fw-bolder">AVERAGE AGE OF SOLO PARENTS</h5>
                            <p class="card-text fs-5 fw-bold text-danger"><?=getAverageAgeOfSoloParents();?></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card rounded-2">
                        <div class="card-body">
                            <h5 class="card-title text-secondary fw-bolder">LEADING NATURE OF SOLO PARENT</h5>
                            <p class="card-text fs-5 fw-bold text-danger"><?= getLeadingNatureOfSoloParent();?></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card rounded-2">
                        <div class="card-body">
                            <h5 class="card-title text-secondary fw-bolder">TOTAL NUMBER OF DISABLED SOLO PARENTS</h5>
                            <p class="card-text fs-5 fw-bold text-danger"><?=getTotalNumberOfDisabledSoloParents();?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
	const ctx = document.getElementById('myChart').getContext('2d');
	new Chart(ctx, {
		type: 'bar',
		data: {
			labels: [<?= getBarangayNames()?>],
			datasets: [{
				label: 'Population',
				data: [
                    <?= getPopulationPerBarangay();?>
					],
                backgroundColor: [
                    '#ba181b',
                    ],
				},
			],
		},
        options: {},
	});
</script>
<script>
	const ctx2 = document.getElementById('myChart2').getContext('2d');
	new Chart(ctx2, {
		type: 'pie',
		data: {
			labels: [
				'ACTIVE',
                'FOR RENEWAL',
                'INACTIVE',
                'INELIGIBLE'
			],
			datasets: [
				{
					label: 'Population',
					data: [
                        <?=getPopulationPerMembershipStatus();?>
                    ],
					backgroundColor: [
                        '#ff595e',
                        '#ffca3a',
                        '#1982c4',
                        '#6a4c93'
                    ],
				},
			],
		},
		options: {},
	});
</script>
<!-- Include page footer -->
<?php include 'footer.php'; ?>