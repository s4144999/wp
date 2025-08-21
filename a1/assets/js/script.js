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

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('addSkillForm');
    const input = document.getElementById('image');
    const msg = document.getElementById('imgMsg');

    function isAllowedImage(file) {
        if (!file) return false;
        // allow jpg/jpeg, png, gif, webp; block svg
        const okMime = /^image\/(jpeg|png|gif|webp)$/i.test(file.type);
        const okExt = /\.(jpe?g|png|gif|webp)$/i.test(file.name);
        return (okMime || okExt) && file.type.toLowerCase() !== 'image/svg+xml';
    }

    function showError(text) {
        msg.textContent = text;
        msg.style.display = 'block';
    }
    function clearError() {
        msg.textContent = '';
        msg.style.display = 'none';
    }

    // Show/clear message as soon as user picks a file
    input.addEventListener('change', () => {
        const f = input.files[0];
        if (!f) { clearError(); return; }
        if (isAllowedImage(f)) {
            clearError();
        } else {
            showError('Only image files are allowed (JPG, PNG, GIF, WEBP).');
            // optional: clear bad selection so user must pick again
            input.value = '';
            input.focus();
        }
    });

    // Block submit if invalid
    form.addEventListener('submit', (e) => {
        const f = input.files[0];
        if (!isAllowedImage(f)) {
            e.preventDefault();
            showError('Only image files are allowed (JPG, PNG, GIF, WEBP).');
        } else {
            clearError();
        }
    });
});