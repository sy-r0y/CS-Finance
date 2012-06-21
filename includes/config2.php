<?php /*// ALL THE INCLUDES THAT WOULD BE NEEDED SPECIFICALLY FOR THE mysqli METHODS.

  /*$con=mysqli_connect("localhost","soumya","hello","finance");
mysqli_set_charset($con,"utf8"); // Set the Character Encoding

header("Content-type:application/json");
 
mysqli_autocommit($con,FALSE);
session_start(); */

?>

<?php

define("HOST", "localhost");
define("USER","soumya");
define("PASS","hello");
define("DB_NAME","finance");
define("YAHOO", "http://download.finance.yahoo.com/d/quotes.csv?f=snl1d1t1c1ohg&s=");


//define("YAHOO","http//download.finance.yahoo.com/d/quotes.csv?f=snl1d1t1c1ohg&s=");


// Include the "funtion.php" to have access to "predefined" functions.
require("../lib/function.php");
$con=open_mysqli_conn();  // This is a function that is defined in the function.php.

session_start();  // Also start the session.

?>