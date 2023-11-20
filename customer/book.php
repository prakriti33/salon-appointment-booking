<?php

@include '../dbconnection.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
};


@include 'header.php';


//  you have already established a database connection ($conn) and user_id is defined somewhere before this code.

if (isset($_POST['book_now'])) {
    $schedule_id = $_POST['Schedule_id'];
    $beautician_id = $_POST['beautician_id'];
    $service_price = $_POST['service_price'];
    $appointments_time = $_POST['appointments_time'];
    $selected_service_ids = $_POST['service_id'];

    $date = date("Y-m-d");
    $appointment_value = 1;

    // Check if the user already has a booking for this schedule
    $check_booking = mysqli_query($conn, "SELECT * FROM `appointment` WHERE schedule_id = '$schedule_id'") or die(mysqli_error($conn));

    if (mysqli_num_rows($check_booking) > 0) {
        $message[] = 'You have already booked this schedule!';
    } else {
        // Get the start_time and end_time from the schedule table
        $schedule_query = mysqli_query($conn, "SELECT start_time, end_time FROM `schedule` WHERE id = '$schedule_id'") or die(mysqli_error($conn));
        $schedule_data = mysqli_fetch_assoc($schedule_query);
        $start_time = $schedule_data['start_time'];
        $end_time = $schedule_data['end_time'];

        // Calculate the appointment end time by adding the service time
        $service_time = 0; // Set the default service time
        $service_query = mysqli_query($conn, "SELECT service_time FROM `service` WHERE id IN (" . implode(',', $selected_service_ids) . ")") or die(mysqli_error($conn));
        while ($service_row = mysqli_fetch_assoc($service_query)) {
            $service_time += $service_row['service_time'];
        }
        $appointment_end_time = strtotime($appointments_time) + ($service_time * 60); // Convert service time to seconds and add to appointment start time

        if (strtotime($appointments_time) < strtotime($start_time) || $appointment_end_time > strtotime($end_time)) {
            $message[] = 'Selected appointment time is outside the schedule range!';
        } else {
            // Calculate the total service time
            $total_service_time_minutes = $service_time; // This already contains the total service time in minutes

            // Check if the variables are numeric before performing calculations
            if (is_numeric($total_service_time_minutes)) {
                // Insert the booking into the database with the original service price
                $service_id_list = implode(',', $selected_service_ids);
                mysqli_query($conn, "INSERT INTO `appointment` (user_id, schedule_id, beautician_id, service_price, appointments_time, date) VALUES ('$user_id', '$schedule_id', '$beautician_id', '$service_price', '$appointments_time', '$date')") or die(mysqli_error($conn));

                $appointment_id = mysqli_insert_id($conn); // Get the auto-generated appointment ID

                // Insert the appointment and service IDs into the appointment_service table
                foreach ($selected_service_ids as $service_id) {
                    mysqli_query($conn, "INSERT INTO `appointment_service` (appointment_id, service_id) VALUES ('$appointment_id', '$service_id')") or die(mysqli_error($conn));
                }
                $message[] = 'Schedule booked successfully!';

                // Update max_appointments in the schedule table
                mysqli_query($conn, "UPDATE `schedule` SET max_appointments = max_appointments - $appointment_value WHERE id = '$schedule_id'") or die(mysqli_error($conn));
            } else {
                $message[] = 'Error: Invalid numeric value encountered in calculations!';
            }
        }
    }
}


// ... (rest of the code)


@include 'message.php';

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
    <link rel="stylesheet" href="css/style.css">

</head>
<style>
    input[type="submit"] {
        background-color: #830303;
        color: white;
        border: none;
        padding: 10px 16px;
        cursor: pointer;
        border-radius: 4px;
    }
</style>

