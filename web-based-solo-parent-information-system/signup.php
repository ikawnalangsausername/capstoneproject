<!-- Include database connection and functions -->
<?php include 'includes/connection.php'; ?>
<?php include 'includes/functions.php'; ?>

<!-- Page PHP Code -->
<?php 
$firstNameErrorMessage = $middleNameErrorMessage = $lastNameErrorMessage = $usernameErrorMessage = $passwordErrorMessage = "";
$firstName = $lastName = $middleName = $username = $password = $passwordConfirmation = "";
$isAllInputValid = true;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signUp'])) {
    // Get inputs
    $firstName = mysqli_real_escape_string($connection, test_input($_POST['firstName']));
    $middleName = mysqli_real_escape_string($connection, test_input($_POST['middleName']));
    $lastName = mysqli_real_escape_string($connection, test_input($_POST['lastName']));
    $username = strtolower(mysqli_real_escape_string($connection, test_input($_POST['username'])));
    $password = mysqli_real_escape_string($connection, test_input($_POST['password']));
    $passwordConfirmation = mysqli_real_escape_string($connection, test_input($_POST['passwordConfirmation']));

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

    if (empty($username) || !preg_match("/^\w{6,}$/", $username)) { // username checking
        $usernameErrorMessage = 'Invalid username';
        $isAllInputValid = false;
        $username = "";
    }

    // Password checking
    $doesHaveNumber = preg_match('@[0-9]@', $password);
    $doesHaveUppercaseLetter = preg_match('@[A-Z]@', $password);
    $doesHaveLowercaseLetter = preg_match('@[a-z]@', $password);
    $doesHaveSpecialChars = preg_match('@[^\w]@', $password);

    if (strlen($password) < 8 || !$doesHaveNumber || !$doesHaveUppercaseLetter || !$doesHaveLowercaseLetter || !$doesHaveSpecialChars) {
        $passwordErrorMessage = "Password must be at least 8 characters in length and must contain at least one number, one upper case letter, one lower case letter and one special character.";
        $isAllInputValid = false;
        $password = $passwordConfirmation = "";
    } else {
        if (!($password === $passwordConfirmation)) {
            $isAllInputValid = false;
            $passwordErrorMessage = "Confirmation password does not match the entered password.";
        }
    }

    // Checking of database
    if ($isAllInputValid) {
        if (isUsernameExist($username)) {
            $usernameErrorMessage = "Username is taken.";
        } else { 
            // Insert all data to database or create user account
            if (!(mysqli_query($connection, createAccount($firstName,$middleName, $lastName, $username, $password)))) {
                echo "Error: " . $sql . "<br>" . mysqli_error($connection);
                echo "AN ERROR OCCURED, PLEASE CONTACT THE DEVELOPERS.";
            } else {
                $_SESSION["signedUp"] = 1;
                mysqli_close($connection);
                header("Location: login.php");
            }
        }
    }
}
?>

<!-- Include page header -->
<?php include 'header.php'; ?>

<!-- Page HTML -->
<div class="container d-flex align-items-center justify-content-center">
    <div class="form-body" id="signUpForm">
        <form action="signUp.php" method="post">
            <div class="row mb-5 text-center">
                <h1 class="display-6">Create an Account</h1>
                <span>Already have an account? <a href="login.php" class="link text-danger">Login</a> instead.</span>
            </div>
            <div class="row mb-3 g-3">
                <div class="col-sm-6">
                    <label for="firstName" class="form-label">First Name</label>
                    <input type="text" class="form-control" name="firstName" id="firstName" placeholder="Enter first name" required value="<?= $firstName ?>">
                    <span class="error-message text-danger "><?= $firstNameErrorMessage ?></span>
                </div>
                <div class="col-sm-6">
                    <label for="middleName" class="form-label">Middle Name</label>
                    <input type="text" class="form-control" name="middleName" id="middleName" placeholder="Enter middle name" value="<?= $middleName ?>">
                    <span class="error-message text-danger"><?= $middleNameErrorMessage ?></span>
                </div>
            </div>
            <div class="row mb-3 g-3">
                <div class="col-sm-6">
                    <label for="lastName" class="form-label">Last Name</label>
                    <input type="text" class="form-control" name="lastName" id="lastName" placeholder="Enter last name "required value="<?= $lastName ?>">
                    <span class="error-message text-danger"><?= $lastNameErrorMessage ?></span>
                </div>
                <div class="col-sm-6">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" name="username" id="username" placeholder="Enter username" required value="<?= $username ?>">
                    <span class="error-message text-danger"><?= $usernameErrorMessage ?></span>
                </div>
            </div>
            <div class="row mb-3 g-3">
                <div class="col">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" id="password" placeholder="Enter password" required value="<?= $password ?>">
                </div>
            </div>
            <div class="row mb-3 g-3">
                <div class="col">
                    <label for="passwordConfirmation" class="form-label fs-sm-1">Password Confirmation</label>
                    <input type="password" class="form-control" name="passwordConfirmation" id="passwordConfirmation" placeholder="Confirm password" required value="<?= $passwordConfirmation ?>">
                </div>
            </div>
            <div class="row mb-3 g-3">
                <div class="col">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="passwordVisibilityToggler" id="passwordVisibilityToggler" onclick="togglePasswordVisibilityInSignUp()">
                        <label for="passwordVisibilityToggler" class="form-check-label">Show password</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3 g-3">
                <div class="col text-center">
                    <p class="error-message text-danger"><?= $passwordErrorMessage ?></p>
                </div>
            </div>
            <div class="row mt-4 mb-3">
                <div class="col d-grid">
                    <button class="btn btn-danger fw-bold" type="submit" name="signUp">Sign Up</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Include page footer -->
<?php include 'footer.php'; ?>