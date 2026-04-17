// Enhanced Login Page JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Initialize form validation and interactions
    initializeLoginForm();
    initializeParticles();
    initializeFormValidation();
    initializeMicroInteractions();
});

// ===== Login Form Initialization =====
function initializeLoginForm() {
    const loginForm = document.getElementById('loginForm');
    const loginBtn = document.getElementById('loginBtn');
    
    if (loginForm) {
        loginForm.addEventListener('submit', handleLoginSubmit);
    }
    
    // Add input animations
    const inputs = document.querySelectorAll('.form-group input');
    inputs.forEach(input => {
        input.addEventListener('focus', handleInputFocus);
        input.addEventListener('blur', handleInputBlur);
        input.addEventListener('input', handleInputChange);
    });
}

// ===== Particle Animation System =====
function initializeParticles() {
    // Animate existing particles
    const particles = document.querySelectorAll('.particle');
    particles.forEach((particle, index) => {
        animateParticle(particle, index);
    });
    
    // Animate advanced particles
    const glowParticles = document.querySelectorAll('.glow-particle');
    glowParticles.forEach((particle, index) => {
        animateGlowParticle(particle, index);
    });
    
    const orbitalParticles = document.querySelectorAll('.orbital-particle');
    orbitalParticles.forEach((particle, index) => {
        animateOrbitalParticle(particle, index);
    });
    
    const waveParticles = document.querySelectorAll('.wave-particle');
    waveParticles.forEach((particle, index) => {
        animateWaveParticle(particle, index);
    });
}

function animateParticle(particle, index) {
    const duration = 4 + Math.random() * 4;
    const delay = index * 0.5;
    
    particle.style.animation = `float ${duration}s ease-in-out ${delay}s infinite`;
    
    particle.addEventListener('mouseenter', function() {
        this.style.transform = 'scale(2)';
        this.style.boxShadow = '0 0 20px rgba(255,255,255,1)';
    });
    
    particle.addEventListener('mouseleave', function() {
        this.style.transform = 'scale(1)';
        this.style.boxShadow = '0 0 10px rgba(255,255,255,0.5)';
    });
}

function animateGlowParticle(particle, index) {
    const duration = 2 + Math.random() * 2;
    const delay = index * 0.3;
    
    particle.style.animation = `glow ${duration}s ease-in-out ${delay}s infinite`;
}

function animateOrbitalParticle(particle, index) {
    const duration = 6 + Math.random() * 4;
    const delay = index * 0.5;
    const reverse = index % 2 === 1;
    
    particle.style.animation = `orbit ${duration}s linear ${delay}s ${reverse ? 'reverse' : 'normal'} infinite`;
}

function animateWaveParticle(particle, index) {
    const duration = 3 + Math.random() * 2;
    const delay = index * 0.4;
    
    particle.style.animation = `wave ${duration}s ease-in-out ${delay}s infinite`;
}

// ===== Form Validation System =====
function initializeFormValidation() {
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    
    if (emailInput) {
        emailInput.addEventListener('blur', validateEmail);
    }
    
    if (passwordInput) {
        passwordInput.addEventListener('blur', validatePassword);
    }
}

function validateEmail() {
    const emailInput = document.getElementById('email');
    const email = emailInput.value.trim();
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    if (email && !emailRegex.test(email)) {
        showInputError(emailInput, 'Please enter a valid email address');
        return false;
    } else if (email && emailRegex.test(email)) {
        showInputSuccess(emailInput);
        return true;
    }
    
    clearInputStatus(emailInput);
    return false;
}

function validatePassword() {
    const passwordInput = document.getElementById('password');
    const password = passwordInput.value;
    
    if (password && password.length < 6) {
        showInputError(passwordInput, 'Password must be at least 6 characters');
        return false;
    } else if (password && password.length >= 6) {
        showInputSuccess(passwordInput);
        return true;
    }
    
    clearInputStatus(passwordInput);
    return false;
}

