<?php include 'includes/connection.php'; ?>

<?php
    if (isset($_GET['deleteMaterial'])) {
        $path = "informativeMaterials/".$_GET['InformativeMaterial'];
        unlink($path);

        $sql = "DELETE FROM informativematerials WHERE InformativeMaterialID ='".$_GET['InformativeMaterialID']."'";
        $result = mysqli_query($connection, $sql);

        if ($_GET['deleteMaterial'] === 'IM') {
            header("Location: archiveInformativeMaterial.php?reportDeletionSuccessful");
        } else {
            header("Location: generateSoloParentReport.php?reportDeletionSuccessful");
        }
    }
?>