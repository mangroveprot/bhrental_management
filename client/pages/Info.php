<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenant Details</title>
    <link rel="stylesheet" href="path/to/your/modal-styles.css">
</head>

<body>
    <div id="detailsModal" class="modal">
        <div class="details-modal-content">
            <span class="close" onclick="closeDetailsModal()">&times;</span>
            <?php
            // Include the necessary PHP file
            include('../../server/database/models/tenants_model.php');
            $tenantsModel = new TenantsModel();
            // Check if the customer_id is set in the URL
            if (isset($_GET['id'])) {
                $customerID = $_GET['id'];
                try {
                    // Fetch tenant details by customer ID
                    $tenants = $tenantsModel->getTenantsByID($customerID);
                    // Render tenant details
                    print_r($tenants);
                } catch (Exception $e) {
                    // Handle exceptions
                    echo 'Error fetching tenant details: ' . $e->getMessage();
                }
            } else {
                // Display a message if customer_id is not set
                echo 'Customer ID not provided.';
            }
            ?>
        </div>
    </div>

    <!-- JavaScript to close the modal -->
    <script>
        function closeDetailsModal() {
            var modal = document.getElementById("detailsModal");
            modal.style.display = "none";
        }
    </script>
</body>

</html>