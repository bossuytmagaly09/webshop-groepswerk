document.addEventListener('DOMContentLoaded', () => {
    // 1. Hero Entrance
    gsap.from(".hero-el", {
        y: 30,
        opacity: 0,
        duration: 1,
        stagger: 0.2,
        ease: "power3.out"
    });

    // 2. Card Scroll Animation
    const cards = document.querySelectorAll('.category-card, .product-card');
    cards.forEach((card, i) => {
        gsap.from(card, {
            scrollTrigger: {
                trigger: card,
                start: "top bottom-=100px",
            },
            y: 20,
            opacity: 0,
            duration: 0.8,
            delay: i * 0.1,
            ease: "power2.out"
        });
    });

    // 3. Hover Micro-interaction for buttons
    const primaryBtns = document.querySelectorAll('button');
    primaryBtns.forEach(btn => {
        btn.addEventListener('mouseenter', () => gsap.to(btn, { scale: 1.02, duration: 0.3 }));
        btn.addEventListener('mouseleave', () => gsap.to(btn, { scale: 1, duration: 0.3 }));
    });
});