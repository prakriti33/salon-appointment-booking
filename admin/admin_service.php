<?php
include 'admin_header.php';


if (isset($_POST['add_service'])) {
   // SQL operator - store function
   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $description = mysqli_real_escape_string($conn, $_POST['description']);
   $price = mysqli_real_escape_string($conn, $_POST['price']);
   
   // New code for service time
   $service_time = mysqli_real_escape_string($conn, $_POST['service_time']);

   // Perform validation or checks on other fields if required

   $select_beautician_name = mysqli_query($conn, "SELECT name FROM `service` WHERE name = '$name'") or die('query failed');

   if (mysqli_num_rows($select_beautician_name) > 0) {
      $message_1[] = 'Beautician name already exists';
   } else {
      $add_beautician_query = mysqli_query($conn, "INSERT INTO `service` (name, description, price, service_time) VALUES ('$name', '$description', '$price', '$service_time')") or die('query failed');

      if ($add_beautician_query) {
          
          $message[] = 'Beautician added successfully!';
         
      } else {
         $message_1[] = 'Beautician could not be added!';
      }
   }
}

if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];

   // Check if there are appointments associated with the service
   $check_schedule_query = "SELECT COUNT(*) AS schedule_count FROM schedule_service WHERE service_id = '$delete_id'";
   $check_schedule_result = mysqli_query($conn, $check_schedule_query);

   if (!$check_schedule_result) {
      // Handle the query error here
      die(mysqli_error($conn));
   }

   $schedule_count = mysqli_fetch_assoc($check_schedule_result)['schedule_count'];

   if ($schedule_count > 0) {
      // Show error message that you can't delete the service as there are appointments associated with it
      $message_1[] = 'You cannot delete the service as there are appointments associated with it.';
   } else {
      

      $message[] = 'Service deleted.';

      // Delete the service from the database
      $delete_service_query = "DELETE FROM service WHERE id = '$delete_id'";
      $delete_service_result = mysqli_query($conn, $delete_service_query);

      if (!$delete_service_result) {
         // Handle the query error here
         die(mysqli_error($conn));
      }

      header('location:admin_service.php');
      exit; // Stop further execution
   }
}

?>
<?php if (isset($message_1)) {
   foreach ($message_1 as $message_1) {
      echo '
   <div class="message">
   <span>' . $message_1 . '</span>
  <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
   </div>
 ';
   }
}  ?>


<section class="form-container" style="min-height: 10vh; background-color: #F5EBEB">
   <form action="" method="post" enctype="multipart/form-data">
      <h3>Add Services</h3>

      <div class="flex">
         <div class="box">
            <input type="text" name="name" class="box" placeholder="Enter service name" required>
         </div>
      </div>

      <div class="flex">
         <div class="box">
            <input type="text" name="description" class="box" placeholder="Enter service details" required>
         </div>
      </div>

      <div class="flex">
         <div class="box">
            <input type="number" name="price" class="box" placeholder="Enter  price" required>
         </div>
      </div>

      <div class="flex">
         <div class="box">
            <input type="number" name="service_time" class="box" min="1" placeholder="Enter time" required>
         </div>
      </div>



      

      <input type="submit" value="Add service" name="add_service" class="btn" style="background-color:#830303;">
   </form>
</section>


<div class="table__body" style="font-size: 1.9rem;">
   <h1>Service List</h1>
   <table class="table">
      <thead>
         <tr>
            
            <th>Name</th>
            <th>description</th>
            <th>price</th>
            <th>service_time</th>
            <th>Action</th>
         </tr>
      </thead>
      <tbody>
         <?php
         $select_category = mysqli_query($conn, "SELECT * FROM `service` ORDER BY id DESC") or die('query failed');
         if (mysqli_num_rows($select_category) > 0) {
            while ($fetch_category = mysqli_fetch_assoc($select_category)) {
               // Extracting data from the database
         ?>
               <tr>


                  <td><?php echo $fetch_category['name']; ?></td>
                  <td><?php echo $fetch_category['description']; ?></td>

                  <td><?php echo $fetch_category['price']; ?></td>
                  <td><?php echo $fetch_category['service_time']; ?></td>
                  <td>
                     <a href="admin_service_update.php?update=<?php echo $fetch_category['id']; ?>" class="option-btn" style="background-color:black;">Update</a>
                     <hr><br>
                     <a href="admin_service.php?delete=<?php echo $fetch_category['id']; ?>" class="delete-btn" onclick="return confirm('Delete this category?');">Delete</a>
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