<?php 
    session_start();

    // Remove spaces, slashes and special characters for html
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    // Gives sql for inserting a new account in accounts table in database
    function createAccount($firstName, $middleName, $lastName, $username, $password) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO accounts (AccountID, FirstName, MiddleName, LastName, Username, Password, AccountStatus) 
                VALUES (NULL, '$firstName', '$middleName', '$lastName', '$username', '$password', 'Pending for activation');";
        return $sql;
    }

    // Checks if a username exist in the database
    function isUsernameExist($username) {
        global $connection;

        $sql = "SELECT Username FROM accounts WHERE Username ='$username';";
        $result = mysqli_query($connection, $sql);
        
        return (mysqli_num_rows($result) > 0);
    }

    // Gets the data from an account via username
    function getAccountData($username) {
        global $connection; 

        $sql = "SELECT * FROM accounts WHERE Username = '$username';";
        $result = mysqli_query($connection, $sql);

        return mysqli_fetch_assoc($result);
    }

    // Checks if entered password and password in database match
    function isPasswordCorrect($password, $encryptedPassword) {
        return password_verify($password, $encryptedPassword);
    }

    // Checks if someone is logged in in the system
    function isSomeoneIsLoggedIn() {
        return (isset($_SESSION['loggedAccount'])); 
    }

    // Checks if someone is successfully signed up
    function isSignedUpSuccessfully() {
        return (isset($_SESSION["signedUp"]));
    }

    function displaySignedUpSuccessfully() {
        echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                <span class='fw-bold'>Congratulations!</span> You have successfully created an account and is pending for activation.
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
    }

    function updateAccountProfile($accountID, $username, $firstName, $middleName, $lastName) {
        $sql = "UPDATE accounts 
                SET FirstName = '$firstName', MiddleName = '$middleName', LastName = '$lastName', Username = '$username'
                WHERE accounts.AccountID = $accountID;";

        return $sql;
    }

    function updateAccountProfileAndPassword($accountID, $username, $firstName, $middleName, $lastName, $newPassword) {
        $newPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        $sql = "UPDATE accounts 
                SET FirstName = '$firstName', MiddleName = '$middleName', LastName = '$lastName', Username = '$username', Password = '$newPassword'
                WHERE accounts.AccountID = $accountID;";
        return $sql;
    }

    function generateEmployeesAccountList(){
        global $connection;
        $sql = "SELECT AccountID, FirstName, MiddleName, LastName, Username, AccountStatus 
                FROM accounts
                WHERE AccountID  != 1;";
        $result = mysqli_query($connection, $sql);

        while ($row = mysqli_fetch_assoc($result)) {
            $currentAccountStatus = $row['AccountStatus'];
            $buttonAction = 'Activate';
            $action = 'activation';
            if ($currentAccountStatus === 'Active') {
                $buttonAction = 'Deactivate';
                $action = 'deactivation';
            }

            echo "<tr class='table-row'>
                    <td>" . $row['AccountID'] . "</td>
                    <td>" . $row['Username'] . "</td>
                    <td>" . $row['FirstName'] . "</td>
                    <td>" . $row['MiddleName'] . "</td>
                    <td>" . $row['LastName'] . "</td>
                    <td>" . $row['AccountStatus'] ."</td>
                    <td>
                        <button class='btn btn-danger fw-bolder' data-bs-toggle='modal' data-bs-target='#confirmPasswordModal" . $row['AccountID'] . "' type='button'>". $buttonAction ."</button>
                        <div class='modal fade' id='confirmPasswordModal" . $row['AccountID'] . "' tabindex='-1' aria-labelledby='passwordConfirmation' aria-hidden='true'>
                        <div class='modal-dialog modal-dialog-centered'>
                            <div class='modal-content'>
                            <div class='modal-header'>
                                <h1 class='modal-title fs-md fw-bolder' id='passwordConfirmation'>Confirm ". $action ." of " . $row['FirstName'] . " " . $row['LastName'] . "'s account</h1>
                                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                            </div>
                            <form method='POST' action='employeeAccountManagement.php'>
                                <div class='modal-body'>
                                    <div class='row'>
                                        <div class='col'>
                                            <input type='hidden' name='selectedAccountID' value='" . $row['AccountID'] . "'>
                                            <input type='hidden' name='status' value='" . $row['AccountStatus'] . "'>
                                            <label for='adminPasswordConfirmation' class='form-label'>Enter your current password</label>
                                            <input type='password' class='form-control' name='adminPasswordConfirmation' id='adminPasswordConfirmation' placeholder='Current password'>
                                        </div>
                                    </div>
                                </div>
                                <div class='modal-footer'>
                                    <button type='button' class='btn btn-danger' data-bs-dismiss='modal'>Cancel</button>
                                    <button type='submit' class='btn btn-outline-danger' name='updateEmployeeAccountStatus'>Confirm</button>
                                </div>
                                </div>
                            </form>
                        </div>
                        </div>
                    </td>
                </tr>";
        }
    }

    function updateEmployeeAccountStatus($employeeAccountID, $accountStatus){
        global $connection;
        $accountStatusUpdate = 'Active';

        if ($accountStatus === 'Active'){
            $accountStatusUpdate = 'Deactivated';
        }
        
        $sql = "UPDATE accounts 
                SET AccountStatus = '$accountStatusUpdate'
                WHERE accounts.AccountID = $employeeAccountID;";

        return mysqli_query($connection, $sql);
    }

    function generateBarangayOptions($selectedBarangay) {
        global $connection;

        $sql = "SELECT * FROM barangays";
        $result = mysqli_query($connection, $sql);

        while ($row = mysqli_fetch_assoc($result)) {
            if ($selectedBarangay === $row['BarangayID']) {
                echo '<option selected value="' . $row['BarangayID'] . '">' . $row['Barangay'] . '</option>';
                continue;
            }
            echo '<option value="' . $row['BarangayID'] . '">' . $row['Barangay'] . '</option>';
        }
    }

    function generateNaturesOfSoloParentOptions($selectedNature) {
        global $connection;

        $sql = "SELECT * FROM naturesofsoloparent";
        $result = mysqli_query($connection, $sql);

        while ($row = mysqli_fetch_assoc($result)) {
            if ($selectedNature === $row['NatureOfSoloParentID']){
                echo '<option selected value="' . $row['NatureOfSoloParentID'] . '">' . $row['NatureOfSoloParent'] . '</option>';
                continue;
            }
            echo '<option value="' . $row['NatureOfSoloParentID'] . '">' . $row['NatureOfSoloParent'] . '</option>';
        }
    }

    function generatePwdStatusOptions($selectedPWDStatus){
        global $connection;

        $sql = "SELECT * FROM pwdstatus";
        $result = mysqli_query($connection, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            if($selectedPWDStatus === $row['PWDStatusID']) {
                echo '<option selected value="' . $row['PWDStatusID'] . '">' . $row['PWDStatus'] . '</option>';
                continue;
            }
            echo '<option value="' . $row['PWDStatusID'] . '">' . $row['PWDStatus'] . '</option>';
        }
    }

    function generateRelationshipToSoloParentCategories(){
        global $connection;

        $sql = "SELECT * FROM relationshiptosoloparentcategories";
        $result = mysqli_query($connection, $sql);

        while ($row = mysqli_fetch_assoc($result)) {
            echo '<option value="' . $row['RelationshipToSoloParentCategoryID'] . '">' . $row['RelationshipToSoloParentCategory'] . '</option>';
        }
    }

    function displayChildFirstNameErrorMessage($childNumber){
        echo "<script>
            document.getElementById('child" . $childNumber . "FirstNameErrorMessage').innerText = 'Invalid first name';
        </script>";
    }

    function displayChildFirstName($childNumber, $childFirstName) {
        echo "<script>
            document.getElementById('child" . $childNumber . "FirstName').value = '". $childFirstName . "';
        </script>";
    }
    
    function displayChildMiddleNameErrorMessage($childNumber){
        echo "<script>
            document.getElementById('child" . $childNumber . "MiddleNameErrorMessage').innerText = 'Invalid middle name';
        </script>";
    }

    function displayChildMiddleName($childNumber, $childMiddleName) {
        echo "<script>
            document.getElementById('child" . $childNumber . "MiddleName').value = '". $childMiddleName . "';
        </script>";
    }
    
    function displayChildLastNameErrorMessage($childNumber){
        echo "<script>
            document.getElementById('child" . $childNumber . "LastNameErrorMessage').innerText = 'Invalid last name';
        </script>";
    }

    function displayChildLastName($childNumber, $childLastName) {
        echo "<script>
            document.getElementById('child" . $childNumber . "LastName').value = '". $childLastName . "';
        </script>";
    }

    function displayChildSexErrorMessage($childNumber) {
        echo "<script>
            document.getElementById('child" . $childNumber . "SexErrorMessage').innerText = 'Please select child\'s sex';
        </script>";
    }

    function displayChildSex($childNumber,$selectedSex) {
        echo "<script>
            document.getElementById('child" . $childNumber . "Sex".$selectedSex ."').selected = true;
        </script>";
    }

    function displayChildBirthdateErrorMessage($childNumber){
        echo "<script>
            document.getElementById('child" . $childNumber . "BirthdateErrorMessage').innerText = 'Invalid birthdate';
        </script>";
    }

    function displayChildBirthdate($childNumber,$birthdate) {
        echo "<script>
            document.getElementById('child" . $childNumber . "Birthdate').value = '". $birthdate ."';
        </script>";
    }

    function displayRelationshipToSoloParentErrorMessage($childNumber){
        echo "<script>
            document.getElementById('child" . $childNumber . "RelationshipErrorMessage').innerText = 'Please select a relationship';
        </script>";
    }

    function displayRelationshipToSoloParent($childNumber,$selectedRelationship) {
        echo "<script>
            document.getElementById('child" . $childNumber . "RelationshipToSoloParent".$selectedRelationship ."').selected = true;
        </script>";
    }

    function checkIfSoloParentExist($firstName, $middleName, $lastName) {
        global $connection;
        
        $sql = "SELECT Firstname 
                FROM soloparents 
                WHERE FirstName = '". strtoupper($firstName) ."' && MiddleName = '". strtoupper($middleName) ."' && LastName = '". strtoupper($lastName) ."';";
        $result = mysqli_query($connection, $sql);
        
        return (mysqli_num_rows($result) > 0);
    }

    function checkIfChildExist($firstName, $middleName, $lastName) {
        global $connection;
        
        $sql = "SELECT Firstname 
                FROM children 
                WHERE FirstName = '". strtoupper($firstName) ."' && MiddleName = '". strtoupper($middleName) ."' && LastName = '". strtoupper($lastName) ."';";
        $result = mysqli_query($connection, $sql);
        
        return (mysqli_num_rows($result) > 0);
    }

    function checkIfControlNumberExist($controlNumber) {
        global $connection;  

        $sql = "SELECT ControlNumber FROM soloparents WHERE ControlNumber = '". strtoupper($controlNumber) ."'";
        $result = mysqli_query($connection, $sql);

        return (mysqli_num_rows($result) > 0);
    }

    function addSoloParent($controlNumber, $firstName, $middleName, $lastName, $sex, $birthdate, $barangayID, $streetAddress, $occupation, $phoneNumber, $natureOfSoloParentID, $pwdStatusID, $fourPsStatus) {
        $controlNumber = strtoupper($controlNumber);
        $firstName = strtoupper($firstName);
        $middleName = strtoupper($middleName);
        $lastName = strtoupper($lastName);
        $streetAddress = strtoupper($streetAddress);
        $occupation = strtoupper($occupation);
        $dateToday = date("Y-m-d");

        $sql = "INSERT INTO soloparents (`SoloParentID`, `ControlNumber`, `FirstName`, `MiddleName`, `LastName`, `Sex`, `Birthdate`, `BarangayID`, `StreetAddress`, `Occupation`, `PhoneNumber`, `NatureOfSoloParentID`, `PWDStatusID`, `4PsStatus`, `MembershipStatus`, `DateJoined`, `DateLastRenewed`, `Remarks`) 
                VALUES (NULL, '". $controlNumber ."', '". $firstName ."', '". $middleName ."', '". $lastName ."', '". $sex ."', '". $birthdate ."', '". $barangayID ."', '". $streetAddress ."', '". $occupation ."', '". $phoneNumber ."', '". $natureOfSoloParentID ."', '". $pwdStatusID ."', '". $fourPsStatus ."', 'ACTIVE', '". $dateToday ."', NULL, NULL);";

        return $sql;
    }

    function addChild($controlNumber ,$firstName, $middleName, $lastName, $birthdate, $sex, $relationshipToSoloParentCategoryID) {
        global $connection;

        $sql = "SELECT SoloParentID FROM soloparents WHERE ControlNumber = '". $controlNumber ."'";
        $result = mysqli_query($connection, $sql);
        $row = mysqli_fetch_row($result);
        
        $soloParentID = $row[0];

        $sql = "INSERT INTO `children` (`ChildID`, `FirstName`, `MiddleName`, `LastName`, `Birthdate`, `Sex`, `SoloParentID`, `RelationshipToSoloParentCategoryID`) 
                VALUES (NULL, '". $firstName ."', '". $middleName ."', '". $lastName ."', '$birthdate', '". $sex ."', '". $soloParentID ."', '". $relationshipToSoloParentCategoryID ."');";

        return $sql;
    }

    function generateSoloParentRecords() {
        global $connection; 

        $sql = "SELECT SoloParentID, ControlNumber, LastName, FirstName, MiddleName, Birthdate, Barangay, NatureOfSoloParent, PWDStatus, MembershipStatus, DateJoined, DateLastRenewed
                FROM soloparents
                INNER JOIN barangays
                    ON soloparents.BarangayID = barangays.BarangayID
                INNER JOIN naturesofsoloparent
                    ON soloparents.NatureOfSoloParentID = naturesofsoloparent.NatureOfSoloParentID
                INNER JOIN pwdstatus
                    ON soloparents.PWDStatusID = pwdstatus.PWDStatusID
                ORDER BY SoloParentID DESC";

        $result = mysqli_query($connection, $sql);

        while ($row = mysqli_fetch_assoc($result)) {
            // Get age 
            $dateOfBirth = $row['Birthdate'];
            $today = date("Y-m-d");
            $diff = date_diff(date_create($dateOfBirth), date_create($today));
            $fullName = '';
            if (empty($row['MiddleName'][0])) {
                $fullName = $row['LastName'] .", ".$row['FirstName'];
            } else {
                $fullName = $row['LastName'] .", ".$row['FirstName']. " " .$row['MiddleName'][0] . ".";
            }
            $buttons = "<form  action='editOrViewSoloParentRecord.php' class='mb-1' method='GET'>
                            <input type='hidden' name='SoloParentID' value='" . $row['SoloParentID'] ."'>
                            <button class='btn btn-danger btn-sm fw-bold fs-xs' type='submit'>View/Update</button>
                        </form>
                        <form  action='renewSoloParentRecord.php' method='GET'>
                            <input type='hidden' name='SoloParentID' value='" . $row['SoloParentID'] ."'>
                            <button class='btn btn-outline-danger btn-sm fw-bold fs-xs' type='submit'>Renew</button>
                        </form>";
            $dateLastRenewed = $row['DateLastRenewed'];

            if (empty($row['MiddleName'])) {
                $fullName = $row['LastName'] .", ".$row['FirstName'];
            }

            if ($row['MembershipStatus'] === "ACTIVE") {
                $buttons = "<form  action='editOrViewSoloParentRecord.php' class='mb-1' method='GET'>
                                <input type='hidden' name='SoloParentID' value='" . $row['SoloParentID'] ."'>
                                <button class='btn btn-danger btn-sm fw-bold fs-xs' type='submit'>View/Update</button>
                            </form>";
                
            }

            if (empty($row['DateLastRenewed'])) {
                $dateLastRenewed = "N/A";
            } else {
                $dateLastRenewed = strtoupper(date_format(date_create($row['DateLastRenewed']), "M d, Y"));
            }

            echo "<tr class='table-row'>
                    <td>" .$row['ControlNumber'] . "</td>
                    <td>" . $fullName ."</td>
                    <td>" .$diff->format('%y') . "</td>
                    <td>" .$row['Barangay'] . "</td>
                    <td>" .$row['NatureOfSoloParent'] . "</td>
                    <td>" .$row['MembershipStatus'] . "</td>
                    <td>" .strtoupper(date_format(date_create($row['DateJoined']), "M d, Y")). "</td>
                    <td>" . $dateLastRenewed. "</td>
                    <td>" .$buttons. "</td>    
                </tr>";
        }
    }

    function getNumberOfChildren($soloParentID) {
        global $connection;

        $sql = "SELECT SoloParentID FROM children WHERE SoloParentID = $soloParentID;";
        $result = mysqli_query($connection, $sql);
        $numberOfChildren = mysqli_num_rows($result);

        return $numberOfChildren;
    }

    function getSoloParentChildren($soloParentID) {
        global $connection;

        $sql = "SELECT * FROM children WHERE SoloParentID = $soloParentID;";
        $result = mysqli_query($connection, $sql);
        $childrenDetails = [];

        while ($row = mysqli_fetch_assoc($result)) {
            array_push($childrenDetails,$row);
        }

        return $childrenDetails;
    }

    function displayChildrenData($numberOfChild, $childrenDetails) {
        global $connection;
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['updateSoloParent'])) {
            for ($i = 0; $i < $numberOfChild; $i++) {
                $childNumber = $i+1;
                $childFirstName = strtoupper(mysqli_real_escape_string($connection, test_input($_POST['child' . $childNumber . 'FirstName'])));
                $childMiddleName = strtoupper(mysqli_real_escape_string($connection, test_input($_POST['child' . $childNumber . 'MiddleName'])));
                $childLastName = strtoupper(mysqli_real_escape_string($connection, test_input($_POST['child' . $childNumber . 'LastName'])));
                $childBirthdate = $_POST['child' . $childNumber . 'Birthdate'];
                
                if (empty($childFirstName) || !preg_match("/^[a-zA-Z-' ]*$/", $childFirstName)) {
                    displayChildFirstNameErrorMessage($childNumber);
                } else {
                    displayChildFirstName($childNumber, $childFirstName);
                }
                if (!empty($childMiddleName) && !preg_match("/^[a-zA-Z-' ]*$/", $childMiddleName)) {
                    displayChildMiddleNameErrorMessage($childNumber);
                } else {
                    displayChildMiddleName($childNumber, $childMiddleName);
                }
    
                if (empty($childLastName) || !preg_match("/^[a-zA-Z-' ]*$/", $childLastName)) {
                    displayChildLastNameErrorMessage($childNumber);
                } else {
                    displayChildLastName($childNumber, $childLastName);
                }
        
                if (!isset($_POST['child' . $childNumber . 'Sex'])) {
                    displayChildSexErrorMessage($childNumber);
                } else {
                    $childSex = $_POST['child' . $childNumber . 'Sex'];
                    displayChildSex($childNumber, $childSex);
                }
        
                if (empty($_POST['child' . $childNumber . 'Birthdate']) || $_POST['child' . $childNumber . 'Birthdate'] > date('Y-m-d')) {
                    displayChildBirthdateErrorMessage($childNumber);
                } else {
                    displayChildBirthdate($childNumber, $childBirthdate);
                }
        
                if (!isset($_POST['child' . $childNumber . 'RelationshipToSoloParent'])) {
                    displayRelationshipToSoloParentErrorMessage($childNumber);
                } else {
                    $childRelationshipToSoloParent = $_POST['child' . $childNumber . 'RelationshipToSoloParent'];
                    displayRelationshipToSoloParent($childNumber, $childRelationshipToSoloParent);
                }
            }
        } else {
            for ($i = 0; $i < $numberOfChild; $i++) {
                $childNumber = $i+1;
                $childDetails = $childrenDetails[$i];
                $childFirstName = $childDetails['FirstName'];
                $childMiddleName = $childDetails['MiddleName'];
                $childLastName = $childDetails['LastName'];
                $childBirthdate = $childDetails['Birthdate'];
                $childSex = $childDetails['Sex'];
                $childRelationshipToSoloParent = $childDetails['RelationshipToSoloParentCategoryID'];
                
                
                displayChildFirstName($childNumber, $childFirstName);
                displayChildMiddleName($childNumber, $childMiddleName);
                displayChildLastName($childNumber, $childLastName);
                displayChildSex($childNumber, $childSex);
                displayChildBirthdate($childNumber, $childBirthdate);
                displayRelationshipToSoloParent($childNumber, $childRelationshipToSoloParent);
                displayChildAgeField($childNumber, $childBirthdate);
            }
        }
    }

    function displayChildAgeField($childNumber,$birthdate) {
        // Get age 
        $dateOfBirth = $birthdate;
        $today = date("Y-m-d");
        $diff = date_diff(date_create($dateOfBirth), date_create($today));
        $age = '';
        
        if ($diff->format('%y') > 0){
            $age = $diff->format('%y Year/s Old');
        } else{
            if (($diff->format('%m') > 0)) {
                $age = $diff->format('%m Month/s Old');
            } else {
                $age = $diff->format('%d Day/s Old');
            }
        }
        
        echo "
        <script>
            for (let i = 0; i < 1; i++) {
                let ageRowDiv = document.createElement('div');
                ageRowDiv.className = 'row mb-3';
    
                let newCol = document.createElement('div');
                newCol.className = 'col-lg-6';
    
                let ageLabel = document.createElement('label');
                ageLabel.for = 'child". $childNumber ."Age';
                ageLabel.className = 'form-label';
                ageLabel.innerHTML = 'Age';
    
                let ageField = document.createElement('input');
                ageField.type = 'text';
                ageField.disabled = 'true';
                ageField.className = 'form-control';
                ageField.value = '". $age ."';
                ageField.id = 'child". $childNumber ."Age';
    
                newCol.appendChild(ageLabel);
                newCol.appendChild(ageField);
    
                ageRowDiv.appendChild(newCol);
    
                let childDiv = document.getElementById('child".$childNumber."Div');
                
                childDiv.appendChild(ageRowDiv);
            }
        </script>
        ";
    }

    function isFullNameSameAsRecord($soloParentRecord,$firstName, $middleName, $lastName) {
        return $firstName === $soloParentRecord['FirstName'] && $middleName === $soloParentRecord['MiddleName'] && $lastName === $soloParentRecord['LastName'];
    }

    function isChildNameSameAsRecord($childNumber, $childrenRecord, $firstName, $middleName, $lastName) {
        return $firstName === $childrenRecord[$childNumber]['FirstName'] && $middleName === $childrenRecord[$childNumber]['MiddleName'] && $lastName === $childrenRecord[$childNumber]['LastName'];
    }

    function updateSoloParentRecord($soloParentID, $controlNumber, $firstName, $middleName, $lastName, $sex, $birthdate, $barangayID, $streetAddress, $occupation, $phoneNumber, $natureOfSoloParentID, $pwdStatusID, $fourPsStatus, $remarks) {
        $sql = "UPDATE soloparents 
                SET ControlNumber = '".$controlNumber."', FirstName = '".$firstName."', MiddleName = '".$middleName."', LastName = '".$lastName."', Sex = '".$sex."', Birthdate = '".$birthdate."', BarangayID = '".$barangayID."', StreetAddress = '".$streetAddress."', Occupation = '".$occupation."', PhoneNumber = '".$phoneNumber."', NatureOfSoloParentID = '".$natureOfSoloParentID."', PWDStatusID = '".$pwdStatusID."', 4PsStatus = '".$fourPsStatus."', Remarks = '".$remarks."' 
                WHERE SoloParentID = ".$soloParentID.";";

        return $sql;
    }

    function updateChildRecord($childID ,$firstName, $middleName, $lastName, $birthdate, $sex, $relationshipToSoloParentCategoryID) {
        $sql = "UPDATE children
                SET FirstName ='".$firstName."', MiddleName = '".$middleName."', LastName = '".$lastName."', Birthdate ='".$birthdate."', Sex ='".$sex."', RelationshipToSoloParentCategoryID ='".$relationshipToSoloParentCategoryID."' 
                WHERE ChildID = '".$childID."';";

        return $sql;
    }

    function renewSoloParent($soloParentID) {
        $dateToday = date("Y-m-d");

        $sql = "UPDATE soloparents 
                SET MembershipStatus = 'ACTIVE', DateLastRenewed = '$dateToday'
                WHERE SoloParentID = '".$soloParentID."';";

        return $sql;
    }

    function getTotalPopulationOfSoloParents(){
        global $connection;
        
        $sql = "SELECT COUNT(*) FROM soloparents";
        $result = mysqli_query($connection, $sql);
        $finalResult = mysqli_fetch_array($result);

        $totalPopulationOfSoloParents = $finalResult[0];

        return $totalPopulationOfSoloParents;
    }

    function getTotalPopulationOfChildren(){
        global $connection;
        
        $sql = "SELECT COUNT(*) FROM children";
        $result = mysqli_query($connection, $sql);
        $finalResult = mysqli_fetch_array($result);

        $totalPopulationOfChildren = $finalResult[0];

        return $totalPopulationOfChildren;
    }

    function getNewSoloParentsCountToday() {
        global $connection; 

        $dateToday = date("Y-m-d");
        $sql = "SELECT COUNT(*) AS Population FROM soloparents WHERE DateJoined = '$dateToday'";
        $result = mysqli_query($connection, $sql);
        $finalResult = mysqli_fetch_assoc($result);

        return $finalResult['Population'];
    }

    function getRenewedSoloParentsCountToday() {
        global $connection; 

        $dateToday = date("Y-m-d");
        $sql = "SELECT COUNT(*) FROM soloparents WHERE DateLastRenewed = '$dateToday'";
        $result = mysqli_query($connection, $sql);
        $finalResult = mysqli_fetch_array($result);

        $renewedSoloParentsCountToday = $finalResult[0];

        return $renewedSoloParentsCountToday;
    }

    function getLeadingNatureOfSoloParent() {
        global $connection;

        $sql = "SELECT naturesofsoloparent.NatureOfSoloParent, COUNT(soloparents.NatureOfSoloParentID) 
                FROM soloparents 
                INNER JOIN naturesofsoloparent 
                    ON soloparents.NatureOfSoloParentID = naturesofsoloparent.NatureOfSoloParentID
                GROUP BY (NatureOfSoloParent)
                ORDER BY COUNT(soloparents.NatureOfSoloParentID) DESC;";

        $result = mysqli_query($connection, $sql);
        $finalResult = mysqli_fetch_row($result);

        $leadingNatureOfSoloParent = $finalResult[0];

        return $leadingNatureOfSoloParent;
    }

    function getBarangayNames() {
        global $connection;

        $barangays = [];
        $barangayNames = '';
        $sql = "SELECT Barangay FROM barangays;";
        $result = mysqli_query($connection, $sql);
        
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($barangays,$row['Barangay']);
        }

        for ($i = 0; $i < count($barangays); $i++) {
            $barangayNames .= "'" . $barangays[$i] . "', "; 
        }

        return $barangayNames;
    }

    function getTotalNumberOfBarangays() {
        global $connection;

        $sql = "SELECT COUNT(Barangay) FROM barangays;";
        $result = mysqli_query($connection, $sql);
        $finalResult = mysqli_fetch_row($result);

        $totalNumberOfBarangays = $finalResult[0];

        return $totalNumberOfBarangays;
    }

    function getPopulationPerBarangay() {
        global $connection;

        $populations = [];
        $populationPerBarangay = '';

        for ($i = 0; $i < getTotalNumberOfBarangays(); $i++) {
            $barangayID = $i + 1;
            $sql = "SELECT SoloParentID FROM soloparents WHERE BarangayID = $barangayID";
            $result = mysqli_query($connection, $sql);
            $population = mysqli_num_rows($result);

            array_push($populations, $population);
        }

        for ($i = 0; $i < count($populations); $i++) {
            $populationPerBarangay .= $populations[$i] . ", ";
        }

        return $populationPerBarangay;
    }

    function getTotalActiveSoloParents() {
        global $connection;

        $sql = "SELECT SoloParentID FROM soloparents WHERE MembershipStatus = 'ACTIVE';";
        $result = mysqli_query($connection, $sql);
        $number = mysqli_num_rows($result);
        return $number;
    }

    function getTotalForRenewalSoloParents() {
        global $connection;

        $sql = "SELECT SoloParentID FROM soloparents WHERE MembershipStatus = 'FOR RENEWAL';";
        $result = mysqli_query($connection, $sql);
        $number = mysqli_num_rows($result);
        return $number;
    }

    function getTotalInactiveSoloParents() {
        global $connection;

        $sql = "SELECT SoloParentID FROM soloparents WHERE MembershipStatus = 'INACTIVE';";
        $result = mysqli_query($connection, $sql);
        $number = mysqli_num_rows($result);
        return $number;
    }

    function getTotalIneligibleSoloParents() {
        global $connection;

        $sql = "SELECT SoloParentID FROM soloparents WHERE MembershipStatus = 'INELIGIBLE';";
        $result = mysqli_query($connection, $sql);
        $number = mysqli_num_rows($result);
        return $number;
    }

    function getPopulationPerMembershipStatus() {
        $populationPerMembershipStatus = getTotalActiveSoloParents() . ', ' . getTotalForRenewalSoloParents() . ', ' . getTotalInactiveSoloParents() . ', ' . getTotalIneligibleSoloParents();
        return $populationPerMembershipStatus;
    }

    function getAverageAgeOfSoloParents() {
        global $connection;

        $soloParentAges = [];

        $sql = "SELECT Birthdate FROM soloparents";
        $result = mysqli_query($connection, $sql);

        while ($row = mysqli_fetch_array($result)) {
            $dateOfBirth = $row[0];
            $today = date("Y-m-d");
            $diff = date_diff(date_create($dateOfBirth), date_create($today));
            array_push($soloParentAges,$diff->format('%y'));
        }

        $sumOfAges = 0;

        for ($i = 0; $i < count($soloParentAges); $i++) {
            $sumOfAges += $soloParentAges[$i];
        }

        $averageAgeOfSoloParents = round($sumOfAges / count($soloParentAges),0);

        return $averageAgeOfSoloParents;
    }

    function getTotalSoloParentsWith4Ps() {
        global $connection;

        $sql="SELECT SoloParentID FROM soloparents WHERE 4PsStatus = 'Y';";
        $result = mysqli_query($connection, $sql);

        $totalSoloParentsWith4Ps = mysqli_num_rows($result);

        return $totalSoloParentsWith4Ps;
    }

    function getTotalSoloParentsWithout4Ps() {
        global $connection;

        $sql="SELECT SoloParentID FROM soloparents WHERE 4PsStatus != 'Y';";
        $result = mysqli_query($connection, $sql);

        $totalSoloParentsWith4Ps = mysqli_num_rows($result);

        return $totalSoloParentsWith4Ps;
    }

    function getTotalNumberOfDisabledSoloParents() {
        global $connection;

        $sql="SELECT SoloParentID FROM soloparents WHERE PWDStatusID != 8;";
        $result = mysqli_query($connection, $sql);

        $totalNumberOfDisabledSoloParents = mysqli_num_rows($result);

        return $totalNumberOfDisabledSoloParents;
    }

    function updateMembershipStatus($currentYear, $todaysYear) {
        if ($todaysYear > $currentYear) {
            updateAllForRenewalSoloParentsStatus();
            updateAllActiveSoloParentsStatus();
            setcookie('currentYear', date('Y'), time() + (86400 * 3652));
        }
    }

    function updateAllForRenewalSoloParentsStatus() {
        global $connection;
        
        $sql = "UPDATE soloparents SET MembershipStatus = 'INACTIVE' WHERE MembershipStatus = 'FOR RENEWAL';";
        mysqli_query($connection, $sql);
    }

    function updateAllActiveSoloParentsStatus() {
        global $connection;
        
        $sql = "UPDATE soloparents SET MembershipStatus = 'FOR RENEWAL' WHERE MembershipStatus = 'ACTIVE';";
        mysqli_query($connection, $sql);
    }

    function markSoloParentAsIneligible($soloParentID) {
        $sql = "UPDATE soloparents 
                SET MembershipStatus = 'INELIGIBLE'
                WHERE SoloParentID = '".$soloParentID."';";
    
        return $sql;
    }

    function generateSoloParentRecordsForIDGeneration() {
        global $connection; 

        $sql = "SELECT SoloParentID, ControlNumber, LastName, FirstName, MiddleName, Birthdate, Barangay, NatureOfSoloParent, PWDStatus, MembershipStatus 
                FROM soloparents
                INNER JOIN barangays
                    ON soloparents.BarangayID = barangays.BarangayID
                INNER JOIN naturesofsoloparent
                    ON soloparents.NatureOfSoloParentID = naturesofsoloparent.NatureOfSoloParentID
                INNER JOIN pwdstatus
                    ON soloparents.PWDStatusID = pwdstatus.PWDStatusID
                WHERE MembershipStatus = 'ACTIVE'
                ORDER BY SoloParentID DESC";

        $result = mysqli_query($connection, $sql);

        while ($row = mysqli_fetch_assoc($result)) {
            // Get age 
            $dateOfBirth = $row['Birthdate'];
            $today = date("Y-m-d");
            $diff = date_diff(date_create($dateOfBirth), date_create($today));
                echo "<tr class='table-row'>
                <td>" .$row['ControlNumber'] . "</td>
                <td>" .$row['LastName'] . "</td>
                <td>" .$row['FirstName'] . "</td>
                <td>" .$row['MiddleName'] . "</td>
                <td>" .$diff->format('%y') . "</td>
                <td>" .$row['Barangay'] . "</td>
                <td>" .$row['NatureOfSoloParent'] . "</td>
                <td>" .$row['MembershipStatus'] . "</td>
                <td>
                    <form  action='generateID.php' class='mb-1' method='GET'>
                        <input type='hidden' name='SoloParentID' value='" . $row['SoloParentID'] ."'>
                        <button class='btn btn-danger btn-sm fw-bold fs-xs' type='submit'>Generate ID</button>
                    </form>
                </td>
            </tr>";
        }
    }

    function updateOfficials($cswdoOfficer, $cityMayor, $idVerifier) {
        setcookie('cswdoOfficer', $cswdoOfficer, time() + (86400 * 3652));
        setcookie('cityMayor', $cityMayor, time() + (86400 * 3652));
        setcookie('idVerifier', $idVerifier, time() + (86400 * 3652));
    }

    function setCurrentYear() {
        setcookie('currentYear', date('Y'), time() + (86400 * 3652));
    }

    function setTodaysYear() {
        setcookie('todaysYear', date('Y'), time() + (86400 * 3652));
    }

    function getSoloParentRecord($soloParentID) {
        global $connection;

        $sql = "SELECT SoloParentID, ControlNumber, FirstName, MiddleName, LastName, Sex, Birthdate, soloparents.BarangayID, barangays.Barangay, StreetAddress, Occupation, PhoneNumber, NatureOfSoloParentID, PWDStatusID, 4PsStatus, MembershipStatus, DateJoined, DateLastRenewed, Remarks 
                FROM soloparents 
                INNER JOIN barangays
                    ON soloparents.BarangayID = barangays.BarangayID 
                WHERE SoloParentID = '". $soloParentID ."';";
        $result = mysqli_query($connection, $sql);
        $soloParentRecord = mysqli_fetch_assoc($result);

        return $soloParentRecord;
    }

    function generateChildTableForID($soloParentID) {
        global $connection; 

        $sql = "SELECT * FROM children WHERE SoloParentID = $soloParentID";
        $result = mysqli_query($connection, $sql);

        while($row = mysqli_fetch_assoc($result)) {
            $relationship = '';
            if (empty($row['MiddleName'])) {
                echo "<tr>
                <td>".$row['FirstName']." ". $row['LastName']. "</td>";
            } else {
                echo "<tr>
                <td>".$row['FirstName']." " . $row['MiddleName'][0] . ". " . $row['LastName']. "</td>";
            }
            echo "<td>".strtoupper(date_format(date_create($row['Birthdate']), "F d, Y"))."</td>";

            if ($row['Sex'] == "M") {
                switch($row['RelationshipToSoloParentCategoryID']) {
                    case 1:
                    case 2: 
                        $relationship = "SON";
                    break;
                    case 3: 
                        $relationship = "BROTHER";
                    break;
                    case 4:
                    case 5: 
                        $relationship = "STEPSON";
                    break;
                    case 6: 
                        $relationship = "STEP-BROTHER";
                    break;
                    case 7: 
                        $relationship = "HALF-BROTHER";
                    break;
                    case 8:
                    case 9: 
                        $relationship = "FOSTER SON";
                    break;
                    case 10:
                    case 11: 
                        $relationship = "NEPHEW";
                    break;
                    case 12: 
                        $relationship = "COUSIN";
                    break;
                    case 13: 
                    case 14: 
                        $relationship = "GRANDSON";
                    break;
                }
            } else {
                switch($row['RelationshipToSoloParentCategoryID']) {
                    case 1:
                    case 2: 
                        $relationship = "DAUGHTER";
                    break;
                    case 3: 
                        $relationship = "SISTER";
                    break;
                    case 4:
                    case 5: 
                        $relationship = "STEPDAUGHTER";
                    break;
                    case 6: 
                        $relationship = "STEP-SISTER";
                    break;
                    case 7: 
                        $relationship = "HALF-SISTER";
                    break;
                    case 8:
                    case 9: 
                        $relationship = "FOSTER DAUGHTER";
                    break;
                    case 10:
                    case 11: 
                        $relationship = "NIECE";
                    break;
                    case 12: 
                        $relationship = "COUSIN";
                    break;
                    case 13: 
                    case 14: 
                        $relationship = "GRANDDAUGHTER";
                    break;
                }
            }
            echo "<td>".$relationship."</td>
            </tr>";
        }
    }
    function getPopulationOfMinorSoloParents() {
        global $connection;
        
        $numberOfMinorSoloParents = 0;

        $sql = "SELECT Birthdate FROM soloparents;";
        $result = mysqli_query($connection, $sql);

        while ($row = mysqli_fetch_assoc($result)) {
            $dateOfBirth = $row['Birthdate'];
            $today = date("Y-m-d");
            $diff = date_diff(date_create($dateOfBirth), date_create($today));
            $age = $diff->format('%y');

            if ($age < 18) {
                $numberOfMinorSoloParents++;
            }
        }

        return $numberOfMinorSoloParents;
    }

    function getPopulationOfYoungAdultSoloParents() {
        global $connection;
        
        $numberOfYoungAdultSoloParents = 0;

        $sql = "SELECT Birthdate FROM soloparents;";
        $result = mysqli_query($connection, $sql);

        while ($row = mysqli_fetch_assoc($result)) {
            $dateOfBirth = $row['Birthdate'];
            $today = date("Y-m-d");
            $diff = date_diff(date_create($dateOfBirth), date_create($today));
            $age = $diff->format('%y');

            if ($age >= 18 && $age <= 30) {
                $numberOfYoungAdultSoloParents++;
            }
        }

        return $numberOfYoungAdultSoloParents;
    }

    function getPopulationOfMiddleAgedAdultSoloParents() {
        global $connection;
        
        $numberOfMiddleAgedAdultSoloParents = 0;

        $sql = "SELECT Birthdate FROM soloparents;";
        $result = mysqli_query($connection, $sql);

        while ($row = mysqli_fetch_assoc($result)) {
            $dateOfBirth = $row['Birthdate'];
            $today = date("Y-m-d");
            $diff = date_diff(date_create($dateOfBirth), date_create($today));
            $age = $diff->format('%y');

            if ($age >= 31 && $age <= 50) {
                $numberOfMiddleAgedAdultSoloParents++;
            }
        }

        return $numberOfMiddleAgedAdultSoloParents;
    }
    
    function getPopulationOfOldAgedAdultSoloParents() {
        global $connection;
        
        $numberOfOldAgedAdultSoloParents = 0;

        $sql = "SELECT Birthdate FROM soloparents;";
        $result = mysqli_query($connection, $sql);

        while ($row = mysqli_fetch_assoc($result)) {
            $dateOfBirth = $row['Birthdate'];
            $today = date("Y-m-d");
            $diff = date_diff(date_create($dateOfBirth), date_create($today));
            $age = $diff->format('%y');

            if ($age >= 51) {
                $numberOfOldAgedAdultSoloParents++;
            }
        }

        return $numberOfOldAgedAdultSoloParents;
    }
    
    function getMajorityOfSoloParentsByAge() {
        $majorityOfSoloParentsByAge = '';
        $highestPopulationByCategory = [];
        $highestPopulation = 0;
        $numberOfMinorSoloParents= getPopulationOfMinorSoloParents();
        $numberOfYoungAdultSoloParents = getPopulationOfYoungAdultSoloParents();
        $numberOfMiddleAgedAdultSoloParents = getPopulationOfMiddleAgedAdultSoloParents();
        $numberOfOldAgedAdultSoloParents = getPopulationOfOldAgedAdultSoloParents();
        $population = [$numberOfMinorSoloParents, $numberOfYoungAdultSoloParents ,$numberOfMiddleAgedAdultSoloParents, $numberOfOldAgedAdultSoloParents];
        
        for ($i = 0; $i < count($population); $i++){
            if ($population[$i] > $highestPopulation) {
                $highestPopulationByCategory = []; 
                array_push($highestPopulationByCategory, $i);
                $highestPopulation = $population[$i];
            } elseif ($population[$i] === $highestPopulation) {
                array_push($highestPopulationByCategory, $i);
            }
        }

        if (count($highestPopulationByCategory) === 1) {
            switch($highestPopulationByCategory[0]) {
                case 0:
                    $majorityOfSoloParentsByAge = 'MINORS';
                break;
                case 1:
                    $majorityOfSoloParentsByAge = 'YOUNG ADULTS';
                break;
                case 2:
                    $majorityOfSoloParentsByAge = 'MIDDLE AGED ADULTS';
                break;
                case 3:
                    $majorityOfSoloParentsByAge = 'OLD AGED ADULTS';
                break;
            }
        } else {
            if (in_array(0,$highestPopulationByCategory)) {
                $majorityOfSoloParentsByAge .= 'MINORS,';
            }
            if (in_array(1,$highestPopulationByCategory)) {
                $majorityOfSoloParentsByAge .= ' YOUNG ADULTS,';
            }
            if (in_array(3,$highestPopulationByCategory)) {
                $majorityOfSoloParentsByAge .= ' MIDDLE AGED ADULTS,';
            }
            if (in_array(4,$highestPopulationByCategory)) {
                $majorityOfSoloParentsByAge .= ' OLD AGED ADULTS';
            }
        }
        return $majorityOfSoloParentsByAge;
    }

    function getSoloParentsPopulationPerBarangay() {
        $sql = "SELECT barangays.Barangay, COUNT(soloparents.BarangayID) AS 'Population'
                FROM soloparents 
                INNER JOIN barangays
                    ON soloparents.BarangayID = barangays.BarangayID
                GROUP BY barangay
                ORDER BY COUNT(soloparents.BarangayID) DESC;";
        
        return $sql;
    }

    function getPopulationPerNumberOfChildren() {

        $sql = "SELECT NumberOfChildren, COUNT(NumberOfChildren) AS Population
                FROM(
                    SELECT soloparents.ControlNumber, COUNT(children.SoloParentID) AS NumberOfChildren
                    FROM `children`
                    INNER JOIN soloparents 
                        ON children.SoloParentID = soloparents.SoloParentID
                    GROUP BY children.SoloParentID 
                )AS Result GROUP BY NumberOfChildren;";
        
        return $sql;
    }

    function getMajorityOfNumberOfChildren() {
        global $connection;

        $numberPopulation = [];
        $soloParentPopulation = [];

        $result = mysqli_query($connection, getPopulationPerNumberOfChildren());
        
        while($row = mysqli_fetch_assoc($result)) {
            array_push($numberPopulation,$row);
            array_push($soloParentPopulation, $row['Population']);
        }

        $maxVal = max($soloParentPopulation);
        $maxKey = array_search($maxVal, $soloParentPopulation);

        return $numberPopulation[$maxKey]['NumberOfChildren'];
    }


    function getPopulationPerNatureOfSoloParent($forChart) {
        global $connection;

        $populationPerNature = [];

        for ($i = 0; $i < 9; $i++) {
            $natureID = $i + 1;
            $sql = "SELECT SoloParentID FROM soloparents WHERE NatureOfSoloParentID = '$natureID';";
            $result = mysqli_query($connection, $sql);
            $currentNaturePopulation = mysqli_num_rows($result);

            array_push($populationPerNature, $currentNaturePopulation);
        }
        
        $list = implode(', ', $populationPerNature);
        
        if ($forChart) {
            print_r($list);
        }
        
        return $populationPerNature;
    }

    function getPopulationOfEveryChildAgeGroup() {
        global $connection;
        $populationPerChildAgeGroup = [];
        
        for ($i = 0; $i < 3; $i++) {
            $condition = '';
            switch($i) {
                case 0: 
                    $condition = 'Age <= 3';
                break;
                case 1: 
                    $condition = 'Age > 3 && Age <=11;';
                break;
                case 2: 
                    $condition = 'Age > 11 && Age < 20';
                break;
            }

            $sql = "SELECT SUM(Population) 
                    FROM (
                        SELECT Age, COUNT(Age) as Population
                        FROM (
                            SELECT ChildID, DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), Birthdate)), '%Y') + 0 AS Age
                            FROM children
                        ) AS Result 
                        GROUP BY Age
                    ) AS Result2 WHERE $condition;";

            $result = mysqli_query($connection, $sql);
            $population = mysqli_fetch_row($result);
            if (empty($population[0])) {
                array_push($populationPerChildAgeGroup, 0);
            } else {
                array_push($populationPerChildAgeGroup, $population[0]);
            }
        }

        return $populationPerChildAgeGroup;
    }

    function getTotalChildBelow20() {
        global $connection;

        $sql = "SELECT COUNT(*) FROM children WHERE DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), Birthdate)), '%Y') < 20";
        $result = mysqli_query($connection, $sql);
        $totalChildBelow20 = mysqli_fetch_row($result);
        
        return $totalChildBelow20[0];
    }

    function getPopulationOfEverySoloParentsPWDStatus() {
        global $connection;
        $populationOfEverySoloParentsPWDStatus = [];
        for ($i = 0; $i < 8; $i++){
            $pwdStatusID = $i + 1;
            $sql = "SELECT COUNT(soloparents.PWDStatusID)
                FROM soloparents
                WHERE soloparents.PWDStatusID = $pwdStatusID;";
            $result = mysqli_query($connection, $sql);
            $population = mysqli_fetch_row($result);

            if (empty($population[0])) {
                array_push($populationOfEverySoloParentsPWDStatus, 0);
            } else {
                array_push($populationOfEverySoloParentsPWDStatus, $population[0]);
            }
        }
        return $populationOfEverySoloParentsPWDStatus;
    }

    function getAllNewApplications() {
        global $connection;
        $newApplicantsPerMonth = [];
        for ($i = 0; $i < 12; $i++) {
            $month = $i + 1;
            $nextMonth = $month + 1;
            $year = date('Y');
            $nextYear = date('Y');

            if ($month < 10) {
                $month = '0' + $month;
            }

            if ($nextMonth < 10) {
                $nextMonth = '0' + $nextMonth;
            }

            if ($month === 12) {
                $nextYear++;
                $nextMonth = '01';
            }

            $sql = "SELECT COUNT(SoloParentID) AS NumberOfNewSoloParents
                FROM soloparents
                WHERE (DateJoined >= '$year-$month-01' AND DateJoined < '$nextYear-$nextMonth-01'); ";
            $result = mysqli_query($connection, $sql);
            $monthsNewSoloParent = mysqli_fetch_row($result);

            array_push($newApplicantsPerMonth, $monthsNewSoloParent[0]);
        }
        return $newApplicantsPerMonth;
    }

    function getAllRenewalApplications() {
        global $connection;
        $newRenewalPerMonth = [];
        for ($i = 0; $i < 12; $i++) {
            $month = $i + 1;
            $nextMonth = $month + 1;
            $year = date('Y');
            $nextYear = date('Y');

            if ($month < 10) {
                $month = '0' + $month;
            }

            if ($nextMonth < 10) {
                $nextMonth = '0' + $nextMonth;
            }

            if ($month === 12) {
                $nextYear++;
                $nextMonth = '01';
            }

            $sql = "SELECT COUNT(SoloParentID) AS NumberOfNewSoloParents
                FROM soloparents
                WHERE (DateLastRenewed >= '$year-$month-01' AND DateLastRenewed < '$nextYear-$nextMonth-01'); ";
            $result = mysqli_query($connection, $sql);
            $monthsNewSoloParent = mysqli_fetch_row($result);

            array_push($newRenewalPerMonth, $monthsNewSoloParent[0]);
        }
        return $newRenewalPerMonth;
    }   

    function isInformativeMaterialExist($informativeMaterial) {
        global $connection;

        $sql = "SELECT * FROM informativematerials WHERE InformativeMaterial = '$informativeMaterial'";
        $result = mysqli_query($connection, $sql);

        return mysqli_num_rows($result) > 0;
    }
?>