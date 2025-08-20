// modal
const imgEl = document.getElementById('lightboxImg');
const titleEl = document.getElementById('lightboxTitle');

document.addEventListener('click', (e) => {
    const a = e.target.closest('a[data-bs-target="#lightboxModal"]');
    if (!a) return;
    e.preventDefault();
    imgEl.src = a.getAttribute('href');
    imgEl.alt = a.dataset.title || '';
    titleEl.textContent = a.dataset.title || '';
});

document.getElementById("addSkillForm").addEventListener("submit", function (event) {
    event.preventDefault();                               // ← exactly like your example

    const fileInput = document.getElementById("image");
    const errorMsg = document.getElementById("imgMsg");
    errorMsg.style.color = "#c00";
    errorMsg.innerHTML = "";                              // clear message

    // 1) required
    if (!fileInput.files.length) {
        errorMsg.innerHTML = "Please choose a file.";
        return;
    }

    // 2) extension test (your hint):
    //    allow jpg / jpeg / png / gif / webp; block svg & others
    const v = (fileInput.value || "").toLowerCase();      // e.g. C:\fakepath\pic.png
    const isAllowed = v.match(/\.(jpg|jpeg|png|gif|webp)$/i) && !v.endsWith(".svg");

    if (!isAllowed) {
        errorMsg.innerHTML = "Only image files are allowed (JPG, JPEG, PNG, GIF, WEBP).";
        return;
    }

    // success (A1: still not submitting)
    errorMsg.style.color = "green";
    errorMsg.innerHTML = "Validation passed (demo only).";
});

// Optional: show error immediately after a non-image is picked
document.getElementById("image").addEventListener("change", function () {
    const errorMsg = document.getElementById("imgMsg");
    errorMsg.style.color = "#c00";
    errorMsg.innerHTML = "";

    const v = (this.value || "").toLowerCase();
    const bad = !v.match(/\.(jpg|jpeg|png|gif|webp)$/i) || v.endsWith(".svg");

    if (this.files.length && bad) {
        errorMsg.innerHTML = "Only image files are allowed (JPG, JPEG, PNG, GIF, WEBP).";
    }
});