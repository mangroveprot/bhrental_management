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

                $stmt = $this->connect->prepare('SELECT COUNT(*) AS total_beds, SUM(CASE WHEN occupied = 0 THEN 1 ELSE 0 END) AS available_beds FROM beds WHERE room_id = :roomId');
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

    public function addRoom($roomName)
    {
        try {
            $stmt = $this->connect->prepare('INSERT INTO room (room_name) VALUES (:roomName)');
            $stmt->bindParam(':roomName', $roomName);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            throw new Exception("Error adding room: " . $e->getMessage());
        }
        return false;
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