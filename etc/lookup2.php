<?php 

 class Flag
 {
   public $noerror;
   public $symbol;
   public $name=NULL;
   public $price=NULL;
   //   public $time=NULL;
   public $change=NULL;
   //   public $open=NULL;
   public $high=NULL;
   public $low=NULL;
 }

require("../includes/config2.php");
       
header("Content-type:application/json");
/* This is the "interceptor" page that will receive the "Symbol" from the "lookup.php" and then query the Yahoo 
   Finance page with that.
   Received data(if any) would be sent back to the "lookup.php" as the AJAX response in a JSON format.
    JSON format : {"real":"TRUE/FALSE","price":"...","high":"...","low":"...","change":"..."}
*/

/* Will receive the SYMBOL information as a POST data in the field 'symbol'.
 */ 

 // JSON format: {"error":"true/false", "symbol":$symboltxt,"name":" ","price":" ","change":" ","high":" "

/* Here I will receive the symboltxt from the lookup.php via AJAX POST request and then pass that symbol to a      function lookup($symbol) =>> This function will query the YAHOOO finance website.
   
 * The  csv received would be parsed using the fgetcsv() and furhter actions will be based upong the condition:
 *  1. If proper symbol and proper response received (i.e
 *  2. 
 *  3. 

 */

       //$symbol=mysqli_real_escape_string($con,$_GET['symboltxt']);
$symbol=mysqli_real_escape_string($con,$_POST['symboltxt']);

//$quantity=mysqli_real_escape_string($con,$_GET['qty']);
//$quantity=mysqli_real_escape_string($con,$_POST['qty']);

$lookup2=lookup($symbol); // $lookup2 has the object $stock returned by the function.

$flag=new Flag();

if(($lookup2)===NULL)
  {
    // Create a JSON field to be sent back to lookup.php.
    $flag->noerror=false;
  }    
else
  {
    // If everything went alright and we got proper data fields from the function lookup().
    $flag->noerror=true;
    $flag->symbol=$lookup2->symbol;
    $flag->name=$lookup2->name;
    $flag->price=$lookup2->price;
    $flag->change=$lookup2->change;
    $flag->high=$lookup2->high;
    $flag->low=$lookup2->low;

  }

// Now simply return the $flag object to lookup.php
print(json_encode($flag));

?>