<?php
    session_start();
    
    // Use an absolute path based on the current file directory to avoid relative path errors
    include_once __DIR__ . "/../../config/db_carngren.php";

    /* =====================================
       UPDATE LOGIN STATUS (ADMIN)
    ===================================== */
    if (isset($conn)) {
        if (isset($_SESSION['adminID'])) {
            mysqli_query(
                $conn,
                "UPDATE admin SET logStatus = 0 WHERE adminID = '" . mysqli_real_escape_string($conn, $_SESSION['adminID']) . "'"
            );
        } else {
            // Fallback to clear stale admin login status if session expired
            mysqli_query($conn, "UPDATE admin SET logStatus = 0 WHERE logStatus = 1");
        }

        /* =====================================
           UPDATE LOGIN STATUS (USER)
        ===================================== */
        if (isset($_SESSION['userID'])) {
            mysqli_query(
                $conn,
                "UPDATE user SET logStatus = 0 WHERE userID = '" . mysqli_real_escape_string($conn, $_SESSION['userID']) . "'"
            );
        } else {
            // Fallback to clear stale user login status if session expired
            mysqli_query($conn, "UPDATE user SET logStatus = 0 WHERE logStatus = 1");
        }
    }

    /* =====================================
       DESTROY SESSION & REDIRECT
    ===================================== */
    session_unset();
    session_destroy();

    header("Location: login.php");
    exit();
?>