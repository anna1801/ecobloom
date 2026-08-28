// Archive gallery filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const filterBtns = document.querySelectorAll('.filter-btn');
    const galleryItems = document.querySelectorAll('.gallery-item');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active classes
            filterBtns.forEach(b => {
                b.classList.remove('btn-primary', 'active');
                b.classList.add('btn-outline-secondary', 'bg-light', 'text-dark');
            });
            // Add active class to clicked
            this.classList.remove('btn-outline-secondary', 'bg-light', 'text-dark');
            this.classList.add('btn-primary', 'active');

            const filter = this.getAttribute('data-filter');

            galleryItems.forEach(item => {
                if(filter === 'all' || item.getAttribute('data-category') === filter) {
                    item.style.display = 'block';
                    // small animation
                    item.style.animation = 'fadeIn 0.5s ease';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
});

// Lightbox for gallery items in the inner page
document.addEventListener('DOMContentLoaded', function() {

    const lightbox = document.getElementById('lightbox');

    if (!lightbox) {
        return;
    }

    const lbImg = document.getElementById('lbImg');
    const lbCounter = document.getElementById('lbCounter');
    const items = document.querySelectorAll('.masonry-item');
    let currentIdx = 0;
    let zoom = 1;

    function openLb(idx) {
        currentIdx = idx;
        lbImg.src = items[currentIdx].querySelector('img').src;
        lbImg.style.transform = 'scale(1)';
        zoom = 1;
        lbCounter.textContent = (currentIdx + 1) + ' / ' + items.length;
        lightbox.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeLb() {
        lightbox.classList.remove('active');
        document.body.style.overflow = '';
        lbImg.src = '';
        zoom = 1;
        lbImg.style.transform = 'scale(1)';
    }

    // Handle item clicks (removing the inline onclick from html)
    items.forEach((item, i) => {
        // Remove the inline onclick attribute
        item.removeAttribute('onclick');
        item.addEventListener('click', () => openLb(i));
    });

    document.getElementById('lbClose').addEventListener('click', closeLb);
    lightbox.addEventListener('click', e => { if (e.target === lightbox) closeLb(); });

    document.getElementById('lbPrev').addEventListener('click', e => {
        e.stopPropagation();
        currentIdx = (currentIdx - 1 + items.length) % items.length;
        lbImg.src = items[currentIdx].querySelector('img').src;
        lbImg.style.transform = 'scale(1)';
        zoom = 1;
        lbCounter.textContent = (currentIdx + 1) + ' / ' + items.length;
    });

    document.getElementById('lbNext').addEventListener('click', e => {
        e.stopPropagation();
        currentIdx = (currentIdx + 1) % items.length;
        lbImg.src = items[currentIdx].querySelector('img').src;
        lbImg.style.transform = 'scale(1)';
        zoom = 1;
        lbCounter.textContent = (currentIdx + 1) + ' / ' + items.length;
    });

    // Zoom feature
    lbImg.addEventListener('click', e => {
        e.stopPropagation();
        zoom = zoom === 1 ? 1.5 : 1;
        lbImg.style.transform = `scale(${zoom})`;
        lbImg.style.cursor = zoom === 1 ? 'zoom-in' : 'zoom-out';
    });

});