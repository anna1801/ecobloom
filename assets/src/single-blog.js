// Reading Progress Bar
window.addEventListener('scroll', () => {
    const article = document.getElementById('articleBody');
    if (!article) return;
    const rect   = article.getBoundingClientRect();
    const top    = article.offsetTop;
    const height = article.offsetHeight;
    const scrolled = window.scrollY - top;
    const pct = Math.min(100, Math.max(0, (scrolled / height) * 100));
    document.getElementById('readingProgress').style.width = pct + '%';
});

// Highlight active TOC link on scroll
const tocLinks = document.querySelectorAll('.toc-link');
const sections = ['section1','section2','section3','section4','section5'].map(id => document.getElementById(id)).filter(Boolean);
window.addEventListener('scroll', () => {
    let current = '';
    sections.forEach(s => { if (window.scrollY >= s.offsetTop - 120) current = s.id; });
    tocLinks.forEach(l => { l.classList.toggle('active', l.getAttribute('href') === '#' + current); });
});

// Copy to Clipboard functionality for share button
document.addEventListener('click', function (e) {
    const button = e.target.closest('.share-btn.cp');

    if (!button) return;

    const url = button.dataset.url;

    navigator.clipboard.writeText(url).then(() => {
        const icon = button.querySelector('i');

        icon.classList.remove('bi-link-45deg');
        icon.classList.add('bi-check2');

        setTimeout(() => {
            icon.classList.remove('bi-check2');
            icon.classList.add('bi-link-45deg');
        }, 1500);
    });
});