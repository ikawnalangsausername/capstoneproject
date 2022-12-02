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
?>

<!-- Include page header -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$soloParentID . " " . $soloParentRecord['LastName'] . ', ' . $soloParentRecord['FirstName'];?> ID</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- Roboto Font -->
    <link rel="stylesheet" href="css/robotoFont.css">
    <!-- CSS Reset -->
    <link rel="stylesheet" href="css/styleReset.css">
    <!-- CSS -->
    <link rel="stylesheet" href="css/baseStyle.css">
    <!-- Font Awesome -->
    <script src="js/fontawesome.js" crossorigin="anonymous"></script>
    <!-- Chart.js -->
    <script src="js/chart.js"></script>
    <!-- DataTables -->
    <link rel="stylesheet" href="css/dataTables.bootstrap5.min.css">
    <style>
        #bondPaper {
            width: 8.5in;
            height: 11in;
            background-image: none !important;
        }
        #frontID, #backID{
            width: 3.375in;
            height: 2.125in;
            border: 1px solid black;
        }
        #logo {
            width: calc(2in/4);
            height: calc(2in/4);
        }
        .extra-small-text {
            font-size: 9px;
        }
        .small-text {
            font-size: 10px;
        }
        .medium-text {
            font-size: 11px;
        }
        .large-text {
            font-size: 12px;
        }
        #header {
            height: calc(2in/4);
        }
        .row, .col-2, .col-8, .col-12, .col, .col-4, .col-5, .col-3, .col-6 {
            margin: 0;
            padding: 0;
        }
        #soloParentPicture {
            max-width: 1in;
            max-height: 1in;
            border: 1px solid black;
            box-sizing: border-box;
        }
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }
        #lastRow {
            position: relative;
            bottom: 0px;
        }
        table.no-spacing {
        border-spacing:0; /* Removes the cell spacing via CSS */
        border-collapse: collapse;  /* Optional - if you don't want to have double border where cells touch */
        }
    </style>
<body>
    <div class="container d-flex justify-content-evenly" id="bondPaper">
        <div class="row d-flex justify-content-center">
            <div class="col me-3">
                <div class="row px-1 py-2 d-block" id="frontID">
                    <div class="row" id="header">
                        <div class="col-2">
                            <img id="logo" src="images/CabuyaoLogo.jpg">
                        </div>
                        <div class="col-8 text-center">
                            <div class="row px-1">
                                <div class="col-12 small-text">
                                    Republic of the Philippines
                                </div>
                                <div class="col-12 small-text">
                                    City Social Welfare and Development Office
                                </div>
                                <div class="col-12 medium-text">
                                    City of Cabuyao, Laguna
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            
                        </div>
                    </div>
                    <div class="row">
                        <div class="col large-text text-center">
                            SOLO PARENT ID
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <img id="soloParentPicture" src="images/SamplePicture.png" alt="">
                        </div>
                        <div class="col-5 mt-5">
                            <p class="text-center medium-text text-decoration-underline fw-bold">
                                <?php if (empty($soloParentRecord['MiddleName'])){
                                    echo $soloParentRecord['FirstName'] . ' ' . $soloParentRecord['LastName']; 
                            } else {
                                echo $soloParentRecord['FirstName'] . ' ' . $soloParentRecord['MiddleName'][0] . '. ' . $soloParentRecord['LastName'];
                            }?></p>
                            <p class="text-center small-text">Name</p>
                        </div>
                        <div class="col-3">
                            <p class="text-center extra-small-text text-decoration-underline fw-bold"><?=$soloParentRecord['ControlNumber']?></p>
                            <p class="text-center small-text"><?php
                            if (empty($soloParentRecord['DateLastRenewed'])) {
                                echo "(New)";
                            } else {
                                echo "(Renewal)";
                            }
                            ?></p>
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-6">
                            <p class="text-center small-text text-decoration-underline fw-bold"><?=$_COOKIE['cswdoOfficer']?></p>
                            <p class="text-center extra-small-text">City Social Welfare & Development Officer</p>
                        </div>
                        <div class="col-6">
                            <p class="text-center medium-text text-decoration-underline fw-bold">HON. <?=$_COOKIE['cityMayor']?></p>
                            <p class="text-center small-text">City Mayor</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="row px-1 py-2 d-block position-relative" id="backID"> 
                    <div class="row">
                        <div class="col extra-small-text mb-1"><span class="fw-bold">Address: </span><?=$soloParentRecord['StreetAddress'] . ", " . $soloParentRecord['Barangay']?>, CITY OF CABUYAO, LAGUNA</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col extra-small-text"><span class="fw-bold">Date of Birth: </span><?=strtoupper(date_format(date_create($soloParentRecord['Birthdate']), "F d, Y"))?></div>
                        <div class="col extra-small-text"><span class="fw-bold">Gender: </span><?php if ($soloParentRecord['Sex'] == 'M') {
                            echo "MALE";
                        } else {
                            echo "FEMALE";
                        }?></div>
                    </div>
                    <div class="row">
                        <table class="" style="font-size: 10px; height: 50px">
                            <thead>
                                <tr>
                                    <th class="fw-bold text-center">Name</th>
                                    <th class="fw-bold text-center">Date of Birth</th>
                                    <th class="fw-bold text-center">Relationship</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    generateChildTableForID($soloParentID)
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="row mt-4">
                        <div class="col-8"  style="font-size: 3px;">
                            <p class="extra-small-text">This card is not transferrable and valid until:</p>
                            <p class="extra-small-text">January 1, <?=$_COOKIE['currentYear']?> - December 31, <?=$_COOKIE['currentYear']?></p>
                        </div>
                    </div>
                    <div class="row position-absolute mb-1" id="lastRow" >
                        <div class="col-7 text-center" style="border-top: 1px solid black;">
                            <span class="fw-bold extra-small-text">Validated by: <?=$_COOKIE['idVerifier']?></span>
                        </div>
                        <div class="col-1"></div>
                        <div class="col-3 text-center" style="border-top: 1px solid black;">
                            <span class="fw-bold extra-small-text d-inline-block">Signature</span>
                        </div>
                        <div class="col-1"></div>
                    </div>
                </div>
            </div>
        </div>
    </div> 
<script>
    window.print();
    window.onafterprint = function(){
        location.replace("generateSoloParentID.php");
    }
</script>
<!-- Include page footer
<?php include 'footer.php'; ?>