function showInputError(input, message) {
    input.style.borderColor = 'rgba(220, 53, 69, 0.8)';
    input.style.boxShadow = '0 0 0 3px rgba(220, 53, 69, 0.2)';
    input.style.animation = 'shake 0.5s ease-in-out';
    
    // Remove error message if exists
    const existingError = input.parentNode.querySelector('.error-message');
    if (existingError) {
        existingError.remove();
    }
    
    // Add error message
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.textContent = message;
    errorDiv.style.cssText = `
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.85rem;
        margin-top: 5px;
        font-weight: 500;
        background: rgba(220, 53, 69, 0.2);
        padding: 5px 10px;
        border-radius: 5px;
        backdrop-filter: blur(10px);
    `;
    input.parentNode.appendChild(errorDiv);
    
    setTimeout(() => {
        input.style.animation = '';
    }, 500);
}

function showInputSuccess(input) {
    input.style.borderColor = 'rgba(40, 167, 69, 0.8)';
    input.style.boxShadow = '0 0 0 3px rgba(40, 167, 69, 0.2)';
    
    // Remove any existing messages
    const existingMessage = input.parentNode.querySelector('.error-message');
    if (existingMessage) {
        existingMessage.remove();
    }
}

function clearInputStatus(input) {
    input.style.borderColor = 'rgba(255,255,255,0.2)';
    input.style.boxShadow = 'none';
    
    // Remove any existing messages
    const existingMessage = input.parentNode.querySelector('.error-message');
    if (existingMessage) {
        existingMessage.remove();
    }
}

// ===== Input Event Handlers =====
function handleInputFocus(e) {
    const input = e.target;
    const formGroup = input.closest('.form-group');
    
    formGroup.style.transform = 'scale(1.02)';
    input.style.transform = 'translateY(-3px) scale(1.02)';
    
    // Add focus glow effect
    createFocusGlow(input);
}

function handleInputBlur(e) {
    const input = e.target;
    const formGroup = input.closest('.form-group');
    
    formGroup.style.transform = 'scale(1)';
    input.style.transform = 'translateY(0) scale(1)';
    
    // Remove focus glow
    removeFocusGlow(input);
    
    // Validate on blur
    if (input.type === 'email') {
        validateEmail();
    } else if (input.type === 'password') {
        validatePassword();
    }
}

function handleInputChange(e) {
    const input = e.target;
    
    // Real-time validation feedback
    if (input.value.length > 0) {
        if (input.type === 'email') {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (emailRegex.test(input.value)) {
                showInputSuccess(input);
            } else {
                clearInputStatus(input);
            }
        } else if (input.type === 'password') {
            if (input.value.length >= 6) {
                showInputSuccess(input);
            } else {
                clearInputStatus(input);
            }
        }
    } else {
        clearInputStatus(input);
    }
}

// ===== Visual Effects =====
function createFocusGlow(input) {
    const glow = document.createElement('div');
    glow.className = 'input-glow';
    glow.style.cssText = `
        position: absolute;
        top: -2px;
        left: -2px;
        right: -2px;
        bottom: -2px;
        background: linear-gradient(45deg, transparent, rgba(255,255,255,0.3), transparent);
        border-radius: 15px;
        animation: rotate 2s linear infinite;
        pointer-events: none;
        z-index: -1;
    `;
    
    input.parentNode.appendChild(glow);
}

function removeFocusGlow(input) {
    const glow = input.parentNode.querySelector('.input-glow');
    if (glow) {
        glow.remove();
    }
}

// ===== Login Form Submission =====
function handleLoginSubmit(e) {
    e.preventDefault();
    
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const loginBtn = document.getElementById('loginBtn');
    
    // Validate inputs
    const isEmailValid = validateEmail();
    const isPasswordValid = validatePassword();
    
    if (!isEmailValid || !isPasswordValid) {
        // Shake the form
        const authCard = document.querySelector('.auth-card');
        authCard.style.animation = 'shake 0.5s ease-in-out';
        setTimeout(() => {
            authCard.style.animation = '';
        }, 500);
        
        showNotification('Please fix the errors before submitting', 'error');
        return;
    }
    
    // Show loading state
    loginBtn.classList.add('loading-state');
    loginBtn.disabled = true;
    
    // Simulate login process
    setTimeout(() => {
        // Remove loading state
        loginBtn.classList.remove('loading-state');
        loginBtn.disabled = false;
        
        // Show success animation
        const authCard = document.querySelector('.auth-card');
        authCard.classList.add('success-animation');
        
        showNotification('Login successful! Redirecting...', 'success');
        
        // Submit form normally
        e.target.submit();
    }, 2000);
}

