//unit

const unitConversions = {
    length: [
        'meters', 'kilometers', 'miles', 'feet', 'inches', 'yard', 'link', 'rod', 'chain', 'angstrom', 'mil', 'nanometer', 'micrometer', 'centimeter',
        'decimeter', 'picometer'
    ],
    mass: [
        'kilograms', 'grams', 'milligrams', 'micrograms', 'lbs', 'ounces', 'tonne', 'ton', 'stone'
    ],
    temperature: [
        'celsius', 'fahrenheit', 'kelvin', 'rankine'
    ],
    area: [
        'm2', 'hectares', 'acres', 'sqft', 'sqin', 'sqyd', 'sqmi', 'sqrd', 'sqch', 'sqmil', 'cm2', 'mm2', 'km2', 'nm2'
    ],
    volume: [
        'liters', 'milliliters', 'gallons', 'm3', 'cm3', 'mm3', 'pint', 'quart', 'cup', 'tablespoon', 'teaspoon'
    ],
    speed: [
        'm/s', 'km/h', 'm/h', 'ft/s', 'ft/h', 'mi/s','mi/h'
    ],
    pressure: [
        'bar', 'psi', 'atm', 'torr', 'mmHg'
    ],
    energy: [
        'joules', 'BTU', 'erg', 'electronvolt'
    ],
    power: [
        'watts', 'kilowatts', 'megawatts', 'gigawatts', 'BTU/s', 'erg/s'
    ],
    time: [
        'seconds', 'minutes', 'hours', 'days', 'milliseconds', 'microseconds', 'nanoseconds', 'weeks', 'years', 'decades', 'centuries'
    ],
    frequency: [
        'hertz', 'kilohertz', 'megahertz', 'gigahertz', 'terahertz'
    ],
    current: [
        'ampere', 'milliampere', 'microampere', 'nanoampere'
    ],
    voltage: [
        'volt', 'millivolt', 'kilovolt'
    ],
    resistance: [
        'ohm', 'kiloohm', 'megaohm'
    ],
    capacitance: [
        'farad', 'millifarad', 'microfarad', 'nanofarad', 'picofarad'
    ],
    inductance: [
        'henry', 'millihenry', 'microhenry'
    ],
    force: [
        'newton', 'kilonewton', 'poundforce', 'dyne'
    ],
    torque: [
        'newtonmeter', 'poundforcefoot', 'poundforceinch'
    ],
    angle: [
        'radian', 'degree', 'grad', 'arcmin', 'arcsec'
    ]
};


function populateUnits() {
    const unitType = document.getElementById('unitType').value;
    const fromUnitSelect = document.getElementById('fromUnit');
    const toUnitSelect = document.getElementById('toUnit');

    fromUnitSelect.innerHTML = '';
    toUnitSelect.innerHTML = '';

    for (const unit of unitConversions[unitType]) {
        const optionFrom = document.createElement('option');
        optionFrom.value = unit;
        optionFrom.textContent = unit;
        fromUnitSelect.appendChild(optionFrom);

        const optionTo = document.createElement('option');
        optionTo.value = unit;
        optionTo.textContent = unit;
        toUnitSelect.appendChild(optionTo);
    }

    convertUnits(); // Update conversion when unit type changes
}

function convertUnits() {
    const value = parseFloat(document.getElementById('valueInput').value);
    const unitType = document.getElementById('unitType').value;
    const fromUnit = document.getElementById('fromUnit').value;
    const toUnit = document.getElementById('toUnit').value;

    if (isNaN(value)) {
        document.getElementById('resultDisplay').textContent = 'Result: ';
        return;
    }

    // Create the expression for math.js
    const expression = `number(${value} ${fromUnit}, ${toUnit})`;
    console.log('Evaluating expression:', expression); // Debugging line

    try {
        const result = math.evaluate(expression);

        // Check if result is a number and handle accordingly
        if (typeof result === 'number') {
            // Rounding the result to avoid floating-point issues
            const roundedResult = Math.abs(result) < 1e-10 ? 0 : parseFloat(result.toFixed(10));
            // Display the result in the resultDisplay div
            document.getElementById('resultDisplay').textContent = `Result: ${roundedResult}`;
        } else {
            throw new Error("Invalid result type"); // Throw an error if result is not a number
        }
    } catch (error) {
        console.error('Conversion error:', error); // Log the error for debugging
        document.getElementById('resultDisplay').textContent = `Result: Error - ${error.message}`;
    }
}


// Initialize the unit type dropdown and add event listeners
document.addEventListener('DOMContentLoaded', () => {
    const unitType = document.getElementById('unitType');
    const fromUnit = document.getElementById('fromUnit');
    const toUnit = document.getElementById('toUnit');

    if (unitType && fromUnit && toUnit) {
        populateUnits(); // Populate units when the page loads
        unitType.addEventListener('change', populateUnits);
        fromUnit.addEventListener('change', convertUnits);
        toUnit.addEventListener('change', convertUnits);
    } else {
        console.error('One or more elements not found:', { unitType, fromUnit, toUnit });
    }
});
