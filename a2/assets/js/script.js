// modal
//grabs the image and caption of the skill for the modal
const imgEl = document.getElementById('lightboxImg');
const titleEl = document.getElementById('lightboxTitle');
//listens for clicks in the page, if the image if clicked the model opens
document.addEventListener('click', (e) => {
    const a = e.target.closest('a[data-bs-target="#lightboxModal"]');
    if (!a) return;
    e.preventDefault();
    imgEl.src = a.getAttribute('href');
    imgEl.alt = a.dataset.title || '';
    titleEl.textContent = a.dataset.title || '';
});
//validation script
document.addEventListener('DOMContentLoaded', () => {//waits for the page to load and only runs the code after it loads up
    const form = document.getElementById('addSkillForm');
    const input = document.getElementById('image');
    const msg = document.getElementById('imgMsg');

    function isAllowedImage(file) {
        if (!file) return false;
        // allow jpg/jpeg, png, gif, webp
        const okMime = /^image\/(jpeg|png|gif|webp)$/i.test(file.type);
        const okExt = /\.(jpe?g|png|gif|webp)$/i.test(file.name);
        return (okMime || okExt) && file.type.toLowerCase();
    }
    //putws the error msg 
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
// Lightbox for details page (and anywhere else)
// document.addEventListener('DOMContentLoaded', function () {
//     const modal = document.getElementById('lightboxModal');
//     if (!modal) return;

//     modal.addEventListener('show.bs.modal', function (e) {
//         const trigger = e.relatedTarget; // the <a> that opened the modal
//         if (!trigger) return;

//         const href = trigger.getAttribute('href');    // full image URL
//         const title = trigger.dataset.title || '';

//         const imgEl = modal.querySelector('#lightboxImg');
//         const titleEl = modal.querySelector('#lightboxTitle');

//         imgEl.src = href;
//         imgEl.alt = title;
//         titleEl.textContent = title;
//     });

//     // optional: clear src when closing (so previous image doesn't flash)
//     modal.addEventListener('hidden.bs.modal', function () {
//         const imgEl = modal.querySelector('#lightboxImg');
//         if (imgEl) imgEl.src = '';
//     });
// });