// ===== Password Toggle =====
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleBtn = document.querySelector('.password-toggle');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleBtn.textContent = '🙈';
        toggleBtn.style.background = 'rgba(255,255,255,0.2)';
    } else {
        passwordInput.type = 'password';
        toggleBtn.textContent = '👁️';
        toggleBtn.style.background = 'none';
    }
    
    // Add animation
    toggleBtn.style.transform = 'translateY(-50%) scale(1.2)';
    setTimeout(() => {
        toggleBtn.style.transform = 'translateY(-50%) scale(1)';
    }, 200);
}

// ===== Social Login =====
function socialLogin(provider) {
    showNotification(`${provider} login coming soon!`, 'info');
    
    // Add ripple effect
    event.target.style.transform = 'scale(0.95)';
    setTimeout(() => {
        event.target.style.transform = 'scale(1)';
    }, 200);
}

// ===== Forgot Password =====
function showForgotPassword() {
    showNotification('Password reset link sent to your email!', 'success');
    event.preventDefault();
}

// ===== Notification System =====
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 25px;
        background: ${type === 'success' ? 'linear-gradient(45deg, #28a745, #20c997)' : 
                   type === 'error' ? 'linear-gradient(45deg, #dc3545, #c82333)' : 
                   'linear-gradient(45deg, #17a2b8, #138496)'};
        color: white;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        z-index: 10000;
        transform: translateX(400px);
        transition: all 0.4s ease;
        font-weight: 500;
        max-width: 300px;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.2);
    `;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    // Remove after 5 seconds
    setTimeout(() => {
        notification.style.transform = 'translateX(400px)';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 400);
    }, 5000);
}

// ===== Micro-interactions =====
function initializeMicroInteractions() {
    // Add hover sound effects (visual feedback)
    const interactiveElements = document.querySelectorAll('button, a, input');
    interactiveElements.forEach(element => {
        element.addEventListener('mouseenter', addHoverEffect);
        element.addEventListener('mouseleave', removeHoverEffect);
    });
    
    // Add click ripple effects
    const buttons = document.querySelectorAll('button, .social-btn');
    buttons.forEach(button => {
        button.addEventListener('click', createRippleEffect);
    });
}

function addHoverEffect(e) {
    const element = e.target;
    element.style.transition = 'all 0.3s ease';
    
    if (element.tagName === 'BUTTON' || element.classList.contains('social-btn')) {
        element.style.transform = 'translateY(-2px) scale(1.02)';
    }
}

function removeHoverEffect(e) {
    const element = e.target;
    element.style.transform = 'translateY(0) scale(1)';
}

function createRippleEffect(e) {
    const button = e.currentTarget;
    const ripple = document.createElement('span');
    const rect = button.getBoundingClientRect();
    const size = Math.max(rect.width, rect.height);
    const x = e.clientX - rect.left - size / 2;
    const y = e.clientY - rect.top - size / 2;
    
    ripple.style.cssText = `
        position: absolute;
        width: ${size}px;
        height: ${size}px;
        left: ${x}px;
        top: ${y}px;
        background: rgba(255,255,255,0.3);
        border-radius: 50%;
        transform: scale(0);
        animation: ripple 0.6s ease-out;
        pointer-events: none;
    `;
    
    button.style.position = 'relative';
    button.style.overflow = 'hidden';
    button.appendChild(ripple);
    
    setTimeout(() => {
        ripple.remove();
    }, 600);
}

// ===== Add Ripple Animation =====
const style = document.createElement('style');
style.textContent = `
    @keyframes ripple {
        to {
            transform: scale(2);
            opacity: 0;
        }
    }
    
    .input-glow {
        animation: rotate 2s linear infinite;
    }
`;
document.head.appendChild(style);

console.log('🔐 Enhanced Login Page - Loaded Successfully!');
