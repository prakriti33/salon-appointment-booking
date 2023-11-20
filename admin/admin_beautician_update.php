<?php
include 'admin_header.php';



if (isset($_POST['update_product'])) {
   $update_p_id = $_POST['update_p_id'];
   $update_name = mysqli_real_escape_string($conn, $_POST['update_name']);
   $update_price = mysqli_real_escape_string($conn, $_POST['update_price']);
   $update_details = mysqli_real_escape_string($conn, $_POST['update_details']);
   $update_mobile = mysqli_real_escape_string($conn, $_POST['update_mobile']);
   $update_gender = mysqli_real_escape_string($conn, $_POST['update_gender']);

   mysqli_query($conn, "UPDATE `beautician` SET name = '$update_name', price = '$update_price', details = '$update_details', mobile = '$update_mobile', gender = '$update_gender' WHERE id = '$update_p_id'") or die('query failed');

   $update_image = $_FILES['update_image']['name'];
   $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
   $update_image_size = $_FILES['update_image']['size'];
   $update_folder = '../uploaded_img/' . $update_image;
   $update_old_image = $_POST['update_old_image'];

   if (!empty($update_image)) {
      if ($update_image_size > 2000000) {
         $message[] = 'Image file size is too large';
      } else {
         mysqli_query($conn, "UPDATE `beautician` SET image = '$update_image' WHERE id = '$update_p_id'") or die('query failed');
         move_uploaded_file($update_image_tmp_name, $update_folder);
         unlink('../uploaded_img/' . $update_old_image);
      }
   }

   header('location: admin_beautician.php');
   exit();
}

?>

<!-- !-- ........................update             -->
</end>
<section class="form-container" style="background-color: darkcyan;">
   <?php
   if (isset($_GET['update'])) {
      $update_id = $_GET['update'];
      $update_query = mysqli_query($conn, "SELECT * FROM `beautician` WHERE id = '$update_id'") or die('query failed');
      if (mysqli_num_rows($update_query) > 0) {
         $fetch_update = mysqli_fetch_assoc($update_query);
   ?>
         <form action="" method="post" enctype="multipart/form-data">
            <h3>Update Beautician Category</h3>
            <img src="../uploaded_img/<?php echo $fetch_update['image']; ?>" height="100" alt="">
            <div class="flex">
               <div class="box">
                  <input type="text" name="update_name" value="<?php echo $fetch_update['name']; ?>" class="box" required placeholder="Enter beautician name">
               </div>
            </div>
            <div class="flex">
               <div class="box">
                  <input type="number" name="update_price" value="<?php echo $fetch_update['price']; ?>" class="box" required placeholder="Enter price">
               </div>
            </div>
            <div class="flex">
               <textarea name="update_details" placeholder="Enter category details" class="box"><?php echo $fetch_update['details']; ?></textarea>
            </div>
            <div class="flex">
               <div class="inputBox">
                  <input type="text" name="update_mobile" value="<?php echo $fetch_update['mobile']; ?>" class="box" required placeholder="Enter mobile">
               </div>
               <div class="inputBox">
                  <input type="text" name="update_gender" value="<?php echo $fetch_update['gender']; ?>" class="box" required placeholder="Enter gender">
               </div>
            </div>
            <div class="flex">
               <div class="box">
                  <input type="file" class="box" name="update_image" accept="image/jpg, image/jpeg, image/png">
               </div>
            </div>
            <input type="hidden" name="update_p_id" value="<?php echo $fetch_update['id']; ?>">
            <input type="hidden" name="update_old_image" value="<?php echo $fetch_update['image']; ?> ">
            <input type="submit" value="Update" name="update_product" class="btn" style="background-color: lightpink;">
            <a href="admin_beautician.php" class="delete-btn" onclick="return confirm('Cancel?');">Cancel</a>
         </form>
   <?php
      }
   } else {
      echo '<script>document.querySelector(".form-container1").style.display = "none";</script>';
   }
   ?>
</section>