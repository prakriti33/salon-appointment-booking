<?php

@include '../dbconnection.php';

@include 'header.php';



?>



<section class="home" id="home">
   <div class="homeContent">
      <h2>Where Beauty Meets Serenity</h2>
      <p>Elegance find it's home.</p>
      
   </div>
   
</section>



<div class="content">

   <a href="about.php" class="btn" style="display: flex; justify-content: center; background-color:#E4D0D0; color:black; "> Discover More</a>

</div>
<section class="products">

   <h1 class="title">Beautician</h1>

   <div class="box-container">

      <?php
      // database connection from here .

      $select_beauticians = mysqli_query($conn, "SELECT * FROM `beautician`") or die('Failed to fetch beauticians');

      if (mysqli_num_rows($select_beauticians) > 0) {
         while ($fetch_beautician = mysqli_fetch_assoc($select_beauticians)) {
            // Output the beautician's information
            echo '<form action="" method="POST" class="box">';
            echo '<img src="../uploaded_img/' . $fetch_beautician['image'] . '" style="width: 100%;" alt="">';
            echo '<div class="name">';
            echo '<br>';

            echo htmlspecialchars($fetch_beautician['name']);
            echo '<br>';
            echo '<br>';

            // Display the beautician's description
            echo '<label>Description: </label>';

            echo htmlspecialchars($fetch_beautician['details']);

            echo '<br>';
            echo '</div>';
            echo '</form>';
         }
      } else {
         echo "No beauticians found in the database.";
      }

      ?>


   </div>



</section>






<?php @include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>

</html>
<!-- side bar section ends -->