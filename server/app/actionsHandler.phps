<?php
if (!isset($_SESSION)) {
    session_start();
}
include_once ('../../server/database/models/tenants_model.php');
include_once ('../../server/database/models/beds_model.php');

class Handlers
{
    private $tenantsModel;
    private $bedsModel;

    public function __construct()
    {
        $this->tenantsModel = new TenantsModel();
        $this->bedsModel = new BedModel();
    }

    public function addNewTenants($firstName, $lastName, $contactNumber, $gender)
    {
        $firstName = htmlspecialchars($firstName);
        $lastName = htmlspecialchars($lastName);
        $contactNumber = htmlspecialchars($contactNumber);
        $gender = htmlspecialchars($gender);

        try {
            $success = $this->tenantsModel->addNewTenants($firstName, $lastName, $contactNumber, $gender);

            if ($success) {
                $successMessage = "Tenant $firstName $lastName added successfully";
                $_SESSION['successMessage'] = $successMessage;
            } else {
                $errorMessage = "Failed to add new tenants";
                $_SESSION['error'] = $errorMessage;
            }
        } catch (Exception $e) {
            $errorMessage = "Failed to add new tenants. Error: " . $e->getMessage();
            $_SESSION['error'] = $errorMessage;
        }
    }

    public function addTenantInBed($tenantID, $bedID, $names)
    {
        $bedId = htmlspecialchars($bedID);
        $customerId = htmlspecialchars($tenantID);
        $name = htmlspecialchars($names);
        try {
            $success = $this->bedsModel->assignCustomerToBed($bedId, $customerId);

            if ($success) {
                $successMessage = $name . "has been in beed " . $bedId . "successfully!";
                $_SESSION['successMessage'] = $successMessage;
            } else {
                $errorMessage = "Failed to add tenants in bed " . $bedId . ". Please Try Again Later!";
                $_SESSION['error'] = $errorMessage;
            }
        } catch (Exception $e) {
            $errorMessage = "Failed to add tenants in bed. Error: " . $e->getMessage();
            $_SESSION['error'] = $errorMessage;
        }
    }
}
?>