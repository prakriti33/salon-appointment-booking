<?php
include 'admin_header.php';

if (isset($_POST['update_product'])) {
   $update_p_id = $_POST['update_p_id'];
   $update_name = mysqli_real_escape_string($conn, $_POST['update_name']);
   $update_description = mysqli_real_escape_string($conn, $_POST['update_description']);
   $update_price = mysqli_real_escape_string($conn, $_POST['update_price']);
   $update_time = mysqli_real_escape_string($conn, $_POST['update_sevice_service_time']);

   mysqli_query($conn, "UPDATE `service` SET name = '$update_name', description = '$update_description', price = '$update_price', service_time = '$update_sevice_service_time' WHERE id = '$update_p_id'") or die('query failed');

  
   header('location: admin_service.php');
   exit();
}
?>

<section class="form-container" style="background-color: #F5EBEB;">
   <?php
   if (isset($_GET['update'])) {
      $update_id = $_GET['update'];
      $update_query = mysqli_query($conn, "SELECT * FROM `service` WHERE id = '$update_id'") or die('query failed');
      if (mysqli_num_rows($update_query) > 0) {
         $fetch_update = mysqli_fetch_assoc($update_query);
   ?>
         <form action="" method="post" enctype="multipart/form-data">
            <h3>Update Service</h3>

            <div class="flex">
               <div class="box">
                  <input type="text" name="update_name" value="<?php echo $fetch_update['name']; ?>" class="box" required placeholder="Enter name">
               </div>
            </div>
            <div class="flex">
               <textarea name="update_description" placeholder="Enter description" class="box"><?php echo $fetch_update['description']; ?></textarea>
            </div>
            <div class="flex">
               <div class="inputBox">
                  <input type="number" name="update_price" value="<?php echo $fetch_update['price']; ?>" class="box" required placeholder="Enter price">
               </div>
            </div>

            <div class="flex">
               <div class="inputBox">
                  <input type="number" name="update_sevice_service_time" value="<?php echo $fetch_update['service_time']; ?>" class="box" required placeholder="Enter time">
               </div>
            </div>

            <div class="flex">
               <div class="box">
                  <input type="file" class="box" name="update_image" accept="image/jpg, image/jpeg, image/png">
               </div>
            </div>
            <input type="hidden" name="update_p_id" value="<?php echo $fetch_update['id']; ?>">
            <input type="submit" value="Update" name="update_product" class="btn" style="background-color: #830303;">
            <a href="admin_service.php" class="delete-btn" onclick="return confirm('Cancel?');">Cancel</a>
         </form>
   <?php
      }
   } else {
      echo '<script>document.querySelector(".form-container").style.display = "none";</script>';
   }
   ?>
</section>