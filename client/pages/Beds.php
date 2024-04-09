<?php
session_start();
include ('../../server/database/models/tenants_model.php');
include '../../server/database/models/beds_model.php';
$bedModel = new BedModel();
$tenantsModel = new TenantsModel();
$error = "";
$succesMessage = "";
$beds = [];
$tenantsID = isset($_GET['tenantsID']) ? $_GET['tenantsID'] : null;
$bedID = isset($_GET['bedID']) ? $_GET['bedID'] : null;

try {
    $beds = $bedModel->getAllBeds();
} catch (Exception $e) {
    $error = 'Error fetching bed data: ' . $e->getMessage();
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
                    <td>
                        <?php echo $bed['beds_id']; ?>
                    </td>
                    <td>
                        <?php echo $bed['room_id']; ?>
                    </td>
                    <td>
                        <?php echo isset($bed['customer_id']) ? $bed['customer_id'] : 'Not Set'; ?>
                    </td>
                    <td>
                        <?php echo $bed['occupied'] ? 'Yes' : 'No'; ?>
                    </td>
                    <td>
                        <?php if ($bed['occupied']): ?>
                            <?php
                            $customerUrl = $_SERVER["PHP_SELF"] . "?tenantsID=" . $bed['customer_id'];
                            $removeUrl = $_SERVER["PHP_SELF"] . "?bedID=" . $bed['beds_id'];
                            ?>
                            <a href="<?= $customerUrl ?>" onclick="openDetailsModal()">Details</a> |
                            <a href="<?= $removeUrl ?>" onclick="openRemoveModal()">Delete</a>
                        <?php else: ?>
                            <p>Add</p>
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
            <?php
            if (isset($tenantsID)) {
                try {
                    $tenants = $tenantsModel->getTenantsByID($tenantsID);
                    print_r($tenants);
                } catch (Exception $e) {
                    echo 'Error fetching tenant details: ' . $e->getMessage();
                }
            }
            ?>
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
                    href="../../server/app/removeTenantsHandler.php?bedID=<?php echo $bedID; ?>">Delete</a></button>
            <button onclick="closeRemoveModal()">Cancel</button>
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
    </script>

</body>

</html>