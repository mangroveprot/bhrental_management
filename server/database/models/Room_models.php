<?php
/*
//pATH CHECKER
$file_path = dirname(__DIR__, 2) . '/database/db_connections.php';

if (file_exists($file_path)) {
    echo "The file exists.";
    return;
} else {
    echo "The file does not exist.";
    return;
}
*/
require_once dirname(__DIR__, 2) . '/database/db_connections.php';

class RoomModel {
    private $connect;

    public function __construct() {
        $this->connect = connections();
    }

    public function getRooms() {
        $rooms = [];

        try {
            $stmt = $this->connect->query('SELECT * FROM Room');
            $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($rooms as &$room) {
                $roomId = $room['room_id'];

                $stmt = $this->connect->prepare('SELECT COUNT(*) AS total_beds, SUM(CASE WHEN occupied = 0 THEN 1 ELSE 0 END) AS available_beds FROM beds WHERE room_id = :roomId');
                $stmt->bindParam(':roomId', $roomId);
                $stmt->execute();

                $availability = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($availability && isset($availability['available_beds']) && isset($availability['total_beds'])) {
                    $room['availability'] = $availability['available_beds'] . '/' . $availability['total_beds'];
                } else {
                    $room['availability'] = 'Data Unavailable';
                    echo "<p>Error: Unable to fetch availability data for room ID {$room['room_id']}</p>";
                    $errorInfo = $stmt->errorInfo();
                    echo "<p>Error Info: " . implode(", ", $errorInfo) . "</p>";
                }
            }
        } catch (PDOException $e) {
            $errMsg = $e->getMessage();
            echo "<p>PDO Exception: $errMsg</p>";
        }

        return $rooms;
    }
}
?>
