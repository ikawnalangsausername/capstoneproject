<!-- Include database connection and functions -->
<?php include 'includes/connection.php'; ?>
<?php include 'includes/functions.php'; ?>

<!-- Page PHP Code -->
<?php 
if (!isSomeoneIsLoggedIn()) {
    header("Location: login.php");
}

$soloParentID = $_GET['SoloParentID'];
$soloParentRecord = getSoloParentRecord($soloParentID);

$isAllInputValid = true;
$controlNumber = $soloParentRecord['ControlNumber'];
$firstName = $soloParentRecord['FirstName'];
$middleName = $soloParentRecord['MiddleName'];
$lastName = $soloParentRecord['LastName'];
$sex = $soloParentRecord['Sex'];
$birthdate = $soloParentRecord['Birthdate'];
$phoneNumber = $soloParentRecord['PhoneNumber'];
$occupation = $soloParentRecord['Occupation'];
$barangay = $soloParentRecord['BarangayID'];
$streetAddress = $soloParentRecord['StreetAddress'];
$pwdStatus = $soloParentRecord['PWDStatusID'];
$fourPsStatus = $soloParentRecord['4PsStatus'];
$natureOfSoloParent = $soloParentRecord['NatureOfSoloParentID'];
$dateJoined = $soloParentRecord['DateJoined'];
$dateLastRenewed = $soloParentRecord['DateLastRenewed'];
$remarks = $soloParentRecord['Remarks'];
$membershipStatus = $soloParentRecord['MembershipStatus'];
$numberOfChildOnRecord = getNumberOfChildren($soloParentRecord['SoloParentID']);    
$childrenDetailsOnRecord = getSoloParentChildren($soloParentRecord['SoloParentID']);

$controlNumberErrorMessage = $firstNameErrorMessage = $middleNameErrorMessage = $lastNameErrorMessage = $sexErrorMessage = 
$birthdateErrorMessage = $phoneNumberErrorMessage = $occupationErrorMessage = $barangayErrorMessage = $streetAddressErrorMessage =
$pwdStatusErrorMessage = $fourPsStatusErrorMessage = $natureOfSoloParentErrorMessage = $childSectionErrorMessage = "";
$childFirstName = $childMiddleName = $childLastName = $childSex = $childBirthdate = $childRelationshipToSoloParent = "";
$numberOfChild = $numberOfChildOnRecord;
$isUpdateRecordSuccessful = true;

