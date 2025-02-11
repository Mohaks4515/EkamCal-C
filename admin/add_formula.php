<?php
include('../includes/conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the data sent via AJAX
    $category = $_POST['category1'];
    $formula_name = $_POST['formula_name1'];
    $inputs = $_POST['inputs1'];
    $expression = $_POST['expression1'];
    $unit = $_POST['unit1'];

    // Insert new formula into the formulas table
    $query = "INSERT INTO formulas (category, formula_name, inputs, expression, unit) 
              VALUES ('$category', '$formula_name', '$inputs', '$expression', '$unit')";

    if (mysqli_query($conn, $query)) {
        echo "Formula added successfully.";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
