<?php
function validate()
{
    $erroresult = array();
    $command_Valid_Flag = false;

    // Valid commands that will only be accpeted from the user into SQL.
    // Hard coded because anyone can type in key values with no login
    // verification in place!
    $valid_Commands = array("update", 
                            "insert", 
                            "select",
                            "delete",
                            "drop");
    // Convert from numeric array to associative array
    $valid_Commands = array_flip($valid_Commands);

    if(empty($_POST["userQuery"])){
        $erroresult["userQuery"] = "You hit submit on empty an text box with no query. Please go back and try again.";
    } else
    {
        $pieces = explode(" ", $_POST["userQuery"]);

        $part = strtolower($pieces[0]);

        if (isset($valid_Commands[ $part ])) { 
            echo '<p>Valid Command.</p>';

        } else {
            echo '<p>Not a valid command from the list issued. Please be responsible and write your query correctly. (ie., update, insert, select, delete, drop).</p>';
        }
    }
    return $erroresult;
}

function run_User_Query()
{
    // Admin server configure woe's, admin should fix this.
    include "../info.php";

        try{
            // Creating PDO call and sanitize it.
            $db = new PDO('mysql:host=localhost;dbname=rneal;charset=utf8mb4', $username, $password);
            $newStr = $_POST['userQuery'];

            $pos = strpos($newStr, ";");

            if ( $pos === false ){
                $newStr = $newStr . ';';
            }
            else{
                $newStr = substr($newStr, 0, strpos($newStr, ";"));
                $newStr = $newStr . ";";
            }

            $result = $db->query( $newStr, PDO::FETCH_ASSOC);
            
            $numRow = $result->rowCount();

            if ($numRow > 0){
                echo '<h2>Number of Rows effected:' . $numRow.'</h2>';
                printTable($result);
            }elseif ($numRow == 0){
                echo '<h2>No Rows effected.</h2>';
            }
            else{
                Echo '<h2>Invalid</h2>';
            }

            // Close connection.
            $result = NULL;
            $db = NULL;

        } catch(PDOException $e){
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        
    return $erroresult;
}

/*
*   Prints the table from the results of a PDO query command.
*   $result - must be in a PDO object container to be displayed correctly.
*/
function printTable($result){
    // Gets column Field names of PDO container
    for ($i = 0; $i < $result->columnCount(); $i++) {
        $col = $result->getColumnMeta($i);
        $columns[] = $col['name'];
    }

    // Start Table creation.
    echo '<table class="blueTable"><tr>';

    // Displays the column field names in th html tag.
    echo '<thead>';
    foreach( $columns as $fieldName){
        echo '<th>' .$fieldName .'</th>';
    }
    echo '</tr></thead><tbody>';
    
        // Prints out all data field values table row (td html tag).
        foreach( $result as $row){
            echo '<tr>';
            foreach( $row as $fieldData){
                echo '<td>' .$fieldData .'</td>';
            }
        }

        echo '</tbody></table>';

    return;
}

function format_telephone($phone_number)
{
    $cleaned = preg_replace('/[^[:digit:]]/', '', $phone_number);
    preg_match('/(\d{3})(\d{3})(\d{4})/', $cleaned, $matches);
    return "({$matches[1]}) {$matches[2]}-{$matches[3]}";
}

function validate_phone_number($phone)
{
     // Allow +, - and . in phone number
     $filtered_phone_number = filter_var($phone, FILTER_SANITIZE_NUMBER_INT);
     // Remove "-" from number
     $phone_to_check = str_replace("-", "", $filtered_phone_number);
     // Check the lenght of number
     // This can be customized if you want phone number from a specific country
     if (strlen($phone_to_check) < 10 || strlen($phone_to_check) > 14) {
        return false;
     } else {
       return true;
     }
}

?>