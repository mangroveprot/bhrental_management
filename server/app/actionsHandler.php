<?php
ob_start();
$action = isset($_GET['action']) ? $_GET['action'] : null;
include_once ('../../server/database/models/tenants_model.php');
include_once ('../../server/database/models/beds_model.php');

$tenantsModel = new TenantsModel();
$bedsModel = new BedModel();

if ($action == 'update-tenant') {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $tenantID = $_POST['tenantID'];
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $gender = $_POST['gender'];
        $contactNumber = $_POST['contactNumber'];

        $response = $tenantsModel->updateTenant($tenantID, $firstName, $lastName, $contactNumber, $gender);
        echo $response;
        exit;
    }
}

if ($action == 'add-tenant') {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $gender = $_POST['gender'];
        $contactNumber = $_POST['contactNumber'];

        $response = $tenantsModel->addNewTenants($firstName, $lastName, $contactNumber, $gender);
        echo $response;
        exit;
    }
}

if ($action == 'delete-tenant') {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $tenantID = $_POST['tenantID'];
        $response = $tenantsModel->deleteTenant($tenantID);
        echo $response;
        exit;
    }
}
if ($action == 'assign-bed') {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $tenantID = $_POST['tenantID'];
        $bedID = $_POST['bedID'];
        $response = $bedsModel->assignTenantToBed($bedID, $tenantID);
        echo $response;
        exit;
    }
}
if ($action == 'remove-tenant-bed') {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $bedID = $_POST['bedID'];
        $response = $bedsModel->removeTenants($bedID);
        echo $response;
        exit;
    }
}

ob_end_flush();
?>