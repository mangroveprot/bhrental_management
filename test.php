<?php
if (!isset($_SESSION)) {
    session_start();
}
include ('../../server/database/models/tenants_model.php');
include ('../../server/database/models/beds_model.php');
include ('../../server/app/actionsHandler.php');

$bedModel = new BedModel();
$tenantsModel = new TenantsModel();
$handlers = new Handlers();
$error = "";
$succesMessage = "";
$beds = [];
$tenants = [];
$tenantsID = isset($_GET['tenantsID']) ? $_GET['tenantsID'] : null;
$bedID = isset($_GET['bedID']) ? $_GET['bedID'] : null;
$roomId = (isset($_GET['room_id']) ? $_GET['room_id'] : null);
if (!empty($roomId)) {

    try {
        $beds = $bedModel->getBedsByRoomId($roomId);
    } catch (Exception $e) {
        $error = 'Error fetching bed data: ' . $e->getMessage();
    }
}


//add tenants in bed
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addTenant'], $_POST['customer_id'], $_POST['names'])) {
    $customerId = $_POST['customer_id'];
    $bedID = $_POST['bed_id'];
    $names = $_POST['names'];
    $handlers->addTenantInBed($customerId, $bedID, $names);
    header("Location: {$_SERVER['PHP_SELF']}");
    exit();
}

// Check if form on Adding Tenant is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['first_name'], $_POST['last_name'], $_POST['contact_number'], $_POST['gender'])) {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $contactNumber = $_POST['contact_number'];
    $gender = $_POST['gender'];

    try {
        $handlers->addNewTenants($firstName, $lastName, $contactNumber, $gender);
        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
    } catch (Exception $e) {
        $error = 'Error adding new tenant: ' . $e->getMessage();
    }
}


//Fetch All Tenants
try {
    $tenants = $tenantsModel->getAllTenants();
} catch (Exception $e) {
    $error = 'Error fetching tenants data: ' . $e->getMessage();
}
//Filter all tenants that not in beds
$occupiedCustomerIds = array_column(array_filter($beds, function ($bed) {
    return $bed['occupied'] == 1;
}), 'customer_id');

$unoccupiedTenants = array_filter($tenants, function ($tenant) use ($occupiedCustomerIds) {
    return !in_array($tenant['customer_id'], $occupiedCustomerIds);
});

