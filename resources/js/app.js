
import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Pointer-tracked warm spotlight that follows the cursor over premium cards.
let spotlightScheduled = false;
document.addEventListener('pointermove', (event) => {
    if (spotlightScheduled) {
        return;
    }

    spotlightScheduled = true;

    requestAnimationFrame(() => {
        spotlightScheduled = false;

        const sheen = event.target.closest('.card-sheen');

        if (!sheen) {
            return;
        }

        const rect = sheen.getBoundingClientRect();

        sheen.style.setProperty('--mx', `${event.clientX - rect.left}px`);
        sheen.style.setProperty('--my', `${event.clientY - rect.top}px`);
    });
}, { passive: true });

// Scroll progress hairline at the top of the public pages.
const scrollProgress = document.getElementById('scroll-progress');

if (scrollProgress) {
    let queued = false;

    window.addEventListener('scroll', () => {
        if (queued) {
            return;
        }

        queued = true;

        requestAnimationFrame(() => {
            queued = false;

            const height = document.documentElement.scrollHeight - window.innerHeight;

            scrollProgress.style.width = height > 0 ? `${Math.round((window.scrollY / height) * 100)}%` : '0%';
        });
    }, { passive: true });
}

// Scroll-in reveal: `.animate-*` classes are paused (hidden) in CSS until the
// element scrolls into view, then `.is-visible` lets the animation play. This
// guarantees the staggered show of cards even deep in the page, and reduced
// motion — handled in CSS — shows everything instantly.
document.documentElement.classList.add('io-gated');

const markVisible = (element) => element.classList.add('is-visible');

if (!('IntersectionObserver' in window)) {
    document.querySelectorAll('.animate-fade-in-up, .animate-fade-in, .animate-slide-up, .animate-slide-down, .animate-scale-in').forEach(markVisible);
} else {
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) {
                return;
            }

            entry.target.classList.add('is-visible');
            revealObserver.unobserve(entry.target);
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -6% 0px' });

    const revealAll = () => document.querySelectorAll('.animate-fade-in-up:not(.is-visible), .animate-fade-in:not(.is-visible), .animate-slide-up:not(.is-visible), .animate-slide-down:not(.is-visible), .animate-scale-in:not(.is-visible)').forEach((element) => revealObserver.observe(element));

    revealAll();

    // Alpine toggles x-show, which changes intersection state — sweep again.
    setTimeout(revealAll, 900);

    // Safety net: never leave content hidden because IO did not fire.
    setTimeout(() => {
        document.querySelectorAll('.animate-fade-in-up:not(.is-visible), .animate-fade-in:not(.is-visible), .animate-slide-up:not(.is-visible), .animate-slide-down:not(.is-visible), .animate-scale-in:not(.is-visible)').forEach(markVisible);
    }, 2000);
}

Alpine.data('heroCarousel', (count) => ({
    index: 0,
    count,
    timer: null,
    reduced: false,
    touchStartX: null,

    init() {
        this.reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        this.start();
    },

    start() {
        this.stop();

        if (this.reduced || this.count < 2) {
            return;
        }

        this.timer = setInterval(() => this.next(), 6500);
    },

    stop() {
        if (this.timer) {
            clearInterval(this.timer);
            this.timer = null;
        }
    },

    next() {
        this.index = (this.index + 1) % this.count;
    },

    prev() {
        this.index = (this.index - 1 + this.count) % this.count;
    },

    go(index) {
        this.index = index;
    },

    onTouchStart(event) {
        this.touchStartX = event.touches[0].clientX;
    },

    onTouchEnd(event) {
        if (this.touchStartX === null) {
            return;
        }

        const delta = event.changedTouches[0].clientX - this.touchStartX;
        this.touchStartX = null;

        if (Math.abs(delta) >= 40) {
            if (delta < 0) {
                this.next();
            } else {
                this.prev();
            }
        }
    },
}));

Alpine.start();
