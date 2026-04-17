// Enhanced JavaScript for animations and interactions
document.addEventListener('DOMContentLoaded', function() {
    
    // Counter Animation for Statistics
    const counters = document.querySelectorAll('.stat-number');
    const speed = 200;
    
    const animateCounter = (counter) => {
        const target = +counter.getAttribute('data-target');
        const increment = target / speed;
        
        const updateCount = () => {
            const count = +counter.innerText;
            
            if(count < target) {
                counter.innerText = Math.ceil(count + increment);
                setTimeout(updateCount, 10);
            } else {
                counter.innerText = target.toLocaleString();
            }
        };
        
        updateCount();
    };
    
    // Intersection Observer for counter animation
    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if(entry.isIntersecting) {
                animateCounter(entry.target);
                counterObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });
    
    counters.forEach(counter => {
        counterObserver.observe(counter);
    });
    
    // Smooth Scroll for navigation links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if(target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Scroll Indicator hide/show
    const scrollIndicator = document.querySelector('.scroll-indicator');
    if(scrollIndicator) {
        window.addEventListener('scroll', () => {
            if(window.scrollY > 100) {
                scrollIndicator.style.opacity = '0';
            } else {
                scrollIndicator.style.opacity = '1';
            }
        });
    }
    
    // Parallax effect for hero section
    const hero = document.querySelector('.hero');
    if(hero) {
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const parallax = scrolled * 0.5;
            hero.style.transform = `translateY(${parallax}px)`;
        });
    }
    
    // Add animation to feature cards on scroll
    const featureCards = document.querySelectorAll('.feature-card');
    const featureObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if(entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.style.animation = 'slideInUp 0.6s ease-out forwards';
                }, index * 100);
                featureObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });
    
    featureCards.forEach(card => {
        card.style.opacity = '0';
        featureObserver.observe(card);
    });
    
    // Add animation to destination cards on scroll
    const destinationCards = document.querySelectorAll('.destination-card');
    const destinationObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if(entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.style.animation = 'fadeInUp 0.6s ease-out forwards';
                }, index * 150);
                destinationObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });
    
    destinationCards.forEach(card => {
        card.style.opacity = '0';
        destinationObserver.observe(card);
    });
    
    // Add animation to testimonial cards on scroll
    const testimonialCards = document.querySelectorAll('.testimonial-card');
    const testimonialObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if(entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.style.animation = 'slideInUp 0.6s ease-out forwards';
                }, index * 200);
                testimonialObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });
    
    testimonialCards.forEach(card => {
        card.style.opacity = '0';
        testimonialObserver.observe(card);
    });
    
    // Form validation for newsletter
    const newsletterForm = document.querySelector('.newsletter-form');
    if(newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input[type="email"]').value;
            
            if(validateEmail(email)) {
                // Show success message
                showNotification('Thank you for subscribing!', 'success');
                this.reset();
            } else {
                showNotification('Please enter a valid email address', 'error');
            }
        });
    }
    
    // Form validation for contact form
    const contactForm = document.querySelector('.contact-form form');
    if(contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const name = this.querySelector('input[type="text"]').value;
            const email = this.querySelector('input[type="email"]').value;
            const message = this.querySelector('textarea').value;
            
            if(name && validateEmail(email) && message) {
                showNotification('Message sent successfully!', 'success');
                this.reset();
            } else {
                showNotification('Please fill in all fields correctly', 'error');
            }
        });
    }
    
    // Email validation helper
    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
    
    // Notification system
    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.textContent = message;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 10px;
            color: white;
            font-weight: 500;
            z-index: 10000;
            animation: slideInRight 0.3s ease-out;
            ${type === 'success' ? 'background: linear-gradient(45deg, #28a745, #20c997);' : 'background: linear-gradient(45deg, #dc3545, #c82333);'}
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.animation = 'slideOutRight 0.3s ease-out';
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }
    
    // Add hover effect to cards
    const cards = document.querySelectorAll('.feature-card, .destination-card, .testimonial-card, .stat-card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-10px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
    
    // Dynamic navbar background on scroll
    const header = document.querySelector('header');
    if(header) {
        window.addEventListener('scroll', () => {
            if(window.scrollY > 50) {
                header.style.background = 'linear-gradient(90deg, rgba(0,123,255,0.95), rgba(0,198,255,0.95))';
                header.style.backdropFilter = 'blur(10px)';
            } else {
                header.style.background = 'linear-gradient(90deg, #007bff, #00c6ff)';
        }
    });
}, { threshold: 0.5 });
    
