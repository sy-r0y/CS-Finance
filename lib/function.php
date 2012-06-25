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

function open_mysqli_conn()
{
  // Connect to the Database.


  /*  mysqli mysqli_connect ( [string host [, string username [, string passwd [, string dbname [, int port 
                              [, string socket]]]]]] )
   */

  $con=mysqli_connect(HOST,USER,PASS,DB_NAME);
  mysqli_set_charset($con,"utf8");

  //  if(($con=mysql_connect(HOST,USER,PASS))===FALSE)
  if($con===FALSE)  
  {
      exit(" COULD NOT CONNECT TO DB" );
  }

  mysqli_autocommit($con,FALSE); // Make sure that mysql doesn't automatically start committing any changes.

  /*
    // Since mysqli already connects to the database through the connect() --> the following is "Redundant" and    //  also Depracated(I assume..!!! ;D )

    if(mysql_select_db(DB_NAME,$con)===FALSE)
    {
      exit("COULD NOT SELECT DB");
    }
  */
  
  return $con;
}

// Functin to "redirect" users to the page specified in the argument.
function redirect($destination)
{
  //  echo "HELLO ";
  //  print($_SERVER['HTTP_HOST']);
  //  print($_SERVER['PHP_SELF']);


  // Redirect the user to a different page in the current directory.
  /* Redirect to a different page in the current directory that was requested */

    $host=$_SERVER['HTTP_HOST'];
    $uri=rtrim(dirname($_SERVER['PHP_SELF']),'/\\');
    header("Location: http://$host$uri/$destination");
  exit;
}

function userName($usrid)
{
  $con=open_mysqli_conn();
  $sql="SELECT email from users WHERE id='$usrid'";
  $result=mysqli_fetch_array(mysqli_query($con,$sql));
  return $result[0];
}
function getBalance($usrid)
{
  $con=open_mysqli_conn();
  $sql="SELECT balance FROM users WHERE id='$usrid'";
  $result=mysqli_fetch_array(mysqli_query($con,$sql));
  return $result[0];
}

function lookup($symbol)
{

  /* lookup($symbol)
   * Returns a stock by its symbol(Case-Insenstively) else NULL if not found.
  */

 class Stock
 {

   public $symbol;
   public $name=NULL;
   public $price=NULL;
   //   public $time=NULL;
   public $change=NULL;
   //   public $open=NULL;
   public $high=NULL;
   public $low=NULL;
 }


  // Reject $symbol that start with \^.
    if(preg_match("/^\^/",$symbol))
      {
      	return NULL;
      }

  // Reject $symbol that contain commas.
  if(preg_match("/,/",$symbol))
    {
      return NULL;
    }

  // open connection to Yahoo
  if (($fp = @fopen(YAHOO . $symbol, "r")) === false)
    {
      return NULL;
    }
  // download first line of CSV file
  if (($data = fgetcsv($fp)) === false || count($data) == 1)
    {
      return NULL;
    }
  // close connection to Yahoo
  fclose($fp);
  
  // Ensure $symbol was found
  if ($data[2] == 0.00)
    {
      return NULL;
    }

  // Instantiate a stock object.
  $stock=new Stock();

  
  /*//define("YAHOO", "http://download.finance.yahoo.com/d/quotes.csv?f=snl1d1t1c1ohg&s=");
  $handle=@fopen("http://download.finance.yahoo.com/d/quotes.csv?s=".$symbol."&f=snl1d1t1c1ohg","r");
  if($handle===false)
    {
      return NULL;
    }
  else
    {
      $data=fgetcsv($handle);
      if($data===false || count($data)==1)
	{
	  return NULL;
	}
      else
	{
	  
  	  // Close the connection to YAHOO.
	  //	  fclose($fp);

	  fclose($handle);

  
	  // Ensure $symbol was found.
	  if($data[2]==0.00)
	    {  // echo "HELLO11"; 
	      return NULL;
	    }
  */	  
	  
	  // Remember stock's symbol and trade details => this information is to be sent back to lookup.php
	  $stock->symbol=$data[0];
	  $stock->name=$data[1]; 
	  $stock->price=$data[2];
	  //	  $stock->time=strtotime($data[3]." ".$data[4]);
	  $stock->change=$data[5];
	  $stock->open=$data[6];
	  $stock->high=$data[7];
	  $stock->low=$data[8];
	  
	  // Return the stock object(if everything went alright).
	  return $stock;
}