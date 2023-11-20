
<?php
include 'admin_header.php';

if (isset($_POST['add_schedule'])) {
   // Retrieve other form fields
   $beautician_id = mysqli_real_escape_string($conn, $_POST['beautician_id']);
   $start_time = mysqli_real_escape_string($conn, $_POST['start_time']);
   $end_time = mysqli_real_escape_string($conn, $_POST['end_time']);
   $selected_service_ids = $_POST['service_id'];

   $date = mysqli_real_escape_string($conn, $_POST['date']);
   $max_appointments = mysqli_real_escape_string($conn, $_POST['max_appointments']);

   // Perform validation or checks on other fields if required

   // Insert the schedule record into the schedule table
   $add_schedule_query = mysqli_query($conn, "INSERT INTO `schedule` (beautician_id, start_time, end_time, date, max_appointments) VALUES ('$beautician_id', '$start_time', '$end_time', '$date', '$max_appointments')") or die(mysqli_error($conn));

   if ($add_schedule_query) {
      $schedule_id = mysqli_insert_id($conn); // Get the last inserted schedule_id

      // Insert the associated service_id values into the schedule_service table
      foreach ($selected_service_ids as $service_id) {
         mysqli_query($conn, "INSERT INTO `schedule_service` (schedule_id, service_id) VALUES ('$schedule_id', '$service_id')") or die(mysqli_error($conn));
      }

      $message[] = 'Schedule added successfully!';
   } else {
      $message_1[] = 'Schedule could not be added!';
   }
}



if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];

   // Check if there are appointments associated with the schedule
   $check_appointments_query = mysqli_query($conn, "SELECT COUNT(*) AS count FROM `appointment` WHERE schedule_id = '$delete_id'") or die(mysqli_error($conn));
   $fetch_appointments_count = mysqli_fetch_assoc($check_appointments_query);
   $appointments_count = $fetch_appointments_count['count'];

   if ($appointments_count > 0) {
      // Show error message that you can't delete the schedule
      $message_1 = 'You cannot delete the schedule as there are appointments associated with it.';
   } else {
      // Delete the schedule
      mysqli_query($conn, "DELETE FROM `schedule` WHERE id = '$delete_id'") or die(mysqli_error($conn));
      $message_1 = 'Schedule deleted.';
      header('location: admin_schedule.php');
      // Stop further execution
   }
}

// Fetch the message for display
if (isset($message_1)) {
   echo $message_1;
} else {
   // If $message_1 is not set, you can display a default message or handle it as needed.
   echo '';
}


?>

<section class="form-container" style="min-height: 10vh;background-color:#F5EBEB">
   <form action="" method="post">
      <h3>Add Schedule</h3>

      <div class="flex">
         <div class="inputBox">
            <select name="beautician_id" class="box">
               <option value="" selected disabled>select beautician</option>
               <?php
               $select_category = mysqli_query($conn, "SELECT * FROM `beautician`") or die('query failed');
               if (mysqli_num_rows($select_category) > 0) {
                  while ($fetch_category = mysqli_fetch_assoc($select_category)) {
                     // Extracting data from a database 
               ?>
                     <option value="<?php echo $fetch_category['id']; ?>"><?php echo $fetch_category['name']; ?></option>
               <?php
                  }
               } else {
                  echo '<p class="empty">no product!</p>';
               }
               ?>
            </select>
         </div>
      </div>

      <div class="flex">
         <div class="box" style="display: flex; flex-wrap: wrap;">
            <?php
            $select_category = mysqli_query($conn, "SELECT * FROM `service`") or die('query failed');
            if (mysqli_num_rows($select_category) > 0) {
               while ($fetch_category = mysqli_fetch_assoc($select_category)) {
                  // Extracting data from the database
                  $service_id = $fetch_category['id'];
                  $service_name = $fetch_category['name'];
                  // ... other service data

                  // Check if the service ID is selected (based on the checkbox input name)
                  if (isset($_POST['service_id']) && in_array($service_id, $_POST['service_id'])) {
                     // Service is selected, display the details or perform desired actions
                     echo '<div style="display: flex; align-items: center; margin-right: 10px;">';
                     echo '<input type="checkbox" name="service_id[]" value="' . $service_id . '" checked style="margin-right: 5px;">';
                     echo '<label style="margin-right: 5px;">' . $service_name . '</label>';
                     // ... display other service details
                     echo '</div>';
                  } else {
                     // Service is not selected
                     echo '<div style="display: flex; align-items: center; margin-right: 10px;">';
                     echo '<input type="checkbox" name="service_id[]" value="' . $service_id . '" style="margin-right: 5px;">';
                     echo '<label style="margin-right: 5px;">' . $service_name . '</label>';
                     // ... display other service details
                     echo '</div>';
                  }
               }
            } else {
               echo '<p class="empty">No service available!</p>';
            }
            ?>
         </div>
      </div>


      <div class="flex">
         <div class="box">
            <input type="date" name="date" class="box" placeholder="Select date" required>
         </div>
      </div>

      <div class="flex">
         <div class="box">
            <input type="time" name="start_time" class="box" placeholder="Select start time" required>
         </div>
      </div>

      <div class="flex">
         <div class="box">
            <input type="time" name="end_time" class="box" placeholder="Select end time" required>
         </div>
      </div>

      <div class="flex">
         <div class="box">
            <input type="number" name="max_appointments" class="box" placeholder="Enter maximum appointments" required>
         </div>
      </div>

      <input type="submit" value="Add Schedule" name="add_schedule" class="btn" style="background-color: #830303;">
   </form>
