<!-- Connection of the database -->
<?php 
$serverName = 'localhost';
$databaseUsername = 'root';
$databasePassword = '';
$databaseName = 'solo_parent_information_system_db';

// Defining the connection 
$connection = mysqli_connect($serverName, $databaseUsername, $databasePassword, $databaseName);

// Checking the connection 
if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}
date_default_timezone_set('Asia/Manila');
?>