// let count = 0;

// function incrementCounter() {
//     count++;
//     document.getElementById("counterText").textContent = `Clicked ${count} times.`;
// }
function calculate() {
    const num1 = parseFloat(document.getElementById("num1").value);
    const num2 = parseFloat(document.getElementById("num2").value);
    const operator = document.getElementById("operator").value;
    const resultDiv = document.getElementById("calcResult");

    // Check for valid numbers
    if (isNaN(num1) || isNaN(num2)) {
        resultDiv.textContent = "Error: Please enter valid numbers.";
        return;
    }

    // Check for division by zero
    if (operator === "/" && num2 === 0) {
        resultDiv.textContent = "Error: Division by zero is not allowed.";
        return;
    }

    let result;
    switch (operator) {
        case "+":
            result = num1 + num2;
            break;
        case "-":
            result = num1 - num2;
            break;
        case "*":
            result = num1 * num2;
            break;
        case "/":
            result = num1 / num2;
            break;
        default:
            resultDiv.textContent = "Error: Invalid operator.";
            return;
    }

    resultDiv.textContent = "Result: " + result;
}