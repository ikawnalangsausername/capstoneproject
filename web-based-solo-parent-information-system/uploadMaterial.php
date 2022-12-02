<!-- Include database connection and functions -->
<?php include 'includes/connection.php'; ?>
<?php include 'includes/functions.php'; ?>

<?php
if (isset($_POST['uploadReport'])) {
    $pdf = $_FILES['pdf']['name'];
    $fileExt = explode('.', $pdf);
    $fileActualExt = strtolower(end($fileExt));
    $allowed = ['pdf','doc','docx', 'xlsx'];
    if (in_array($fileActualExt, $allowed)) {
        if (isInformativeMaterialExist($pdf)){
            header("Location: generateSoloParentReport.php?informativeMaterialExist");
        } else {
            $pdf_type = $_FILES['pdf']['type'];
            $pdf_size = $_FILES['pdf']['size'];
            $pdf_tem_loc= $_FILES['pdf']['tmp_name'];
            $pdf_store = "informativeMaterials/".$pdf;
            move_uploaded_file($pdf_tem_loc, $pdf_store);
            $dateToday = date("Y-m-d");
    
            $sql = "INSERT INTO informativematerials (InformativeMaterialID, InformativeMaterialCategory, InformativeMaterial, DateAdded) 
            VALUES (NULL, 'REPORT', '$pdf', '$dateToday')";
            $result = mysqli_query($connection, $sql);
    
            header("Location: generateSoloParentReport.php?uploadSuccessful");
        }
    } else {
        header("Location: generateSoloParentReport.php?invalidFileFormat");
    }
    
    
}
?>