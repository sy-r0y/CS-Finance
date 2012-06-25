<?php

require_once("../includes/config.php");// Include the config file.
//If user is already logged in -> simply redirect to his portfolio.
if(isset($_SESSION['id']) || !empty($_SESSION['id']))
	 {
	   redirect("portfolio.php");
	   /* The argument passed here -"portfolio.php" is appended to already defined code inside the                      * redirect() and allows us to specify exactly where we want the redirection to end up.
	   */    
	   // Also we do not want for any of the rest of the code in this page to execute-> so we exit().
	   exit();
	 }
?>
<!doctype html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8"> 
 <title> FINANCE-Register </title>
 <link rel="stylesheet" href="" type="text/css" media=""/>   
 <script src="../scripts/yahoo-min.js"></script> 
  <script src="../scripts/event-min.js"></script> 
  <script src="../scripts/connection-min.js"></script> 
  <script>
   /* For JS=> We would :-
      1. Validate- email, password & confirm password fields
      2. Whichever field is not properly validated=> Set Foucus+Highlight in Red.
      3. If both "Valid" => Pass data using AJAX to "interceptor" php file.
         This file would perform neccessary sanity checks + if any problem=> respond with an error(thru AJAX).
	 If user exits=> respond with "success"(thru AJAX)=> redirect the user to his "Portfolio" php.         
         If user not exists=> We're Cool !!!
	 If We're Cool(Which we are!!)=> Insert data into the database(i.e Create a new row)
   */
function validate()
   {
     var userinput=true;
     var nameinput=document.getElementById("username").value;
     var emailinput=document.getElementById("email").value;
     var passinput=document.getElementById("passw").value;
     var passconfinput=document.getElementById("passconf").value;
     var unamerex=/[a-z0-9._\-]{2,}$/i;
     var emailrex=/[a-z0-9._\-]+@[a-z0-9][a-z0-9.\-]*[\.]{1}[a-z]{2,4}$/i;
     var passrex=/.*(?=.{6})(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%^&+=]).*$/;
     //Did the user properly fill 'username','email','password','confirm password' field(i.e blank, constraints etc)

     if((nameinput=="") || (!unamerex.test(nameinput)))
      {
	document.getElementById("first").style.color="red";
	document.getElementById("username").focus();
	userinput=false;
      }
     else
      {
	document.getElementById("first").style.color="black";
      }
     if((emailinput=="") || (!emailrex.test(emailinput)))
       {
	 document.getElementById("second").style.color="red";
	 if(userinput)
	   {
	     document.getElementById("email").focus();
	   }
	 userinput=false;
	 
       }
     // If the username field is not blank
     else
       {
	 document.getElementById("second").style.color="black";
       }
     
     if((passinput=="") || (!passrex.test(passinput)))
       {
	 document.getElementById("third").style.color="red";
	 if(userinput) 
	   {
	     document.getElementById("passw").focus(); 
	   }
	 userinput=false;
       }
     //If the password field is not blank.
     else
       {
	 document.getElementById("third").style.color="black";
       }
     
     if((passconfinput=="") || (passconfinput!=passinput))
       {
	 document.getElementById("fourth").style.color="red";
	 if(userinput)
	   {
	     document.getElementById("passconf").focus();
	   }
	 userinput=false;
       }
     else
       {
	 document.getElementById("fourth").style.color="black";
       }
     
     if(userinput)
       {
	 register();
       }
     return false;
   }
function register()
{
  var nameinp=encodeURIComponent(document.getElementById("username").value);
  var emailinp=encodeURIComponent(document.getElementById("email").value);
  var passinp=encodeURIComponent(document.getElementById("passw").value);
  var postData="username="+nameinp+"&email="+emailinp+"&passw="+passinp;
  var url="../etc/register2.php"; 
  YAHOO.util.Connect.initHeader("Content-Type","text/html; charset=utf-8");
  YAHOO.util.Connect.asyncRequest('POST',url,{success:handler},postData);
}
function handler(o)
{
  var response=eval("("+o.responseText+")");
  // Now that we have the JSON in the 'response', we can access the data using "Dot Notatin".
  if(response.sanitized===false)
    {
      document.getElementById("error").innerHTML="INVALID FIELDS ENTERED";
      document.getElementById("username").focus();
    }
  
  else if(response.noconflict===false)
    {
      document.getElementById("error").innerHTML="Email Address Already Exists !!!";
      document.getElementById("email").focus();
    }
  else if(response.insert===false)
    {
      document.getElementById("error").innerHTML="Could not Insert into the Database...Please try again later";
      document.getElementById("email").focus();
    }
  else
    {
      // Meaning everything is cool...so redirect user to his portfolio.
      window.location="./portfolio.php";
      //      exit; // Since we used header() --> We need to make sure no exra code 'below' this one is "executed".
    }
}
</script>
</head>
<body>
 <div id="header">
   <ul>
    <li>
  <a href=""><button id="back" name="back">BACK</button></a>
   </li>
   <li> 
    <a href="index.php"><button id="login" name="login">LOG In </button></a>
  </li>
  </ul>
</div>
<div id="middle">
 <div id="registerform">

 <form name="regform" method="post" onsubmit="return validate(); return false;">
<table>
     <tbody>
      <tr>
       <td><span id="first">Email :</span></td>
       <td><input type="text" id="email" name="email" size="40" maxlength="50"/></td>
      </tr>
      <tr>
    <td><span id="second">Password :</span></td>
   <td><input type="text" id="passw" name="passw" size="40" maxlength="40"/></td>
   </tr>
   <tr>
   <td><span id="fourth">Confirm Password :</span></td>
   <td><input type="password" id="passconf" name="passconf" size="40" maxlength="50"/></td>
   </tr>
<tr>
<td></td>
<td><input type="submit" value="REgister"/> </td>
</tr>
  </tbody>
   </table>
  </form>
</div>
<br/><br/>
<div id="error"></div>
</div>
<?php 
    require("/includes/footer.html");
?>