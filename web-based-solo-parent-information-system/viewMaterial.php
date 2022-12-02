<?php
    if (isset($_GET['viewMaterial'])) {
        $file = $_GET['InformativeMaterial'];
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$file?></title>
    <link rel="stylesheet" href="css/styleReset.css">
</head>
<body>
    <embed src="informativeMaterials/<?=$file?>" type="application/pdf" style="min-height:100vh;width:100%">
</body>
</html>