<?php
require './database/config.php';

$rooms = [];

try {
    // Fetch rooms from the database
    $stmt = $connect->query('SELECT * FROM Room');
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch and store availability of beds for each room
    foreach ($rooms as &$room) {
        $roomId = $room['room_id'];

        $stmt = $connect->prepare('SELECT COUNT(*) AS total_beds, SUM(CASE WHEN occupied = 0 THEN 1 ELSE 0 END) AS available_beds FROM beds WHERE room_id = :roomId');
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
?>



<!DOCTYPE html>
<html>

<head>
    <title>Rooms Data</title>
    <style>
        /* Styles for your table */
    </style>
</head>

<body>

    <h2>Rooms Data</h2>

    <a href="dashboard.php">Click</a>
    <table border="1">
        <tr>
            <th>Room ID</th>
            <th>Room Name</th>
            <th>Availability</th>
            <th>More Details</th>
        </tr>
        <?php foreach ($rooms as $room): ?>
            <tr>
                <td>
                    <?php echo $room['room_id']; ?>
                </td>
                <td>
                    <?php echo $room['room_name']; ?>
                </td>
                <td>
                    <?php echo $room['availability']; ?>
                </td>
                <td><a href="beds.php?room_id=<?php echo $room['room_id']; ?>">Show Details</a></td>
            </tr>
        <?php endforeach; ?>
    </table>

</body>

</html>