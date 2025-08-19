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
    const preview = document.getElementById('imagePreview');
    if (!form || !input) return; // not on add page

    const EXT_OK = /\.(jpe?g|png|gif|webp)$/i;
    const TYPE_OK = /^image\/(jpeg|png|gif|webp)$/i;
    const MAX_BYTES = 5 * 1024 * 1024;

    function setError(msg) {
        input.classList.add('is-invalid');
        input.setCustomValidity(msg);
        const fb = input.nextElementSibling;
        if (fb && fb.classList.contains('invalid-feedback')) fb.textContent = msg;
    }
    function clearError() {
        input.classList.remove('is-invalid');
        input.setCustomValidity('');
    }
    function validateImage() {
        const f = input.files[0];
        if (!f) { setError('Please choose an image.'); return false; }

        // Hard block SVG
        if (f.type === 'image/svg+xml' || /\.svg$/i.test(f.name)) {
            setError('SVG is not allowed. Use JPG/PNG/GIF/WEBP.'); return false;
        }

        const byType = f.type ? TYPE_OK.test(f.type) : true; // tolerate empty type
        const byExt = EXT_OK.test(f.name);
        if (!(byType && byExt)) {
            setError('Only JPG, PNG, GIF, or WEBP images are allowed.'); return false;
        }

        if (f.size > MAX_BYTES) { setError('Image is too large (max 5 MB).'); return false; }

        clearError(); return true;
    }

    input.addEventListener('change', () => {
        if (!validateImage()) {
            if (preview) { preview.classList.add('d-none'); preview.removeAttribute('src'); }
            return;
        }
        if (preview) {
            const url = URL.createObjectURL(input.files[0]);
            preview.src = url; preview.alt = input.files[0].name;
            preview.classList.remove('d-none');
            preview.onload = () => URL.revokeObjectURL(url);
        }
    });

    form.addEventListener('submit', (e) => {
        if (!validateImage() || !form.checkValidity()) {
            e.preventDefault();
            form.classList.add('was-validated');
            form.reportValidity();
            input.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });
});