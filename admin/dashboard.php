<?php
  $error = "";

  include('../includes/conn.php');

  session_start();

  if(isset($_SESSION['user'])){
    $user = $_SESSION['user'];
    $q = "SELECT * FROM login WHERE username='$user'";
    $r = mysqli_query($conn, $q);
    $data = mysqli_fetch_array($r);
    $count = mysqli_num_rows($r);
  }
  else {
    header('location:login.php');
  }

  // Fetch categories from the formulas table
  $categories_query = mysqli_query($conn, "SELECT DISTINCT category FROM formulas");
  $categories = mysqli_fetch_all($categories_query, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <header>
      <h1>Admin Dashboard - Welcome <?php echo $data['username'] ?></h1>
      <a href="logout.php" class="logout-link">Logout</a>
    </header>

    <div class="container">
        <div class="dashboard-section" id="nav-bar-menu">
            <h1>Formula Categories</h1>

            <button class="btn btn-primary" id="openModalBtn1">Add New Formula</button>
            <?php
            // Loop through each category and fetch formulas related to that category
            foreach($categories as $category) {
                echo "<h2>" . htmlspecialchars($category['category']) . "</h2>";
                
                // Fetch formulas by category
                $category_name = $category['category'];
                $query = mysqli_query($conn, "SELECT * FROM formulas WHERE category = '$category_name'");

                if (mysqli_num_rows($query) > 0) {
                    echo "<table class='table'>
                            <thead>
                              <tr>
                                <th>ID</th>
                                <th>Formula Name</th>
                                <th>Inputs</th>
                                <th>Expression</th>
                                <th>Unit</th>
                                <th>Action</th>
                              </tr>
                            </thead>
                            <tbody>";
                    
                    while ($row = mysqli_fetch_array($query)) {
                        echo "<tr>
                                <td contenteditable='true' class='editable' data-column='id'>{$row['id']}</td>
                                <td contenteditable='true' class='editable' data-column='formula_name'>{$row['formula_name']}</td>
                                <td contenteditable='true' class='editable' data-column='inputs'>{$row['inputs']}</td>
                                <td contenteditable='true' class='editable' data-column='expression'>{$row['expression']}</td>
                                <td contenteditable='true' class='editable' data-column='unit'>{$row['unit']}</td>
                                <td>
                                  <button class='delete-btn' data-id='{$row['id']}'>Delete</button>
                                </td>
                              </tr>";
                    }
                    echo "</tbody></table>";
                } else {
                    echo "<p>No formulas available for this category.</p>";
                }
            }
            ?>
        </div>
    </div>

    <!-- Modal for adding new formula -->
    <div class="modal-overlay" id="modalOverlay1" style="display:none;">
        <div class="modal-content">
          <div class="modal-header">
            <h2>Add New Formula</h2>
            <button class="modal-close-btn" id="closeModalBtn1"></button>
          </div>
          <form class="modal-form" method="POST" action="add_formula.php">
            <label for="category1">Category:</label>
            <input type="text" name="category1" id="category1">
            <label for="formula_name1">Formula Name:</label>
            <input type="text" name="formula_name1" id="formula_name1">
            <label for="inputs1">Inputs:</label>
            <input type="text" name="inputs1" id="inputs1">
            <label for="expression1">Expression:</label>
            <input type="text" name="expression1" id="expression1">
            <label for="unit1">Unit:</label>
            <input type="text" name="unit1" id="unit1">
            <input type="submit" name="add" value="Add">
          </form>
        </div>
    </div>

    <script>
      $(document).ready(function () {
    // Editable content handling
    $('.editable').on('blur', function () {
        const id = $(this).closest('tr').find('td:first').text();
        const column = $(this).data('column');
        const value = $(this).text();

        console.log("Sending data: ", { id, column, value });
        $.ajax({
            url: 'update_formula.php',
            method: 'POST',
            data: {
                id: id,
                column: column,
                value: value
            },
            success: function (response) {
                location.reload();  // Reload only after success message is logged
            },
            error: function () {
                alert('Error updating data. Please try again.');
            }
        });
    });

    // Delete formula handling
    $('.delete-btn').on('click', function () {
        const id = $(this).closest('tr').find('td:first').text();

        $.ajax({
            url: 'delete_formula.php',
            method: 'POST',
            data: {
                id: id
            },
            success: function (response) {
                location.reload();  // Reload only after success message is logged
            },
            error: function () {
                alert('Error deleting data. Please try again.');
            }
        });
    });

    // Add new formula using AJAX
    $('#openModalBtn1').on('click', function () {
        $('#modalOverlay1').show(); // Show modal when the button is clicked
    });

    $('#closeModalBtn1').on('click', function () {
        $('#modalOverlay1').hide(); // Hide modal when the close button is clicked
    });

    // AJAX for submitting the form
    $('.modal-form').on('submit', function (e) {
        e.preventDefault();  // Prevent the form from submitting normally

        const category = $('#category1').val();
        const formula_name = $('#formula_name1').val();
        const inputs = $('#inputs1').val();
        const expression = $('#expression1').val();
        const unit = $('#unit1').val();

        // Send data via AJAX to add_formula.php
        $.ajax({
            url: 'add_formula.php',
            method: 'POST',
            data: {
                category1: category,
                formula_name1: formula_name,
                inputs1: inputs,
                expression1: expression,
                unit1: unit
            },
            success: function (response) {
                alert('Formula added successfully!');
                location.reload();  // Reload page after successful addition
            },
            error: function () {
                alert('Error adding formula. Please try again.');
            }
        });
    });
});
    </script>

</body>
</html>
