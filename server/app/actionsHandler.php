<?php
ob_start();
$action = isset($_GET['action']) ? $_GET['action'] : null;
include_once ('../../server/database/models/tenants_model.php');
include_once ('../../server/database/models/beds_model.php');
include_once ('../../server/database/models/rooms_model.php');
include_once ('../../server/database/models/payments_model.php');
$tenantsModel = new TenantsModel();
$bedsModel = new BedModel();
$roomsModel = new RoomModel();
$paymentsModel = new PaymentsModel();

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
if ($action == 'add-room') {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $roomName = $_POST['roomName'];
        $numBeds = $_POST['beds_capacity'];
        $roomPrice = $_POST['room_price'];
        $response = $roomsModel->addRoomWithBeds($roomName, $numBeds, $roomPrice);
        echo $response;
        exit;
    }
}
if ($action == 'new-transaction') {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $tenantID = $_POST['tenantID'];
        $amounts = $_POST['amounts'];
        $date = $_POST['date'];
        $response = $paymentsModel->addNewPayments($tenantID, $amounts, $date);
        echo $response;
        exit;
    }
}
// tenantID: tenantID,
//                 amounts: amountsInput.value,
//                 date: dateInput,
//                 transactionID: transactionID

if ($action == 'edit-transaction') {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $tenantID = $_POST['tenantID'];
        $transactionID = $_POST['transactionID'];
        $amounts = $_POST['amounts'];
        $date = $_POST['date'];
        $response = $paymentsModel->editTransaction($transactionID, $tenantID, $amounts, $date);
        echo $response;
        exit;
    }
}
if ($action == 'delete-transaction') {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $transactionID = $_POST['tID'];
        $response = $paymentsModel->deleteTransaction($transactionID);
        echo $response;
        exit;
    }
}


ob_end_flush();
?>