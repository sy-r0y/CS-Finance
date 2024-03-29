<?php
require("../includes/config2.php");
$id=$_SESSION['id'];
if(!(isset($_SESSION['id']))||(empty($_SESSION['id'])))
  {
    redirect("index.php");
  }
?>
<?php
/* This module will allow the logged in user to sell stocks that s/he owns.
NevER SEttLENevEr SettlENever SETTLEnever SEttleNever SETTlENEVER settlenever settleNeVeR SeTtLenEvEr sEtTlE |\|/3ver S3ttl3|\|3V3R S377l_3
 * Currently, there is only the facillity to 
 * 1. Sell stocks of one company in entireity i.e all stocks of that company at the 
 * same time.
 * 2. The selling is more like "throwing away to cyber oblivion", since there is no 
 * current facillity to specify the buyer.
 * 3. Obviously the "current value" of the stock is credited to the user's balance.
 */
/* The core functionality would be would be contained in 3 divs- 1st one will contain a 
 * Drop Down list which will contain the list of stocks owned by the user, the 2nd div 
 * would display the response received from sell2.php(price, shares held etc).
 * The 3rd div would contain the response recived by sell3.php after sales transaction
 * has either successfully or unsuccessfully taken place. 
 * The user will select one of the stocks to sell.
 * When an option is selected, and AJAX call to sell2.php would be made, the 'price and
 * and 'quantity' information would be returned and displayed.
 * Sanity checks would be done (like check "quantity" field) when the user clicks on 
 * the "Sell" button.
 * After sanitization, send the information to a "sell3.php" in /etc (using AJAX), here 
 * the necessary database actions would be done(like updating the appropriate table), 
 * if everything is cool => provide an appropriate response to "sell.php".
 */
?>
<?php
require("./header2.html");
?>
<script>
var qty=0;
var price=0;
var index=0;
var qtytosell=0;
window.onload=function(){
  var eselect=document.getElementById('stocks');
  eselect.onchange=function(){
    document.getElementById('response').style.display="none";
    document.getElementById('q').value="";
    document.getElementById('qtyspan').style.color="black";
    if(eselect.value>=1)
      {
	index=eselect.value;
	var url="../etc/sell2.php";
	var postData="index="+index;
	YAHOO.util.Connect.initHeader("Content-Type","text/html;charset=utf-8");
	YAHOO.util.Connect.asyncRequest('POST',url,{success:handler},postData);
      }
  }
}
function handler(o)
{
  //JSON response format: {"noerror":false/true,"price":null/["100.00"]}
  var response=eval("("+o.responseText+")");
  if(response.noerror)
    {
      price=response.price[0];
      document.getElementById('price').innerHTML=price;
      qty=+(response.quantity);
      document.getElementById('shareqty').innerHTML=qty;
    }
  else{ alert("Some Error occured, please tye again...");}
} //handler() ends here.
function sell()
{ 
  if(document.getElementById('stocks').value==0)
    { alert("Please select a stock option"); }
  else
    {
      qtytosell=+(document.getElementById('q').value);
      var regex=/^[0-9]+$/; 
      //RiDucuLuS EXpreSsioN ****MUST BE IMPROVED !!*****
      if((!(regex.test(qtytosell)))||(qtytosell>qty)||(qtytosell==0)||(qtytosell==null))
	{
	  document.getElementById("qtyspan").style.color="red";
	  document.getElementById("q").focus();
	  return false;
	}
      else
       	{
	  document.getElementById('qtyspan').style.color="black";
	  /* Send the 'price' field, 'qty' field and 'index' information using AJAX to 
	   * sell3.php. sell3.php would do the necessary work and respond accordingly.
	   * The result of this transaction would be displayed on the 4th hidden div.
	   */
	  var url="../etc/sell3.php";
	  var postData="price="+price+"&qtytosell="+qtytosell+"&index="+index;
	  YAHOO.util.Connect.initHeader("Content-Type","text/html;charser-utf-8");
	  YAHOO.util.Connect.asyncRequest('POST',url,{success:handler2},postData);
	  return false;
	}
    }
}
function handler2(d)
{
  var response=eval("("+d.responseText+")");
  var respdiv=document.getElementById('response');
  if(response.noerror)
    {
      respdiv.style.display="block";
      respdiv.style.color="black";
      respdiv.style.display="block";
      respdiv.innerHTML="You Sold <b>"+qtytosell+"</b> Shares, for total worth<b> "+response.worth+"</b>. Your Credit Balance now is : <b>"+response.newbal+"</b>";
    }
  else
    {
      respdiv.style.display="block";
      respdiv.style.color="red";
      respdiv.innerHTML="Some Error Occured...Please try again.";
    }
}
</script>
</head>
<?php require("bodyheader.php");?>
<div id="wrapper">
  <div id="drpdwn" style="display:block">
    <?php 
  $result=mysqli_query($con,"SELECT id,symbol FROM portfolio WHERE uid='{$id}'");
echo '<SELECT name="stocks" id="stocks"><OPTION value="0">';
echo "Select Option...</OPTION>";
while($row=mysqli_fetch_array($result))
  {
    $id=$row['id'];
    $stock=$row['symbol'];
    echo '<OPTION value="'.$id.'">'.$stock.'</OPTION>';
  }
echo '</SELECT>';
?>
  </div>  <!--Div for the drop down list -->
  <div id="price_qty" style="display:block">
  <table border="0">
   <tr>
    <td colspan="10"><label for="price">Price :</label></td>
    <td><span id="price"></span></td>
   </tr>
   <tr>
    <td colspan="10"><label>Shares Held :</label></td>
    <td><span id="shareqty"></span></td>
   </tr> 
   <form method="POST" action="" onsubmit="sell();return false;">
   <tr>					  
    <td colspan="10"><span id='qtyspan'><label>Quantity :</label></span>
    </td>
    <td><input type="text" id="q">
    </td> 
   </tr>
   <tr>
    <td colspan="10"></td>
    <td><input type="submit" value="SELL">
    </td>
   </tr>
   </form>
 </table>  					
 </div>
 <div id="response" style="display:none">
  </div> <!-- This Div will also be hidden initially, Will be used to display the response 
              received via sell3.php -->
</div>  <!-- Wrapper Div Ends -->
<br/><br/><br/>
<div align="center" sytle="padding-top:10px">
  <a href="portfolio.php"><button class="button"> PORTFOLIO </button></a>
</div>
<br/>
<?php
require("./footer.html");
?>