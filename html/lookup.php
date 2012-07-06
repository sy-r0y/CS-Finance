<?php
require("../includes/config2.php");

if((!isset($_SESSION['id'])) || (empty($_SESSION['id'])))
  {
    redirect("index.php");
  }
/*else
  {
    print("inside else-session");
  }
*/
?>
<?php
/* 1. This page will display to the user a form where he wil enter the symbol of the company
   2. An AJAX request would be sent to interceptor page "lookup2.php".
   3. "lookup2.php" will then query the Yahoo Finance and get the results.
   4. If the symbol was real and results are returned => a JSON response conatining the various fields would be       sent back to "lookup.php" as the AJAX response.
   5. If the symbol was not actual => the JSON response would send a FALSE to the field "real".
   
      JSON format : {"real":"TRUE/FALSE","price":"...","high":"...","low":"...","change":"..."}

   6. "lookup.php" will then either display an "Error" or display the various data in new "divs" and also show a       button "BUY" & "RESET"
   7. If user click "BUY" => display a textbox form field "QUANTITY" where the user will then type a numerical 
      number for the number of shares that he wants to buy.
   8. An additional form field button "CONFIRM BUY" will add the number of stocks of the company to the user's "      portfolio 
   
   9. ***DESIGN CONSIDERATION***
      Since I will use AJAX there's no need to change pages when user perofrms any actions => even when he buys
      new stocks => simply use JAVASCRIPT to "HIDE" the previous "DIVS" and other such "ELEMENTS" => and 
      "CREATE/SHOW" the new "DIVS/ELEMENTS".
*/
?>
<?php
require("./header2.html");  // Header Template.
?>
<script>

 /* All the JS that would be needed for the lookup.php ( for AJAX , hiding/showing elements ...etc). */
 /*
function getDetail()
{
  // This function will initiate the AJAX. 
}
 */

var price=0; // Global Variable.
var stocksymbol=null; // Global variable.
function getDetail()
{
  // This funtion will perfomr some validation(on the symbol entered)
  // Also it will take the symbol value-> and pass it through AJAX to lookup2.php
  /* Should we use UTF-8 encoding OR simply pass the      * symbol information as text.
  */
  var symbol= document.getElementById('symbltxt').value;

  var regEx1=/^\^/;

  /* Reject $symbol that contain commas.*/
  var regEx2=/,/;

  if((regEx1.test(symbol)) || (regEx2.test(symbol)))
    {
      document.getElementById('symblspan').style.color="red";  
      document.getElementById('symbltxt').focus();
    }
  else
    {
      var url="../etc/lookup2.php"; 
      var postData="symboltxt="+symbol;
      document.getElementById('symblspan').style.color="black";
      YAHOO.util.Connect.initHeader("Content-Type","text/html;charset=utf-8");
      YAHOO.util.Connect.asyncRequest('POST',url,{success:handler},postData);
    }

  return false;
} //getDetail() ends here.


