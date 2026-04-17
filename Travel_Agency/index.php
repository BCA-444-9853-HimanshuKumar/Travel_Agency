<?php 
session_start();
require_once 'config/database.php';
require_once 'config/auth.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Travel Agency</title>
  <link rel="stylesheet" href="css/style.css">
  <script src="js/script.js" defer></script>
</head>
<body>
  <!-- Loading Screen -->
  <div class="loading-screen">
    <div class="loading-spinner"></div>
    <div class="loading-text">Loading Amazing Destinations...</div>
  </div>

  <div class="main-container">
    <!-- Navbar -->
    <header>
      <nav>
        <h2>Travel Agency</h2>
        <ul>
          <li><a href="index.php">Home</a></li>
          <li><a href="packages.php">Packages</a></li>
          <?php if (isLoggedIn()): ?>
            <li><a href="booking.php">Booking</a></li>
            <li><a href="logout.php">Logout (<?php echo $_SESSION['username']; ?>)</a></li>
          <?php else: ?>
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Register</a></li>
          <?php endif; ?>
          <li><a href="admin/login.php">Admin</a></li>
        </ul>
      </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero fade-in-up">
      <!-- Video-Like Background Effects -->
      <div class="video-background">
        <div class="video-overlay"></div>
        <div class="video-noise"></div>
        <div class="video-scanlines"></div>
      </div>
      
      <!-- Enhanced Floating Particles -->
      <div class="particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
      </div>
      
      <!-- Advanced Particle Systems -->
      <div class="advanced-particles">
        <div class="glow-particle"></div>
        <div class="glow-particle"></div>
        <div class="glow-particle"></div>
        <div class="glow-particle"></div>
        <div class="glow-particle"></div>
        <div class="orbital-particle"></div>
        <div class="orbital-particle"></div>
        <div class="wave-particle"></div>
        <div class="wave-particle"></div>
        <div class="wave-particle"></div>
        <div class="wave-particle"></div>
      </div>
      
      <div class="hero-content">
        <h1>Welcome to Our Travel Agency</h1>
        <p>Discover amazing destinations and book your dream vacation today!</p>
        <a href="packages.php">Explore Packages</a>
      </div>
      
      <div class="scroll-indicator"></div>
    </section>

    <!-- Features Section -->
    <section class="features fade-in-up">
      <div class="container">
        <h2>Why Choose Us</h2>
        <div class="features-grid">
          <div class="feature-card scale-in">
            <div class="feature-icon">✈️</div>
            <h3>Best Prices</h3>
            <p>Get the most competitive rates for flights, hotels, and packages.</p>
          </div>
          <div class="feature-card scale-in">
            <div class="feature-icon">🌍</div>
            <h3>Worldwide Destinations</h3>
            <p>Explore over 100+ destinations across the globe with expert guidance.</p>
          </div>
          <div class="feature-card scale-in">
            <div class="feature-icon">🎯</div>
            <h3>Expert Planning</h3>
            <p>Our travel experts create personalized itineraries just for you.</p>
          </div>
          <div class="feature-card scale-in">
            <div class="feature-icon">🛡️</div>
            <h3>Safe & Secure</h3>
            <p>Travel with confidence knowing your safety is our top priority.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Statistics Section -->
    <section class="statistics fade-in-up">
      <div class="container">
        <h2>Our Achievements</h2>
        <div class="stats-grid">
          <div class="stat-card scale-in">
            <div class="stat-number" data-target="50000">0</div>
            <div class="stat-label">Happy Travelers</div>
          </div>
          <div class="stat-card scale-in">
            <div class="stat-number" data-target="150">0</div>
            <div class="stat-label">Destinations</div>
          </div>
          <div class="stat-card scale-in">
            <div class="stat-number" data-target="1000">0</div>
            <div class="stat-label">Tour Packages</div>
          </div>
          <div class="stat-card scale-in">
            <div class="stat-number" data-target="25">0</div>
            <div class="stat-label">Years Experience</div>
          </div>
        </div>
      </div>
    </section>

    <!-- Popular Destinations -->
    <section class="destinations fade-in-up">
      <div class="container">
        <h2>Popular Destinations</h2>
        <div class="destinations-grid">
          <div class="destination-card fade-in-left">
            <div class="destination-image">
              <div class="destination-overlay">
                <h3>Paris</h3>
                <p>City of Light</p>
              </div>
            </div>
          </div>
          <div class="destination-card fade-in-left">
            <div class="destination-image">
              <div class="destination-overlay">
                <h3>Bali</h3>
                <p>Island Paradise</p>
              </div>
            </div>
          </div>
          <div class="destination-card fade-in-right">
            <div class="destination-image">
              <div class="destination-overlay">
                <h3>Dubai</h3>
                <p>Modern Luxury</p>
              </div>
            </div>
          </div>
          <div class="destination-card fade-in-right">
            <div class="destination-image">
              <div class="destination-overlay">
                <h3>New York</h3>
                <p>Big Apple</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Testimonials -->
    <section class="testimonials fade-in-up">
      <div class="container">
        <h2>What Our Customers Say</h2>
        <div class="testimonials-grid">
          <div class="testimonial-card fade-in-left">
            <div class="stars">⭐⭐⭐⭐⭐</div>
            <p>"Amazing experience! The travel agency planned everything perfectly. Will definitely book again!"</p>
            <div class="author">- Sarah Johnson</div>
          </div>
          <div class="testimonial-card scale-in">
            <div class="stars">⭐⭐⭐⭐⭐</div>
            <p>"Best prices and excellent service. Made our honeymoon trip unforgettable."</p>
            <div class="author">- Michael Chen</div>
          </div>
          <div class="testimonial-card fade-in-right">
            <div class="stars">⭐⭐⭐⭐⭐</div>
            <p>"Professional team and great recommendations. Our family vacation was perfect!"</p>
            <div class="author">- Emily Davis</div>
          </div>
        </div>
      </div>
    </section>

    <!-- Interactive Timeline Section -->
    <section class="timeline fade-in-up">
      <div class="container">
        <h2>Our Journey</h2>
        <div class="timeline-container">
          <div class="timeline-line"></div>
          <div class="timeline-item">
            <div class="timeline-dot"></div>
            <div class="timeline-content">
              <h3>Founded</h3>
              <div class="date">1998</div>
              <p>Started with a small office and a big dream to make travel accessible to everyone.</p>
            </div>
          </div>
          <div class="timeline-item">
            <div class="timeline-dot"></div>
            <div class="timeline-content">
              <h3>First International Office</h3>
              <div class="date">2005</div>
              <p>Expanded globally with our first international office in London, serving European travelers.</p>
            </div>
          </div>
          <div class="timeline-item">
            <div class="timeline-dot"></div>
            <div class="timeline-content">
              <h3>Digital Transformation</h3>
              <div class="date">2015</div>
              <p>Launched our revolutionary online platform, making booking easier than ever before.</p>
            </div>
          </div>
          <div class="timeline-item">
            <div class="timeline-dot"></div>
            <div class="timeline-content">
              <h3>Million Happy Travelers</h3>
              <div class="date">2023</div>
              <p>Celebrated serving over 1 million happy travelers across 150+ destinations worldwide.</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Progress Indicators Section -->
    <section class="progress-section fade-in-up">
      <div class="progress-container">
        <h2>Our Excellence Metrics</h2>
        <div class="progress-item">
          <div class="progress-header">
            <span class="progress-label">Customer Satisfaction</span>
            <span class="progress-percentage">98%</span>
          </div>
          <div class="progress-bar">
            <div class="progress-fill" data-progress="98"></div>
          </div>
        </div>
        <div class="progress-item">
          <div class="progress-header">
            <span class="progress-label">On-Time Performance</span>
            <span class="progress-percentage">95%</span>
          </div>
          <div class="progress-bar">
            <div class="progress-fill" data-progress="95"></div>
          </div>
        </div>
        <div class="progress-item">
          <div class="progress-header">
            <span class="progress-label">Service Quality</span>
            <span class="progress-percentage">92%</span>
          </div>
          <div class="progress-bar">
            <div class="progress-fill" data-progress="92"></div>
          </div>
        </div>
        <div class="progress-item">
          <div class="progress-header">
            <span class="progress-label">Global Reach</span>
            <span class="progress-percentage">87%</span>
          </div>
          <div class="progress-bar">
            <div class="progress-fill" data-progress="87"></div>
          </div>
        </div>
        <div class="progress-item">
          <div class="progress-header">
            <span class="progress-label">Innovation Index</span>
            <span class="progress-percentage">94%</span>
          </div>
          <div class="progress-bar">
            <div class="progress-fill" data-progress="94"></div>
          </div>
        </div>
      </div>
    </section>

    <!-- Contact Section -->
    <section class="contact fade-in-up">
      <div class="container">
        <h2>Get In Touch</h2>
        <div class="contact-content">
          <div class="contact-info fade-in-left">
            <h3>Contact Information</h3>
            <div class="contact-item">
              <strong>📍 Address:</strong> 123 Travel Street, City, Country 12345
            </div>
            <div class="contact-item">
              <strong>📞 Phone:</strong> +1 (555) 123-4567
            </div>
            <div class="contact-item">
              <strong>📧 Email:</strong> info@travelagency.com
            </div>
            <div class="contact-item">
              <strong>🕐 Hours:</strong> Mon-Fri: 9AM-6PM, Sat: 10AM-4PM
            </div>
          </div>
          <div class="contact-form fade-in-right">
            <h3>Send us a Message</h3>
            <form>
              <input type="text" placeholder="Your Name" required>
              <input type="email" placeholder="Your Email" required>
              <textarea placeholder="Your Message" rows="5" required></textarea>
              <button type="submit">Send Message</button>
            </form>
          </div>
        </div>
      </div>
    </section>

    <!-- Newsletter Section -->
    <section class="newsletter fade-in-up">
      <div class="container">
        <h2>Stay Updated</h2>
        <p>Subscribe to our newsletter for exclusive deals and travel tips!</p>
        <form class="newsletter-form">
          <input type="email" placeholder="Enter your email" required>
          <button type="submit">Subscribe</button>
        </form>
      </div>
    </section>

    <!-- Footer -->
    <footer>
      &copy; <?php echo date("Y"); ?> Travel Agency. All rights reserved.
    </footer>
  </div>
</body>
</html>
