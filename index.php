<?php
session_start();
include 'header.php';
// check if user is logged in -->
if (!isset($_SESSION['username'])) {
   header('location:customer/');
   exit();
}
?>

<a href="users/" class="btn">go back</a>





