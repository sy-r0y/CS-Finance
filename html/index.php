<?php


require("../includes/config2.php");

if((isset($_SESSION['id'])) || (!empty($_SESSION['id'])))
  {
    redirect("portfolio.php");     // Since user NOT logged in => redirect user to the index.php
  }

?>

<!--
 This will be the landing page....so login would be done from this.
 user wants to register, he will have to click on the 'Sign-Up' button, on the page header.
-->
<?php 
  require("header2.html");
?>
 <script>
   /* For JS=> We would :-
      1. Validate both username & password
      2. Whcihever field is not properly validated=> Set Foucus+Highlight in Red.
      3. If both "Valid" => Pass data using AJAX to "interceptor" php file.
         This file would perform neccessary sanity checks + if any problem=> respond with an error(thru AJAX).
         If no problem=> Query database => Check if corresponding user exists.
         If user exits=> respond with "success"(thru AJAX)=> redirect the user to his "Portfolio" php.
         If user not exists=> respond with "No User" (thru AJAX).
   */
 function validate()
   {

    var userinput=true;
    var emailinput=document.getElementById("uname").value;
    var passinput=document.getElementById("pswd").value;
    var userrg=/[a-z0-9._\-]+@[a-z0-9][a-z0-9.\-]*[\.]{1}[a-z]{2,4}$/i;

    var passrg=/.*(?=.{6})(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%^&+=]).*$/;

    // \W => ILLEGAL CHARS(whitepace, tabs, newline+ ALSO #$#$#$# etc etc),So we have 'negated' the @#%^&*.
    
    //Did the user left the username field blank?
    if((emailinput=="")|| (!userrg.test(emailinput)))
     {
      document.getElementById("userspan").style.color="red";
      document.getElementById("uname").focus();
      userinput=false;
     }
     // If the username field is not blank
     else
       {
	 document.getElementById("userspan").style.color="black";
       }
     
    if((passinput=="")|| (!passrg.test(passinput)))
    {
      document.getElementById("passspan").style.color="red";
      if(userinput) 
	{
	  document.getElementById("pswd").focus(); 
	}
      userinput=false;
    }
    //If the password field is not blank.
    else
      {
	document.getElementById("passspan").style.color="black";
      }
    
    if(userinput)
     {
    	authenticate();
     }
    return false;
   }
   function authenticate()
   {
     var email=encodeURIComponent(document.getElementById("uname").value);
     var pass=encodeURIComponent(document.getElementById("pswd").value);    
     var postData="uname="+email+"&pswd="+pass;
     var url="../etc/login.php"; 
     YAHOO.util.Connect.initHeader("Content-Type","text/html; charset=utf-8");
     YAHOO.util.Connect.asyncRequest('POST',url,{success:handler},postData);
   }
function handler(o)
{
  /* login.php will send a "FLAG" -> this "FLAG" if:
   * 1. SET(ie 1) => redirect user to portfolio.php and set $_SESSION["id"] to TRUE
   * 2. NOT SET(ie 0) => highlight the fields and display that "INVALID LOGIN".
  */

  var response=eval("("+o.responseText+")");
  
  // Now that we have the JSON in the 'response', we can access the data using "Dot Notatin".
  if(response.authentic===false)
    {
      document.getElementById("error").innerHTML="INVALID LOGNdfdfdf";
      //      alert("invalid login");
      document.getElementById("uname").focus();
      //      document.getElementById("error").appendChild(div);
      //      document.getElementById("error").style.color="red";
    }
  else if(response.authentic===true)
    {
      // Redirect the user to "portfolio.php"
      // Use the function "redirect", defined in the function.php".
      //      redirect("portfolio.php");
      // MAY ALSO be able to use the header(Location:"portfolio.php"); function.
      //header("Location:portfolio.php");
      alert("successful login");
      window.location="./portfolio.php";
    }
}
</script>
</head>
<body>
  <!-- WE NEED A HEADER TO DISPLAY THE SIGNUP BUTTON -->
 <div id="header">
   <br/>
   <div id="head">
     <a href="register.php"><button id="signup" name="signup" >SIGN UP</button></a>
   </div>
 </div>
 <div id="middle" >
   <br/><br/>
   
   <!-- THE LOGIN FORM WILL ASK THE USER FOR "username" & "password" and on validation+ succesful authentication        redirect him to his "portfolio". -->

   <form name="loginform" method="POST" action="" onsubmit="return validate();return false;" accept-charset="utf-8">
     <!-- Clearly we need to "VALIDATE" the user input on the clientside & on succesful "validation" sen          d the form details to an intercepting php page(AJAX), this "intercepting" page will defensively check          received field details and authenticate accordingly....if succesful authenticatin occurs, the user is           redirected from the index form(where he currently is. But if invalid authenticatin occurs => then thru           AJAX user is notified of this inauthentication.
      -->
     <div id="logindetails">
       <br/><br/>
       <span id="userspan"><b>User Name :</b><input type="text" charset="utf-8" id="uname" name="uname" class="textual"/></span>
       <br/><br/>
      <span id="passspan"> <b>Password :</b><input type="password" charset="utf-8" id="pswd" name="pswd" class="textual"/></span>
       <br/><br/>
       <div align="center">
	 <input type="submit" name="sbmit" id="sbmit" class="button" value="LOGIN"/>
       </div>
     </div>
   </form>
<br/><br/>
<div id="error"></div>
<br/><br/>
</div>
<?php
 require("./footer.html");
?>