<?php
session_start();
//include ('../database/models/diaries_model.php');

class RemoveTenantsHandler
{
    public function removeTenantFromBed()
    {

        if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['bedID'])) {

            // $diary = new Diaries();
            // try {
            //     // Delete the diary entry
            //     $deleted = $diary->deleteDiary($diary_id);
            //     if ($deleted) {
            //         $_SESSION['notification'] = "Diary successfully deleted!";
            //     } else {
            //         $_SESSION['err_message'] = "Failed to delete diary.";
            //     }
            // } catch (Exception $e) {
            //     $_SESSION['err_message'] = "Error: " . $e->getMessage();
            // }
            $_SESSION['succesMessage'] = "Succesfully Remove!";
            header("Location: ../../client/pages/Beds.php");
            exit();
        } else {
            $_SESSION['error'] = "Failed!";
            header("Location: ../../client/pages/Beds.php");
            exit();
        }
    }
}

$handler = new RemoveTenantsHandler();
$handler->removeTenantFromBed();
?>