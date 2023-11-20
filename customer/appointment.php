<?php
//session_start();
include_once('../dbconnection.php');
include_once('header.php');


if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
    header('location:login.php');
};

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
          .title{
            font-size: medium;
            text-align: center;
          }

        /* Style for the appointment boxes */
        .box-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .box {
            background-color:  #F5EBEB;
            border-style:inset;
            padding: 15px;
            background: #f9f9f9;
        }

        /* Style for appointment details */
        .box p {
            color: #830303;
            font-style: italic;
            font-size: large;

            margin: 5px 0;
        }

        /* Style for specific spans within appointment details */
        .box p span {
            color: black;
            font-weight: bold;
        }

        /* Empty message style */
        .empty {
            text-align: center;
            font-style: italic;
            color: #888;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <section class="placed-orders">

        <h1 class="title"></h1>

        <div class="box-container">
            <?php
            // It first checks if the user is logged in and then retrieves and displays appointment 

            if (isset($_SESSION['user_id'])) {
                $user_id = $_SESSION['user_id'];
            } else {
                $user_id = '';
            };
            include_once('../dbconnection.php');

            include_once('header.php');

            ?>
            <section class="placed-orders">

                <h1 class="title">Appointment Bill</h1>

                <div class="box-container">
                    <?php
                    $user_id = $_SESSION['user_id'];
                    $query = "
                    SELECT 
                      a.*,
                   b.name AS beautician_name,
                      b.image AS beautician_image,
                      s.name AS service_name,
                      s.id AS service_id,
                      sch.date AS schedule_date,
                      a.service_price
                   FROM 
                      appointment AS a
                   LEFT JOIN 
                      beautician AS b ON a.beautician_id = b.id
                   LEFT JOIN 
                      schedule AS sch ON a.schedule_id = sch.id
                   LEFT JOIN 
                      service AS s ON sch.service_id = s.id
                   WHERE 
                      a.user_id = '$user_id'
                   ORDER BY 
                      a.id DESC
                      ";
                    $result = mysqli_query($conn, $query) or die(mysqli_error($conn));

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                    ?>


                            <div class="box">
                    
                                <p> Appointment On: <span><?php echo $row['appointments_time']; ?></span> </p><br>
                                <p> Appointment Date: <span><?php echo htmlspecialchars($row['schedule_date']); ?></span> </p><br>
                                <p> Beautician Name: <span><?php echo htmlspecialchars($row['beautician_name']); ?></span> </p><br>
                                <p> Service Name: <span><?php echo htmlspecialchars($row['service_name']); ?></span> </p><br>
                                <hr>
                                <p> Total Price: <span>Rs <?php echo $row['service_price']; ?>/-</span> </p>

                        <?php
                        }
                    } else {
                        echo '<p class="empty">No orders placed yet!</p>';
                    }
                        ?>


                            </div>



                </div>

            </section>
</body>

</html>