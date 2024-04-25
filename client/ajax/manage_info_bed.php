<?php
if (!isset($_SESSION)) {
    session_start();
}
include ('../../server/database/models/tenants_model.php');
$tenantsModel = new TenantsModel();
$tenantID = $_POST['tenantID'];
$tenants = $tenantsModel->getTenantsByID($tenantID);
?>

<div id="details_clone">
    <div class='d'>
        <?php foreach ($tenants as $tenant): ?>
            <p>Tenant: <b><?php echo $tenant['last_name'] . ', ' . $tenant['first_name']; ?></b></p>
            <p>Contact Number: <b><?php echo $tenant['contact_number']; ?></b></p>
            <p>Gender: <b><?php echo $tenant['gender']; ?></b></p>
        <?php endforeach; ?>
    </div>
</div>