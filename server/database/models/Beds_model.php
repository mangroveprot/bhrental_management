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

    // You can add more methods as needed for updating beds, fetching specific beds, etc.
}
?>