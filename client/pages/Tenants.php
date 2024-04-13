<?php
if (!isset($_SESSION)) {
    session_start();
}
include ('../../server/database/models/tenants_model.php');
include ('../../server/app/actionsHandler.php');
$tenantsModel = new TenantsModel();
$handlers = new Handlers();

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

// Fetch all tenants
$error = "";
try {
    $tenantsModel = new TenantsModel();
    $tenants = $tenantsModel->getAllTenants();
} catch (Exception $e) {
    $error = 'Error fetching tenants data: ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenants Information</title>
    <link rel="stylesheet" href="../assets/style.css?v=<?php echo time(); ?>">
</head>

<body>
    <?php include_once ('../includes/navbar.php'); ?>
    <div class="container">
        <h1>Tenants Information</h1>
        <button onclick="openAddTenantModal()">Add New Tenants</button>
    </div>

    <?php
    // Responsible for any notifications to display if it has a value to set.
    include_once ('../includes/messageHandler.php');
    ?>
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