function handler(o)
{
   /* handler() would simply check whether the ajax response received via lookup2.php is appropriate
   * and then simply display the results of the result in the table inside <div id=detail> (after setting its 
   * display property to block).
   */
  var response=eval("("+o.responseText+")");
  //Now that we have our JSON in the response object => we can access the fields using Dot Notation.
  if(response.noerror)
    {
      // Everything is cool i.e We can display the data received in the JSON formate in the table.

      document.getElementById("errormsg").style.display="none";
      document.getElementById("symbol").style.display="none"; // Hide the symbol div.
      document.getElementById("detail").style.display="blocK"; // Show the detail div
      

      price=encodeURIComponent(response.price); /* This is the Global variable which will store the price information
			     * to be sent to 'buy.php' via buyStock().
			     */

      stocksymbol=encodeURIComponent(response.symbol); // Also a Global variable which will be sent to buy.php.
      // Now Enter the data into the appropriate table cells.
      document.getElementById("td1").innerHTML=response.symbol;
      document.getElementById("td2").innerHTML=response.name;
      document.getElementById("td3").innerHTML=response.price;
      document.getElementById("td4").innerHTML=response.high;
      document.getElementById("td5").innerHTML=response.low;
      document.getElementById("td6").innerHTML=response.change;
    }
  else
    {
      //Display an error message.
      document.getElementById("errormsg").style.display="block";
      document.getElementById("errormsg").style.color="red";
      document.getElementById("errormsg2").innerHTML="No Company with such symbol exists...!!!";
    }      

}
function buyStock()
{
  /* buyStock() will be called when user types a quantity of stocks that the user wants to purchase.
   * Firstly sanitization checks would be done on the amount that user wants to purchase
       - Numerical or not.
       - Less than 200(Just for kicks, this one!!!).
   * if the data passes sanitization ==>> it will be passed using AJAX to buy.php
   * buy.php will process it & respond with whether the transactin was successful or not.
   * If transaction was successful ==>> display to the user the message describing the transaction.
   * If transaction was not successful ==>> tell the user this as an error message.
   * COOOLLL!!!!!
   */
  var usrinput=true;
  var stock=document.getElementById("qty").value;
  var regex=/^(0|[1-9][0-9]*)$/;  // Only allow Numerical input.

  if((regex.test(stock))&&(stock>=1)&&(stock<=200))
    {
      /* Since the amount of stock that the uesr wants to buy is now 'Sanitized'==>>
       * Issue an AJAX request that will send, in its postData the "symbol" 
       * and the "stock" field to buy.php
       * buy.php will then take the symbol field and check whether that symbol is already in the databse
       * if it is=> it will ADD the stock amount to the previous amount + Update user's balance.
       * if it is NOT=> it will add the new stock records to the database table+ Update user's balance.
       * for the current Price information,
       * then it will mutiply the price with the stock quantity ==>> 
       * the total price would then be checked against the balance in the user's account.
       * If the balance is sufficient ==>> carry out the transaction update user's database 
       * + send a JSON encoded response informing about the successful transaction 
       * and any other details.
       * If the balance is insufficient ==>> send JSON encoded response 
       * informing the user about this error.
       */

      /* ***IMPORNTANT*** 
       * We have to somehow gather the 'price' information of the current symbol selected.
       * 1. Either we could simply have 'buy.php' request the price information for the 'symbol' 
       *    that was passed to it via the AJAX - POST request.
       * 2. Or we could grab the price information that the user last viewed (i.e response.price)
       *    and store it in a Global variable('price')==>> this information, 
       *    we will then simply send to buy.php as AJAX-POST field.
       
       * The point here is that ==>> for 1st case the price field is going to be the latest price
       * for that stock ( even if the price which the user saw was different).
       * but in the 2nd case, the price would be what the user saw, even though it won't be
       * the latest(most up-to-date).
       */

      document.getElementById("qtyspan").style.color="black";
      document.getElementById("label-span").style.color="black";
      document.getElementById("qtyerror").style.display="none";

      var stockamt=encodeURIComponent(document.getElementById('qty').value);

      var url="../etc/buy.php";

      var postData="stockamt="+stockamt+"&price="+price+"&symbol="+stocksymbol;
      YAHOO.util.Connect.initHeader("Content-Type","text/html; charset=utf-8");
      YAHOO.util.Connect.asyncRequest('POST',url,{success:handler2},postData); 
    }
  else
    {
      // if condition failed=> Means the stock amt, user entered, is not valid.
      document.getElementById("qty").focus();
      document.getElementById("label-span").style.color="red";
      document.getElementById("qtyspan").style.color="red";
      document.getElementById("qtyerror").style.display="block";
      document.getElementById("qtyerror").innerHTML="Please Enter a Valid Quantity";
      document.getElementById("qtyerror").style.color="red";
      return false;
    }
  return false;
}
function handler2(d)
{
  //handler2() will take care of the AJAX response that buy.php will send back.
  var response=eval("("+d.responseText+")");
  //  alert(response.qtyvalid);
  if(response.noerror)
    {
      var qty=document.getElementById('qty').value;
      var syml=document.getElementById('symbltxt').value;
      var totalamt=qty*price;
      document.getElementById("detail").style.display="none";
      document.getElementById("bought").style.display="block";
      document.getElementById("qty2").innerHTML=qty; 
      document.getElementById("qty3").innerHTML=syml;
      document.getElementById("qty4").innerHTML=price;
      document.getElementById("qty5").innerHTML=totalamt;
    }
  else
    {
      if(!(response.balsufficient))
	{
	  alert(" You don't have sufficient Credit Balance !!");
	}
      else
	{
	  alert(" Some Error Occured...could not complete transaction ");
	}
    }
}
</script> 
</head>
<?php require("./bodyheader.php"); ?>
<br/><br/>
<div name="wrapper" id="wrapper">

