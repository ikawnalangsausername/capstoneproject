<!-- Include database connection and functions -->
<?php include 'includes/connection.php'; ?>
<?php include 'includes/functions.php'; ?>

<!-- Page PHP Code -->
<?php 
if (!isSomeoneIsLoggedIn()) {
    header("Location: login.php");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['generateReport'])&&isset($_POST['reportTopic'])) {
        switch($_POST['reportTopic']) {
            case 'age': 
                header("Location: generateAgeReport.php");
            break;

            case 'address': 
                header("Location: generateAddressReport.php");
            break;

            case 'natureOfSoloParent': 
                header("Location: generateNatureReport.php");
            break;

            case 'numberOfChildren': 
                header("Location: generateChildrenNumberReport.php");
            break;

            case 'ageOfChildren': 
                header("Location: generateChildrenAgeReport.php");
            break;

            case 'pwdStatus': 
                header("Location: generatePWDStatusReport.php");
            break;  

            case 'fourPsStatus': 
                header("Location: generate4PsStatusReport.php");
            break;

            case 'membershipStatus': 
                header("Location: generateMembershipStatusReport.php");
            break;

            case 'soloParentApplication': 
                header("Location: generateSoloParentApplicationReport.php");
            break;
        }
    }
}
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
            <li class="px-2 py-3"><a href="index.php" class="text-decoration-none d-block text-white"><i class="fa-solid fa-house"></i>Dashboard</a></li>
            <li class="px-2 py-3"><a href="manageSoloParent.php" class="text-decoration-none d-block text-white"><i class="fa-solid fa-people-roof"></i>Manage Solo Parent Data</a></li>
            <li class="px-2 py-3"><a href="generateSoloParentID.php" class="text-decoration-none d-block text-white"><i class="fa-solid fa-id-card"></i>Generate Solo Parent ID</a></li>
            <li class="px-2 py-3 active"><a href="generateSoloParentReport.php" class="text-decoration-none d-block text-white"><i class="fa-solid fa-chart-simple"></i>Generate Solo Parent Report</a></li>
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
        <div class="main-content p-5">
            <div class="row mt-2 mb-3">
                <div class="col border-bottom border-danger pb-2">
                    <h1 class="fs-2 fw-bold">Report Generation</h1>
                </div>
            </div>
            <div class="row">
                <?php 
                if (isset($_POST['generateReport'])){
                    if(!isset($_GET['generationSuccessful'])){
                        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                Solo parent report generation failed. Please try again.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
                    }
                }
                if(isset($_GET['generationSuccessful'])){
                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                            Solo parent report generated successfully.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
                }
                if(isset($_GET['uploadSuccessful'])){
                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                            Solo parent report uploaded successfully.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
                }
                if(isset($_GET['reportDeletionSuccessful'])){
                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                            Solo parent report deleted successfully.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
                }
                if(isset($_GET['informativeMaterialExist'])){
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Solo parent report upload failed. Informative material already exist.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
                }
                if(isset($_GET['invalidFileFormat'])){
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Solo parent report upload failed. Only PDF, WORD, EXCEL files are allowed. 
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
                }

                ?>
            </div>
            <div class="row mb-3 px-5">
                <!-- Button trigger modal -->
                <div class="col-8">
                    <button type="button" class="btn btn-danger w-25" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Generate Report
                    </button>
                    <button type="button" class="btn btn-outline-danger w-25" data-bs-toggle="modal" data-bs-target="#exampleModal2">
                    Upload Report
                    </button>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Report Configuration</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="generateSoloParentReport.php" method="POST">
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="reportTopic" class="form-label">Report Topic</label>
                                    <select name="reportTopic" id="reportTopic" class="form-select mb-2">
                                        <option selected hidden disabled>Select a report topic</option>
                                        <option value="age">Age</option>
                                        <option value="address">Address</option>
                                        <option value="natureOfSoloParent">Nature of Solo Parent</option>
                                        <option value="numberOfChildren">Number of Children</option>
                                        <option value="ageOfChildren">Age of Children</option>
                                        <option value="pwdStatus">PWD Status</option>
                                        <option value="fourPsStatus">4Ps Status</option>
                                        <option value="membershipStatus">Membership Status</option>
                                        <option value="soloParentApplication">Solo Parent Application</option>
                                    </select>
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="generateReport" class="btn btn-danger">Generate</button>
                        </form>
                    </div>
                    </div>
                </div>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Upload Report</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="uploadMaterial.php" method="POST" accept="application/pdf" enctype="multipart/form-data">
                            <div class="row mb-3">
                                <label for="" class="form-label">Select report to upload</label>
                                <input type="file" name="pdf" id="report" required class="form-control">    
                            </div>
                            
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-outline-danger" name="uploadReport">Upload</button>
                        </form>
                    </div>
                    </div>
                </div>
                </div>
            </div>
            <div class="row mb-5 px-5">
                <table id="pdfTable" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Date Added</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $sql  = "SELECT * FROM informativematerials WHERE InformativeMaterialCategory = 'REPORT'";
                            $result = mysqli_query($connection, $sql);

                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>
                                    <td>".$row['InformativeMaterialID']."</td>
                                    <td>".$row['InformativeMaterial']."</td>
                                    <td>".$row['DateAdded']."</td>
                                    <td>
                                        <form action='viewMaterial.php' target='_blank' method='GET' id='view".$row['InformativeMaterialID']."' class='d-inline-block'>
                                            <input type='hidden' name='InformativeMaterial' value='".$row['InformativeMaterial']."'>
                                            <button type='submit' name='viewMaterial' class='btn btn-danger'>View</button>
                                        </form>
                                        <button class='btn btn-outline-danger fw-bolder' data-bs-toggle='modal' data-bs-target='#confirmReportDeletion" . $row['InformativeMaterialID']. "' type='button'>Delete</button>
                                        <div class='modal fade' id='confirmReportDeletion" . $row['InformativeMaterialID'] . "' tabindex='-1' aria-labelledby='passwordConfirmation' aria-hidden='true'>
                                        <div class='modal-dialog modal-dialog-centered'>
                                            <div class='modal-content'>
                                            <div class='modal-header'>
                                                <h1 class='modal-title fs-md fw-bolder' id='passwordConfirmation'>Delete Report</h1>
                                                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                            </div>
                                            <form action='deleteMaterial.php' method='GET' id='delete".$row['InformativeMaterialID']."' class='d-inline-block'>
                                                <div class='modal-body text-center'>
                                                Confirm  deletion of " . $row['InformativeMaterial'] . "
                                                    <div class='row'>
                                                        <div class='col'>
                                                            <input type='hidden' name='InformativeMaterialID' value='".$row['InformativeMaterialID']."'>
                                                            <input type='hidden' name='InformativeMaterial' value='".$row['InformativeMaterial']."'>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class='modal-footer'>
                                                    <button type='button' class='btn btn-danger' data-bs-dismiss='modal'>Cancel</button>
                                                    <button type='submit' name='deleteMaterial' class='btn btn-outline-danger' value='R'>Confirm</button>
                                                </div>
                                                </div>
                                            </form>
                                        </div>
                                        </div>
                                    </td>
                                </tr>";
                            }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Date Added</th>
                            <th>Actions</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Include page footer -->
<?php include 'footer.php'; ?>