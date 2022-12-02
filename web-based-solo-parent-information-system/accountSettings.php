<!-- Include database connection and functions -->
<?php include 'includes/connection.php'; ?>
<?php include 'includes/functions.php'; ?>

<!-- Page PHP Code -->
<?php 
if (!isSomeoneIsLoggedIn()) {
    header("Location: login.php");
}

// Initialize all fields 
$username = $_SESSION['loggedAccount']['Username'];
$firstName = $_SESSION['loggedAccount']['FirstName'];
$middleName = $_SESSION['loggedAccount']['MiddleName'];
$lastName = $_SESSION['loggedAccount']['LastName'];
$newPassword = $newPasswordConfirmation = $currentPasswordConfirmation ='';
// Error messages
$usernameErrorMessage = $firstNameErrorMessage = $middleNameErrorMessage = $lastNameErrorMessage = $passwordErrorMessage = '';
$alertMessage = 'incorrect <span class="fw-bold">current password</span>.';
$isAllInputValid = true;
$isUpdateSuccessfull = false;
$isUpdateFailedDueToNewPasswordInput = false;

// Get all fields
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateAccountDetails'])) {
    $username = strtolower(mysqli_real_escape_string($connection, test_input($_POST['username'])));
    $firstName = mysqli_real_escape_string($connection, test_input($_POST['firstName']));
    $middleName = mysqli_real_escape_string($connection, test_input($_POST['middleName']));
    $lastName = mysqli_real_escape_string($connection, test_input($_POST['lastName'])); 
    $newPassword = mysqli_real_escape_string($connection, test_input($_POST['newPassword']));
    $newPasswordConfirmation = mysqli_real_escape_string($connection, test_input($_POST['newPasswordConfirmation']));
    $currentPasswordConfirmation = mysqli_real_escape_string($connection, test_input($_POST['currentPasswordConfirmation']));

    if ($username !== $_SESSION['loggedAccount']['Username'] && (empty($username) || !preg_match("/^\w{6,}$/", $username))) { // username checking
        $usernameErrorMessage = 'Invalid username';
        $username = $_SESSION['loggedAccount']['Username'];
        $isAllInputValid = false;
    }

    if (empty($firstName) || !preg_match("/^[a-zA-Z-' ]*$/", $firstName)) { // First name checking
        $firstNameErrorMessage = 'Invalid first name';
        $firstName = $_SESSION['loggedAccount']['FirstName'];
        $isAllInputValid = false;
    }

    if (!empty($middleName) && !preg_match("/^[a-zA-Z-' ]*$/", $middleName)) { // Middle name checking
        $middleNameErrorMessage = 'Invalid middle name';
        $middleName = $_SESSION['loggedAccount']['MiddleName'];
        $isAllInputValid = false;
    } else if (empty($middleName)) {
        $middleName = "";
    }

    if (empty($lastName) || !preg_match("/^[a-zA-Z-' ]*$/", $lastName)) { // Last name checking
        $lastNameErrorMessage = 'Invalid last name';
        $lastName = $_SESSION['loggedAccount']['LastName'];
        $isAllInputValid = false;
    }

    if (!empty($newPassword)) {
        $doesHaveNumber = preg_match('@[0-9]@', $newPassword);
        $doesHaveUppercaseLetter = preg_match('@[A-Z]@', $newPassword);
        $doesHaveLowercaseLetter = preg_match('@[a-z]@', $newPassword);
        $doesHaveSpecialChars = preg_match('@[^\w]@', $newPassword);
    
        if (strlen($newPassword) < 8 || !$doesHaveNumber || !$doesHaveUppercaseLetter || !$doesHaveLowercaseLetter || !$doesHaveSpecialChars) {
            $passwordErrorMessage = "Password must be at least 8 characters in length and must contain at least one number, one upper case letter, one lower case letter and one special character.";
            $newPassword = $newPasswordConfirmation = "";
            $isAllInputValid = false;
            $isUpdateFailedDueToNewPasswordInput = true;
        } else {
            if (!($newPassword === $newPasswordConfirmation)) {
                $passwordErrorMessage = "Confirmation password does not match the entered password.";
                $isAllInputValid = false;
                $isUpdateFailedDueToNewPasswordInput = true;
            }
        }
    }

    // check if all input is valid
    if ($isAllInputValid) {
        if ($username === $_SESSION['loggedAccount']['Username'] || !isUsernameExist($username)) { 
            if (isPasswordCorrect($currentPasswordConfirmation, $_SESSION['loggedAccount']['Password'])) { 
                if (empty($newPassword)) {
                    // update profile
                    if (!mysqli_query($connection, updateAccountProfile($_SESSION['loggedAccount']['AccountID'], $username, $firstName, $middleName, $lastName))) {
                        $alertMessage = 'due to <span class="fw-bold">system error</span>. Please contact the developers';
                    } else {
                        $isUpdateSuccessfull = true;
                        $_SESSION['loggedAccount'] = getAccountData($username);
                    }
                } else {
                    // update profile and password
                    if (!mysqli_query($connection, updateAccountProfileAndPassword($_SESSION['loggedAccount']['AccountID'], $username, $firstName, $middleName, $lastName, $newPassword))) {
                        $alertMessage = 'due to <span class="fw-bold">system error</span>. Please contact the developers';
                    } else {
                        $isUpdateSuccessfull = true;
                        $_SESSION['loggedAccount'] = getAccountData($username);
                    }
                }
                mysqli_close($connection);
            }
        } elseif ($username !== $_SESSION['loggedAccount']['Username'] && isUsernameExist($username)) {
            $usernameErrorMessage = "Username is taken.";
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
            <li class="px-2 py-3 active"><a href="accountSettings.php" class="text-decoration-none d-block text-white"><i class="fa-solid fa-gear"></i>Account Settings</a></li>
            <li class="px-2 py-3"><a href="logout.php" class="text-decoration-none d-block text-white"><i class="fa-solid fa-right-from-bracket"></i>Log Out</a></li>
        </ul>
    </div>
    <div class="content d-flex justify-content-center">
        <div class="main-content p-5">
            <?php 
            if (isset($_POST['updateAccountDetails']) && $isAllInputValid){
                if($isUpdateSuccessfull){
                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                            Account updated successfully.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
                } else {
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                            Account update unsuccessful, ' . $alertMessage . '
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
                }
            } elseif (isset($_POST['updateAccountDetails']) && $isUpdateFailedDueToNewPasswordInput) {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Account update unsuccessful, due to incorrect new password input. Kindly check <span class="fw-bold">change password section</span> for more info.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
            }
            ?>
            <form action="accountSettings.php" method="POST">
                <div class="row mb-5">
                    <div class="col border-bottom border-danger pb-2">
                        <h1 class="fs-2 fw-bold">Account Details</h1>
                    </div>
                </div>
                <div class="row mb-4 ps-5">
                    <div class="col-11">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" name="username" id="username" placeholder="Enter username" required value="<?= $username ?>">
                        <span class="error-message text-danger"><?= $usernameErrorMessage ?></span>
                    </div>
                </div>
                <div class="row mb-4 ps-5">
                    <div class="col-11">
                        <label for="firstName" class="form-label">First Name</label>
                        <input type="text" class="form-control" name="firstName" id="firstName" placeholder="Enter first name" required value="<?= $firstName ?>">
                        <span class="error-message text-danger "><?= $firstNameErrorMessage ?></span>
                    </div>
                </div>
                <div class="row mb-4 ps-5">
                    <div class="col-11">
                        <label for="middleName" class="form-label">Middle Name</label>
                        <input type="text" class="form-control" name="middleName" id="middleName" placeholder="Enter middle name" value="<?= $middleName ?>">
                        <span class="error-message text-danger"><?= $middleNameErrorMessage ?></span>
                    </div>
                </div>
                <div class="row mb-4 ps-5">
                    <div class="col-11">
                        <label for="lastName" class="form-label">Last Name</label>
                        <input type="text" class="form-control" name="lastName" id="lastName" placeholder="Enter last name "required value="<?= $lastName ?>">
                        <span class="error-message text-danger"><?= $lastNameErrorMessage ?></span>
                    </div>
                </div>
                <div class="row mb-4 ps-5">
                    <div class="col-11">
                        <p class="pb-2">
                            <button class="btn btn-danger" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                            Change Password
                            </button>
                        </p>
                        <div class="collapse pt-3 ps-3" id="collapseExample">
                        <div class="row">
                            <div class="col">
                                <label for="newPassword" class="form-label">New Password</label>
                                <input type="password" class="form-control" name="newPassword" id="newPassword" placeholder="Enter new password" value="<?= $newPassword ?>">
                            </div>
                            <div class="col">
                                <label for="newPasswordConfirmation" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" name="newPasswordConfirmation" id="newPasswordConfirmation" placeholder="Confirm new password" value="<?= $newPasswordConfirmation ?>">
                            </div>
                            <div class="col-3 pt-4 d-grid">
                                <button class="btn btn-danger fs-xs" id="changePasswordVisibilityToggler" type="button">Show password</button>
                            </div>
                            <div class="col-12 pt-2">
                                <span class="error-message text-danger"><?= $passwordErrorMessage ?></span>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
                <div class="row ps-5">
                    <div class="col-11 pt-3 d-grid">
                        <button class="btn btn-outline-danger fw-bolder" data-bs-toggle="modal" data-bs-target="#confirmPasswordModal" type="button">
                            Save
                        </button>
                        <!-- Modal -->
                        <div class="modal fade" id="confirmPasswordModal" tabindex="-1" aria-labelledby="passwordConfirmation" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="passwordConfirmation">Password Confirmation</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col">
                                        <label for="currentPasswordConfirmation" class="form-label">Enter your current password</label>
                                        <input type="password" class="form-control" name="currentPasswordConfirmation" id="currentPasswordConfirmation" placeholder="Current password">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-outline-danger" name="updateAccountDetails">Confirm</button>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Include page footer -->
<?php include 'footer.php'; ?>