<div id="symbol" style="display:block">
<form id="symbol-form" action="" onsubmit="return getDetail();return false;">
   <br/><br/>
  <span id="symblspan"> <label for="symbltxt"> Symbol : </label></span><input type="text" name="symbltxt" id="symbltxt" />
<span id="errormsg"><div id="errormsg2"></div></span>
<br/><br/>
 <input type="submit" value="LookUp"/>
 <input type="button" value="Reset" onclick="document.getElementById('symbltxt').value=''"/>
</form>
</div> <!-- Div symbol Ends --> 
<br/>
<div id="detail" style="display:none">
<!--<div id="detail" style="display:block">-->
<table border="0" colspan="">
 <tbody>
  <tr>
   <td><label for="td1"> Symbol : </label></td> <td><span id="td1" name="td1"></span></td>
  </tr>
  <tr>
   <td><label for="td2"> Name : </label></td> <td><span id="td2" name="td2"></span></td>
  </tr>
  <tr>
   <td><label for="td3"> Price : </label></td> <td><span id="td3" name="td3"></span></td>
  </tr>
  <tr>
   <td><label for="td4"> High : </label></td> <td><span id="td4" name="td4"></span></td>
  </tr>
  <tr>
   <td><label for="td5"> Low : </label></td> <td><span id="td5" name="td5"></span></td>
  </tr>
  <tr>
   <td><label for="td6"> Change : </label></td> <td><span id="td6" name="td6"></span></td>
  </tr>
  </tbody>
 </table>

<form id="quantity" onsubmit="return buyStock();return false;" class="forms">
  <br/><br/>
  <span id="label-span">  <label for="qty">Number of Stock : </label></span>
  <span id="qtyspan">  <input type="text" id="qty" name="qty" class="textual"/> </span>
  <div id="qtyerror" style="display:none"></div>
  <br/><br/>
  <input type="submit" value="Buy Stock"/> OR  <input type="button" value="Reset" onclick="document.getElementById('qty').value=''"/>
</form>
  
</div> <!-- Div detail Ends -->
<br/>
<div id="bought" style="display:none">
 <table>
 <tbody>
 <tr> <td> You bought </td> <td><span id="qty2" name="qty2"></span></td></tr>
 <tr> <td> <label for="qty3"> Shares Of </label></td><td><div id="qty3" name="qty3"></div></td></tr>
 <tr> <td> at the rate of</td><td><div id="qty4" name="qty4"></div></td></tr>
 <tr> <td> for a total amounting to :</td> <td><div id="qty5" name="qty5"></div></td></tr>
 </tbody>
 </table>
</div> <!-- Div bought Ends -->
<div id="error" style="display:none"></div>
 
 </div> <!-- Div wrapper Ends -->
<br/><br/><br/>
<div align="center" sytle="padding-top:10px">
  <a href="portfolio.php"><button class="button"> PORTFOLIO </button></a>
</div><br/>
<?php 
  require("./footer.html");
?>