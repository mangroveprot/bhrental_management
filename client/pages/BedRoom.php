<?php
include '../../server/database/models/beds_model.php';
$bedModel = new BedModel();
$error = "";
$beds = [];

if (isset($_GET['room_id'])) {
    $roomId = $_GET['room_id'];

    try {
        $beds = $bedModel->getBedsByRoomId($roomId);
    } catch (Exception $e) {
        $error = 'Error fetching bed data: ' . $e->getMessage();
    }
} else {
    header('Location: room.php?error=No room ID provided');
    exit();
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bed Information</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>

<body>
    <button><a href="Room.php">Back</a></button>

    <h1>Bed Information</h1>

    <?php
    //Responsible for any notifications to display if it has a value to set.
    include ('../includes/messageHandler.php');
    ?>

    <table>
        <thead>
            <tr>
                <th>Bed ID</th>
                <th>Room ID</th>
                <th>Occupied</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($beds as $bed): ?>
                <tr>
                    <td>
                        <?php echo $bed['beds_id']; ?>
                    </td>
                    <td>
                        <?php echo $bed['room_id']; ?>
                    </td>
                    <td>
                        <?php echo $bed['occupied'] ? 'Yes' : 'No'; ?>
                    </td>
                    <td>
                        <button>Show Details</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>