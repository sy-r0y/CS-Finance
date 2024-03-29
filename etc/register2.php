<?php

/* This is the interceptor page-> that will receive the AJAX request via the register.php(in the POST varialble)
 * It will "Sanitize" it using regular expression and also escape any control character. 
 * After sanitization, it will check whether any received data is already
 * present in the database(i.e check for any conflicts)-> if it finds any conflicts it will
 * send a JSON response that the handler() will then alert the user about the conflict.
 * If no such conflicts are there a SQL INSERT statement will be executed and the received data 
 * would be inserted into the "user" table.
 * JSON format {"sanitized":"TRUE/FALSE", "conflict":"TRUE/FALSE","insert":"TRUE/FALSE"}
*/ 
session_start(); // Start Session.
$con=mysqli_connect("localhost","soumya","hello","finance");
mysqli_set_charset($con,"utf8"); // Set the Character Encoding
header("Content-type:application/json");
mysqli_autocommit($con,FALSE);
class Register
{
  //  public $minbal;
  public $sanitized;
  public $noconflict;
  public $insert;
}
$register=new Register();
//Defensively deal with the receiced inputs.
// Sanitized it + Ensure compliance with the regular expressions.
//$usr=utf8_decode(mysqli_real_escape_string($con,$_POST["username"]));

$email=utf8_decode(mysqli_real_escape_string($con,$_POST["email"]));
$passw=utf8_decode(mysqli_real_escape_string($con, $_POST["passw"]));

//$email=utf8_decode(mysqli_real_escape_string($con,$_GET["email"]));
//$passw=utf8_decode(mysqli_real_escape_string($con, $_GET["passw"]));


// Now match with regular expressions
$patemail='/[a-z0-9._\-]+@[a-z0-9][a-z0-9.\-]*[\.]{1}[a-z]{2,4}$/i';
$patpsw='/.*(?=.{6})(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%^&+=]).*$/';
$emailmatch=preg_match($patemail,$email);
$passmatch=preg_match($patpsw,$passw);
/* Only if the received inputs match with the regular expression will we then proceed further by  
 * -->> first checking whetther any conflicts are there and if not --> perform an 'INSERT'.
 * if no conflicts are there --> the JSON response would be-- {"sanitized":"yes","conflict":"no","insert":"yes"}
*/
if(($emailmatch) and ($passmatch))
  {
    $register->sanitized=true;
    // Check for any potential conflicts in the database.( By simply checking the "email" field.
    $checkemail="SELECT email FROM users WHERE email='{$email}'";
    if(mysqli_num_rows(mysqli_query($con,$checkemail))==0)
      {
	// Means that there is a NO conflict.
	$register->noconflict=TRUE;
	/* Now Insert into the database.
	 * Also not only do we need to create a new row in the 'users' table but also a corresponding new row in          * the 'portfolio' table ( with SAME user id (uid=id) and 'balance' = 10000
	 */
	// Use Transaction.
	$insertusr="INSERT INTO users(email,password,balance)
                    VALUES('{$email}','{$passw}','10000')";
	if((mysqli_query($con,$insertusr))===FALSE)
	  {
	    $register->insert=FALSE;
	    mysqli_rollback($con);
	  }
	else
	  {
	    $register->insert=TRUE;	
	    // Also get the user id from the users table--> then set it on the session variable.
	    $sql="Select LAST_INSERT_ID();";
	    $result=mysqli_fetch_array(mysqli_query($con,$sql));
	    $_SESSION['id']=$result[0];
	    mysqli_commit($con);
	  }
      }
    else
      {
	// Means the database already has a duplicate(pre-existing) record with SAME 'email' field.
	$register->noconflict=FALSE;
	$register->insert=FALSE;
      }
	mysqli_close($con);
  }
else
  {
    // Means the fields are not properly sanitized.
    $register->sanitized=FALSE;
  }
printf(json_encode($register));
?>