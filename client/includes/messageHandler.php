<?php
$error = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : '';

if (!empty($error)) {
    echo '<p class="error">' . $error . '</p>';
}
?>