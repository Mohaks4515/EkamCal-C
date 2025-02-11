document.addEventListener('DOMContentLoaded', () => {
    let memory = 0; // Memory for MS, MR, MC functionality
    let isDegreeMode = false; // Track mode

    function appendCharacter(char) {
        document.getElementById('input').value += char;
        calculate(); // Auto-update result when appending characters
    }

    function clearInput() {
        document.getElementById('input').value = '';
        document.getElementById('resultDisplay').textContent = ''; // Clear result display
    }

    function toggleDegreeMode() {
        isDegreeMode = document.getElementById('degreeMode').checked;
        calculate(); // Update result when toggling degree mode
    }

    function negateValue() {
        const inputElement = document.getElementById('input');
        const currentValue = parseFloat(inputElement.value);
        inputElement.value = -currentValue; // Negate the current value
        calculate(); // Update the result after negation
    }

    function calculate() {
        try {
            let expression = document.getElementById('input').value.trim();
            console.log('Input:', expression); // Debugging

            // Check if input is empty
            if (!expression) {
                document.getElementById('resultDisplay').textContent = ''; // Clear result if input is empty
                return;
            }

            // Handling degree/radian conversion for trig functions
            if (isDegreeMode) {
                expression = expression
                    .replace(/sin\(([^)]+)\)/g, `sin(unit($1, "deg"))`)
                    .replace(/cos\(([^)]+)\)/g, `cos(unit($1, "deg"))`)
                    .replace(/tan\(([^)]+)\)/g, `tan(unit($1, "deg"))`);
            } else {
                expression = expression
                    .replace(/sin\(([^)]+)\)/g, `sin($1)`)
                    .replace(/cos\(([^)]+)\)/g, `cos($1)`)
                    .replace(/tan\(([^)]+)\)/g, `tan($1)`);
            }

            // Handling factorial using math.js
            expression = expression.replace(/(\d+)!/g, 'factorial($1)');

            // Evaluate the expression using math.js
            const result = math.evaluate(expression);
            const roundedResult = Math.abs(result) < 1e-10 ? 0 : parseFloat(result.toFixed(10));
            document.getElementById('resultDisplay').textContent = `Result: ${roundedResult}`;
        } catch (error) {
            console.error('Calculation error:', error);
            document.getElementById('resultDisplay').textContent = 'Result: Error'; // Display error in result box
        }
    }

    // Make functions globally accessible
    window.appendCharacter = appendCharacter; 
    window.clearInput = clearInput;  
    window.toggleDegreeMode = toggleDegreeMode; 
    window.memoryStore = memoryStore; // Add memoryStore to the global window object
    window.memoryRecall = memoryRecall; // Add memoryRecall to the global window object
    window.memoryClear = memoryClear; // Add memoryClear to the global window object
    window.negateValue = negateValue;

    // Attach event listeners
    document.getElementById('input').addEventListener('input', calculate);

    // Memory functions
    function memoryStore() {
        const value = document.getElementById('input').value;
        if (value !== '') {
            memory = parseFloat(value);
        }
    }

    function memoryRecall() {
        document.getElementById('input').value = memory;
        calculate(); // Update result when recalling memory
    }

    function memoryClear() {
        memory = 0;
    }
});

