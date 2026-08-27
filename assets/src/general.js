// Add highlighted text to the second half of the blog (detail) hero title
document.addEventListener('DOMContentLoaded', function () {
    const blogHeroSection = document.querySelector('.blog-hero-section');

    if (!blogHeroSection) {
        return;
    }

    const title = blogHeroSection.querySelector('.page-hero-title');

    if (!title) {
        return;
    }

    const text = title.textContent.trim();
    const words = text.split(/\s+/);

    const splitAt = Math.ceil(words.length / 2);

    const normalText = words.slice(0, splitAt).join(' ');
    const highlightedText = words.slice(splitAt).join(' ');

    title.innerHTML = normalText + ' <span class="text-magenta">' + highlightedText + '</span>';
});