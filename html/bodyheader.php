<body>
<?php
// Start the body

/* Will contain the body header--> which will be dynamically generated to either render the header for the case  * when the user IS LOOGED or when the user is NOT LOGGED.
 */
 

 /* DESIGN NOTE ==>> SHOULD USE FUNCTION (--say isAuthenticated()) to check whether user logged in or not.
    Exmpl:-    	function blog_isAuthenticated()
                {
		  return (isset($_SESSION[BLOG_USER]));
	        }

AND THEN USE THE FUNCTION ANYWHERE AS
<? 
	if(blog_isAuthenticated()) 
	{ 
        	$user = $_SESSION[BLOG_USER]; 
?>

<li><? echo $user->username; ?></li> | <li><a href="addPost.php">Add Post</a></li> | <li><a href="logout.php">Sign Out</a></li>
<?  } else {  ?>
	<li><a href="login.php">Sign In</a></li>
<?  }  ?>

*/

?>