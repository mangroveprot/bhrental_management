<?php
if (!isset($_SESSION)) {
    session_start();
}

$succesMessage = isset($_SESSION['successMessage']) ? $_SESSION['successMessage'] : '';

$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';

if (!empty($error)) {
    echo '<p class="error">' . htmlspecialchars($error) . '</p>';
}
if (!empty($succesMessage)) {
    echo '<p class="successMessage">' . htmlspecialchars($succesMessage) . '</p>';
}

unset($_SESSION['error']);
unset($_SESSION['successMessage']);
?>

<script>
    function hideMessages() {
        var notifications = document.querySelectorAll('.error, .successMessage');
        notifications.forEach(function (notification) {
            setTimeout(function () {
                notification.style.display = 'none';
            }, 5000);
        });
    }
    window.onload = hideMessages; 
</script>