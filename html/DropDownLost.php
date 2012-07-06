<?php
// Connect to your database ** EDIT THIS **
mysql_connect("localhost","root",""); // (host, username, password)

// Specify database ** EDIT THIS **
mysql_select_db("dropdown") or die("Unable to select database"); //select db

$result = mysql_query("select DISTINCT id,cities from locations"); 

echo '<select name="city"><OPTION>';
echo "Select an option</OPTION>";
while ($row = mysql_fetch_array($result)){
$id = $row["id"];
$city = $row["cities"];
echo "<OPTION value=\"$id\">$city</OPTION>";
}
echo '</SELECT>';
?>
-------------------------

<?
...
mysql cnx code
...

$sql="SELECT id, thing FROM table";
$result=mysql_query($sql);

$options="";

while ($row=mysql_fetch_array($result)) {

    $id=$row["id"];
    $thing=$row["thing"];
    $options.="<OPTION VALUE=\"$id\">".$thing;
}
?>
...
html code
...

<SELECT NAME=thing>
<OPTION VALUE=0>Choose
<?=$options?>
</SELECT>
...


---------------------------
<?
// Array contents
$array2 = array('s'=>'Sydney','m'=>'Melbourne','b'=>'Brisbane','T'=>'Tasmania','a'=>'Adelaide','p'=>'Perth','d'=>'Darwin','t'=>'ACT'); 

// Values from array 2
echo'<select name="cities">';
// For each key of the array assign variable name "cityid"
// For each value of the array assign variable name "city".
foreach($array2 as $cityid=>$city){
    echo'<option value="'.$cityid.'">'.$city.'</option>';
}
echo'</select>';
?>