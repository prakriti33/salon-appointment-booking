<?php
include 'admin_header.php'; ?>

<body style="background-color: #830303">
  <style>
    /* MAin Section */
    .main {
      position: relative;
      padding: 20px;
      width: 100%;
    }

    .main-top {
      display: flex;
      width: 100%;
    }

    .main-top i {
      position: absolute;
      right: 0;
      margin: 10px 30px;
      color: rgb(110, 109, 109);
      cursor: pointer;
    }

    .main .users {
      display: flex;
      width: 100%;
    }

    .users .card {
      width: 25%;
      margin: 10px;
      background: #fff;
      text-align: center;
      border-radius: 10px;
      padding: 10px;
      box-shadow: 0 20px 35px rgba(0, 0, 0, 0.1);
    }

    .users .card img {
      width: 70px;
      height: 70px;
      border-radius: 50%;
    }

    .users .card h4 {
      text-transform: uppercase;
    }

    .users .card p {
      font-size: 12px;
      margin-bottom: 15px;
      text-transform: uppercase;
    }

    .users table {
      margin: auto;
    }

    .users .per span {
      padding: 5px;
      border-radius: 10px;
      background: rgb(223, 223, 223);
    }

    .users td {
      font-size: 14px;
      padding-right: 15px;
    }

    .users .card button {
      width: 100%;
      margin-top: 8px;
      padding: 7px;
      cursor: pointer;
      border-radius: 10px;
      background: transparent;
      border: 1px solid #4AD489;
    }

    .users .card button:hover {
      background: #4AD489;
      color: #fff;
      transition: 0.5s;
    }
  </style>

  <section class="main" style="font-size:large; background-color:#F5EBEB">
    <br>
    <div class="users">
      <div class="card">
        <img src="../images/pending.webp">
        <h4>appointment pendings price</h4>
        <!-- <p>Ui designer</p> -->
        <div class="per">
          <table>
            <tr>
              <?php
              $total_pendings = 0;
              $select_pending = mysqli_query($conn, "SELECT service_price FROM `appointment` WHERE payment_status = 'pending'") or die('query failed');
              if (mysqli_num_rows($select_pending) > 0) {
                while ($fetch_pendings = mysqli_fetch_assoc($select_pending)) {
                  $total_price = $fetch_pendings['service_price'];
                  $total_pendings += $total_price;
                };
              };
              ?>

              <td> <label>Rs:
                  <?php echo $total_pendings; ?></label>
              </td>
              <BR>
              <br>

            </tr>
          </table>
        </div>

      </div>
      <div class="card">
        <img src="../images/complete-icon.png">
        <h4>Appointment complete price</h4>

        <div class="per">
          <table>

            <tr>
              <td> <?php
                    $total_completed = 0;
                    $select_completed = mysqli_query($conn, "SELECT service_price FROM `appointment` WHERE payment_status = 'complete'") or die('query failed');
                    if (mysqli_num_rows($select_completed) > 0) {
                      while ($fetch_completed = mysqli_fetch_assoc($select_completed)) {
                        $total_price = $fetch_completed['service_price'];
                        $total_completed += $total_price;
                      };
                    };
                    ?>
              </td>
              <br>
              <td> <label>Rs:
                  <?php echo $total_completed; ?></label>
              </td>

            </tr>
          </table>
        </div>

      </div>
      <div class="card">
        <img src="../images/service.jpg">
        <h4>Total Service</h4>

        <div class="per">
          <table>
            <?php
            $select_orders = mysqli_query($conn, "SELECT * FROM `service`") or die('query failed');
            $number_of_orders = mysqli_num_rows($select_orders);
            ?>
            <tr>

            </tr>
            <tr>
              <br>
              <br>
              <td><?php echo $number_of_orders; ?></td>

            </tr>
          </table>
        </div>
      </div>
      <div class="card">
        <img src="../images/beautician.png">
        <h4>Total Beauticians</h4>
        <br>
        <div class="per">
          <table>
            <tr>
              <br>
            </tr>
            <tr>
              <td> <?php
                    $select_products = mysqli_query($conn, "SELECT * FROM `beautician`") or die('query failed');
                    $number_of_products = mysqli_num_rows($select_products);

                    echo $number_of_products; ?>
              </td>

            </tr>

          </table>
        </div>

      </div>
    </div>


    <div class="users">
      <div class="card">
        <img src="../images/schedule.jpg">
        <h4>Total Schedule</h4>
        <!-- <p>Ui designer</p> -->
        <div class="per">
          <table>
            <tr>
              <?php
              $select_users = mysqli_query($conn, "SELECT * FROM `schedule` ") or die('query failed');
              $number_of_users = mysqli_num_rows($select_users);
              ?>
            </tr>
            <tr>
              <td><?php echo  $number_of_users; ?></td>
              <BR>
              <br>

            </tr>
          </table>
        </div>

      </div>

      <div class="card">
        <img src="../images/user.png">
        <h4>Total User</h4>

        <div class="per">
          <table>
            <?php
            $select_users = mysqli_query($conn, "SELECT * FROM `users`") or die('query failed');
            $number_of_users = mysqli_num_rows($select_users);
            ?>
            <tr>

            </tr>
            <tr>
              <br>
              <br>
              <td><?php echo $number_of_users; ?></td>

            </tr>
          </table>
        </div>
      </div>
      <div class="card">
        <img src="../images/appointment.jpg">
        <h4>Total Appointment</h4>

        <div class="per">
          <table>
            <?php
            $select_users = mysqli_query($conn, "SELECT * FROM `appointment`") or die('query failed');
            $number_of_users = mysqli_num_rows($select_users);
            ?>
            <tr>

            </tr>
            <tr>
              <br>
              <br>
              <td><?php echo $number_of_users; ?></td>

            </tr>
          </table>
        </div>
      </div>

    </div>
</body>