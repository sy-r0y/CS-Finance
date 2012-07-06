<?php
require("../includes/config2.php");
class Price
{
  public $noerror=false; 
  public $price;
  public $quantity;
  /* Could also have done without "noerror", but the purpose of 'noerror' is to not just 
   * check for 'index' related errors(like no symbol at specified index) but also 
   * any YUI related errors.
   */
}
$price=new Price();
$usrid=$_SESSION['id'];
$index=$_POST['index'];
$sql="SELECT symbol,quantity FROM portfolio WHERE id='{$index}' AND uid='{$usrid}'";
if(($symbol=mysqli_fetch_array(mysqli_query($con,$sql)))!==FALSE)
  {
    if($symbol[0]!=null)
      {
	$handle=fopen("http://download.finance.yahoo.com/d/quotes.csv?f=l1&s=".$symbol[0],"r");
	if($handle!==FALSE)
	  {
		$price->noerror=true;
		$rate=fgetcsv($handle);
		$price->price=$rate;
		$price->quantity=$symbol[1];
	  }
	fclose($handle);
      }
  }
print(json_encode($price)); 
?>