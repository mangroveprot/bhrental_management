<?php
session_start();

include_once dirname(__DIR__) . '/database/models/tenants_model.php';

class AddNewTenants
{
    public function addNewTenants()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['first_name'], $_POST['last_name'], $_POST['contact_number'], $_POST['gender'])) {
            $firstName = htmlspecialchars($_POST['first_name']);
            $lastName = htmlspecialchars($_POST['last_name']);
            $contactNumber = htmlspecialchars($_POST['contact_number']);
            $gender = htmlspecialchars($_POST['gender']);

            $tenantsModel = new TenantsModel();

            try {
                $success = $tenantsModel->addNewTenants($firstName, $lastName, $contactNumber, $gender);

                if ($success) {
                    $successMessage = "Tenant $firstName $lastName added successfully";
                    $encodedSuccessMessage = urlencode($successMessage);
                    $_SESSION['successMessage'] = $successMessage;
                    header("Location: ../../client/pages/Tenants.php?success=" . $encodedSuccessMessage);
                    exit();
                } else {
                    $errorMessage = "Failed to add new tenants";
                    $encodedErrorMessage = urlencode($errorMessage);
                    $_SESSION['error'] = $errorMessage;
                    header("Location: ../../client/pages/Tenants.php?err=" . $encodedErrorMessage);
                    exit();
                }
            } catch (Exception $e) {
                $errorMessage = "Failed to add new tenants. Error: " . $e->getMessage();
                $encodedErrorMessage = urlencode($errorMessage);
                $_SESSION['error'] = $errorMessage;
                header("Location: ../../client/pages/Tenants.php?err=" . $encodedErrorMessage);
                exit();
            }

        } else {
            header("Location: error.php?message=Invalid request");
            exit();
        }
    }
}

$handler = new AddNewTenants();
$handler->addNewTenants();
?>