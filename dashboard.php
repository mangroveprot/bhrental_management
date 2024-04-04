<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="stylesheet" href="assets/gemma.css?v=<?php echo time(); ?>" />
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Boarding House Rental Management System</title>
</head>

<body>
  <!-- Header -->
  <div class="header">
    <h1>Boarding House Rental Management System</h1>
  </div>
  <div class="container">
    <?php include ('includes/sidebar.php') ?>
    <div class="dashboard">
      <h1>Dashboard</h1>
      <!-- Gray Rectangle -->
      <div class="gray-rectangle">
        <!-- Boxes -->
        <div class="box orange-box">
          <a href="Total Houses">
            <img src="home.svg" width="40" height="40" />
          </a>
          <h2>Total Houses</h2>
          <p>2</p>
          <br />
          <br />
          <a href="#">View List</a>
        </div>
        <div class="box yellow-box">
          <a href="TotalTenants">
            <img src="assets/user.svg" width="40" height="40" />
          </a>
          <h2>Total Tenants</h2>
          <p>2</p>
          <br />
          <br />
          <a href="#">View List</a>
        </div>
        <div class="box green-box">
          <a href="Payments">
            <img src="assets/pay.webp" width="40" height="40" />
          </a>
          <h2>Payment This Month</h2>
          <ul>
            <li>Payment 1</li>
            <li>Payment 2</li>
          </ul>
          <br />
          <a href="#">View Payments</a>
        </div>
      </div>
    </div>
  </div>
</body>

</html>