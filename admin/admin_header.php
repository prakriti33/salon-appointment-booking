<?php
include('../dbconnection.php');


session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:../customer/login.php');
};

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="stylesheet" href="../css/admin_style(1).css">

</head>

<body>

    <header class="header" style= background-color:#830303>

        <section class="flex" >

            <a href="index.php" class="logo" style="color: white;">Admin Dashboard</a>

        </section>

    </header>

    <!-- header section ends -->

    <!-- side bar section starts  -->

    <div class="side-bar" >

        <div class="close-side-bar">
            <i class="fas fa-times"></i>
        </div>

        <div class="profile">

            <img src="../images/pic-3.png" alt="">
            <h3><?php echo $_SESSION['admin_email']; ?></h3>

            <!-- <a href="profile.php" class="btn">view profile</a> -->



        </div>

        <nav class="navbar">
            <a href="index.php"><i class="fas fa-home" style="color:black"></i><span style="color:black" >home</span></a>
            <a href="admin_service.php"><i class="fa-solid fa-bars-staggered" style="color:black"></i><span style="color:black">service</span></a>
            <a href="admin_beautician.php"><i class="fa-solid fa-bars-staggered" style="color:black"></i><span style="color:black">beautician</span></a>
            <a href="admin_schedule.php"><i class="fa-solid fa-bars-staggered" style="color:black"></i><span style="color:black">schedule</span></a>
            <a href="admin_appointment.php"><i class="fa-solid fa-bars-staggered" style="color:black"></i><span style="color:black">appointment</span></a>


            <a href="admin_logout.php" onclick="return confirm('logout from this website?');"><i class="fas fa-right-from-bracket" style="color:black" ></i><span style="color:black">logout</span></a>
        </nav>

    </div>

    <!-- side bar section ends -->