</section>


<div class="table__body" style="font-size: 1.9rem;">
   <h1>Schedule List</h1>
   <table class="table">
      <thead>
         <tr>

            <th>id </th>
            <th>beautician</th>
            <th>service</th>
            <th>date</th>
            <th>start_time</th>
            <th> end_time</th>
            <th>appointments</th>
            <th>Action</th>
         </tr>
      </thead>
      <tbody>
         <?php
         $select_category = mysqli_query($conn, "SELECT * FROM `schedule` ORDER BY id DESC") or die('query failed');
         if (mysqli_num_rows($select_category) > 0) {
            while ($fetch_category = mysqli_fetch_assoc($select_category)) {
               // Extracting data from the database
               $service_id = $fetch_category['id'];
               $beautician_id = $fetch_category['beautician_id'];
         ?>
               <tr>

                  <td><?php echo $fetch_category['id']; ?></td>

                  <!-- beautician -->
                  <?php
                  $select_categor = mysqli_query($conn, "SELECT * FROM `beautician` WHERE id = '$beautician_id'") or die('query failed');
                  if (mysqli_num_rows($select_categor) > 0) {
                     while ($fetch_categor = mysqli_fetch_assoc($select_categor)) {
                        // Extracting data from a database 
                  ?>


                        <td><?php echo $fetch_categor['name']; ?></td>
                  <?php
                     }
                  }

                  ?>

                  <!-- service -->
                  <td><?php
                        $select_categor = mysqli_query($conn, "SELECT ss.service_id, s.name FROM `schedule_service` ss
                                                   JOIN `service` s ON ss.service_id = s.id
                                                   WHERE ss.schedule_id = '$service_id'")  or die(mysqli_error($conn));

                        if (mysqli_num_rows($select_categor) > 0) {
                           while ($fetch_categor = mysqli_fetch_assoc($select_categor)) {
                              // Extracting data from the database 
                              $name = $fetch_categor['name'];

                        ?>

                           <?php echo $name; ?>

                     <?php
                           }
                        }
                     ?>

                  </td>



                  <td><?php echo $fetch_category['date']; ?></td>

                  <td><?php echo $fetch_category['start_time']; ?></td>
                  <td><?php echo $fetch_category['end_time']; ?></td>
                  <td><?php echo $fetch_category['max_appointments']; ?></td>

                  <td>
                     <a href="admin_schedule_update.php?update=<?php echo $fetch_category['id']; ?>" class="option-btn" style="background-color:black;">Update</a>
                     <hr><br>
                     <a href="admin_schedule.php?delete=<?php echo $fetch_category['id']; ?>" class="delete-btn" onclick="return confirm('Delete this category?');">Delete</a>
                  </td>
               </tr>
         <?php
            }
         } else {
            echo '<p class="empty">No categories available!</p>';
         }
         ?>
      </tbody>
   </table>
</div>