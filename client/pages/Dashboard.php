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
                        <img src="../assets/room.png" width="40" height="40" />
                     
                    </a>
                    
                    <h2>Total Rooms</h2>
                    <br>
                    <br>
                    <br>
                    <a href="Rooms.php">View List</a>
                </div>
                <div class="box yellow-box">
                    <a href="Tenants.php">
                        <img src="../assets/tenants.png" width="40" height="40" />
                      
                    </a>
                    <h2>Total Tenants</h2>
                    <br>
                    <br>
                    <br>
                    <a href="Tenants.php">View List</a>
                </div>
                <div class="box green-box">
                    <a href="Payments.php">
                        <img src="../assets/pay.png" width="40" height="40" />
                    </a>
                    <h2>Payments This Month</h2>
                    <br>
                    <ul>
                        <a href="Payments.php">View Payments</a>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>

</html>