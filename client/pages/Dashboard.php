<?php
include ('../../server/database/models/rooms_model.php');
include ('../../server/database/models/beds_model.php');
include ('../../server/database/models/tenants_model.php');
$roomModel = new RoomModel();
$bedModel = new BedModel();
$tenantsModel = new TenantsModel();

$beds = $bedModel->getAllBeds();
$rooms = $roomModel->getRooms();
$tenants = $tenantsModel->getAllTenants();

$numRooms = count($rooms);
$numBeds = count($beds);
$numTenants = count($tenants);

?>

<?php include ("../includes/header.php"); ?>

<body>
    <!-- Header -->
    <div class="header">
        <h1>Boarding House Rental Management System</h1>
    </div>
    <div class="container">
        <div class="sidebar">
            <?php include ('../includes/sidebar.php') ?>
        </div>
        <div class="content_wrapper">
            <h1>Dashboard</h1>
            <!-- Gray Rectangle -->
            <div class="gray-rectangle">
                <!-- Boxes -->
                <div class="box orange-box">
                    <a href="Rooms.php">
                        <img src="home.svg" width="40" height="40" />
                    </a>
                    <h2>Total Rooms</h2>
                    <p><b><?php echo $numRooms ?></b></p>
                    <a href="#">View List</a>
                </div>
                <div class="box yellow-box">
                    <a href="Tenants.php">
                        <img src="assets/user.svg" width="40" height="40" />
                    </a>
                    <h2>Total Tenants</h2>
                    <p><b><?php echo $numTenants; ?></b></p>
                    <a href="#">View List</a>
                </div>
                <div class="box green-box">
                    <a href="Payments.php">
                        <img src="assets/pay.webp" width="40" height="40" />
                    </a>
                    <h2>Payments</h2>
                    <ul>
                        <p><b>Payments This Month</b></p>
                        <a href="#">View Payments</a>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>

</html>