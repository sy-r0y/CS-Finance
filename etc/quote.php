<?php
// SAVE FILE FROM OTHER BUFFER .

//    define("YAHOO", "http://download.finance.yahoo.com/d/quotes.csv?f=snl1d1t1c1ohg&s=");

$handle=@fopen("http://download.finance.yahoo.com/d/quotes.csv?f=snl1d1t1c1ohg&s=aapl","r");
$handle2 = @fopen("http://download.finance.yahoo.com/d/quotes.csv?s=AAPL&f=e1l1hg", "r");
$data=fgetcsv($handle);
print("<pre>");
print_r($data);
print("</pre>");
$data2=fgetcsv($handle2);
print("<pre>");
print_r($data2);
print("</pre>");


?>
	      