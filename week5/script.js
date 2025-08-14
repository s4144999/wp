let count = 0;

function incrementCounter() {
    count++;
    document.getElementById("counterText").textContent = `Clicked ${count} times.`;
}