counters.forEach(counter => {
    counterObserver.observe(counter);
});
    
// Smooth Scroll for navigation links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if(target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});
    
// Scroll Indicator hide/show
const scrollIndicator = document.querySelector('.scroll-indicator');
if(scrollIndicator) {
    window.addEventListener('scroll', () => {
        if(window.scrollY > 100) {
            scrollIndicator.style.opacity = '0';
        } else {
            scrollIndicator.style.opacity = '1';
        }
    });
}
    
// Parallax effect for hero section
const hero = document.querySelector('.hero');
if(hero) {
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        const parallax = scrolled * 0.5;
        hero.style.transform = `translateY(${parallax}px)`;
    });
}
    
// Add animation to feature cards on scroll
const featureCards = document.querySelectorAll('.feature-card');
const featureObserver = new IntersectionObserver((entries) => {
    entries.forEach((entry, index) => {
        if(entry.isIntersecting) {
            setTimeout(() => {
                entry.target.style.animation = 'slideInUp 0.6s ease-out forwards';
            }, index * 100);
            featureObserver.unobserve(entry.target);
        }
    });
}, { threshold: 0.1 });
    
featureCards.forEach(card => {
    card.style.opacity = '0';
    featureObserver.observe(card);
});
    
// Add animation to destination cards on scroll
const destinationCards = document.querySelectorAll('.destination-card');
const destinationObserver = new IntersectionObserver((entries) => {
    entries.forEach((entry, index) => {
        if(entry.isIntersecting) {
            setTimeout(() => {
                entry.target.style.animation = 'fadeInUp 0.6s ease-out forwards';
            }, index * 150);
            destinationObserver.unobserve(entry.target);
        }
    });
}, { threshold: 0.1 });
    
destinationCards.forEach(card => {
    card.style.opacity = '0';
    destinationObserver.observe(card);
});
    
// Add animation to testimonial cards on scroll
const testimonialCards = document.querySelectorAll('.testimonial-card');
const testimonialObserver = new IntersectionObserver((entries) => {
    entries.forEach((entry, index) => {
        if(entry.isIntersecting) {
            setTimeout(() => {
                entry.target.style.animation = 'slideInUp 0.6s ease-out forwards';
            }, index * 200);
            testimonialObserver.unobserve(entry.target);
        }
    });
}, { threshold: 0.1 });
    
testimonialCards.forEach(card => {
    card.style.opacity = '0';
    testimonialObserver.observe(card);
});
    
// Form validation for newsletter
const newsletterForm = document.querySelector('.newsletter-form');
if(newsletterForm) {
    newsletterForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const email = this.querySelector('input[type="email"]').value;
        
        if(validateEmail(email)) {
            // Show success message
            showNotification('Thank you for subscribing!', 'success');
            this.reset();
        } else {
            showNotification('Please enter a valid email address', 'error');
        }
    });
}
    
// Form validation for contact form
const contactForm = document.querySelector('.contact-form form');
if(contactForm) {
    contactForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const name = this.querySelector('input[type="text"]').value;
        const email = this.querySelector('input[type="email"]').value;
        const message = this.querySelector('textarea').value;
        
        if(name && validateEmail(email) && message) {
            showNotification('Message sent successfully!', 'success');
            this.reset();
        } else {
            showNotification('Please fill in all fields correctly', 'error');
        }
    });
}
    
// Email validation helper
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}
    
// Notification system
function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        border-radius: 10px;
        color: white;
        font-weight: 500;
        z-index: 10000;
        animation: slideInRight 0.3s ease-out;
        ${type === 'success' ? 'background: linear-gradient(45deg, #28a745, #20c997);' : 'background: linear-gradient(45deg, #dc3545, #c82333);'}
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.3s ease-out';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}
    
// Add hover effect to cards
const cards = document.querySelectorAll('.feature-card, .destination-card, .testimonial-card, .stat-card');
cards.forEach(card => {
    card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-10px) scale(1.02)';
    });
    
    card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0) scale(1)';
    });
});
    
// Dynamic navbar background on scroll
const header = document.querySelector('header');
if(header) {
    window.addEventListener('scroll', () => {
        if(window.scrollY > 50) {
            header.style.background = 'linear-gradient(90deg, rgba(0,123,255,0.95), rgba(0,198,255,0.95))';
            header.style.backdropFilter = 'blur(10px)';
        } else {
            header.style.background = 'linear-gradient(90deg, #007bff, #00c6ff)';
            header.style.backdropFilter = 'none';
        }
    });
}
    
