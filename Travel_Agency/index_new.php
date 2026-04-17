<?php 
session_start();
require_once 'config/database.php';
require_once 'config/auth.php';
require_once 'ai/chatbot.php';
require_once 'ai/recommendation_engine.php';
require_once 'ai/smart_search.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Travel Agency - Discover Your Next Adventure</title>
  <link rel="stylesheet" href="css/style.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  
  <style>
    :root {
      --primary-color: #2563eb;
      --primary-dark: #1e40af;
      --primary-light: #3b82f6;
      --secondary-color: #f59e0b;
      --accent-color: #10b981;
      --danger-color: #ef4444;
      --dark-color: #1f2937;
      --light-color: #f9fafb;
      --text-primary: #111827;
      --text-secondary: #6b7280;
      --border-color: #e5e7eb;
      --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
      --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
      --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
      --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
      --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      --gradient-secondary: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
      --gradient-accent: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', sans-serif;
      line-height: 1.6;
      color: var(--text-primary);
      background: var(--light-color);
      overflow-x: hidden;
    }

    /* Modern Navigation */
    .navbar {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border-bottom: 1px solid var(--border-color);
      z-index: 1000;
      transition: all 0.3s ease;
    }

    .navbar.scrolled {
      background: rgba(255, 255, 255, 0.98);
      box-shadow: var(--shadow-md);
    }

    .nav-container {
      max-width: 1280px;
      margin: 0 auto;
      padding: 0 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      height: 70px;
    }

    .nav-logo {
      display: flex;
      align-items: center;
      font-size: 1.5rem;
      font-weight: 700;
      color: var(--primary-color);
      text-decoration: none;
      transition: transform 0.3s ease;
    }

    .nav-logo:hover {
      transform: scale(1.05);
    }

    .nav-logo i {
      margin-right: 10px;
      font-size: 1.8rem;
    }

    .nav-menu {
      display: flex;
      list-style: none;
      gap: 2rem;
      align-items: center;
    }

    .nav-link {
      color: var(--text-primary);
      text-decoration: none;
      font-weight: 500;
      position: relative;
      transition: color 0.3s ease;
    }

    .nav-link:hover {
      color: var(--primary-color);
    }

    .nav-link::after {
      content: '';
      position: absolute;
      bottom: -5px;
      left: 0;
      width: 0;
      height: 2px;
      background: var(--primary-color);
      transition: width 0.3s ease;
    }

    .nav-link:hover::after {
      width: 100%;
    }

    .nav-actions {
      display: flex;
      gap: 1rem;
      align-items: center;
    }

    .btn {
      padding: 0.75rem 1.5rem;
      border: none;
      border-radius: 0.5rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
    }

    .btn-primary {
      background: var(--primary-color);
      color: white;
    }

    .btn-primary:hover {
      background: var(--primary-dark);
      transform: translateY(-2px);
      box-shadow: var(--shadow-lg);
    }

    .btn-outline {
      background: transparent;
      color: var(--primary-color);
      border: 2px solid var(--primary-color);
    }

    .btn-outline:hover {
      background: var(--primary-color);
      color: white;
    }

    .btn-secondary {
      background: var(--secondary-color);
      color: white;
    }

    .btn-secondary:hover {
      background: #d97706;
      transform: translateY(-2px);
      box-shadow: var(--shadow-lg);
    }

    /* Mobile Menu */
    .mobile-menu-toggle {
      display: none;
      background: none;
      border: none;
      font-size: 1.5rem;
      color: var(--text-primary);
      cursor: pointer;
    }

    /* Hero Section */
    .hero {
      min-height: 100vh;
      background: var(--gradient-primary);
      position: relative;
      display: flex;
      align-items: center;
      overflow: hidden;
    }

    .hero::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,138.7C960,139,1056,117,1152,90.7C1248,64,1344,32,1392,16L1440,0L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
      background-size: cover;
    }

    .hero-particles {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      overflow: hidden;
    }

    .particle {
      position: absolute;
      background: rgba(255, 255, 255, 0.5);
      border-radius: 50%;
      animation: float 6s ease-in-out infinite;
    }

    @keyframes float {
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(-20px); }
    }

    .hero-container {
      max-width: 1280px;
      margin: 0 auto;
      padding: 0 20px;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 4rem;
      align-items: center;
      position: relative;
      z-index: 1;
    }

    .hero-content {
      color: white;
    }

    .hero-title {
      font-size: 3.5rem;
      font-weight: 800;
      line-height: 1.2;
      margin-bottom: 1.5rem;
      animation: slideInLeft 1s ease-out;
    }

    .hero-subtitle {
      font-size: 1.25rem;
      margin-bottom: 2rem;
      opacity: 0.9;
      animation: slideInLeft 1s ease-out 0.2s both;
    }

    .hero-actions {
      display: flex;
      gap: 1rem;
      animation: slideInLeft 1s ease-out 0.4s both;
    }

    .hero-image {
      position: relative;
      animation: slideInRight 1s ease-out;
    }

    .hero-image-container {
      background: rgba(255, 255, 255, 0.1);
      border-radius: 1rem;
      padding: 2rem;
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .hero-image-placeholder {
      width: 100%;
      height: 400px;
      background: linear-gradient(135deg, rgba(255,255,255,0.2), rgba(255,255,255,0.1));
      border-radius: 0.5rem;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2rem;
      color: white;
    }

    @keyframes slideInLeft {
      from {
        opacity: 0;
        transform: translateX(-50px);
      }
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }

    @keyframes slideInRight {
      from {
        opacity: 0;
        transform: translateX(50px);
      }
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }

    /* Features Section */
    .features {
      padding: 6rem 0;
      background: white;
    }

    .section-header {
      text-align: center;
      margin-bottom: 4rem;
    }

    .section-title {
      font-size: 2.5rem;
      font-weight: 700;
      color: var(--text-primary);
      margin-bottom: 1rem;
    }

    .section-subtitle {
      font-size: 1.125rem;
      color: var(--text-secondary);
      max-width: 600px;
      margin: 0 auto;
    }

    .features-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 2rem;
      max-width: 1280px;
      margin: 0 auto;
      padding: 0 20px;
    }

    .feature-card {
      background: white;
      border: 1px solid var(--border-color);
      border-radius: 1rem;
      padding: 2rem;
      text-align: center;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .feature-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: var(--gradient-primary);
      transform: scaleX(0);
      transition: transform 0.3s ease;
    }

    .feature-card:hover {
      transform: translateY(-5px);
      box-shadow: var(--shadow-xl);
    }

    .feature-card:hover::before {
      transform: scaleX(1);
    }

    .feature-icon {
      width: 60px;
      height: 60px;
      background: var(--gradient-primary);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 1.5rem;
      font-size: 1.5rem;
      color: white;
    }

    .feature-title {
      font-size: 1.25rem;
      font-weight: 600;
      color: var(--text-primary);
      margin-bottom: 1rem;
    }

    .feature-description {
      color: var(--text-secondary);
      line-height: 1.6;
    }

    /* Packages Section */
    .packages {
      padding: 6rem 0;
      background: var(--light-color);
    }

    .packages-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
      gap: 2rem;
      max-width: 1280px;
      margin: 0 auto;
      padding: 0 20px;
    }

    .package-card {
      background: white;
      border-radius: 1rem;
      overflow: hidden;
      box-shadow: var(--shadow-md);
      transition: all 0.3s ease;
      position: relative;
    }

    .package-card:hover {
      transform: translateY(-10px);
      box-shadow: var(--shadow-xl);
    }

    .package-badge {
      position: absolute;
      top: 1rem;
      right: 1rem;
      background: var(--accent-color);
      color: white;
      padding: 0.25rem 0.75rem;
      border-radius: 1rem;
      font-size: 0.875rem;
      font-weight: 600;
    }

    .package-image {
      height: 200px;
      background: var(--gradient-accent);
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 1.5rem;
    }

    .package-content {
      padding: 2rem;
    }

    .package-title {
      font-size: 1.5rem;
      font-weight: 700;
      color: var(--text-primary);
      margin-bottom: 1rem;
    }

    .package-description {
      color: var(--text-secondary);
      margin-bottom: 1.5rem;
      line-height: 1.6;
    }

    .package-price {
      font-size: 2rem;
      font-weight: 800;
      color: var(--primary-color);
      margin-bottom: 1.5rem;
    }

    .package-price span {
      font-size: 1rem;
      font-weight: 400;
      color: var(--text-secondary);
    }

    /* Testimonials Section */
    .testimonials {
      padding: 6rem 0;
      background: white;
    }

    .testimonials-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
      gap: 2rem;
      max-width: 1280px;
      margin: 0 auto;
      padding: 0 20px;
    }

    .testimonial-card {
      background: var(--light-color);
      border-radius: 1rem;
      padding: 2rem;
      position: relative;
      transition: all 0.3s ease;
    }

    .testimonial-card:hover {
      transform: translateY(-5px);
      box-shadow: var(--shadow-lg);
    }

    .testimonial-quote {
      font-size: 1.125rem;
      color: var(--text-primary);
      line-height: 1.8;
      margin-bottom: 1.5rem;
      font-style: italic;
    }

    .testimonial-author {
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .testimonial-avatar {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      background: var(--gradient-primary);
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-weight: 600;
    }

    .testimonial-info h4 {
      font-weight: 600;
      color: var(--text-primary);
      margin-bottom: 0.25rem;
    }

    .testimonial-info p {
      color: var(--text-secondary);
      font-size: 0.875rem;
    }

    .testimonial-rating {
      color: var(--secondary-color);
      margin-bottom: 1rem;
    }

    /* CTA Section */
    .cta {
      padding: 6rem 0;
      background: var(--gradient-secondary);
      text-align: center;
      color: white;
    }

    .cta-title {
      font-size: 2.5rem;
      font-weight: 700;
      margin-bottom: 1rem;
    }

    .cta-subtitle {
      font-size: 1.25rem;
      margin-bottom: 2rem;
      opacity: 0.9;
    }

    .cta-actions {
      display: flex;
      gap: 1rem;
      justify-content: center;
      flex-wrap: wrap;
    }

    /* Footer */
    .footer {
      background: var(--dark-color);
      color: white;
      padding: 4rem 0 2rem;
    }

    .footer-container {
      max-width: 1280px;
      margin: 0 auto;
      padding: 0 20px;
    }

    .footer-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 2rem;
      margin-bottom: 3rem;
    }

    .footer-column h3 {
      font-size: 1.25rem;
      font-weight: 600;
      margin-bottom: 1.5rem;
    }

    .footer-links {
      list-style: none;
    }

    .footer-links li {
      margin-bottom: 0.75rem;
    }

    .footer-links a {
      color: rgba(255, 255, 255, 0.8);
      text-decoration: none;
      transition: color 0.3s ease;
    }

    .footer-links a:hover {
      color: white;
    }

    .footer-bottom {
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      padding-top: 2rem;
      text-align: center;
      color: rgba(255, 255, 255, 0.6);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .nav-menu {
        display: none;
      }

      .mobile-menu-toggle {
        display: block;
      }

      .hero-container {
        grid-template-columns: 1fr;
        gap: 2rem;
        text-align: center;
      }

      .hero-title {
        font-size: 2.5rem;
      }

      .hero-actions {
        flex-direction: column;
        align-items: center;
      }

      .features-grid,
      .packages-grid,
      .testimonials-grid {
        grid-template-columns: 1fr;
      }

      .cta-actions {
        flex-direction: column;
        align-items: center;
      }
    }

    /* Loading Animation */
    .loading-screen {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: white;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      z-index: 9999;
      transition: opacity 0.5s ease;
    }

    .loading-screen.hidden {
      opacity: 0;
      pointer-events: none;
    }

    .loading-spinner {
      width: 50px;
      height: 50px;
      border: 4px solid var(--border-color);
      border-top: 4px solid var(--primary-color);
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    .loading-text {
      margin-top: 1rem;
      color: var(--text-secondary);
      font-weight: 500;
    }

    /* Scroll Animations */
    .fade-in-up {
      opacity: 0;
      transform: translateY(30px);
      transition: all 0.6s ease;
    }

    .fade-in-up.visible {
      opacity: 1;
      transform: translateY(0);
    }

    .scale-in {
      opacity: 0;
      transform: scale(0.9);
      transition: all 0.6s ease;
    }

    .scale-in.visible {
      opacity: 1;
      transform: scale(1);
    }
  </style>
</head>
<body>
  <!-- Loading Screen -->
  <div class="loading-screen" id="loadingScreen">
    <div class="loading-spinner"></div>
    <div class="loading-text">Loading amazing experiences...</div>
  </div>

  <!-- Navigation -->
  <nav class="navbar" id="navbar">
    <div class="nav-container">
      <a href="index.php" class="nav-logo">
        <i class="fas fa-plane"></i>
        Travel Agency
      </a>
      
      <ul class="nav-menu">
        <li><a href="#home" class="nav-link">Home</a></li>
        <li><a href="#packages" class="nav-link">Packages</a></li>
        <li><a href="#features" class="nav-link">Features</a></li>
        <li><a href="#testimonials" class="nav-link">Testimonials</a></li>
        <li><a href="#contact" class="nav-link">Contact</a></li>
      </ul>
      
      <div class="nav-actions">
        <?php if (isLoggedIn()): ?>
          <a href="booking.php" class="btn btn-outline">Book Now</a>
          <a href="logout.php" class="btn btn-primary">Logout</a>
        <?php else: ?>
          <a href="login.php" class="btn btn-outline">Login</a>
          <a href="register.php" class="btn btn-primary">Sign Up</a>
        <?php endif; ?>
      </div>
      
      <button class="mobile-menu-toggle" id="mobileMenuToggle">
        <i class="fas fa-bars"></i>
      </button>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="hero" id="home">
    <div class="hero-particles" id="heroParticles"></div>
    <div class="hero-container">
      <div class="hero-content">
        <h1 class="hero-title">Discover Your Next Adventure</h1>
        <p class="hero-subtitle">Experience the world's most amazing destinations with our curated travel packages and personalized service.</p>
        <div class="hero-actions">
          <a href="#packages" class="btn btn-primary btn-lg">
            <i class="fas fa-search"></i>
            Explore Packages
          </a>
          <a href="#contact" class="btn btn-outline btn-lg">
            <i class="fas fa-phone"></i>
            Contact Us
          </a>
        </div>
      </div>
      
      <div class="hero-image">
        <div class="hero-image-container">
          <div class="hero-image-placeholder">
            <i class="fas fa-globe-americas"></i>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Features Section -->
  <section class="features" id="features">
    <div class="section-header fade-in-up">
      <h2 class="section-title">Why Choose Us</h2>
      <p class="section-subtitle">We provide exceptional travel experiences with our comprehensive services</p>
    </div>
    
    <div class="features-grid">
      <div class="feature-card fade-in-up">
        <div class="feature-icon">
          <i class="fas fa-shield-alt"></i>
        </div>
        <h3 class="feature-title">Safe & Secure</h3>
        <p class="feature-description">Your safety is our top priority with comprehensive insurance and 24/7 support throughout your journey.</p>
      </div>
      
      <div class="feature-card fade-in-up">
        <div class="feature-icon">
          <i class="fas fa-dollar-sign"></i>
        </div>
        <h3 class="feature-title">Best Prices</h3>
        <p class="feature-description">Get the most competitive rates for flights, hotels, and complete travel packages with no hidden fees.</p>
      </div>
      
      <div class="feature-card fade-in-up">
        <div class="feature-icon">
          <i class="fas fa-users"></i>
        </div>
        <h3 class="feature-title">Expert Guides</h3>
        <p class="feature-description">Professional local guides who know the destinations inside out and speak your language.</p>
      </div>
      
      <div class="feature-card fade-in-up">
        <div class="feature-icon">
          <i class="fas fa-clock"></i>
        </div>
        <h3 class="feature-title">Flexible Booking</h3>
        <p class="feature-description">Easy booking process with flexible dates, payment options, and free cancellation up to 48 hours.</p>
      </div>
      
      <div class="feature-card fade-in-up">
        <div class="feature-icon">
          <i class="fas fa-globe"></i>
        </div>
        <h3 class="feature-title">Worldwide Coverage</h3>
        <p class="feature-description">Explore over 100+ destinations across 6 continents with our extensive network of partners.</p>
      </div>
      
      <div class="feature-card fade-in-up">
        <div class="feature-icon">
          <i class="fas fa-heart"></i>
        </div>
        <h3 class="feature-title">Personalized Service</h3>
        <p class="feature-description">Tailored itineraries and personalized recommendations based on your preferences and budget.</p>
      </div>
    </div>
  </section>

  <!-- Packages Section -->
  <section class="packages" id="packages">
    <div class="section-header fade-in-up">
      <h2 class="section-title">Popular Packages</h2>
      <p class="section-subtitle">Handpicked destinations with unforgettable experiences</p>
    </div>
    
    <div class="packages-grid">
      <?php
      $packages_query = "SELECT * FROM packages ORDER BY id LIMIT 6";
      $packages_result = mysqli_query($con, $packages_query);
      
      if ($packages_result && mysqli_num_rows($packages_result) > 0) {
        while ($package = mysqli_fetch_assoc($packages_result)) {
          echo '<div class="package-card fade-in-up">';
          echo '<div class="package-badge">Popular</div>';
          echo '<div class="package-image">';
          echo '<i class="fas fa-map-marked-alt"></i>';
          echo '</div>';
          echo '<div class="package-content">';
          echo '<h3 class="package-title">' . htmlspecialchars($package['name']) . '</h3>';
          echo '<p class="package-description">' . htmlspecialchars($package['description']) . '</p>';
          echo '<div class="package-price">Rs. ' . number_format($package['price'], 2) . '<span>/person</span></div>';
          echo '<a href="booking.php?package_id=' . $package['id'] . '" class="btn btn-primary" style="width: 100%;">Book Now</a>';
          echo '</div>';
          echo '</div>';
        }
      } else {
        echo '<div class="package-card fade-in-up">';
        echo '<div class="package-content">';
        echo '<h3 class="package-title">No packages available</h3>';
        echo '<p class="package-description">Check back later for amazing travel deals!</p>';
        echo '</div>';
        echo '</div>';
      }
      ?>
    </div>
    
    <div class="section-header fade-in-up" style="margin-top: 4rem;">
      <a href="packages.php" class="btn btn-secondary btn-lg">
        <i class="fas fa-th"></i>
        View All Packages
      </a>
    </div>
  </section>

  <!-- Testimonials Section -->
  <section class="testimonials" id="testimonials">
    <div class="section-header fade-in-up">
      <h2 class="section-title">What Our Travelers Say</h2>
      <p class="section-subtitle">Real experiences from our valued customers</p>
    </div>
    
    <div class="testimonials-grid">
      <div class="testimonial-card fade-in-up">
        <div class="testimonial-rating">
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
        </div>
        <p class="testimonial-quote">"Amazing experience! The team planned everything perfectly and the destinations were breathtaking. Will definitely book again!"</p>
        <div class="testimonial-author">
          <div class="testimonial-avatar">SJ</div>
          <div class="testimonial-info">
            <h4>Sarah Johnson</h4>
            <p>Paris Getaway</p>
          </div>
        </div>
      </div>
      
      <div class="testimonial-card fade-in-up">
        <div class="testimonial-rating">
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
        </div>
        <p class="testimonial-quote">"Best prices and excellent service. Made our honeymoon trip unforgettable. Highly recommend!"</p>
        <div class="testimonial-author">
          <div class="testimonial-avatar">MC</div>
          <div class="testimonial-info">
            <h4>Michael Chen</h4>
            <p>Maldives Paradise</p>
          </div>
        </div>
      </div>
      
      <div class="testimonial-card fade-in-up">
        <div class="testimonial-rating">
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
        </div>
        <p class="testimonial-quote">"Professional team and great recommendations. Our family vacation was perfect!"</p>
        <div class="testimonial-author">
          <div class="testimonial-avatar">ED</div>
          <div class="testimonial-info">
            <h4>Emily Davis</h4>
            <p>Bali Adventure</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA Section -->
  <section class="cta" id="contact">
    <div class="container fade-in-up">
      <h2 class="cta-title">Ready for Your Next Adventure?</h2>
      <p class="cta-subtitle">Join thousands of happy travelers who have discovered the world with us</p>
      <div class="cta-actions">
        <a href="register.php" class="btn btn-primary btn-lg">
          <i class="fas fa-user-plus"></i>
          Get Started
        </a>
        <a href="packages.php" class="btn btn-outline btn-lg" style="border-color: white; color: white;">
          <i class="fas fa-search"></i>
          Browse Packages
        </a>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="footer">
    <div class="footer-container">
      <div class="footer-grid">
        <div class="footer-column">
          <h3>About Us</h3>
          <p style="color: rgba(255, 255, 255, 0.8); margin-bottom: 1rem;">Your trusted travel partner for unforgettable experiences around the world.</p>
          <div class="social-links" style="display: flex; gap: 1rem; margin-top: 1rem;">
            <a href="#" style="color: white; font-size: 1.2rem;"><i class="fab fa-facebook"></i></a>
            <a href="#" style="color: white; font-size: 1.2rem;"><i class="fab fa-twitter"></i></a>
            <a href="#" style="color: white; font-size: 1.2rem;"><i class="fab fa-instagram"></i></a>
            <a href="#" style="color: white; font-size: 1.2rem;"><i class="fab fa-linkedin"></i></a>
          </div>
        </div>
        
        <div class="footer-column">
          <h3>Quick Links</h3>
          <ul class="footer-links">
            <li><a href="#home">Home</a></li>
            <li><a href="#packages">Packages</a></li>
            <li><a href="#features">Features</a></li>
            <li><a href="#testimonials">Testimonials</a></li>
            <li><a href="booking.php">Book Now</a></li>
          </ul>
        </div>
        
        <div class="footer-column">
          <h3>Support</h3>
          <ul class="footer-links">
            <li><a href="contact.php">Contact Us</a></li>
            <li><a href="faq.php">FAQ</a></li>
            <li><a href="terms.php">Terms & Conditions</a></li>
            <li><a href="privacy.php">Privacy Policy</a></li>
            <li><a href="refund.php">Refund Policy</a></li>
          </ul>
        </div>
        
        <div class="footer-column">
          <h3>Contact Info</h3>
          <ul class="footer-links">
            <li><i class="fas fa-phone"></i> +1 (555) 123-4567</li>
            <li><i class="fas fa-envelope"></i> info@travelagency.com</li>
            <li><i class="fas fa-map-marker-alt"></i> 123 Travel Street, City</li>
            <li><i class="fas fa-clock"></i> Mon-Fri: 9AM-6PM</li>
          </ul>
        </div>
      </div>
      
      <div class="footer-bottom">
        <p>&copy; <?php echo date("Y"); ?> Travel Agency. All rights reserved. | Designed with <i class="fas fa-heart" style="color: #ef4444;"></i> for travelers</p>
      </div>
    </div>
  </footer>

  <!-- AI Chatbot (from existing implementation) -->
  <div class="ai-chatbot">
    <div class="chatbot-window" id="chatbotWindow">
      <div class="chatbot-header">
        <h3>AI Travel Assistant</h3>
        <button class="chatbot-close" onclick="toggleChatbot()">×</button>
      </div>
      <div class="chatbot-messages" id="chatbotMessages">
        <div class="message bot">
          <div class="message-avatar">AI</div>
          <div class="message-content">
            Hello! I'm your AI travel assistant. How can I help you today?
          </div>
        </div>
      </div>
      <div class="chatbot-quick-actions" id="quickActions">
        <button class="quick-action-btn" onclick="sendQuickMessage('View All Packages')">View All Packages</button>
        <button class="quick-action-btn" onclick="sendQuickMessage('Check Prices')">Check Prices</button>
        <button class="quick-action-btn" onclick="sendQuickMessage('How to Book')">How to Book</button>
      </div>
      <div class="chatbot-input">
        <form class="chatbot-input-form" onsubmit="sendChatMessage(event)">
          <input type="text" id="chatInput" placeholder="Type your message..." autocomplete="off">
          <button type="submit" class="chatbot-send">Send</button>
        </form>
      </div>
    </div>
    <div class="chatbot-toggle" onclick="toggleChatbot()">
      <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
      </svg>
    </div>
  </div>

  <script>
    // Loading Screen
    window.addEventListener('load', function() {
      setTimeout(function() {
        document.getElementById('loadingScreen').classList.add('hidden');
      }, 1500);
    });

    // Navbar Scroll Effect
    window.addEventListener('scroll', function() {
      const navbar = document.getElementById('navbar');
      if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
      } else {
        navbar.classList.remove('scrolled');
      }
    });

    // Hero Particles
    function createParticles() {
      const particlesContainer = document.getElementById('heroParticles');
      const particleCount = 20;
      
      for (let i = 0; i < particleCount; i++) {
        const particle = document.createElement('div');
        particle.className = 'particle';
        particle.style.left = Math.random() * 100 + '%';
        particle.style.top = Math.random() * 100 + '%';
        particle.style.width = Math.random() * 10 + 5 + 'px';
        particle.style.height = particle.style.width;
        particle.style.animationDelay = Math.random() * 6 + 's';
        particle.style.animationDuration = (Math.random() * 3 + 3) + 's';
        particlesContainer.appendChild(particle);
      }
    }

    createParticles();

    // Scroll Animations
    function handleScrollAnimations() {
      const elements = document.querySelectorAll('.fade-in-up, .scale-in');
      
      elements.forEach(element => {
        const elementTop = element.getBoundingClientRect().top;
        const elementBottom = elementTop + element.offsetHeight;
        
        if (elementTop < window.innerHeight && elementBottom > 0) {
          element.classList.add('visible');
        }
      });
    }

    window.addEventListener('scroll', handleScrollAnimations);
    handleScrollAnimations(); // Initial check

    // Smooth Scrolling
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
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

    // Mobile Menu Toggle
    document.getElementById('mobileMenuToggle').addEventListener('click', function() {
      // Implementation for mobile menu
      alert('Mobile menu will be implemented here');
    });

    // Chatbot Functions (from existing implementation)
    function toggleChatbot() {
      const chatbotWindow = document.getElementById('chatbotWindow');
      chatbotWindow.style.display = chatbotWindow.style.display === 'flex' ? 'none' : 'flex';
    }

    function sendChatMessage(event) {
      event.preventDefault();
      const input = document.getElementById('chatInput');
      const message = input.value.trim();
      
      if (message) {
        addUserMessage(message);
        input.value = '';
        
        setTimeout(() => {
          getBotResponse(message);
        }, 1000);
      }
    }

    function sendQuickMessage(message) {
      addUserMessage(message);
      setTimeout(() => {
        getBotResponse(message);
      }, 1000);
    }

    function addUserMessage(message) {
      const messagesContainer = document.getElementById('chatbotMessages');
      const messageDiv = document.createElement('div');
      messageDiv.className = 'message user';
      messageDiv.innerHTML = `
        <div class="message-avatar">You</div>
        <div class="message-content">${message}</div>
      `;
      messagesContainer.appendChild(messageDiv);
      messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    function addBotMessage(message) {
      const messagesContainer = document.getElementById('chatbotMessages');
      const messageDiv = document.createElement('div');
      messageDiv.className = 'message bot';
      messageDiv.innerHTML = `
        <div class="message-avatar">AI</div>
        <div class="message-content">${message}</div>
      `;
      messagesContainer.appendChild(messageDiv);
      messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    function getBotResponse(message) {
      // Simulate bot response
      const responses = [
        "I'd be happy to help you with your travel needs!",
        "Let me find the perfect package for you.",
        "That sounds like an amazing destination!",
        "I can help you book your dream vacation."
      ];
      
      const randomResponse = responses[Math.floor(Math.random() * responses.length)];
      addBotMessage(randomResponse);
    }
  </script>
</body>
</html>