<body>

    <section class="book">
        <input type="text" id="search-input" placeholder="Search " style="width: 400px; border-radius: 10px;padding: 20px 20px ; margin-bottom: 20px ;display: flex;justify-content: center;text-align: center;padding-left: 1%; color:#830303">

        <h1 class="title">Schedule </h1>

        <div class="box-container">


            <?php
            if (isset($_GET['from_date']) && isset($_GET['to_date'])) {
                $from_date = $_GET['from_date'];
                $to_date = $_GET['to_date'];

                // Create an array with the dates
                $dateArray = array($from_date, $to_date);

                // Insertion sort function
                function insertionSort(&$arr)
                {
                    $length = count($arr);
                    for ($i = 1; $i < $length; $i++) {
                        $key = $arr[$i];
                        $j = $i - 1;
                        while ($j >= 0 && strtotime($arr[$j]) > strtotime($key)) {
                            $arr[$j + 1] = $arr[$j];
                            $j--;
                        }
                        $arr[$j + 1] = $key;
                    }
                }

                // Sort the date array using insertion sort
                insertionSort($dateArray);

                // Query the database for the sorted date range
                $select_Schedule = mysqli_query($conn, "SELECT * FROM `Schedule` WHERE date BETWEEN '$dateArray[0]' AND '$dateArray[1]'") or die(mysqli_error($conn));
            } elseif (isset($_GET['date'])) {
                $date = $_GET['date'];

                $select_Schedule = mysqli_query($conn, "SELECT * FROM `Schedule`  WHERE date = '$date'") or die(mysqli_error($conn));
            } else {
                $select_Schedule = mysqli_query($conn, "SELECT * FROM `Schedule` ") or die('Query failed');
            }


            // Display the results
            if (mysqli_num_rows($select_Schedule) > 0) {
                while ($fetch_Schedule = mysqli_fetch_assoc($select_Schedule)) {
                    $beautician_id = $fetch_Schedule['beautician_id'];
                    $Schedule_id = $fetch_Schedule['id'];
                    $select_beautician = mysqli_query($conn, "SELECT * FROM `beautician`  WHERE id = ' $beautician_id '") or die('Query failed');


                    $fetch_beautician = mysqli_fetch_assoc($select_beautician);

                    // Fetch array of service IDs from beautician table
            ?>


                    <form action="" method="POST" class=" div-element box"  style="width: 130%;">


                        <div class="price">Rs </span>
                            <?= htmlspecialchars($fetch_beautician['price']) ?>
                        </div>

                        <img src="../uploaded_img/<?php echo $fetch_beautician['image']; ?>" style="width: 100%;" alt="">
                        <div class="name" style="padding-top: 20%;">
                            <label for="name">Beautician Name:</label>
                            <?php echo htmlspecialchars($fetch_beautician['name']); ?>

                        </div>
                        <div class="div" style="font-size: large;" class="box">
                            <label for="date">Schedule Date:</label>
                            <?php echo htmlspecialchars($fetch_Schedule['date']); ?><br>

                         
                            <?php

                            // Create an array of service IDs for the SQL query

                            $select_categories =  mysqli_query($conn, "SELECT ss.service_id, s.name, s.price, s.service_time FROM `schedule_service` ss
                           JOIN `service` s ON ss.service_id = s.id
                           WHERE ss.schedule_id = '$Schedule_id'")  or die(mysqli_error($conn));


                          
                            if (mysqli_num_rows($select_categories) > 0) {
                                while ($fetch_category = mysqli_fetch_assoc($select_categories)) {
                                    // Extract data from the database

                                    $service_id = $fetch_category['service_id'];
                                    $service_name = $fetch_category['name'];

                            ?>
                                 
                                    <div style="display: flex; align-items: center; margin-right: 10px;">
                                        <br>
                                        <input type="checkbox" name="service_id[]" value="<?php echo $service_id; ?>" style="margin-right: 5px;">

                                        <?php echo $service_name . '  Rs: ' . $fetch_category['price'] . ', Time: ' . $fetch_category['service_time'] . 'min'; ?></label>
                                    </div>


                                    <input type="hidden" name="Schedule_id" value="<?php echo htmlspecialchars($fetch_Schedule['id']); ?>">
                                    <input type="hidden" name="beautician_id" value="<?php echo htmlspecialchars($fetch_beautician['id']); ?>">
                                    <input type="hidden" name="start_time" value="<?php echo htmlspecialchars($fetch_Schedule['start_time']); ?>">
                                    <input type="hidden" name="end_time" value="<?php echo htmlspecialchars($fetch_Schedule['end_time']); ?>">
                                    <input type="hidden" name="service_price" value="<?php echo htmlspecialchars($fetch_category['price'] + $fetch_beautician['price']); ?>">
                            <?php
                                };
                            } else {
                                echo '<p class="empty">No service available!</p>';
                            };


                            ?>

                        </div>
                        <br>
                        <?php

                        if (isset($_SESSION['user_id']) && $fetch_Schedule['max_appointments'] > 0) {
                            $schedule_id = $fetch_Schedule['id'];
                            $user_id = $_SESSION['user_id'];

                            // Get booked appointments for the current user
                            $select_appointment = mysqli_query($conn, "SELECT * FROM `appointment` WHERE schedule_id = '$schedule_id'") or die('Query failed');

                            $bookedAppointments = array();
                            $total_service_time = 0;

                            while ($fetch_appointment = mysqli_fetch_assoc($select_appointment)) {
                                $appointment_id = $fetch_appointment['id'];

                                // Fetch appointment services
                                $select_appointme = mysqli_query($conn, "SELECT * FROM `appointment_service` WHERE appointment_id = '$appointment_id'") or die(mysqli_error($conn));

                                while ($fetch_appointmen = mysqli_fetch_assoc($select_appointme)) {
                                    $service_id = $fetch_appointmen['service_id'];

                                    // Fetch the service_time for the current service_id
                                    $service_query = mysqli_query($conn, "SELECT service_time FROM `service` WHERE id = '$service_id'") or die('Query failed');

                                    if ($service_data = mysqli_fetch_assoc($service_query)) {
                                        $total_service_time += $service_data['service_time'];
                                    }
                                }

                                // Store booked appointment times
                                $bookedAppointments[] = strtotime($fetch_appointment['appointments_time']);
                            }

                            $start_Time = strtotime($fetch_Schedule['start_time']);
                            $end_Time = strtotime($fetch_Schedule['end_time']);

                            // Increment time by 20 minutes
                            $interval = 45 * 60; // 30 minutes in seconds
                            $current_Time = $start_Time;

                            $availableTimeSlots = array();

                            while ($current_Time <= $end_Time) {
                                $timeSlot = date('h:i A', $current_Time);

                                // Check if the time slot is booked
                                $isBooked = false;
                                $currentTimePlusService = $current_Time + ($total_service_time * 60);

                                foreach ($bookedAppointments as $bookedTime) {
                                    if ($current_Time >= $bookedTime && $current_Time < $currentTimePlusService) {
                                        $isBooked = true;
                                        break;
                                    }
                                }

                                if (!$isBooked && ($currentTimePlusService <= $end_Time)) {
                                    $availableTimeSlots[] = $timeSlot;
                                }

                                $current_Time += $interval;
                            }

                            if (!empty($availableTimeSlots)) {
                        ?>
                                <form action="" method="post">
                                    <select name="appointments_time" class="time-slot">
                                        <?php foreach ($availableTimeSlots as $timeSlot) { ?>
                                            <option value="<?php echo $timeSlot; ?>">
                                                <?php echo $timeSlot; ?> (Available)
                                            </option>
                                        <?php } ?>
                                    </select>
                                    <br>
                                    <input type="submit" value="book now" name="book_now" class="btn">
                                </form>
                <?php
                            } else {
                                echo '<p class="empty">No available time slots.</p>';
                            }
                        } else {
                            echo '<p class="empty">User not logged in or maximum appointments reached.</p>';
                        }
                    }
                }





                ?>






    </section>



    <?php @include 'footer.php'; ?>

    <script src="js/script.js"></script>



    <script>
        // Search algorithm


        // code allows the user to search for specific text within a list of elements, and it dynamically filters and displays the elements that match the search query.
        const searchInput = document.getElementById('search-input');
        const divElements = document.querySelectorAll('.div-element');
        //  ends here

        // we're selecting the search input element and a collection of elements with the class "div-element" 
        searchInput.addEventListener('input', function() {
            const query = searchInput.value.toLowerCase();

            divElements.forEach(function(divElement) {
                const textContent = divElement.textContent.toLowerCase();

                if (textContent.includes(query)) {
                    divElement.style.display = 'block';
                } else {
                    divElement.style.display = 'none';
                }
            });
        });
        // ends here

        //    function

        function binarySearch(numbers, target) {
            let left = 0;
            let right = numbers.length - 1;

            while (left <= right) {
                const mid = Math.floor((left + right) / 2);
                const midValue = numbers[mid];

                if (midValue === target) {
                    // Found the target
                    return mid;
                } else if (midValue < target) {
                    left = mid + 1;
                } else {
                    right = mid - 1;
                }
            }

            // Target not found
            return -1;
        }



        // Example usage:
        const sortedNumbers = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
        const targetNumber = 7;
        const result = binarySearch(sortedNumbers, targetNumber);

        if (result !== -1) {
            console.log(`Found ${targetNumber} at index ${result}`);
        } else {
            console.log(`${targetNumber} not found in the list`);
        }
    </script>

</body>

</html>