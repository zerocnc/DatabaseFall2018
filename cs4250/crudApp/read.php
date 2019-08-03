<?php
// Check existence of id parameter before processing further
if(isset($_GET["employeeID"]) && !empty(trim($_GET["employeeID"]))){
    // Include config file
    include "../../info.php";
    include "../../phpFunctions.php";
    
    $wage = false;

    // Prepare a select statement

    $pdo = new PDO('mysql:host=localhost;dbname=rneal;charset=utf8mb4', $username, $password);

    // $sql = "SELECT * FROM Employee WHERE Employee.employeeID = :employeeID";
    $sql = "SELECT E.employeeID, E.firstName, E.lastName, E.phoneNum, E.homeAddress, P.hourlyWage FROM Employee E Inner Join PartTime P on (E.employeeID = P.employee_ID) WHERE E.employeeID = :employeeID";

    
    if($stmt = $pdo->prepare($sql)){
        // Bind variables to the prepared statement as parameters
        $stmt->bindParam(":employeeID", $param_id);
        
        // Set parameters
        $param_id = trim($_GET["employeeID"]);
        
        // Attempt to execute the prepared statement
        if($stmt->execute()){
            if($stmt->rowCount() == 1 ){
                /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // Retrieve individual field value
                $firstName = $row["firstName"];
                $lastName = $row["lastName"];
                $address = $row["homeAddress"];
                $hourlyWage = $row["hourlyWage"];

            } elseif ($stmt1->rowCount() == 1 ){
                $row = $stmt1->fetch(PDO::FETCH_ASSOC);
                
                // Retrieve individual field value
                $firstName = $row["firstName"];
                $lastName = $row["lastName"];
                $address = $row["homeAddress"];
                $hourlyWage = $row["employeeSalary"];

            } else{
                // URL doesn't contain valid id parameter. Redirect to error page
                header("location: error.php");
                exit();
            }
            
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
     
    // Close statement
    unset($stmt);
    
    // Close connection
    unset($pdo);
} else{
    // URL doesn't contain id parameter. Redirect to error page
    header("location: error.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Record</title>
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
                        <h1>View Record</h1>
                    </div>
                    <div class="form-group">
                        <label>Name</label>
                        <p class="form-control-static"><?php echo $row["firstName"] . " " . $row["lastName"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <p class="form-control-static"><?php echo $row["homeAddress"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Pay Rate: </label>
                        <p class="form-control-static"><?php echo $row["hourlyWage"]; ?></p>
                        
                    </div>
                    <p><a href="index.php" class="btn btn-primary">Back</a></p>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>