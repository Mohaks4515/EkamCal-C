<?php
include('../includes/conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $column = $_POST['column'];
    $value = $_POST['value'];

    // Ensure that the column is a valid column in the formulas table
    $valid_columns = ['formula_name', 'inputs', 'expression', 'unit'];
    
    // Check if the column is valid
    if (!in_array($column, $valid_columns)) {
        echo "Invalid column name!";
        exit;
    }

    // Sanitize the value to prevent SQL injection
    $value = mysqli_real_escape_string($conn, $value);
    
    // Sanitize the id
    $id = mysqli_real_escape_string($conn, $id);

    // Prepare the update query for the formulas table
    $query = "UPDATE `formulas` SET `$column` = '$value' WHERE `id` = $id";

    // Execute the query
    if (mysqli_query($conn, $query)) {
        // Optionally return a success message or log success
        echo "Formula updated successfully.";
    } else {
        // Handle errors during query execution
        echo "Error: " . mysqli_error($conn);
    }
}
?>
