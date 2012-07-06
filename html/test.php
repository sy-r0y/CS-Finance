
<!DOCTYPE html>
<html>
<head><title>Test Drop Down List </title>
<script type="text/javascript">

window.onload=function(){

   var eselect=document.getElementById('newton');
   eselect.onchange=function(){
     //     alert(eselect.selectedIndex);
     alert(eselect.value);
   }

 }
</script>
</head>
<body>
<?php
$arr= array( 
	     'a'=> 'Mahatma Gandhi','b'=>'Bob Dylan','c'=>'Steve Jobs','d'=>'Alexander',
	    'e'=>'John Lennon','f'=>'Steve Wozniak','g'=>'Henry Ford',
	     'h'=>'Walter Groupious','i'=>'Dieter Rams'
	     );?>
<?php $arr2= array( 
	     'Mahatma Gandhi','Bob Dylan','Steve Jobs','Alexander',
	     'John Lennon','Steve Wozniak','Henry Ford',
	     'Walter Groupious','Dieter Rams'
	     );?>
<pre>
 <?php print_r($arr);?>
</pre>
<? 
echo '<select id="newton" name="newton">';
echo '<option value="0">Select One</option>';
foreach($arr as $id=>$people)
{
  echo '<option value="'.$id.'">'.$people.'</option>';
}
echo '</select>';
?>
</body>
</html>