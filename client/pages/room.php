<?php
include ('../../server/database/models/Room_models.php');
$roomModel = new RoomModel();
$rooms = $roomModel->getRooms();
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
<nav>
    <ul>
        <li><a href="">Rooms</a></li>
        <li><a href="#">Beds</a></li>
    </ul>
</nav>  
</nav>  
    <?php if (!empty($rooms)): ?>
        <h2>Room Availability</h2>
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
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="error">No rooms available.</p>
    <?php endif; ?>

</body>

</html>