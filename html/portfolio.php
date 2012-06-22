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

// Upto <script..YUI ajax is here included...<head> is still 'OPEN'.....any JS script can came next.
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
  <!--  // Here I will show the user's name( from user table) and his Credit Balance( from portfolio table)-->
  <table id="usertable" border="0" class="table">
  <tbody>
  <tr> <td> Email Id :</td> <td><b><?php print(userName($usrid));?></b></td> </tr>
  <tr> <td> Credit Balance :</td><td><b><?php print(getBalance($usrid));?></b></td></tr>
  </tbody>
  </table>
</div>
<br/><br/>
<div id="stock">
  <!-- The table here will display the user\'s stock details -->
  <table class="table" border="1">
   <thead><tr><th> Name </th> <th> Shares </th> <th> Value </th> </tr> </thead>


    <!-- The body portion will hava a LOOP to iterate over the user\'s stock. -->
     <!-- Commenting like this CAN be VERY VERY exploitable==>> NEVER EVER use this in an actual project. !!!-->
  
     <!-- Can call a fucntion that will define the query to the database => and will then "return" the result-->
     <!-- While in the the loop=> USE 'mysqli_num_rows()' + also will USE -> 'mysqli_result()' -->

   <tbody>
<!--  //  $result=getStock(); // getStock() will return the results of the mysql_query(). 
-->
  <!-- // IMPORTANT => for initial version -> lets test whether everything works cool by doing everything in the same page=> i.e NO USE OF FUNCTIONS===>> use fucntion approach after everything workds cool.  


  //         ****  USE JOINS ****
-->

<?php  $sql="SELECT * FROM stock WHERE stock.id=portfolio.stockid";      
?>

<!--  /*

mysql_connect(localhost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");
$query="SELECT * FROM tablename";
$result=mysql_query($query);

$num=mysql_numrows($result);

mysql_close();
?>
<table border="0" cellspacing="2" cellpadding="2">
<tr>
<td><font face="Arial, Helvetica, sans-serif">Value1</font></td>
<td><font face="Arial, Helvetica, sans-serif">Value2</font></td>
<td><font face="Arial, Helvetica, sans-serif">Value3</font></td>
<td><font face="Arial, Helvetica, sans-serif">Value4</font></td>
<td><font face="Arial, Helvetica, sans-serif">Value5</font></td>
</tr>

<?php
$i=0;
while ($i < $num) {

$f1=mysql_result($result,$i,"field1");
$f2=mysql_result($result,$i,"field2");
$f3=mysql_result($result,$i,"field3");
$f4=mysql_result($result,$i,"field4");
$f5=mysql_result($result,$i,"field5");
?>

<tr>
<td><font face="Arial, Helvetica, sans-serif"><?php echo $f1; ?></font></td>
<td><font face="Arial, Helvetica, sans-serif"><?php echo $f2; ?></font></td>
<td><font face="Arial, Helvetica, sans-serif"><?php echo $f3; ?></font></td>
<td><font face="Arial, Helvetica, sans-serif"><?php echo $f4; ?></font></td>
<td><font face="Arial, Helvetica, sans-serif"><?php echo $f5; ?></font></td>
</tr>

<?php
  $i++;
}
?>
</body>
</html>
------------------------


  */
-->
    <tr>
     <td>
     </td>
    </tr>
   </tbody>
   <tfoot> <tr> <td>&nbsp </td> <td> </td> </tr> </tfoot>
  </table>
</div>
</body>
</html>
