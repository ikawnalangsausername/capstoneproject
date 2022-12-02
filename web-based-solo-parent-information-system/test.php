<!-- Include database connection and functions -->
<?php include 'includes/connection.php'; ?>
<?php include 'includes/functions.php'; ?>

<?php

global $connection;

global $connection;

$sql = "SELECT SoloParentID FROM children WHERE SoloParentID =1;";
$result = mysqli_query($connection, $sql);
$numberOfChildren = mysqli_num_rows($result);

print_r($numberOfChildren);
?>