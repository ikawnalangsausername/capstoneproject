<!-- Include database connection and functions -->
<?php include 'includes/connection.php'; ?>
<?php include 'includes/functions.php'; ?>

<!-- Page PHP Code -->
<?php 
// Declare variables
$inputUsername = $inputPassword = $loginError = "";

// Check form if submitted
if ($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['logIn'])) {
    // Get and sanitize input
    $inputUsername = strtolower(mysqli_real_escape_string($connection,$_POST['username']));
    $inputPassword = mysqli_real_escape_string($connection,$_POST['password']);
    // Check database if username exist
    if (isUsernameExist($inputUsername)) {
        // Fetch users account data
        $userAccountData = getAccountData($inputUsername);
        // Check if input password is correct
        $userAccountPassword = $userAccountData['Password'];
        if (!isPasswordCorrect($inputPassword,$userAccountPassword)) {
            $loginError = "Invalid username or password";
        } else {
            $_SESSION['loggedAccount'] = $userAccountData;
            date_default_timezone_set('Asia/Manila');
            // Check if account is active
            if ($_SESSION['loggedAccount']['AccountStatus'] !== 'Active' && $_SESSION['loggedAccount']['AccountStatus'] !== 'Admin') {
                $loginError = "Your account is either deactivated or still pending for account activation, please contact the admin.";
                session_unset();
                session_destroy();
            } else {
                // close our connection
                mysqli_close($connection);
                header("Location: index.php");
            }
        }
    } else {
        $loginError = "Invalid username or password";
    }
}
?>

<!-- Include page header -->
<?php include 'header.php'; ?>

<!-- Page HTML -->
<div class="container d-flex flex-column align-items-center justify-content-center">
    <?php
        if (isSignedUpSuccessfully()) {
            displaySignedUpSuccessfully();
            unset($_SESSION['signedUp']);
        }
    ?>
    <div class="form-body ps-5">
        <form action="login.php" method="post">
            <div class="row mb-4 text-center">
                <h1 class="fs-3 fw-bolder text-danger">SOLO PARENT INFORMATION SYSTEM</h1>
            </div>
            <div class="row mb-3">
                <input type="text" class="form-control" name="username" id="username" placeholder="Username" value="<?= $inputUsername ?>">
            </div>
            <div class="row mb-3">
                <input type="password" class="form-control" name="password" id="password" placeholder="Password">
            </div>
            <div class="row mb-4 text-center">
                <span class="text-danger fw-bold"><?= $loginError ?></span>
            </div>
            <div class="row mb-3 text-center">
                <button class="btn btn-danger mb-4 fw-bold" type="submit" name="logIn">Login</button>
            </div>
            <div class="row">
                <hr>
            </div>
            <div class="row">
                <div class="col d-flex justify-content-center">
                    <a href="signup.php">
                        <button type="button" class="btn btn-outline-danger fw-bold">Create an Account</button>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Include page footer -->
<?php include 'footer.php'; ?>