$childDetails = [];
$childrenDetails = $childrenDetailsOnRecord;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['updateSoloParent'])) {
    $controlNumber = mysqli_real_escape_string($connection, test_input($_POST['controlNumber']));
    $firstName = strtoupper(mysqli_real_escape_string($connection, test_input($_POST['firstName'])));
    $middleName = strtoupper(mysqli_real_escape_string($connection, test_input($_POST['middleName'])));
    $lastName = strtoupper(mysqli_real_escape_string($connection, test_input($_POST['lastName'])));
    $sex = $_POST['sex'];
    $birthdate = $_POST['birthdate'];
    $phoneNumber = mysqli_real_escape_string($connection, test_input($_POST['phoneNumber']));
    $occupation = strtoupper(mysqli_real_escape_string($connection, htmlspecialchars($_POST['occupation'])));
    $barangay = $_POST['barangay'];
    $streetAddress = strtoupper(mysqli_real_escape_string($connection, test_input($_POST['streetAddress'])));
    $pwdStatus = $_POST['pwdStatus'];
    $fourPsStatus = $_POST['fourPsStatus'];
    $natureOfSoloParent = $_POST['natureOfSoloParent'];
    $numberOfChild = $_POST['numberOfChild'];
    $remarks = mysqli_real_escape_string($connection, $_POST['remarks']);
    $alertErrorMessage = "";

    if (empty($controlNumber) || !preg_match("/^[a-zA-Z-'0-9 ]*$/", $controlNumber)) { // Control number
        $controlNumberErrorMessage = 'Invalid control number';
        $isAllInputValid = false;
        $controlNumber = "";
    }

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

    if (empty($streetAddress) || !preg_match("/[A-Za-z0-9]+/", $streetAddress)) {
        $streetAddressErrorMessage = 'Invalid street address';
        $isAllInputValid = false;
        $streetAddress = '';
    }

    $childrenDetails = [];

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
            $childSex = '';
        } else {
            $childSex = $_POST['child' . $childNumber . 'Sex'];
        }

        if (empty($_POST['child' . $childNumber . 'Birthdate'])) {
            $isAllInputValid = false;
            $childBirthdate = '';
        } else {
            $childBirthdate = $_POST['child' . $childNumber . 'Birthdate'];
        }

        if (!isset($_POST['child' . $childNumber . 'RelationshipToSoloParent'])) {
            $isAllInputValid = false;
            $childRelationshipToSoloParent = '';
        } else {
            $childRelationshipToSoloParent = $_POST['child' . $childNumber . 'RelationshipToSoloParent'];
        }

        $childDetails = ['firstName' => $childFirstName, 'middleName' => $childMiddleName, 'lastName' => $childLastName, 'sex' => $childSex,  'birthdate' => $childBirthdate, 'relationshipToSoloParent' => $childRelationshipToSoloParent];
        array_push($childrenDetails,$childDetails);
    }

    if ($isAllInputValid) {
        if (!checkIfControlNumberExist($controlNumber) || $controlNumber === $soloParentRecord['ControlNumber']) {
            if (!checkIfSoloParentExist($firstName, $middleName, $lastName) || isFullNameSameAsRecord($soloParentRecord, $firstName, $middleName, $lastName)) {
                $childrenThatExist = "";
                $isChildAlreadyExist = false;
                for ($i = 0; $i < $numberOfChild; $i++) {
                    $childNumber = $i + 1;
                    $childFirstName = $_POST['child' . $childNumber . 'FirstName'];
                    $childMiddleName = $_POST['child' . $childNumber . 'MiddleName'];
                    $childLastName = $_POST['child' . $childNumber . 'LastName'];
    
                    if (checkIfChildExist($childFirstName, $childMiddleName, $childLastName) && !isChildNameSameAsRecord($i, $childrenDetailsOnRecord, $childFirstName, $childMiddleName, $childLastName)) {
                        $isChildAlreadyExist = true;
                        if ($childNumber < $numberOfChild){
                            $childrenThatExist .= " " . $childNumber . ',' ;
                        } else {
                            $childrenThatExist .= " " . $childNumber;
                        }
                    }
                }
                if (!$isChildAlreadyExist){
                    if (!(mysqli_query($connection, updateSoloParentRecord($soloParentID,$controlNumber, $firstName, $middleName, $lastName, $sex, $birthdate, $barangay, $streetAddress, $occupation, $phoneNumber, $natureOfSoloParent, $pwdStatus, $fourPsStatus, $remarks)))) {
                        echo "Error: " . $sql . "<br>" . mysqli_error($connection);
                        echo "AN ERROR OCCURED, PLEASE CONTACT THE DEVELOPERS.";
                    } else {
                        $isAllChildRecordAdded = true;

                        for ($i = 0; $i < $numberOfChildOnRecord; $i++)  {
                            $childFirstName = strtoupper($childrenDetails[$i]['firstName']);
                            $childMiddleName = strtoupper($childrenDetails[$i]['middleName']);
                            $childLastName = strtoupper($childrenDetails[$i]['lastName']);
                            $childSex = $childrenDetails[$i]['sex'];
                            $childBirthdate = $childrenDetails[$i]['birthdate'];
                            $childRelationshipToSoloParent = $childrenDetails[$i]['relationshipToSoloParent'];
                            $childID = $childrenDetailsOnRecord[$i]['ChildID'];
    
                            if (!(mysqli_query($connection, updateChildRecord($childID,$childFirstName, $childMiddleName, $childLastName, $childBirthdate,  $childSex, $childRelationshipToSoloParent)))) {
                                $isAllChildRecordAdded = false;
                            }
                        }
                        for ($i = $numberOfChildOnRecord; $i < $numberOfChild; $i++)  {
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
                            header("Location: editOrViewSoloParentRecord.php?SoloParentID=".$soloParentID."&updateSuccess");
                        }
                    }
                } else {
                    $isUpdateRecordSuccessful = false;
                    $alertErrorMessage = "Solo parent record update failed. Children" . $childrenThatExist . " already exist.";
                }
            } else {
                $isUpdateRecordSuccessful = false;
                $alertErrorMessage = "Solo parent record update failed. Solo parent already exist.";
            }
        } else {
            $isUpdateRecordSuccessful = false;
            $alertErrorMessage = "Solo parent record update failed. Control number already exist.";
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
                if (isset($_POST['updateSoloParent'])){
                    if(!$isUpdateRecordSuccessful){
                        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                ' . $alertErrorMessage . '
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>';
                    }
                }
                if (isset($_GET['updateSuccess'])) {
                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                                Solo parent record successfully updated.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>';
                }
                ?>
            </div>
            <div class="mx-5">
                <div class="d-flex justify-content-between mb-3">
                    <h2 class="fs-4 fw-bold">View/ Update Solo Parent Record</h2>
                    <button class="btn btn-danger"><a href="manageSoloParent.php" class="text-decoration-none text-white">Back</a></button>
                </div>
                <div class="mx-3">
                    <form action="#" method="POST" id="updateSoloParentForm">
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
                                    <option <?php if ($sex === 'M') {
                                        echo 'selected';
                                    }?> value="M">Male</option>
                                    <option <?php if ($sex === 'F') {
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
                            <div class="col-lg-5 mb-3">
                                <label for="natureOfSoloParent" class="form-label">Nature of Solo Parent</label>
                                <select class="form-select" id="natureOfSoloParent" name="natureOfSoloParent" aria-label="Default select example" required>
                                    <option selected disabled hidden>Select Nature of Solo Parent</option>
                                    <?php generateNaturesOfSoloParentOptions($natureOfSoloParent); ?>
                                </select>
                                <span class="error-message text-danger "><?= $natureOfSoloParentErrorMessage ?></span>
                            </div>
                            <div class="col-lg-5 mb-3">
                                <label for="numberOfChild" class="form-label">Number of Child</label>
                                <input type="number" class="form-control" id="numberOfChild" name="numberOfChild" readonly value="<?= $numberOfChild?>" required>
                            </div>
                            <div class="col-lg-2 mb-3 pt-4 d-grid">
                                <button type="button" class="btn btn-danger" onclick="addNumberOfChild(); generateAdditionalChildFields();">Add Child</button>
                            </div>
                        </div>
                        <div class="row g-2">
                            <?php
                                $columnSize = 3;
                                $displayMarkAsIneligibleButton = true;
                                if ($membershipStatus !== 'ACTIVE'){
                                    $columnSize = 4;
                                    $displayMarkAsIneligibleButton = false;
                                }
                            ?>
                            <div class="col-lg-<?=$columnSize?> mb-3">
                                <label for="dateJoined" class="form-label">Date Joined</label>
                                <input type="date" class="form-control" id="dateJoined" disabled name="dateJoined" value="<?= $dateJoined?>" required>
                            </div>
                            <div class="col-lg-<?=$columnSize?> mb-3">
                            <label for="dateLastRenewed" class="form-label">Date Last Renewed</label>
                                <input type="date" class="form-control" id="dateLastRenewed" disabled name="dateLastRenewed" value="<?= $dateLastRenewed?>" required>
                            </div>
                            <div class="col-lg-<?=$columnSize?> mb-3">
                                <label for="membershipStatus" class="form-label">Membership Status</label>
                                <input type="text" name="membershipStatus" id="membershipStatus" class="form-control" disabled value="<?= $membershipStatus ?>">
                            </div>
                            <?php 
                                if ($displayMarkAsIneligibleButton) {
                                    echo "<div class='col-lg-3 mb-3 pt-4 d-grid'>
                                            <button type='button' class='btn btn-danger'><a href='markSoloParentIneligible.php?SoloParentID=$soloParentID?>' class='text-decoration-none text-white'>Mark as Ineligible</a></button>
                                        </div>";
                                }
                            ?>
                        </div>
                        <div class="row mb-5">
                            <div class="col">
                                <label for="remarks" class="mb-2">Remarks</label>
                                <textarea class="form-control" name="remarks" id="remarks" rows="2" placeholder="Add remarks here..."><?=$remarks?></textarea>
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
                        <button type="submit" class="btn btn-outline-danger" name="updateSoloParent">Update</button>
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
<!-- Assigning values to children details field -->
<?php
    displayChildrenData($numberOfChild, $childrenDetails);
?>