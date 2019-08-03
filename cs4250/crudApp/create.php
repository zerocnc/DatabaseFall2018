<?php
// Include config file
include "../../info.php";
include "../phpFunctions.php";
 
// Define variables and initialize with empty values
$firstName = $lastName = $address = $phoneNum = "";
$firstName_err = $lastName_err = $address_err = $phone_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    $input_firstName = trim($_POST["firstName"]);
    if(empty($input_firstName)){
        $name_err = "Please enter a name.";
    } elseif(!filter_var($input_firstName, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $firstName_err = "Please enter a valid first name.";
    } else{
        $firstName = $input_firstName;
    }

    $input_lastName = trim($_POST["lastName"]);
    if(empty($input_lastName)){
        $lastName_err = "Please enter a lastName.";
    } elseif(!filter_var($input_lastName, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $lastName_err = "Please enter a valid last name.";
    } else{
        $lastName = $input_lastName;
    }
    
    // Validate address
    $input_address = trim($_POST["address"]);
    if(empty($input_address)){
        $address_err = "Please enter an address.";     
    } else{
        $address = $input_address;
    }

    // Validate phoneNumber
    $input_phoneNum = trim($_POST["phoneNum"]);
    if(empty($input_phoneNum)){
        $phone_err = "Please enter the phone number.";     
    } elseif(!validate_phone_number($input_phoneNum) == true){
        $phone_err = "Please enter a valid format.";
    } else{
        $phoneNum = $input_phoneNum;
    }
    
    // Check input errors before inserting in database
    if(empty($firstName_err) && empty($lastName_err) && empty($address_err) && empty($phone_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO Employee (employeeID, firstName, lastName ,address, phoneNum) VALUES (:employeeID, :firstName,:lastName,:address,:phoneNum)";

        //$db = new PDO('mysql:host=localhost;dbname=rneal;charset=utf8mb4', $username, $password);
        $pdo = new PDO('mysql:host=localhost;dbname=rneal;charset=utf8mb4', $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":employeeID", $param_employeeID);
            $stmt->bindParam(":firstName", $param_firstName);
            $stmt->bindParam(":lastName", $param_firstName);
            $stmt->bindParam(":address", $param_address);
            $stmt->bindParam(":phoneNum", $param_phoneNum);
            
            // Set parameters
            $param_employeeID = 1;
            $param_firstName = $firstName;
            $param_lastName = $lastName;
            $param_address = $address;
            $param_phoneNum = $phoneNum;
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Records created successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        unset($stmt);
    }
    
    // Close connection
    unset($pdo);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Create Record</h2>
                    </div>
                    <p>Please fill this form and submit to add employee record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($firstName_err)) ? 'has-error' : ''; ?>">
                            <label>First Name</label>
                            <input type="text" name="firstName" class="form-control" value="<?php echo $firstName; ?>">
                            <span class="help-block"><?php echo $firstName_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($firstName_err)) ? 'has-error' : ''; ?>">
                            <label>Last Name</label>
                            <input type="text" name="lastName" class="form-control" value="<?php echo $lastName; ?>">
                            <span class="help-block"><?php echo $lastName_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($address_err)) ? 'has-error' : ''; ?>">
                            <label>Address</label>
                            <textarea name="address" class="form-control"><?php echo $address; ?></textarea>
                            <span class="help-block"><?php echo $address_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($phone_err)) ? 'has-error' : ''; ?>">
                            <label>Phone Number</label>
                            <input type="text" name="phoneNum" class="form-control" value="<?php echo $phoneNum; ?>">
                            <span class="help-block"><?php echo $phone_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>