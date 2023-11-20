<?php

session_start();

$user_id = $_SESSION['user_id'];




?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- custom admin css file link  -->
    <link rel="stylesheet" href="../css/style.css">

</head>

<body>



    <header class="header" style=background-color:white>



        <header class="header">

            <div class="flex">

                <div class="logoContent">
                  
                    <h1 class="logoName">Salon Appointment Booking </h1>
                </div>
                <nav class="navbar">
                    <ul>
                        <li><a href="index.php" >Home</a></li>
                        <li><a href="about.php">About </a>

                        </li>
                        <li><a href="book.php" >Book</a></li>
                        <li><a href="appointment.php" >Appointment</a></li>
                        <?php if (isset($_SESSION['user_id'])) { ?>

                        <?php } else { ?>
                            <li><a href="#" >Account +</a>
                                <ul>

                                    <li><a href="login.php" >login</a></li>
                                    <li><a href="register.php" >register</a></li>
                                </ul>
                            <?php }
                            ?>


                    </ul>
                </nav>

                <div class="icons">
                    <?php if (isset($_SESSION['user_id'])) { ?>
                        <div id="user-btn" class="fas fa-user">
                            <div class="account-box">
                                <p>Email : <span><?php echo $_SESSION['email']; ?></span></p>
                                <a href="logout.php" class="delete-btn">logout</a>

                            <?php } else { ?>


                            <?php } ?>
                            </div>
                        </div>
                </div>

        </header>