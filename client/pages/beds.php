<?php
require '../../server/database/config.php';
$connect = connections();
$beds = [];
$error = '';

if (isset($_GET['room_id'])) {
    $roomId = $_GET['room_id'];

    try {
        $stmt = $connect->prepare('SELECT * FROM beds WHERE room_id = :roomId');
        $stmt->bindParam(':roomId', $roomId);
        $stmt->execute();

        $beds = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $error = 'Error fetching bed data: ' . $e->getMessage();
    }
} else {
    $error = 'Room ID not provided.';
}

echo htmlspecialchars($error);
?>


<!DOCTYPE html>
<html>

<head>
    <title>Beds in Room</title>
    <style>
        /* Styles for your table */
    </style> 
</head>

<body>
<nav>
    <ul>
        <li><a href="">Rooms</a></li>
        <li><a href="#">Beds</a></li>
    </ul>
</nav>
    </nav>
    <h2>Beds in Room</h2>

    <?php if (!empty($beds)): ?>
        <table border="1">
            <tr>
                <th>Beds ID</th>
                <th>Occupied</th>
                <th>Customer ID</th>
            </tr>
            <?php foreach ($beds as $bed): ?>
                <tr>
                    <td>
                        <?php echo $bed['beds_id']; ?>
                    </td>
                    <td>
                        <?php echo $bed['occupied'] ? 'Yes' : 'No'; ?>
                    </td>
                    <td>
                        <?php echo $bed['customer_id'] ? $bed['customer_id'] : 'Not occupied'; ?>
                    </td>
                    <td><a href="beds.php?room_id=<?php echo $room['room_id']; ?>">Show More</a></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No beds found for this room.</p>
    <?php endif; ?>

</body>

</html>