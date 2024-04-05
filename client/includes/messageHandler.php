<?php
if (isset($_GET['error'])) {
    $error = $_GET['error'];
}
if (!empty($error)) {
    echo '<p class="error">' . htmlspecialchars($error) . '</p>';
}
?>
