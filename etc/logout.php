<?php
require("../includes/config2.php");

session_start();
unset($_SESSION['id']);
session_destroy();
//redirect("index.php");
header("Location:../html/index.php");
?>