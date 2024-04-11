<?php
if (!isset($_SESSION)) {
    session_start();
}

include ('../../server/database/models/rooms_model.php');
$roomModel = new RoomModel();
$error = "";
try {
    $rooms = $roomModel->getRooms();
} catch (Exception $e) {
    $error = 'Error: ' . $e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Availability</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>

<body>
    <?php include_once ('../includes/navbar.php'); ?>
    <h2>Room Availability</h2>
    <?php
    //Responsible for any notifications to display if it has a value to set.
    include_once ('../includes/messageHandler.php');
    ?>
    <?php if (!empty($rooms)): ?>
        <table>
            <thead>
                <tr>
                    <th>Room ID</th>
                    <th>Room Name</th>
                    <th>Availability</th>
                </tr>
            </thead>
            <tbody>
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
                        <td><a href="BedRoom.php?room_id=<?php echo $room['room_id']; ?>">Show Details</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="error">No rooms available.</p>
    <?php endif; ?>

</body>

</html>