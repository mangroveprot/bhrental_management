<?php
//Set a landing page here!
if (!isset($_SESSION)) {
    session_start();
}

header("Location: client/pages/Dashboard.php");
?>