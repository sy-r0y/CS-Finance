<?php

/* This is the "interceptor" page to check for the authenticity of the "username"-"password" combination provide   d by the user.This page receives its data via the "index.html" thru AJAX, and after checking ->
   1. On succesful authenticatin responds with an "OKAY" of sorts and redirects the user to his "portfolio".
   2. On unsuccessful authentication respods (thru AJAX) about the invalidity of the "username"-"password" field.
*/


class Flag
{
  public $authentic;
}
 
require("/includes/config.php");

header("Content-type:application/json");

// Defensively deal with the input--> "Sanitize" it.

$usr=mysql_real_escape_string($_POST["uname"]);
$pass=mysql_real_escape_string($_POST["pswd"]);

/*print_r($user);
 *print("<br/><br/>");
 *print_r($pass);
 */

// Creat the SQL statement to query the database.

//  $sql="Select 1 from user where email={$user} AND password={$pass}";

$sql="SELECT id,password FROM user WHERE email='$usr' AND password='$pass'";

//Execute the query

$result=mysql_query($sql);

$flag = new Flag(); // Instantiate the class "Flag" object $flag.


  //if($result===FALSE)
if(mysql_num_rows($result)==0)
  {

    $flag->authentic=FALSE; // Set the $authentic field of the $flag to FALSE.

    //    $flag->$authentic=FALSE;
  }

//Check if the result contained one row -> thereby confirming that the authentication was successful.

else if(mysql_num_rows($result)==1)
  {
    //Now check that the "passwords" match as well.
    
    $row=mysql_fetch_array($result);
    if($pass==$row["password"])
      {
	  // Set $_SESSION["id"} and set flag to TRUE.
	$_SESSION["id"]=$row["id"];
    
	$flag->authentic=TRUE;

	//    $flag->$authentic=TRUE;
      }
  }

// Now send the AJAX response in a JSON format.

print(json_encode($flag));


//print($_SESSION["id"]);
  // {"authentic":"TRUE"} or {"authentic":"FALSE"}

?>