<?php
include 'includes/conn.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advanced Formula Calculator with Units</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mathjs/10.6.4/math.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="styles.css"> <!-- Ensure this matches your previous styles -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <style>
        #formulaOutput {
            margin-top: 20px;
            font-size: 1.2rem;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
          <a class="navbar-brand" href="index.html">Ekam Cal-C</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
              <li class="nav-item">
                <a class="nav-link" href="./">Cal-C</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="graph.html">Graphical Calculator</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" href="#">Formula Calculator</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="unit.html">Converter</a>
              </li>
            </ul>
          </div>
        </div>
      </nav>
    <header class="text-center my-4">
        <h1>Formula Calculator</h1>
    </header>
    <div class="container">

        <div class="row mt-4">
            <!-- Formula Type Dropdown -->
            <div class="col-md-4">
                <label for="formulaType" class="form-label">Select Formula Type:</label>
                <select id="formulaType" class="form-select" onchange="populateFormulas()">
                    <option value="physics">Physics</option>
                    <option value="math">Math</option>
                    <option value="finance">Finance</option>
                    <option value="chemistry">Chemistry</option>
                    <option value="engineering">Engineering</option>
                    <option value="geometry">Geometry</option>
                    <option value="thermodynamics">Thermodynamics</option>
                    <option value="optics">Optics</option>
                    <option value="astronomy">Astronomy</option>
                    <option value="biomechanics">Biomechanics</option>
                </select>
            </div>

            <!-- Formula Dropdown -->
            <div class="col-md-4">
                <label for="formulaSelect" class="form-label">Select Formula:</label>
                <select id="formulaSelect" class="form-select" onchange="generateInputFields()">
                    <!-- Formulas will be populated here -->
                </select>
            </div>
        </div>

        <!-- Input Fields Section -->
        <div class="row mt-4" id="inputFields">
            <!-- Input fields will be dynamically generated here -->
        </div>

        <!-- Result Section -->
        <div class="row">
            <div class="col-md-12">
                <button class="btn btn-primary" onclick="calculateFormula()">Calculate</button>
                <div id="formulaOutput" class="text-success"></div>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Ekam Cal-C. All rights reserved.</p>
      </footer>


    <?php
        // Example code to fetch the data
        // Assuming you have a database connection set up

        $query = "
        SELECT category,
            formula_name,
            CONCAT(
                '{\"inputs\": [', GROUP_CONCAT(DISTINCT CONCAT('\"', input, '\"') SEPARATOR ','), '],',
                ' \"expression\": \"', expression, '\",',
                ' \"unit\": \"', unit, '\" }'
            ) AS formula_data
        FROM (
            SELECT 
                f.category,
                f.formula_name,
                SUBSTRING_INDEX(SUBSTRING_INDEX(f.inputs, ',', n.n), ',', -1) AS input,
                f.expression,
                f.unit
            FROM formulas f
            CROSS JOIN (
                SELECT 1 AS n UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL 
                SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8
            ) n
            WHERE n.n <= LENGTH(f.inputs) - LENGTH(REPLACE(f.inputs, ',', '')) + 1
        ) subquery
        GROUP BY category, formula_name;
        ";

        // Execute the query
        $result = mysqli_query($conn, $query); // $conn is your database connection

        if ($result) {
            // Initialize an array to hold the nested formulas
            $nested_formulas = [];

            // Fetch results
            while ($row = mysqli_fetch_assoc($result)) {
                // Prepare the JSON structure
                $nested_formulas[$row['category']][$row['formula_name']] = json_decode($row['formula_data']);
            }
        } else {
            // Handle error
            $nested_formulas = '{}';
        }
    ?>

    <script>
        var formulas = <?php echo json_encode($nested_formulas); ?>;


        // Function to populate formulas based on selected type
        function populateFormulas() {
            const formulaType = document.getElementById('formulaType').value;
            const formulaSelect = document.getElementById('formulaSelect');
            formulaSelect.innerHTML = ''; // Clear previous formulas

            Object.keys(formulas[formulaType]).forEach((formula) => {
                const option = document.createElement('option');
                option.value = formula;
                option.textContent = formula;
                formulaSelect.appendChild(option);
            });

            generateInputFields(); // Generate fields for the first formula
        }

        // Function to generate input fields based on selected formula
        function generateInputFields() {
            const formulaType = document.getElementById('formulaType').value;
            const selectedFormula = document.getElementById('formulaSelect').value;
            const formulaData = formulas[formulaType][selectedFormula];
            const inputs = formulaData.inputs;
            const inputFieldsDiv = document.getElementById('inputFields');
            inputFieldsDiv.innerHTML = ''; // Clear previous input fields

            inputs.forEach((input, index) => {
                const div = document.createElement('div');
                div.classList.add('col-md-6', 'mb-3');
                div.innerHTML = `
                    <label for="input${index}" class="form-label">${input}</label>
                    <input type="number" id="input${index}" class="form-control" placeholder="Enter ${input}">
                `;
                inputFieldsDiv.appendChild(div);
            });
        }

        // Function to calculate the result using the selected formula
        function calculateFormula() {
    const formulaType = document.getElementById('formulaType').value;
    const selectedFormula = document.getElementById('formulaSelect').value;
    const formulaData = formulas[formulaType][selectedFormula];
    const inputs = formulaData.inputs;
    const expression = formulaData.expression;

    const values = {};
    inputs.forEach((input, index) => {
        const inputValue = parseFloat(document.getElementById(`input${index}`).value);
        if (isNaN(inputValue)) {
            alert(`Please enter a valid value for ${input}`);
            return;
        }
        const variable = input.match(/\(([^)]+)\)/)[1]; // Extract variable from input name
        values[variable] = inputValue;
    });

    try {
        const result = math.evaluate(expression, values);
        const unit = formulaData.unit;
        document.getElementById('formulaOutput').innerHTML = 
            `Result: ${result.toFixed(2)} ${unit}`;
    } catch (error) {
        document.getElementById('formulaOutput').innerHTML = 
            `<span class="text-danger">Error: ${error.message}</span>`;
    }
}



        // Initialize page by populating formulas
        document.addEventListener('DOMContentLoaded', populateFormulas);
    </script>
</body>
</html>
