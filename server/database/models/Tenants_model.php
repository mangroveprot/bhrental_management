<?php

require_once dirname(__DIR__, 2) . '/database/db_connections.php';
include_once ('beds_model.php');
$bedsModal = new BedModel();
class TenantsModel
{
    private $connect;

    public function __construct()
    {
        $this->connect = connections();
    }

    public function getAllTenants()
    {
        $tenants = [];
        try {
            $stmt = $this->connect->query('SELECT * FROM customer');
            $tenants = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $tenants;
        } catch (PDOException $e) {
            throw new Exception("Error fetching tenants: " . $e->getMessage());
        }
    }
    public function getTenantsByID($tenantID)
    {
        try {
            $stmt = $this->connect->prepare('SELECT * FROM customer WHERE customer_id = :customerID');
            $stmt->bindParam(':customerID', $tenantID);
            $stmt->execute();
            $tenants = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $tenants;
        } catch (PDOException $e) {
            throw new Exception("Error fetching tenants: " . $e->getMessage());
        }
    }

    public function addNewTenants($firstName, $lastName, $contactNumber, $gender)
    {
        $stmt = $this->connect->prepare('INSERT INTO customer (first_name, last_name, contact_number, gender) VALUES (:firstName, :lastName, :contactNumber, :gender)');
        $stmt->bindParam(':firstName', $firstName);
        $stmt->bindParam(':lastName', $lastName);
        $stmt->bindParam(':contactNumber', $contactNumber);
        $stmt->bindParam(':gender', $gender);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return 0;
        } else {
            return 1;
        }
    }

    public function updateTenant($tenantID, $firstName, $lastName, $contactNumber, $gender)
    {

        $stmt = $this->connect->prepare('UPDATE customer SET first_name = :firstName, last_name = :lastName, contact_number = :contactNumber, gender = :gender WHERE customer_id = :customerID');
        $stmt->bindParam(':firstName', $firstName);
        $stmt->bindParam(':lastName', $lastName);
        $stmt->bindParam(':contactNumber', $contactNumber);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':customerID', $tenantID);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return 0;
        } else {
            return 1;
        }
    }

    public function deleteTenant($tenantID)
    {
        try {
            $bedsModel = new BedModel();

            $b = $bedsModel->getAllBeds();
            $bedsWithCustomerIds = array_filter($b, function ($bed) {
                return !empty ($bed['customer_id']);
            });

            $bedId = null;
            foreach ($bedsWithCustomerIds as $bed) {
                if ($bed['customer_id'] == $tenantID) {
                    $bedId = $bed['beds_id'];
                    break;
                }
            }

            if (isset($bedId)) {
                $bedsModel->removeTenants($bedId);
            }

            $stmt = $this->connect->prepare('DELETE FROM customer WHERE customer_id = :customerID');
            $stmt->bindParam(':customerID', $tenantID);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return 0;
            } else {
                return 1;
            }
        } catch (PDOException $e) {
            throw new Exception("Error deleting tenant: " . $e->getMessage());
            error_log("" . $e->getMessage());
        }
    }

}

?>