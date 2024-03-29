<?php
/* This page will act as the interceptor to the lookup.php's module "buyStock()"
 * lookup.php will issue an AJAX request that will transfer the "symbol" + "stockamt" + ""
 * information in the POST field.
 * buy.php will grab these information, do the appropriate database transactions, process them and then
 * send a JSON encoded response back to lookup.php
 */
require("../includes/config2.php");
class Flag
{
  public $symbolvalid=false;
  public $qtyvalid=false;
  public $pricevalid=false;
  public $balsufficient=false;
  public $noerror=false;
  public $totalamt;
}
$flag=new Flag(); // An instance of the class Flag.
$usrid=$_SESSION['id'];// Get the user's id
//mysqli_set_charset($con,"utf8");
mysqli_autocommit($con,FALSE);
header("Content-type:application/json");
$symbol=mysqli_real_escape_string($con,$_POST['symbol']);// For previous database record checking						       OR new record insertion
						      
$stockamt=mysqli_real_escape_string($con,$_POST['stockamt']); // How much user is buying.
$price=mysqli_real_escape_string($con,$_POST['price']); // The stock price which user viewed./

$totalamt=($price)*($stockamt);
$flag->totalamt=$totalamt;

$regex='/^[a-z]{1,4}\.?[a-z]{1,2}$/i';
$regex3='/^(0|[1-9][0-9]*)$/';
$regex4='/^\d*\.?\d*$/'; // ?-> 0 or 1 times || * -> 0 or more times || ? -> 1 or more times

if(preg_match($regex,$symbol))
  {
    $flag->symbolvalid=true; // So, symbol is valid.
    // Now for checking whether stock quantity is valid.
    $validqty=(preg_match($regex3,$stockamt) and ($stockamt<=200));
    if($validqty)
      {
	$flag->qtyvalid=true;
	// Now check validity of $price(it should be only numerical number-integer or float).
	//	$regex4='/^\d*\.?\d*/';  // ?-> 0 or 1 times || *-> 0 or more times ||+-> 1 or more times

	if($validprice=(preg_match($regex4,$price)))
	  {
	    $flag->pricevalid=true;
	    // Now price is also valid=>> check balance, if sufficient= purchase stock
	    $balance=getBalance($usrid);  //Get the balance of the user.
	    $difference=$balance-$totalamt;
	    if($difference<0)
	      {		
		$flag->balsufficient=false;
		$flag->noerror=false;
	      }
	    else
	      {
		$flag->balsufficient=true;
		//Now depriciate the user's balance from portfolio.
		$sql="UPDATE users SET balance='$difference' WHERE id='$usrid'";
		if((mysqli_query($con,$sql))==FALSE)
		  {
		    $flag->noerror=FALSE;
		    mysqli_rollback($con);
		  }
		else
		  {
		    $flag->noerror=true;
		    /* Now check whether the given stock is in the stock table 
		     * If this stock symbol is already in the stock -> simply ADD the $stockamt to the previous
		     * 'number'field of stock table.
		     * If the stock symbol is NOT in the stock=> Would require insertion of new 
		     * stock record in the stock table + corresponding id should also be added to the portfolio.
		     */
		    $sql1="SELECT symbol,id FROM portfolio WHERE symbol='$symbol' AND uid='$usrid'";
		    $result1=mysqli_query($con,$sql1);
		    if(mysqli_num_rows($result1)==1)
		      {
			// Means database already has that symbol stock in it. So simply add                            $stockamt to number
			$row=mysqli_fetch_array($result1);
			if(mysqli_query($con,"UPDATE portfolio SET quantity=quantity+'$stockamt' WHERE id='$row[1]'")==FALSE)
			  {
			    $flag->noerror=false;
			    mysqli_rollback($con);
			  }
		      }
		    else
		      {
			/* In this case, insert new record for this stock symbol into the 
                         * portfolio table
			 */
			$sql="INSERT INTO portfolio(uid,symbol,quantity) VALUES('$usrid','$symbol','$stockamt')";
			if(mysqli_query($con,$sql)==FALSE)
			  {
			    $flag->noerror=false;
			    mysqli_rollback($con);
			  }
			else
			  {
			    $flag->noerror=true;
			  }
		      }
		  }
	      }
	  }
      }
  }

if($flag->noerror==true)
  {
    mysqli_commit($con);
  }
mysqli_close($con);
printf(json_encode($flag));
?>