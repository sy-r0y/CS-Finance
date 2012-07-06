<?php
// Do all the database work. Transactions would be utillized to ensure integrity.
require("../includes/config2.php");
mysqli_set_charset($con,"utf8");
header("Content-type:application/json");
mysqli_autocommit($con,FALSE);
class Sell
{
  public $noerror=false;
  public $worth=0;
  public $newbal=0;
}
$sell=new Sell();
$usrid=$_SESSION['id'];

$price=mysqli_real_escape_string($con,$_POST['price']);
$qtytosell=mysqli_real_escape_string($con,$_POST['qtytosell']);
$index=mysqli_real_escape_string($con,$_POST['index']);

$sell->worth=(float)$qtytosell*$price; //Used to update the balance in users table.

$sql="UPDATE portfolio SET quantity=quantity-'{$qtytosell}'
      WHERE uid='{$usrid}' AND id='{$index}'";
if(mysqli_query($con,$sql)==false)
  { 
    $sell->noerror=false;
    mysqli_rollback($con);
  }
else
  {
  $sell->noerror=true;
  $worth=(float)$sell->worth;

  $query2="SELECT balance FROM users WHERE id='{$usrid}'";
  $result=mysqli_fetch_array(mysqli_query($con,$query2));
  $oldbal=(float)$result[0];
  $newbal=$oldbal+$worth;
  $query3="UPDATE users SET balance='{$newbal}'
          WHERE id='{$usrid}'";
  if(mysqli_query($con,$query3)==false)
    {
      $sell->noerror=false;
      mysqli_rollback($con);
    }
  else
    {
      $sell->noerror=true;
      $sell->newbal=$newbal;

      /*      $query="UPDATE users SET balance=balance+'{$worth}'
              WHERE id='{usrid}'";
      if(mysqli_query($con,$query)==false)
	{
	  $sell->noerror=false;
	  mysqli_rollback($con);
    }
      else
	{
	  $sell->noerror=true;
	  $query2="SELECT balance FROM users WHERE id='{$usrid}'";
	  $result=mysqli_fetch_array(mysqli_query($con,$query2));
	  $sell->newbal=$result[0];
	}
    
      */
    }
  }

if($sell->noerror==true)
  {
    mysqli_commit($con);
  }
mysqli_close($con);
printf(json_encode($sell));
?>