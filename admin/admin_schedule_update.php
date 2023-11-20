<?php
include 'admin_header.php';

if (isset($_POST['update_schedule'])) {
   $update_p_id = $_POST['update_p_id'];

   // Additional fields for update
   $update_beautician_id = mysqli_real_escape_string($conn, $_POST['update_beautician']);
   $update_service_id = mysqli_real_escape_string($conn, $_POST['update_service']);
   $update_date = mysqli_real_escape_string($conn, $_POST['update_date']);
   $update_start_time = mysqli_real_escape_string($conn, $_POST['update_start_time']);
   $update_end_time = mysqli_real_escape_string($conn, $_POST['update_end_time']);
   $update_max_appointments = mysqli_real_escape_string($conn, $_POST['update_max_appointments']);

   mysqli_query($conn, "UPDATE `schedule` SET  beautician_id = '$update_beautician_id', service_id = '$update_service_id', date = '$update_date', start_time = '$update_start_time', end_time = '$update_end_time', max_appointments = '$update_max_appointments' WHERE id = '$update_p_id'") or die('query failed');

   header('location: admin_schedule.php');
   $message[] = 'Schedule added successfully!';
   exit();
}

?>


<section class="form-container" style=background-color:darkcyan;> 
   <?php
   if (isset($_GET['update'])) {
      $update_id = $_GET['update'];
      $update_query = mysqli_query($conn, "SELECT * FROM `schedule` WHERE id = '$update_id'") or die('query failed');
      if (mysqli_num_rows($update_query) > 0) {
         $fetch_update = mysqli_fetch_assoc($update_query);
   ?>
         <form action="" method="post" >
            <h3>Update Schedule</h3>
            <div class="flex">
               <div class="box">
                  <label for="update_beautician">Beautician ID:</label>
                  <input type="text" name="update_beautician" value="<?php echo $fetch_update['beautician_id']; ?>" required placeholder="Enter beautician ID">
               </div>
            </div>
            <div class="flex">
               <div class="box">
                  <label for="update_service">Service ID:</label>
                  <input type="text" name="update_service" value="<?php echo $fetch_update['service_id']; ?>" required placeholder="Enter service ID">
               </div>
            </div>
            <div class="flex">
               <div class="box">
                  <label for="update_date">Date:</label>
                  <input type="text" name="update_date" value="<?php echo $fetch_update['date']; ?>" required placeholder="Enter date">
               </div>
            </div>
            <div class="flex">
               <div class="box">
                  <label for="update_start_time">Start Time:</label>
                  <input type="text" name="update_start_time" value="<?php echo $fetch_update['start_time']; ?>" required placeholder="Enter start time">
               </div>
            </div>
            <div class="flex">
               <div class="box">
                  <label for="update_end_time">End Time:</label>
                  <input type="text" name="update_end_time" value="<?php echo $fetch_update['end_time']; ?>" required placeholder="Enter end time">
               </div>
            </div>
            <div class="flex">
               <div class="box">
                  <label for="update_max_appointments">Max Appointments:</label>
                  <input type="text" name="update_max_appointments" value="<?php echo $fetch_update['max_appointments']; ?>" required placeholder="Enter max appointments">
               </div>
            </div>
            <input type="hidden" name="update_p_id" value="<?php echo $fetch_update['id']; ?>">
            <input type="submit" value="Update_schedule" name="update_schedule" class="btn" style="background-color:lightpink;">
            <a href="admin_service.php" class="delete-btn" onclick="return confirm('Cancel?');">Cancel</a>
         </form>
   <?php
      }
   } else {
      echo '<script>document.querySelector(".form-container").style.display = "none";</script>';
   }
   ?>
</section>