<?php
require_once dirname(__DIR__, 2) . '/database/db_connections.php';

class RoomModel
{
    private $connect;

    public function __construct()
    {
        $this->connect = connections();
    }

    public function getRooms()
    {
        $rooms = [];

        try {
            $stmt = $this->connect->query('SELECT * FROM room');
            $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($rooms as &$room) {
                $roomId = $room['room_id'];

                $stmt = $this->connect->prepare('SELECT * FROM beds WHERE room_id = :roomId');
                $stmt->bindParam(':roomId', $roomId);
                $stmt->execute();
                $beds = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $room['beds'] = $beds;

                $stmt = $this->connect->prepare('SELECT COUNT(*) AS total_beds,
                SUM(CASE WHEN customer_id IS NULL THEN 1 ELSE 0 END) AS available_beds,
                SUM(CASE WHEN customer_id IS NOT NULL THEN 1 ELSE 0 END) AS occupied_beds
                FROM beds WHERE room_id = :roomId
                ');
                $stmt->bindParam(':roomId', $roomId);
                $stmt->execute();

                $availability = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($availability && isset($availability['available_beds']) && isset($availability['total_beds'])) {
                    $room['availability'] = $availability['available_beds'] . '/' . $availability['total_beds'];
                } else {
                    $room['availability'] = 'Data Unavailable';
                    $errorInfo = $stmt->errorInfo();
                    throw new Exception("Error: Unable to fetch availability data for room ID {$room['room_id']}. Error Info: " . implode(", ", $errorInfo));
                }
            }
        } catch (PDOException $e) {
            throw new Exception("Error while fetching rooms: " . $e->getMessage());
        }

        return $rooms;
    }

    public function addRoomWithBeds($roomName, $numBeds, $roomPrice)
    {
        try {
            $this->connect->beginTransaction();

            $stmt = $this->connect->prepare('INSERT INTO room (room_name, room_price) VALUES (:roomName, :roomPrice)');
            $stmt->bindParam(':roomName', $roomName);
            $stmt->bindParam(':roomPrice', $roomPrice);
            $stmt->execute();
            $roomId = $this->connect->lastInsertId();

            for ($i = 0; $i < $numBeds; $i++) {
                $stmt = $this->connect->prepare('INSERT INTO beds (room_id) VALUES (:roomId)');
                $stmt->bindParam(':roomId', $roomId);
                $stmt->execute();
            }

            $this->connect->commit();
            return 0;
        } catch (PDOException $e) {
            $this->connect->rollBack();
            throw new Exception("Error adding room with beds: " . $e->getMessage());
        }
        return 1;
    }

    public function removeRoom($roomId)
    {
        try {
            $stmt = $this->connect->prepare('DELETE FROM room WHERE room_id = :roomId');
            $stmt->bindParam(':roomId', $roomId);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            throw new Exception("Error removing room: " . $e->getMessage());
        }
        return false;
    }
}
?>