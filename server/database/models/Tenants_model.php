<?php

require_once dirname(__DIR__, 2) . '/database/db_connections.php';

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
        if (empty($tenantID)) {
            throw new Exception("No Tenant ID included!");
        }
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
        try {
            $stmt = $this->connect->prepare('INSERT INTO customer (first_name, last_name, contact_number, gender) VALUES (:firstName, :lastName, :contactNumber, :gender)');
            $stmt->bindParam(':firstName', $firstName);
            $stmt->bindParam(':lastName', $lastName);
            $stmt->bindParam(':contactNumber', $contactNumber);
            $stmt->bindParam(':gender', $gender);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            throw new Exception("Error adding new tenant: " . $e->getMessage());
            return false;
        }
    }

}

?>