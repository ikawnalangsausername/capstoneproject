<!-- Include database connection and functions -->
<?php include 'includes/connection.php'; ?>
<?php include 'includes/functions.php'; ?>

<!-- Page PHP Code -->
<?php 
if (!isSomeoneIsLoggedIn()) {
    header("Location: login.php");
}

$isAllInputValid = true;
$controlNumber = $firstName = $middleName = $lastName = $sex = $birthdate = $phoneNumber = $occupation = 
$barangay = $streetAddress = $pwdStatus = $fourPsStatus = $natureOfSoloParent = '';
$controlNumberErrorMessage = $firstNameErrorMessage = $middleNameErrorMessage = $lastNameErrorMessage = $sexErrorMessage = 
$birthdateErrorMessage = $phoneNumberErrorMessage = $occupationErrorMessage = $barangayErrorMessage = $streetAddressErrorMessage =
$pwdStatusErrorMessage = $fourPsStatusErrorMessage = $natureOfSoloParentErrorMessage = $childSectionErrorMessage = "";
$childFirstName = $childMiddleName = $childLastName = $childSex = $childBirthdate = $childRelationshipToSoloParent = "";
$isAddRecordSuccessful = true;
$numberOfChild = 1;
$childDetails = [];
$childrenDetails = [];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addSoloParent'])) {
    // print_r($_POST);

    $controlNumber = mysqli_real_escape_string($connection, test_input($_POST['controlNumber']));
    $firstName = mysqli_real_escape_string($connection, test_input($_POST['firstName']));
    $middleName = mysqli_real_escape_string($connection, test_input($_POST['middleName']));
    $lastName = mysqli_real_escape_string($connection, test_input($_POST['lastName']));
    $phoneNumber = mysqli_real_escape_string($connection, test_input($_POST['phoneNumber']));
    $occupation = mysqli_real_escape_string($connection, htmlspecialchars($_POST['occupation']));
    $birthdate = $_POST['birthdate'];
    $streetAddress = mysqli_real_escape_string($connection, test_input($_POST['streetAddress']));
    $numberOfChild = $_POST['numberOfChild'];
    $alertErrorMessage = "";

    if (empty($controlNumber) || !preg_match("/^[a-zA-Z-'0-9 ]*$/", $controlNumber)) { // First name checking
        $controlNumberErrorMessage = 'Invalid control number';
        $isAllInputValid = false;
        $controlNumber = "";
    }

    // Perform validation to inputs
    if (empty($firstName) || !preg_match("/^[a-zA-Z-' ]*$/", $firstName)) { // First name checking
        $firstNameErrorMessage = 'Invalid first name';
        $isAllInputValid = false;
        $firstName = "";
    }

    if (!empty($middleName) && !preg_match("/^[a-zA-Z-' ]*$/", $middleName)) { // Middle name checking
        $middleNameErrorMessage = 'Invalid middle name';
        $isAllInputValid = false;
        $middleName = "";
    } else if (empty($middleName)) {
        $middleName = "";
    }

    if (empty($lastName) || !preg_match("/^[a-zA-Z-' ]*$/", $lastName)) { // Last name checking
        $lastNameErrorMessage = 'Invalid last name';
        $isAllInputValid = false;
        $lastName = "";
    }

    if (!isset($_POST['sex'])) {
        $sexErrorMessage = 'Please select a sex';
        $isAllInputValid = false;
    } else {
        $sex = $_POST['sex'];
    }

    if (date('Y', strtotime($birthdate)) >= date('Y') - 14 ){
        $birthdateErrorMessage = 'Invalid birthdate';
        $isAllInputValid = false;
        $birthdate = '';
    }

    if (empty($phoneNumber) || !preg_match("/^[0-9]*$/", $phoneNumber)){
        $phoneNumberErrorMessage = 'Invalid phone number';
        $isAllInputValid = false;
        $phoneNumber = '';
    }

    if (empty($occupation) || !preg_match("/^[a-zA-Z-' ]*$/", $occupation)) {
        $occupationErrorMessage = 'Invalid occupation';
        $isAllInputValid = false;
        $occupation = '';
    }

    if (!isset($_POST['barangay'])){
        $barangayErrorMessage = 'Please select a barangay';
        $isAllInputValid = false;
    } else {
        $barangay = $_POST['barangay'];
    }

    if (empty($streetAddress) || !preg_match("/[A-Za-z0-9]+/", $streetAddress)) {
        $streetAddressErrorMessage = 'Invalid street address';
        $isAllInputValid = false;
        $streetAddress = '';
    }

    if (!isset($_POST['pwdStatus'])){
        $pwdStatusErrorMessage = 'Please determine pwd status';
        $isAllInputValid = false;
    } else {
        $pwdStatus = $_POST['pwdStatus'];
    }

    if (!isset($_POST['natureOfSoloParent'])) {
        $natureOfSoloParentErrorMessage = 'Please select a nature of solo parent';
        $isAllInputValid = false;
    } else {
        $natureOfSoloParent = $_POST['natureOfSoloParent'];
    }

    if (!isset($_POST['fourPsStatus'])) {
        $fourPsStatusErrorMessage = 'Please indicate the 4P\'s Status';
        $isAllInputValid = false;
    } else {
        $fourPsStatus = $_POST['fourPsStatus'];
    }

    for ($i = 0; $i < $numberOfChild; $i++) {
        $childNumber = $i+1;
        $childFirstName = mysqli_real_escape_string($connection, test_input($_POST['child' . $childNumber . 'FirstName']));
        $childMiddleName = mysqli_real_escape_string($connection, test_input($_POST['child' . $childNumber . 'MiddleName']));
        $childLastName = mysqli_real_escape_string($connection, test_input($_POST['child' . $childNumber . 'LastName']));
        
        if ((empty($childFirstName) || !preg_match("/^[a-zA-Z-' ]*$/", $childFirstName)) 
        || (!empty($childMiddleName) && !preg_match("/^[a-zA-Z-' ]*$/", $childMiddleName))
        ||(empty($childLastName) || !preg_match("/^[a-zA-Z-' ]*$/", $childLastName))) {
            $isAllInputValid = false;
        }

        if (!isset($_POST['child' . $childNumber . 'Sex'])) {
            $isAllInputValid = false;
        } else {
            $childSex = $_POST['child' . $childNumber . 'Sex'];
        }

        if (empty($_POST['child' . $childNumber . 'Birthdate'])) {
            $isAllInputValid = false;
        } else {
            $childBirthdate = $_POST['child' . $childNumber . 'Birthdate'];
        }

        if (!isset($_POST['child' . $childNumber . 'RelationshipToSoloParent'])) {
            $isAllInputValid = false;
        } else {
            $childRelationshipToSoloParent = $_POST['child' . $childNumber . 'RelationshipToSoloParent'];
        }

        $childDetails = ['firstName' => $childFirstName, 'middleName' => $childMiddleName, 'lastName' => $childLastName, 'sex' => $childSex,  'birthdate' => $childBirthdate, 'relationshipToSoloParent' => $childRelationshipToSoloParent];
        array_push($childrenDetails,$childDetails);
    }

    if ($isAllInputValid) {
        if (!checkIfControlNumberExist($controlNumber)) {
            if (!checkIfSoloParentExist($firstName, $middleName, $lastName)) {
                $childrenThatExist = "";
                $isChildAlreadyExist = false;
                for ($i = 0; $i < $numberOfChild; $i++) {
                    $childNumber = $i + 1;
                    $childFirstName = $_POST['child' . $childNumber . 'FirstName'];
                    $childMiddleName = $_POST['child' . $childNumber . 'MiddleName'];
                    $childLastName = $_POST['child' . $childNumber . 'LastName'];
    
                    if (checkIfChildExist($childFirstName, $childMiddleName, $childLastName)) {
                        $isChildAlreadyExist = true;
                        if ($childNumber < $numberOfChild){
                            $childrenThatExist .= " " . $childNumber . ',' ;
                        } else {
                            $childrenThatExist .= " " . $childNumber;
                        }
                    }
                }
                if (!$isChildAlreadyExist){
                    if (!(mysqli_query($connection, addSoloParent($controlNumber, $firstName, $middleName, $lastName, $sex, $birthdate, $barangay, $streetAddress, $occupation, $phoneNumber, $natureOfSoloParent, $pwdStatus, $fourPsStatus)))) {
                        echo "Error: " . $sql . "<br>" . mysqli_error($connection);
                        echo "AN ERROR OCCURED, PLEASE CONTACT THE DEVELOPERS.";
                    } else {
                        $isAllChildRecordAdded = true;
                        for ($i = 0; $i < $numberOfChild; $i++)  {
                            $childFirstName = strtoupper($childrenDetails[$i]['firstName']);
                            $childMiddleName = strtoupper($childrenDetails[$i]['middleName']);
                            $childLastName = strtoupper($childrenDetails[$i]['lastName']);
                            $childSex = $childrenDetails[$i]['sex'];
                            $childBirthdate = $childrenDetails[$i]['birthdate'];
                            $childRelationshipToSoloParent = $childrenDetails[$i]['relationshipToSoloParent'];
    
                            if (!(mysqli_query($connection, addChild($controlNumber,$childFirstName, $childMiddleName, $childLastName, $childBirthdate,  $childSex, $childRelationshipToSoloParent)))) {
                                $isAllChildRecordAdded = false;
                            }
                        }
                        if ($isAllChildRecordAdded) {
                            mysqli_close($connection);
                            header("Location: manageSoloParent.php?addSoloParentRecordSuccessful");
                        }
                    }
                } else {
                    $isAddRecordSuccessful = false;
                    $alertErrorMessage = "Solo parent record creation failed. Children" . $childrenThatExist . " already exist.";
                }
            } else {
                $isAddRecordSuccessful = false;
                $alertErrorMessage = "Solo parent record creation failed. Solo parent already exist.";
            }
        } else {
            $isAddRecordSuccessful = false;
            $alertErrorMessage = "Solo parent record creation failed. Control number already exist.";
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
            <li class="px-2 py-3 active"><a href="manageSoloParent.php" class="text-decoration-none d-block text-white"><i class="fa-solid fa-people-roof"></i>Manage Solo Parent Data</a></li>
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
        <div class="main-content p-5">
            <div class="row mb-5">
                <div class="col border-bottom border-danger pb-2">
                    <h1 class="fs-2 fw-bold">Solo Parent Management</h1>
                </div>
            </div>
            <div class="row px-5">
                <?php 
                if (isset($_POST['addSoloParent'])){
                    if(!$isAddRecordSuccessful){
                        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                ' . $alertErrorMessage . '
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>';
                    }
                }
                ?>
            </div>
            <div class="mx-5">
                <div class="d-flex justify-content-between mb-3">
                    <h2 class="fs-4 fw-bold">Add Solo Parent</h2>
                    <button class="btn btn-danger"><a href="manageSoloParent.php" class="text-decoration-none text-white">Back</a></button>
                </div>
                <div class="mx-3">
                    <form action="#" method="POST">
                        <h3 class="fw-bold mb-3">Solo Parent Details</h3>
                        <div class="row g-2">
                            <div class="col-lg-3 mb-3">
                                <label for="controlNumber" class="form-label">Control Number</label>
                                <input type="text" class="form-control" id="controlNumber" name="controlNumber" placeholder="SAL XX-XX-XX" value="<?= $controlNumber?>" required>
                                <span class="error-message text-danger"><?= $controlNumberErrorMessage ?></span>
                            </div>
                            <div class="col-lg-3 mb-3">
                                <label for="firstName" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="firstName" name="firstName" placeholder="Juan" value="<?= $firstName?>" required>
                                <span class="error-message text-danger"><?= $firstNameErrorMessage ?></span>
                            </div>
                            <div class="col-lg-3 mb-3">
                                <label for="middleName" class="form-label">Middle Name</label>
                                <input type="text" class="form-control" id="middleName" name="middleName" placeholder="San Juan" value="<?= $middleName?>">
                                <span class="error-message text-danger "><?= $middleNameErrorMessage ?></span>
                            </div>
                            <div class="col-lg-3 mb-3">
                                <label for="lastName" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Dela Cruz" value="<?= $lastName?>" required>
                                <span class="error-message text-danger "><?= $lastNameErrorMessage ?></span>
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col-lg-2 mb-3">
                                <label for="sex" class="form-label">Sex</label>
                                <select class="form-select" id="sex" name="sex" aria-label="Default select example" required>
                                    <option selected disabled hidden>Select Sex</option>
                                    <option <?php if (isset($_POST['sex']) && $sex === 'M') {
                                        echo 'selected';
                                    }?> value="M">Male</option>
                                    <option <?php if (isset($_POST['sex']) && $sex === 'F') {
                                        echo 'selected';
                                    }?> value="F">Female</option>
                                </select>
                                <span class="error-message text-danger "><?= $sexErrorMessage ?></span>
                            </div>
                            <div class="col-lg-3 mb-3">
                                <label for="birthdate" class="form-label">Birthdate</label>
                                <input type="date" class="form-control" id="birthdate" name="birthdate" value="<?= $birthdate?>" required>
                                <span class="error-message text-danger "><?= $birthdateErrorMessage ?></span>
                            </div>
                            <div class="col-lg-3 mb-3">
                                <label for="phoneNumber" class="form-label">Phone Number</label>
                                <input type="text" class="form-control" id="phoneNumber" name="phoneNumber" placeholder="XXXXXXXXXXX" value="<?= $phoneNumber?>" required>
                                <span class="error-message text-danger "><?= $phoneNumberErrorMessage ?></span>
                            </div>
                            <div class="col mb-3">
                                <label for="occupation" class="form-label">Occupation</label>
                                <input type="text" class="form-control" id="occupation" name="occupation" placeholder="Ex. Production Operator" value="<?= $occupation?>" required>
                                <span class="error-message text-danger "><?= $occupationErrorMessage ?></span>
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col-lg-3 mb-3">
                                <label for="barangay" class="form-label">Barangay</label>
                                <select class="form-select" id="barangay" name="barangay" aria-label="Default select example" required>
                                    <option selected disabled hidden>Select Barangay</option>
                                    <?php generateBarangayOptions($barangay);?>
                                </select>
                                <span class="error-message text-danger "><?= $barangayErrorMessage ?></span>
                            </div>
                            <div class="col mb-3">
                                <label for="streetAddress" class="form-label">Street Address</label>
                                <input type="text" class="form-control" id="streetAddress" name="streetAddress" placeholder="Block # Lot # Sample St. Sample Village" value="<?= $streetAddress?>" required>
                                <span class="error-message text-danger "><?= $streetAddressErrorMessage ?></span>
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col-6 mb-3">
                                <label for="pwdStatus" class="form-label">PWD Status</label>
                                <select class="form-select" id="pwdStatus" name="pwdStatus" aria-label="Default select example" required>
                                <option selected disabled hidden>Select PWD Status</option>
                                    <?php generatePwdStatusOptions($pwdStatus); ?>
                                </select>
                                <span class="error-message text-danger "><?= $pwdStatusErrorMessage ?></span>
                            </div>
                            <div class="col-6 mb-3">
                                <label for="fourPsStatus" class="form-label">4Ps Member</label>
                                <select class="form-select" id="fourPsStatus" name="fourPsStatus" aria-label="Default select example" required>
                                    <option selected disabled hidden>Is 4P's member?</option>
                                    <option <?php if ($fourPsStatus === 'Y') {
                                        echo 'selected ';
                                    }?> value="Y">Yes</option>
                                    <option <?php if ($fourPsStatus === 'N') {
                                        echo 'selected ';
                                    }?> value="N">No</option>  
                                </select>
                                <span class="error-message text-danger "><?= $fourPsStatusErrorMessage ?></span>
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col-lg-6 mb-3">
                                <label for="natureOfSoloParent" class="form-label">Nature of Solo Parent</label>
                                <select class="form-select" id="natureOfSoloParent" name="natureOfSoloParent" aria-label="Default select example" required>
                                    <option selected disabled hidden>Select Nature of Solo Parent</option>
                                    <?php generateNaturesOfSoloParentOptions($natureOfSoloParent); ?>
                                </select>
                                <span class="error-message text-danger "><?= $natureOfSoloParentErrorMessage ?></span>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="numberOfChild" class="form-label">Number of Child</label>
                                <input type="number" class="form-control" id="numberOfChild" name="numberOfChild" min="1" value="<?= $numberOfChild?>" onchange="generateChildFields()" required>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <h3 class="fw-bold mb-3">Child/Children Details</h3>
                            <div id="childFields">
                                <div class="row g-2 mt-2">
                                    <h4 class="fw-bold mb-2">Child 1 Details</h4>
                                    <div class="col-lg-4 mb-3">
                                        <label for="child1FirstName" class="form-label">First Name</label>
                                        <input type="text" class="form-control" id="child1FirstName" name="child1FirstName" placeholder="Pedro" required>
                                    </div>
                                    <div class="col-lg-4 mb-3">
                                        <label for="child1MiddleName" class="form-label">Middle Name</label>
                                        <input type="text" class="form-control" id="child1MiddleName" name="child1MiddleName" placeholder="San Juan" required>
                                    </div>
                                    <div class="col-lg-4 mb-3">
                                        <label for="child1LastName" class="form-label">Last Name</label>
                                        <input type="text" class="form-control" id="child1LastName" name="child1LastName" placeholder="Dela Cruz" required>
                                    </div>
                                </div>
                                <div class="row g-2">
                                    <div class="col-lg-4 mb-3">
                                        <label for="child1Sex" class="form-label">Sex</label>
                                        <select class="form-select" id="child1Sex" name="child1Sex" aria-label="Default select example" required>
                                            <option selected>Select Sex</option>
                                            <option value="M">Male</option>
                                            <option value="F">Female</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-4 mb-3">
                                        <label for="child1sBirthdate" class="form-label">Birthdate</label>
                                        <input type="date" class="form-control" id="child1Birthdate" name="child1Birthdate" required>
                                    </div>
                                    <div class="col-lg-4 mb-3">
                                        <label for="child1RelationshipToSoloParent" class="form-label">Relationship to Solo Parent</label>
                                        <select class="form-select" id="child1RelationshipToSoloParent" name="child1RelationshipToSoloParent" aria-label="Default select example" required>
                                            <option selected disabled hidden>Select Relationship</option>
                                            <?php 
                                                generateRelationshipToSoloParentCategories();
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-outline-danger" name="addSoloParent">Add</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="js/jquery-3.6.1.js"></script>
<!-- Bootstrap JS -->
<script src="js/bootstrap.bundle.min.js"></script>
<!-- Datatables -->
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap5.min.js"></script>
<!-- Actual script -->
<script src="js/script.js"></script>
<script>generateChildFields()</script>
</body>
</html>

<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addSoloParent'])) {
        for ($i = 0; $i < $numberOfChild; $i++) {
            $childNumber = $i+1;
            $childFirstName = mysqli_real_escape_string($connection, test_input($_POST['child' . $childNumber . 'FirstName']));
            $childMiddleName = mysqli_real_escape_string($connection, test_input($_POST['child' . $childNumber . 'MiddleName']));
            $childLastName = mysqli_real_escape_string($connection, test_input($_POST['child' . $childNumber . 'LastName']));
            $childBirthdate = $_POST['child' . $childNumber . 'Birthdate'];
            
            if (empty($childFirstName) || !preg_match("/^[a-zA-Z-' ]*$/", $childFirstName)) {
                displayChildFirstNameErrorMessage($childNumber);
            } else {
                displayChildFirstName($childNumber, $childFirstName);
            }
            if (!empty($childMiddleName) && !preg_match("/^[a-zA-Z-' ]*$/", $childMiddleName)) {
                displayChildMiddleNameErrorMessage($childNumber);
            } else {
                displayChildMiddleName($childNumber, $childMiddleName);
            }

            if (empty($childLastName) || !preg_match("/^[a-zA-Z-' ]*$/", $childLastName)) {
                displayChildLastNameErrorMessage($childNumber);
            } else {
                displayChildLastName($childNumber, $childLastName);
            }
    
            if (!isset($_POST['child' . $childNumber . 'Sex'])) {
                displayChildSexErrorMessage($childNumber);
            } else {
                $childSex = $_POST['child' . $childNumber . 'Sex'];
                displayChildSex($childNumber, $childSex);
            }
    
            if (empty($_POST['child' . $childNumber . 'Birthdate']) || $_POST['child' . $childNumber . 'Birthdate'] > date('Y-m-d')) {
                displayChildBirthdateErrorMessage($childNumber);
            } else {
                displayChildBirthdate($childNumber, $childBirthdate);
            }
    
            if (!isset($_POST['child' . $childNumber . 'RelationshipToSoloParent'])) {
                displayRelationshipToSoloParentErrorMessage($childNumber);
            } else {
                $childRelationshipToSoloParent = $_POST['child' . $childNumber . 'RelationshipToSoloParent'];
                displayRelationshipToSoloParent($childNumber, $childRelationshipToSoloParent);
            }
        }
    }
?>
