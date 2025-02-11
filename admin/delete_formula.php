<?php
include('../includes/conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    // Escape the input to prevent SQL injection
    $id = mysqli_real_escape_string($conn, $id);

    // Prepare the delete query for the formulas table
    $query = "DELETE FROM `formulas` WHERE `id` = $id";

    // Execute the query
    if (mysqli_query($conn, $query)) {
        // Optionally return a success response or just indicate success
        echo "Formula deleted successfully.";
    } else {
        // Handle any errors in query execution
        echo "Error: " . mysqli_error($conn);
    }
}
?>
