<?php
require_once dirname(__DIR__, 2) . '/database/db_connections.php';

class BedModel
{
    private $connect;

    public function __construct()
    {
        $this->connect = connections();
    }

    public function getBedsByRoomId($roomId)
    {
        $beds = [];

        try {
            $stmt = $this->connect->prepare('SELECT * FROM beds WHERE room_id = :roomId');
            $stmt->bindParam(':roomId', $roomId);
            $stmt->execute();

            $beds = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error fetching bed data: " . $e->getMessage());
        }

        return $beds;
    }

    public function getAllBeds()
    {
        $beds = [];

        try {
            $stmt = $this->connect->query('SELECT * FROM beds');
            $stmt->execute();
            $beds = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error fetching beds: " . $e->getMessage());
        }
        return $beds;
    }

    public function removeTenants($bedID)
    {
        try {
            $stmt = $this->connect->prepare('UPDATE beds SET customer_id = NULL WHERE beds_id = :beds_id');
            $stmt->bindParam(':beds_id', $bedID);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return 0;
            } else {
                return 1;
            }
        } catch (PDOException $e) {
            throw new Exception("Error removing tenants: " . $e->getMessage());
        }
    }

    public function assignTenantToBed($bedID, $tenantID)
    {
        try {
            $stmt = $this->connect->prepare('UPDATE beds SET customer_id = :customerId WHERE beds_id = :bedId');
            $stmt->bindParam(':customerId', $tenantID);
            $stmt->bindParam(':bedId', $bedID);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return 0;
            } else {
                return 1;
            }
        } catch (PDOException $e) {
            throw new Exception("Error assigning customer to bed: " . $e->getMessage());
        }
    }
}
?>