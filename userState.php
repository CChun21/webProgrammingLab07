<?php
    function isAdmin() {
        // Check if they are logged in AND if their role is 1
        return (isset($_SESSION['role']) && $_SESSION['role'] === "admin");
    }

    function isLoggedIn() {
        return isset($_SESSION['email']);
    }
?>