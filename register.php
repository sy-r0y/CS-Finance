<?php

// Include the config file.
require_once("includes/config.php");
//If user is already logged in -> simply redirect to his portfolio.

if(isset($_SESSION["id"]) || !empty($_SESSION["id"]))
	 {
	   //Call the functin "redirect()" - redirect() is defined in the "function.php".
	   redirect("portfolio.php");
	   /* The argument passed here -"portfolio.php" is appended to already defined code inside the                      * redirect() and allows us to specify exactly where we want the redirection to end up.
	   */    
	   // Also we do not want for any of the rest of the code in this page to execute-> so we exit().

	   //exit();

	 }



<?php

require("includes/header.php");

?>

 <div id="registerform">
   <form id="regform" action="register2.php" method="POST" onsubmit="return validate()";>
    <table>
     <tbody>
      <tr>
       <td><label for="uname">Name :</label></td>
       <td><input type="text" id="uname" name="uname" size="40" maxlength="50"/></td>
      </tr>
      <tr>
   <td><label for="email">Email :</label></td>
   <td><input type="text" id="email" name="email" size="40" maxlength="40"/></td>
   </tr>
   <tr>
   <td><label for="password">Password :</label></td>
   <td><input type="password" name="password" id="password" size="40" maxlength="50"/></td>
   </tr>
   <tr>
   <td><label for="passconf">Confirm Password :</label></td>
   <td><input type="password" id="passconf" name="passconf" size="40" maxlength="50"/></td>
   </tr>
   <tr>
   <td>&nbsp</td>
   <td><input type="submit" value="Register"/>&nbsp</td>
   </td>
   </tbody>
   </table>
   </body>
</html>
   
     