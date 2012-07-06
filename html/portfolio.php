<?php
require("../includes/config2.php");
if((!isset($_SESSION['id'])) || (empty($_SESSION['id'])))
  {
    // Since user NOT logged in => redirect user to the index.php
    redirect("index.php");
  }
?>
<?php
$usrid=$_SESSION['id'];
require("./header2.html");
?>
<script>
 /* 
    Any JS scripts would be here.
 */
</script>
</head>
<?php
require("./bodyheader.php");
?>
<!-- Now on to the content of the page=> will contain the users portfolio(in a tabluar format) -->
<br/><br/>
<div id="content">
<br/><br/>
<div id="user">
  <!-- //Here I will show the user's name( from user table) and his Credit Balance( from portfolio table).-->
  <table id="usertable" border="0" class="usrtable">
  <tbody>
  <tr> <td> Email Id :</td> <td><b><?php print(userName($usrid));?></b></td> </tr>
  <tr> <td> Credit Balance :</td><td><b><?php print($balance=getBalance($usrid));?></b></td></tr>
  </tbody>
  </table>
</div>
<br/><br/>
<div id="stock">
  <!-- The table here will display the user\'s stock details -->
  <table class="stocktable" border="1">
  <thead><tr><th> Name </th> <th> Shares </th> <th> Value(Rs.) </th> </tr> </thead>
     <!-- The body portion will hava a LOOP to iterate over the user\'s stock. -->
     <!-- Can call a fucntion that will define the query to the database => and will then "return" the result-->
     <!-- While in the the loop=> USE 'mysqli_fetch_array()'-->
   <tbody>
<?php 
$sum_qty=0;
$sum_val=0;
$sql="SELECT symbol,quantity FROM portfolio WHERE uid='$usrid'";
$result=mysqli_query($con,$sql);
while($row=mysqli_fetch_array($result))
  {
    ?>    <tr>
        <td>
         <?php $name=getName($row[0]);
               print($name);
         ?>&nbsp
        </td>
	<td>
	   <? print($row[1]);
      	      $sum_qty+=$row[1];
	   ?>
        &nbsp
        </td>
        <? $total_value=getValue($row[0],$row[1]);?>
	<td> <? print($total_value);
	      $sum_val+=$total_value;  ?>
  	</td>						     
     </tr>
   <? } ?>
    </tr>
   </tbody>
    <tfoot> 
     <tr>
      <td><b>Total</b>&nbsp</td><td><b><?print($sum_qty);?></b></td><td><b><?print($sum_val)?></b></td> 
     </tr>
    </tfoot>
  </table>
      <b> Your Total Net Worth : Rs. <? print($balance+$sum_val) ?> </b>
</div>
<br/><br/><br/>
<div align="center" style="padding-top:10px">
  <a href="lookup.php"><button class="button"> BUY SHARES </button></a>
  <a href="sell.php"><button class="button"> SELL SHARES </button></a>
</div>
<?php
      require("footer.html");
?>