// Add typing effect to hero title (optional enhancement)
const heroTitle = document.querySelector('.hero h1');
if(heroTitle) {
    const text = heroTitle.textContent;
    heroTitle.textContent = '';
    let index = 0;
    
    function typeWriter() {
        if(index < text.length) {
            heroTitle.textContent += text.charAt(index);
            index++;
            setTimeout(typeWriter, 50);
        }
    }
    
    setTimeout(typeWriter, 500);
}
    
// Loading Screen Removal
setTimeout(() => {
    const loadingScreen = document.querySelector('.loading-screen');
    if (loadingScreen) {
        loadingScreen.style.display = 'none';
    }
}, 1500);

// Enhanced Scroll Animations
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver(function(entries) {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('visible');
            
            // Animate Progress Bars
            if (entry.target.classList.contains('progress-item')) {
                const progressFill = entry.target.querySelector('.progress-fill');
                if (progressFill) {
                    const progress = progressFill.getAttribute('data-progress');
                    setTimeout(() => {
                        progressFill.style.width = progress + '%';
                    }, 200);
                }
            }
            
            // Animate Timeline Items
            if (entry.target.classList.contains('timeline-item')) {
                const timelineContent = entry.target.querySelector('.timeline-content');
                if (timelineContent) {
                    timelineContent.style.animation = 'none';
                    setTimeout(() => {
                        timelineContent.style.animation = '';
                    }, 100);
                }
            }
        }
    });
}, observerOptions);

// Observe Elements
document.querySelectorAll('.fade-in-up, .fade-in-left, .fade-in-right, .scale-in, .timeline-item, .progress-item').forEach(el => {
    observer.observe(el);
});

// Enhanced Statistics Counter
const statNumbers = document.querySelectorAll('.stat-number');
const statsObserver = new IntersectionObserver(function(entries) {
    entries.forEach(entry => {
        if (entry.isIntersecting && !entry.target.classList.contains('counted')) {
            const target = parseInt(entry.target.getAttribute('data-target'));
            const duration = 2000;
            const increment = target / (duration / 16);
            let current = 0;
            
            entry.target.classList.add('counted');
            
            const updateCounter = () => {
                current += increment;
                if (current < target) {
                    entry.target.textContent = Math.floor(current).toLocaleString();
                    requestAnimationFrame(updateCounter);
                } else {
                    entry.target.textContent = target.toLocaleString();
                }
            };
            
            updateCounter();
        }
    });
}, { threshold: 0.5 });

statNumbers.forEach(stat => {
    statsObserver.observe(stat);
});

// Smooth Scrolling for Anchor Links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Enhanced Navbar Background on Scroll
const navbar = document.querySelector('header');
if (navbar) {
    }

    // Enhanced Parallax Effect
    window.addEventListener('scroll', function() {
        const scrolled = window.pageYOffset;
        const parallaxElements = document.querySelectorAll('.hero-content, .feature-icon');
        
        parallaxElements.forEach(element => {
            const speed = element.classList.contains('hero-content') ? 0.5 : 0.3;
            const yPos = -(scrolled * speed);
            element.style.transform = `translateY(${yPos}px)`;
        });
    });

    // Advanced Cursor Trail Effect
    let cursorTrail = [];
    const maxTrailLength = 10;
    
    document.addEventListener('mousemove', function(e) {
        if (cursorTrail.length >= maxTrailLength) {
            const oldTrail = cursorTrail.shift();
            if (oldTrail && oldTrail.parentNode) {
                oldTrail.parentNode.removeChild(oldTrail);
            }
        }
        
        const trail = document.createElement('div');
        trail.style.cssText = `
            position: fixed;
            left: ${e.clientX}px;
            top: ${e.clientY}px;
            width: 8px;
            height: 8px;
            background: radial-gradient(circle, rgba(102,126,234,0.8), transparent);
            border-radius: 50%;
            pointer-events: none;
            z-index: 9999;
            animation: fadeOut 1s ease-out forwards;
        `;
        
        document.body.appendChild(trail);
        cursorTrail.push(trail);
        
        setTimeout(() => {
            if (trail.parentNode) {
                trail.parentNode.removeChild(trail);
            }
        }, 1000);
    });

    console.log('🚀 Travel Agency - Advanced Features Loaded Successfully!');
});

// Add CSS animations dynamically
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(100px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes slideOutRight {
        from {
            opacity: 1;
            transform: translateX(0);
        }
        to {
            opacity: 0;
            transform: translateX(100px);
        }
    }
`;
document.head.appendChild(style);
