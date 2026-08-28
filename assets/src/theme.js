/**
 * EcoBloom - Strength in Every Cycle
 * Main Interactive JavaScript
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Sticky Navbar Scroll Effect
    const navbar = document.querySelector('.ecobloom-navbar');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 200) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

    // 2. My Account Hover Dropdown with Buffer (for Desktop & Touch fallback)
    const accountDropdown = document.querySelector('.account-dropdown');
    const dropdownMenu = accountDropdown?.querySelector('.dropdown-menu');
    let dropdownTimeout;
    if (accountDropdown && dropdownMenu) {
        const openMenu = () => {
            clearTimeout(dropdownTimeout);
            accountDropdown.classList.add('show');
        };
        const closeMenu = () => {
            dropdownTimeout = setTimeout(() => {
                accountDropdown.classList.remove('show');
            }, 350);
        };
        accountDropdown.addEventListener('mouseenter', openMenu);
        accountDropdown.addEventListener('mouseleave', closeMenu);
        dropdownMenu.addEventListener('mouseenter', openMenu);
        dropdownMenu.addEventListener('mouseleave', closeMenu);
    }

    // 3. Side Opening Cart State & Interactivity
    const cartBadge = document.getElementById('cartBadgeCount');
    const cartItemsContainer = document.getElementById('cartItemsList');
    const cartSubtotalEl = document.getElementById('cartSubtotal');
    const cartTotalEl = document.getElementById('cartTotalPrice');

    let cartState = [
        {
            id: 1,
            name: 'EcoBloom Soft Menstrual Cup - Medium',
            price: 1499,
            qty: 1,
            image: 'images/banner_cup.png'
        }
    ];

    // Function to render side cart contents
    function renderSideCart() {
        if (!cartItemsContainer) return;

        if (cartState.length === 0) {
            cartItemsContainer.innerHTML = `
                <div class="cart-empty-state">
                    <div class="cart-empty-icon"><i class="bi bi-bag-x"></i></div>
                    <h5 class="fw-bold mb-2">Your Bag is Empty</h5>
                    <p class="text-muted small mb-4">Discover reliable comfort for every stage of life.</p>
                    <a href="#shop-section" class="btn btn-ecobloom-primary btn-sm" data-bs-dismiss="offcanvas">Start Shopping</a>
                </div>
            `;
            if (cartBadge) cartBadge.textContent = '0';
            if (cartSubtotalEl) cartSubtotalEl.textContent = '₹0';
            if (cartTotalEl) cartTotalEl.textContent = '₹0';
            return;
        }

        let totalItems = 0;
        let subtotal = 0;
        cartItemsContainer.innerHTML = '';

        cartState.forEach(item => {
            totalItems += item.qty;
            subtotal += item.price * item.qty;

            const row = document.createElement('div');
            row.className = 'cart-item-row';
            row.innerHTML = `
                <img src="${item.image}" alt="${item.name}" class="cart-item-img">
                <div class="cart-item-details">
                    <div class="cart-item-title">${item.name}</div>
                    <div class="cart-item-price">₹${item.price.toLocaleString('en-IN')}</div>
                    <div class="cart-item-qty">
                        <button class="qty-btn" onclick="updateCartQty(${item.id}, -1)" title="Decrease">-</button>
                        <span class="px-2 fw-semibold small">${item.qty}</span>
                        <button class="qty-btn" onclick="updateCartQty(${item.id}, 1)" title="Increase">+</button>
                    </div>
                </div>
                <button class="btn btn-link text-muted p-1" onclick="removeCartItem(${item.id})" title="Remove item">
                    <i class="bi bi-trash3"></i>
                </button>
            `;
            cartItemsContainer.appendChild(row);
        });

        if (cartBadge) cartBadge.textContent = totalItems.toString();
        if (cartSubtotalEl) cartSubtotalEl.textContent = `₹${subtotal.toLocaleString('en-IN')}`;
        if (cartTotalEl) cartTotalEl.textContent = `₹${subtotal.toLocaleString('en-IN')}`;
    }

    // Global helper methods for inline button clicks in cart
    window.updateCartQty = function(id, delta) {
        const item = cartState.find(i => i.id === id);
        if (item) {
            item.qty += delta;
            if (item.qty <= 0) {
                cartState = cartState.filter(i => i.id !== id);
            }
            renderSideCart();
        }
    };

    window.removeCartItem = function(id) {
        cartState = cartState.filter(i => i.id !== id);
        renderSideCart();
    };

    window.addSampleCupToCart = function() {
        const existing = cartState.find(i => i.id === 1);
        if (existing) {
            existing.qty += 1;
        } else {
            cartState.push({
                id: 1,
                name: 'EcoBloom Soft Menstrual Cup - Medium',
                price: 1499,
                qty: 1,
                image: 'images/banner_cup.png'
            });
        }
        renderSideCart();
        // Automatically open side cart to delight user
        const sideCartEl = document.getElementById('sideCart');
        if (sideCartEl && window.bootstrap) {
            const bsOffcanvas = new bootstrap.Offcanvas(sideCartEl);
            bsOffcanvas.show();
        }
    };

    // Initial render
    renderSideCart();

    // 4. Testimonials Manual & Automatic Scrolling Carousel
    const track = document.getElementById('testimonialsTrack');
    const prevBtn = document.getElementById('testimonialPrevBtn');
    const nextBtn = document.getElementById('testimonialNextBtn');
    const dots = document.querySelectorAll('#testimonialDots .carousel-dot');
    const sliderWrapper = document.querySelector('.testimonials-slider-wrapper');

    if (track) {
        const getCardWidth = () => {
            const firstCard = track.querySelector('.testimonial-slide-item');
            if (!firstCard) return 300;
            const cardRect = firstCard.getBoundingClientRect();
            const trackStyle = window.getComputedStyle(track);
            const gap = parseFloat(trackStyle.gap) || 19;
            return cardRect.width + gap;
        };

        const updateActiveDot = () => {
            const cardWidth = getCardWidth();
            const maxScroll = track.scrollWidth - track.clientWidth;
            let activeIndex = 0;
            if (maxScroll > 0 && track.scrollLeft >= maxScroll - 10) {
                activeIndex = dots.length - 1;
            } else {
                activeIndex = Math.round(track.scrollLeft / cardWidth);
            }
            dots.forEach((dot, idx) => {
                dot.classList.toggle('active', idx === activeIndex);
            });
        };

        track.addEventListener('scroll', updateActiveDot);

        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                track.scrollBy({ left: -getCardWidth(), behavior: 'smooth' });
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                const maxScroll = track.scrollWidth - track.clientWidth;
                if (track.scrollLeft >= maxScroll - 10) {
                    track.scrollTo({ left: 0, behavior: 'smooth' });
                } else {
                    track.scrollBy({ left: getCardWidth(), behavior: 'smooth' });
                }
            });
        }

        dots.forEach((dot, idx) => {
            dot.addEventListener('click', () => {
                track.scrollTo({ left: idx * getCardWidth(), behavior: 'smooth' });
            });
        });

        // Auto-Scroll Loop (every 3.5s)
        let autoScrollTimer = null;
        const startAutoScroll = () => {
            stopAutoScroll();
            autoScrollTimer = setInterval(() => {
                const maxScroll = track.scrollWidth - track.clientWidth;
                if (maxScroll <= 0) return;
                if (track.scrollLeft >= maxScroll - 10) {
                    track.scrollTo({ left: 0, behavior: 'smooth' });
                } else {
                    track.scrollBy({ left: getCardWidth(), behavior: 'smooth' });
                }
            }, 3500);
        };

        const stopAutoScroll = () => {
            if (autoScrollTimer) {
                clearInterval(autoScrollTimer);
                autoScrollTimer = null;
            }
        };

        if (sliderWrapper) {
            sliderWrapper.addEventListener('mouseenter', stopAutoScroll);
            sliderWrapper.addEventListener('mouseleave', startAutoScroll);
            sliderWrapper.addEventListener('touchstart', stopAutoScroll, { passive: true });
            sliderWrapper.addEventListener('touchend', startAutoScroll, { passive: true });
        }

        startAutoScroll();
    }

    // 5. Newsletter Form Submission Handling
    const subscribeForm = document.getElementById('newsletterForm');
    if (subscribeForm) {
        subscribeForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const emailInput = subscribeForm.querySelector('input[type="email"]');
            if (emailInput && emailInput.value) {
                alert(`Thank you for subscribing, ${emailInput.value}! Welcome to the EcoBloom community. 🌸`);
                emailInput.value = '';
            }
        });
    }

    // 6. Desktop Direct Navigation for Dropdown Toggle Links (About Us, Our Products, etc.)
    const dropdownLinks = document.querySelectorAll('.navbar-nav .dropdown-toggle[href]');
    dropdownLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            if (window.innerWidth >= 992) {
                const href = this.getAttribute('href');
                if (href && href !== '#' && !href.startsWith('javascript')) {
                    e.preventDefault();
                    e.stopPropagation();
                    window.location.href = href;
                }
            }
        }, true);
    });

    // 7. Shop Category Filter & Interactive Grid
    const filterButtons = document.querySelectorAll('.ecobloom-filter-btn, [data-filter]');
    const productCards = document.querySelectorAll('.product-item-card, [data-category]');
    if (filterButtons.length > 0 && productCards.length > 0) {
        filterButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                filterButtons.forEach(b => {
                    b.classList.remove('active', 'btn-primary');
                    b.classList.add('btn-outline-dark');
                });
                btn.classList.add('active', 'btn-primary');
                btn.classList.remove('btn-outline-dark');

                const filterValue = (btn.getAttribute('data-filter') || btn.textContent.trim()).toLowerCase();
                productCards.forEach(card => {
                    const cardCat = (card.getAttribute('data-category') || '').toLowerCase();
                    if (filterValue === 'all' || filterValue === 'all products' || cardCat.includes(filterValue)) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
    }

    // 8. Product Inner Slider Thumbnails & Image Zoom
    const mainProductImg = document.getElementById('mainProductImg');
    const thumbnails = document.querySelectorAll('.product-thumb-item, .thumb-item');
    if (mainProductImg && thumbnails.length > 0) {
        thumbnails.forEach(thumb => {
            thumb.addEventListener('click', () => {
                thumbnails.forEach(t => t.classList.remove('active', 'border-magenta'));
                thumb.classList.add('active', 'border-magenta');
                const newSrc = thumb.getAttribute('data-img') || thumb.querySelector('img')?.getAttribute('src');
                if (newSrc) {
                    mainProductImg.style.opacity = '0.3';
                    setTimeout(() => {
                        mainProductImg.src = newSrc;
                        mainProductImg.style.opacity = '1';
                    }, 150);
                }
            });
        });
    }

    // 9. Product Quantity Controller
    const productQtyInput = document.getElementById('productQtyInput');
    const qtyMinusBtn = document.getElementById('qtyMinusBtn');
    const qtyPlusBtn = document.getElementById('qtyPlusBtn');
    if (productQtyInput) {
        if (qtyMinusBtn) {
            qtyMinusBtn.addEventListener('click', () => {
                let current = parseInt(productQtyInput.value, 10) || 1;
                if (current > 1) {
                    productQtyInput.value = current - 1;
                }
            });
        }
        if (qtyPlusBtn) {
            qtyPlusBtn.addEventListener('click', () => {
                let current = parseInt(productQtyInput.value, 10) || 1;
                productQtyInput.value = current + 1;
            });
        }
    }

    // 10. Image Zoom Modal Trigger
    window.openImageZoom = function() {
        const modalImg = document.getElementById('zoomedModalImg');
        const mainImg = document.getElementById('mainProductImg');
        const zoomModalEl = document.getElementById('imageZoomModal');
        if (modalImg && mainImg && zoomModalEl && window.bootstrap) {
            modalImg.src = mainImg.src;
            const modal = new bootstrap.Modal(zoomModalEl);
            modal.show();
        }
    };

    // 11. Shopping Bag / Add to Bag Button -> redirect to Cart Page
    window.goToCartPage = function() {
        window.location.href = 'cart.html';
    };

    // 12. Dynamic Cup Size Dropdown Select
    const cupSizeSelect = document.getElementById('cupSizeSelect');
    const productTitleEl = document.querySelector('h1.font-serif');
    if (cupSizeSelect && productTitleEl) {
        cupSizeSelect.addEventListener('change', (e) => {
            const selectedSize = e.target.value;
            productTitleEl.textContent = `EcoBloom Soft Cup — Size ${selectedSize}`;
        });
    }

    // 13. Interactive Shop Pagination
    const pageLinks = document.querySelectorAll('.ecobloom-pagination .page-item:not(.disabled) .page-link');
    if (pageLinks.length > 0) {
        pageLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const parentLi = link.closest('.page-item');
                if (parentLi) {
                    document.querySelectorAll('.ecobloom-pagination .page-item').forEach(li => li.classList.remove('active'));
                    parentLi.classList.add('active');
                    const shopGrid = document.querySelector('.product-item-card')?.closest('section');
                    if (shopGrid) {
                        shopGrid.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                }
            });
        });
    }
});

