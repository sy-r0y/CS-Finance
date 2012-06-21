<?php

define("HOST", "localhost");
define("USER","soumya");
define("PASS","hello");
define("DB_NAME","finance");



// Include the "funtion.php" to have access to "predefined" functions.
require("../lib/function.php");

open_db_conn();  // This is a function that is defined in the function.php.

session_start();  // Also start the session.


?>