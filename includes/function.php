<?php

// This file will contain all the "predefined" functions that will be used in our project.

function open_db_conn()
{
  // Connect to the Database.


  $con=mysql_connect(HOST,USER,PASS);
  mysql_set_charset("utf8");


  //  if(($con=mysql_connect(HOST,USER,PASS))===FALSE)
  if($con===FALSE)  
  {
      exit(" COULD NOT CONNECT TO DB" );
    }
  //  mysql_set_charset("utf8");

  if(mysql_select_db(DB_NAME,$con)===FALSE)
    {
      exit("COULD NOT SELECT DB");
    }
  return $con;
}

// Functin to "redirect" users to the page specified in the argument.
function redirect($destination)
{
  // Redirect the user to a different page in the current directory.
  $host=$_SERVER['HTTP-HOST'];
  $uri=rtrim(dirname($_SERVER['PHP-SELF']),'/\\');
  header("Location:http://$host$uri/$destination");
  exit;
}
?>