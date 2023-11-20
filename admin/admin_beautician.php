<?php
include 'admin_header.php';

if (isset($_POST['add_beautician'])) {
   // sqloperator _ store function
   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $price = mysqli_real_escape_string($conn, $_POST['price']);
   $details = mysqli_real_escape_string($conn, $_POST['details']);
   $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
   $gender = mysqli_real_escape_string($conn, $_POST['gender']);
   $image = $_FILES['image']['name'];
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../uploaded_img/' . $image;

   // Perform validation or checks on other fields if required

   $select_beautician_name = mysqli_query($conn, "SELECT name FROM `beautician` WHERE name = '$name'") or die('query failed');

   if (mysqli_num_rows($select_beautician_name) > 0) {
      $message_1[] = 'Beautician name already exists';
   } else {
      $add_beautician_query = mysqli_query($conn, "INSERT INTO `beautician` (name, price, details, mobile, gender, image) VALUES ('$name', '$price', '$details', '$mobile', '$gender', '$image')") or die('query failed');

      if ($add_beautician_query) {
         if ($image_size > 2000000) {
            $message_1[] = 'Image size is too large';
         } else {
            move_uploaded_file($image_tmp_name, $image_folder);
            $message[] = 'Beautician added successfully!';
         }
      } else {
         $message_1[] = 'Beautician could not be added!';
      }
   }
}

if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];

   // Check if there are products associated with the category
   $check_products_query = mysqli_query($conn, "SELECT COUNT(*) AS count FROM `appointment` WHERE beautician_id = '$delete_id'") or die(mysqli_error($conn));
   $fetch_products_count = mysqli_fetch_assoc($check_products_query);
   $products_count = $fetch_products_count['count'];

   if ($products_count > 0) {
      // Show error message that you can't delete the category
      $message[] = 'You cannot delete the beautician as there are appointment associated with it.';
   } else {
      // Delete the category
      $delete_image_query = mysqli_query($conn, "SELECT image FROM `beautician` WHERE id = '$delete_id'") or die(mysqli_error($conn));
      $fetch_delete_image = mysqli_fetch_assoc($delete_image_query);
      unlink('../uploaded_img/' . $fetch_delete_image['image']);
      $message[] = 'beautician deleted.';
      mysqli_query($conn, "DELETE FROM `beautician` WHERE id = '$delete_id'") or die(mysqli_error($conn));
      header('location:admin_beautician.php');
      exit; // Stop further execution
   }
}






?>

<!-- product CRUD section starts  -->



<?php
if (isset($message)) {
   foreach ($message as $message) {
      echo '
      <div class="message">
         <span>' . $message . '</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
} ?>


<section class="form-container" style="min-height: 10vh; background-color:#F5EBEB"> 
   <form action="" method="post" enctype="multipart/form-data">
      <h3>Add Beautician</h3>
       <br>
      <div class="flex">
         <div class="box">
            <input type="text" name="name" class="box" placeholder="Enter beautician name" required>
         </div>
      </div>

      <div class="flex">
         <div class="box">
            <input type="number" name="price" class="box" placeholder="Enter beautician price" required>
         </div>
      </div>

      <div class="flex">
         <div class="box">
            <input type="text" name="details" class="box" placeholder="Enter beautician details" required>
         </div>
      </div>

      <div class="flex">
         <div class="box">
            <input type="text" name="mobile" class="box" placeholder="Enter mobile number" required>
         </div>
      </div>

      <div class="flex">
         <div class="box">
            <select name="gender" class="box" required>
               <option value="">Select gender</option>
               <option value="Male">Male</option>
               <option value="Female">Female</option>
            </select>
         </div>
      </div>

      <div class="flex">
         <div class="inputBox">
            <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="box" required>
         </div>
      </div>

      <input type="submit" value="Add Beautician" name="add_beautician" class="btn" style="background-color:#830303;">
   </form>
</section>


<div class="table__body" style="font-size: 1.9rem;">
   <h1>Beautician List</h1>
   <table class="table">
      <thead>
         <tr>
            <th>Image</th>
            <th>Name</th>
            <th>Gender</th>
            <th>Details</th>
            <th>price</th>
            <th>Action</th>
         </tr>
      </thead>
      <tbody>
         <?php
         $select_category = mysqli_query($conn, "SELECT * FROM `beautician` ORDER BY id DESC") or die('query failed');
         if (mysqli_num_rows($select_category) > 0) {
            while ($fetch_category = mysqli_fetch_assoc($select_category)) {
               // Extracting data from the database
         ?>
               <tr>

                  <td> <img src="../uploaded_img/<?php echo $fetch_category['image']; ?>" style="width: 10rem;height: 50%;" alt=""></td>


                  <td><?php echo $fetch_category['name']; ?></td>
                  <td><?php echo $fetch_category['gender']; ?></td>
                  <td><?php echo $fetch_category['details']; ?></td>
                  <td><?php echo $fetch_category['price']; ?></td>
                  <td>
                     <a href="admin_beautician_update.php?update=<?php echo $fetch_category['id']; ?>" class="option-btn" style="background-color:black;">Update</a>
                     <hr><br>
                     <a href="admin_beautician.php?delete=<?php echo $fetch_category['id']; ?>" class="delete-btn" onclick="return confirm('Delete this category?');">Delete</a>
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










</div>











<!-- custom admin js file link  -->
<script src="../js/admin_script.js"></script>

</body>

</html>