<?php
include 'admin_header.php';

if (isset($_POST['update_order'])) {
    $appointment_id = $_POST['appointment_id'];
    $update_payment = $_POST['update_payment'];
    $date = $_POST['date'];

    // Get the service price from the appointment
    $get_service_price_query = mysqli_query($conn, "SELECT service_price FROM `appointment` WHERE id = '$appointment_id'") or die(mysqli_error($conn));
    $service_price = mysqli_fetch_assoc($get_service_price_query)['service_price'];



    mysqli_query($conn, "UPDATE `appointment` SET payment_status = '$update_payment', date = '$date' WHERE id = '$appointment_id'") or die(mysqli_error($conn));
    $message[] = 'Payment status has been updated!';

    // Insert bill invoice data

    $payment_date = date('Y-m-d'); // Current date

    mysqli_query($conn, "INSERT INTO `billinvoice` (appointment_id, payment_status, payment_date) VALUES ('$appointment_id', 'paid', '$payment_date')") or die(mysqli_error($conn));

    $message[] = 'Bill invoice has been created!';
}

if (isset($message)) {
    foreach ($message as $message) {
        echo '
  <div class="message">
     <span>' . $message . '</span>
     <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
  </div>
  ';
    }
}

if (isset($message_1)) {
    foreach ($message_1 as $message_1) {
        echo '
<div class="message_1">
  <span>' . $message_1 . '</span>
  <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
</div>
 ';
    }
}
?>
<div class="table__body" style="font-size: 1.9rem;">
    <h1>Appointment List</h1>
    <div class="card-body">




        <table class="table">
            <thead>
                <tr>

                    <th style="width:1%;">SNo.</th>
                    <th style="width:1%;">Date</th>
                    <th style="width:1%;">Username</th>


                    <th style="width:1%;">Totalprice </th>
                    <th style="width:1%;">Status</th>
                    <th style="width:1%;">Action</th>
                </tr>
            </thead>
            <?php

            // Check if a filter date is set

            $select_appointment_query = "SELECT a.id, a.date, a.service_price, a.payment_status, u.username
            FROM appointment a
            JOIN beautician b ON a.beautician_id = b.id
            JOIN users u ON a.user_id = u.id
            JOIN schedule s ON a.schedule_id = s.id";
        
        $select_appointment = mysqli_query($conn, $select_appointment_query) or die(mysqli_error($conn));
        
        if (mysqli_num_rows($select_appointment) > 0) {
            while ($fetch_appointment = mysqli_fetch_assoc($select_appointment)) {
                ?>
                <tbody>
                <tr class="active">
                    <td><?php echo $fetch_appointment['id']; ?></td>
                    <td><?php echo $fetch_appointment['date']; ?></td>
                    <td><?php echo $fetch_appointment['username']; ?></td>
                    <td>RS <?php echo $fetch_appointment['service_price']; ?></td>
        
                    <td>
                        <form class="status delivered" action="" method="post">
                            <input type="hidden" name="appointment_id" value="<?php echo $fetch_appointment['id']; ?>">
                            <input type="hidden" name="date" value="<?php echo $fetch_appointment['date']; ?>">
                            <select name="update_payment" style="background-color:#ffb3db;">;color: black;">
                                <option value=""><?php echo $fetch_appointment['payment_status']; ?></option>
                                <option value="pending">pending</option>
                                <option value="completed">completed</option>
                                <option value="cancel">cancel</option>
                            </select><br>
                    </td>
        
                    <td>
                        <input type="submit" class="option-btn" value="update" name="update_order" style="background-color:pink;">
                    </td>
                </tr>
                <?php
            }
        } else {
            echo '<p class="empty">No Appointments</p>';
        }
?>        
                                                    </tbody>
        </table>
    </div>
    </section>
    </section>
</div>