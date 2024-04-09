<?php
$succesMessage = isset($_SESSION['succesMessage']) ? $_SESSION['succesMessage'] : '';
unset($_SESSION['succesMessage']);

$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
unset($_SESSION['succesMessage']);

if (!empty($error)) {
    echo '<p class="error">' . htmlspecialchars($error) . '</p>';
}
if (!empty($succesMessage)) {
    echo '<p class="succesMessage">' . htmlspecialchars($succesMessage) . '</p>';
}
?>

<script>
    function hideMessages() {
        var notifications = document.querySelectorAll('.error, .succesMessage');
        notifications.forEach(function (notification) {
            setTimeout(function () {
                notification.style.display = 'none';
            }, 5000);
        });
    }
    window.onload = hideMessages; 
</script>