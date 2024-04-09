<?php
include ('../../server/database/models/tenants_model.php');
$tenantsModel = new TenantsModel();
$error = "";
try {
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
    <link rel="stylesheet" href="../assets/style.css">
</head>

<body>
    <?php include_once ('../includes/navbar.php'); ?>
    <h1>Tenants Information</h1>
    <?php
    //Responsible for any notifications to display if it has a value to set.
    include_once ('../includes/messageHandler.php');
    ?>
    <table>
        <thead>
            <tr>
                <th>Customer ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Contact Number</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Paid Status</th>
                <th>Amount Paid</th>
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
                        <?php echo $tenant['date_start']; ?>
                    </td>
                    <td>
                        <?php echo $tenant['date_end']; ?>
                    </td>
                    <td>
                        <?php echo $tenant['paid_status'] ? 'Paid' : 'Not Paid'; ?>
                    </td>
                    <td>
                        <?php echo $tenant['amount_paid']; ?>
                    </td>
                    <td>
                        <?php echo $tenant['gender']; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>