//Fetch Tenants By id
if (isset($tenantsID)) {
    try {
        $tenants = $tenantsModel->getTenantsByID($tenantsID);
    } catch (Exception $e) {
        echo 'Error fetching tenant details: ' . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bed Information</title>
    <link rel="stylesheet" href="../assets/style.css?e=<?php echo time() ?>">
</head>

<body>
    <?php include_once ('../includes/navbar.php'); ?>

    <h1>All Beds</h1>

    <?php
    include ('../includes/messageHandler.php');
    ?>

    <table>
        <thead>
            <tr>
                <th>Bed ID</th>
                <th>Room ID</th>
                <th>Customer ID</th>
                <th>Occupied</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($beds as $bed): ?>
                <tr>
                    <td><?php echo $bed['beds_id']; ?></td>
                    <td><?php echo $bed['room_id']; ?></td>
                    <td><?php echo isset($bed['customer_id']) ? $bed['customer_id'] : 'Not Set'; ?></td>
                    <td><?php echo $bed['occupied'] ? 'Yes' : 'No'; ?></td>
                    <td>
                        <?php if ($bed['occupied']): ?>
                            <?php
                            $customerUrl = $_SERVER["PHP_SELF"] . "?tenantsID=" . $bed['customer_id'];
                            $removeUrl = $_SERVER["PHP_SELF"] . "?bedID=" . $bed['beds_id'];
                            ?>
                            <a href="<?= $customerUrl ?>"><button onclick="openDetailsModal()">Details</button></a>
                            <a href="<?= $removeUrl ?>"><button onclick="openRemoveModal()">Remove</button></a>
                        <?php else: ?>
                            <?php
                            $addUrl = $_SERVER["PHP_SELF"] . "?bedID=" . $bed['beds_id'];
                            ?>
                            <!-- Add button opens add modal with bedId -->
                            <a href="<?= $addUrl ?>"><button onclick="openAddModal()">Add</button></a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>

        </tbody>
    </table>

    <!-- Details Modal  -->
    <div id="detailsModal" class="modal"
        style="<?php echo isset($tenantsID) ? 'display: block;' : 'display: none;'; ?>">
        <div class="details-modal-content">
            <span class="close" onclick="closeDetailsModal()">&times;</span>
            <?php echo "Thus" . $tenantsID; ?>
            <table>
                <thead>
                    <tr>
                        <th>Customer ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Contact Number</th>
                        <th>Gender</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($tenants as $tenant): ?>
                        <tr>
                            <td>
                                <?php echo $tenant['customer_id']; ?>
                            </td>
                            <td>
                                <?php echo $tenant['first_name']; ?>
                            </td>
                            <td>
                                <?php echo $tenant['last_name']; ?>
                            </td>
                            <td>
                                <?php echo $tenant['contact_number']; ?>
                            </td>
                            <td>
                                <?php echo $tenant['gender']; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Remove Modal  -->
    <div id="removeModal" class="modal" style="<?php echo isset($bedID) ? 'display: block;' : 'display: none;'; ?>">
        <div class="details-modal-content">
            <span class="close" onclick="closeRemoveModal()">&times;</span>
            <p>
                Are you sure you want to remove this tenant?
            </p>
            <button class="btn"><a
                    href="../../server/app/removeTenantsHandler.php?bedID=<?php echo $bedID; ?>">Remove</a></button>
            <button onclick="closeRemoveModal()">Cancel</button>
        </div>
    </div>

    <!-- Add Modal  -->
    <div id="addModal" class="modal" style="<?php echo isset($bedID) ? 'display: block;' : 'display: none;'; ?>">
        <div class="details-modal-content">
            <span class="close" onclick="closeAddModal()">&times;</span>
            <?php if (empty($tenants)): ?>
                <p>No customers</p>
            <?php else: ?>
                <div style="text-align: right;">
                    <button onclick="openAddTenantModal()">Add New Tenants</button>
                </div>
            <?php endif; ?>
            <table>
                <thead>
                    <tr>
                        <th>Customer ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($unoccupiedTenants as $tenant): ?>
                        <tr>
                            <td><?php echo $tenant['customer_id']; ?></td>
                            <td><?php echo $tenant['first_name']; ?></td>
                            <td><?php echo $tenant['last_name']; ?></td>
                            <td>
                                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                    <input type="hidden" name="bed_id" value="<?php echo $bedID; ?>">
                                    <input type="hidden" name="names"
                                        value="<?php echo $tenant['first_name'] . " " . $tenant['last_name'] ?>">
                                    <input type="hidden" name="customer_id" value="<?php echo $tenant['customer_id']; ?>">
                                    <button type="submit" name="addTenant" class="btn">Add</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Tenants Modal  -->
    <div id="addTenantModal" class="modal">
        <div class="addTenant-modal-content">
            <span class="close" onclick="closeAddTenantModal()">&times;</span>
            <h1>Add New Tenant</h1>
            <form id="editForm" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name" required><br><br>

                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name" required><br><br>

                <label for="contact_number">Contact Number:</label>
                <input type="text" id="contact_number" name="contact_number" required><br><br>

                <label for="gender">Gender:</label>
                <select id="gender" name="gender" required>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select><br><br>

                <input type="submit" value="Submit">
            </form>

        </div>
    </div>

    <script>
        function openDetailsModal() {
            var modal = document.getElementById("detailsModal");
            modal.style.display = "block";
        }

        function openRemoveModal() {
            var modal = document.getElementById("removeModal");
            modal.style.display = "block";
        }

        function openAddModal() {
            var modal = document.getElementById("addModal");
            modal.style.display = "block";
        }

        function closeAddModal() {
            var modal = document.getElementById("addModal");
            modal.style.display = "none";

            var url = window.location.href;
            var urlParts = url.split('?');
            if (urlParts.length > 1) {
                var params = new URLSearchParams(urlParts[1]);
                params.delete('bedID');
                window.location.href = urlParts[0] + params.toString();
            }
        }

        function closeDetailsModal() {
            var modal = document.getElementById("detailsModal");
            modal.style.display = "none";

            var url = window.location.href;
            var urlParts = url.split('?');
            if (urlParts.length > 1) {
                var params = new URLSearchParams(urlParts[1]);
                params.delete('tenantsID');
                window.location.href = urlParts[0] + params.toString();
            }
        }
        function closeRemoveModal() {
            var modal = document.getElementById("removeModal");
            modal.style.display = "none";

            var url = window.location.href;
            var urlParts = url.split('?');
            if (urlParts.length > 1) {
                var params = new URLSearchParams(urlParts[1]);
                params.delete('bedID');
                window.location.href = urlParts[0] + params.toString();
            }
        }

        function openAddTenantModal() {
            var modal = document.getElementById("addTenantModal");
            modal.style.display = "block";
        }

        function closeAddTenantModal() {
            document.getElementById("addTenantModal").style.display = "none";
        }
    </script>

</body>

</html>