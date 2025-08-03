<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Daewoo Transport System</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    :root {
      --bg: #ffffff;
      --text: #1e293b;
      --header-bg: #001f3f;
      --nav-bg: #ffffff;
      --nav-link: #001f3f;
      --nav-link-hover: #0077b6;
      --section-bg: #f8fafc;
      --card-bg: #f1f5f9;
      --highlight: #0077b6;
      --footer-bg: #e2e8f0;
    }

    body.dark-theme {
      --bg: #0a0e21;
      --text: #cccccc;
      --header-bg: #001f3f;
      --nav-bg: #001f3f;
      --nav-link: #00b4d8;
      --nav-link-hover: #ffffff;
      --section-bg: #1e293b;
      --card-bg: #334155;
      --highlight: #00b4d8;
      --footer-bg: #001f3f;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      scroll-behavior: smooth;
      transition: background 0.3s, color 0.3s;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      background: var(--bg);
      color: var(--text);
    }

    header {
      background: var(--header-bg);
      padding: 30px 20px;
      text-align: center;
      color: white;
      position: relative;
    }

    .logo img {
      position: absolute;
      top: 20px;
      left: 20px;
      height: 60px;
    }

    header h1 {
      font-size: 2.5rem;
      margin-bottom: 10px;
    }

    .theme-toggle {
      position: absolute;
      top: 20px;
      right: 20px;
      background: var(--highlight);
      color: white;
      border: none;
      padding: 8px 16px;
      border-radius: 6px;
      cursor: pointer;
      font-size: 0.9rem;
    }

    nav {
      background: var(--nav-bg);
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      padding: 12px 0;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    nav a {
      margin: 10px 20px;
      text-decoration: none;
      color: var(--nav-link);
      font-weight: bold;
      font-size: 1rem;
    }

    nav a:hover {
      color: var(--nav-link-hover);
    }

    .hero {
      text-align: center;
      padding: 60px 20px;
      background-color: var(--section-bg);
    }

    .hero h2 {
      font-size: 2rem;
      margin-bottom: 15px;
    }

    .hero p {
      font-size: 1.1rem;
      margin-bottom: 25px;
    }

    .hero button {
      background-color: var(--highlight);
      color: white;
      padding: 12px 28px;
      border: none;
      border-radius: 8px;
      font-size: 1rem;
      cursor: pointer;
    }

    .hero button:hover {
      background-color: #005f91;
    }

    .travel-text {
      text-align: center;
      font-size: 1.2rem;
      font-weight: bold;
      color: var(--highlight);
      margin: 20px 0;
      animation: floatText 4s infinite ease-in-out;
    }

    @keyframes floatText {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-10px); }
    }

    .routes, .about, .gallery, .contact {
      padding: 50px 20px;
      text-align: center;
      background-color: var(--section-bg);
    }

    .routes h3, .about h3, .gallery h3, .contact h3 {
      color: var(--highlight);
      margin-bottom: 30px;
      font-size: 1.8rem;
    }

    .route-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 25px;
      padding: 0 20px;
    }

    .route-card {
      background: var(--card-bg);
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
      transition: transform 0.3s, box-shadow 0.3s;
    }

    .route-card:hover {
      transform: scale(1.03);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .route-card h4 {
      color: var(--highlight);
      margin-bottom: 10px;
    }

    .route-card p {
      font-size: 1rem;
    }

    .gallery-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 20px;
      padding: 0 20px;
    }

    .gallery img {
      width: 100%;
      height: 500px;
      object-fit: cover;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      transition: transform 0.3s;
    }

    .gallery img:hover {
      transform: scale(1.02);
    }

    .contact p {
      font-size: 1.1rem;
      margin-top: 10px;
    }

    footer {
      background-color: var(--footer-bg);
      color: var(--text);
      text-align: center;
      padding: 20px;
      font-size: 0.9rem;
      margin-top: 50px;
    }

    @media (max-width: 600px) {
      header h1 {
        font-size: 2rem;
      }

      .hero h2 {
        font-size: 1.5rem;
      }

      .hero button {
        width: 100%;
      }

      nav {
        flex-direction: column;
        align-items: center;
      }

      nav a {
        margin: 8px 0;
      }
    }
  </style>
</head>
<body>

  <button class="theme-toggle" onclick="toggleTheme()">Toggle Theme</button>

  <header>
    <h1>Daewoo Bus Transport System</h1>
    <p>Book your ride. Safe, Fast & Comfortable.</p>
  </header>

  <nav>
    <a href="#routes">Routes</a>
    <a href="#gallery">Gallery</a>
    <a href="#about">About</a>
    <a href="#contact">Contact</a>
    <a href="book.php">Book Now</a>
    <a href="admin_login.php">Admin</a>
    <a href="user_login.php">User Login</a>
  </nav>

  <section class="hero">
    <h2>Ready to Travel?</h2>
    <p>Check available routes and book your Daewoo ticket online with ease.</p>
    <a href="book.php"><button>Book Now</button></a>
  </section>

  <div class="travel-text"> Travel with Comfort & Class | Book Now! </div>

  <section class="routes" id="routes">
    <h3>Available Routes & Fares</h3>
    <div class="route-grid">
      <div class="route-card">
        <h4>Abbottabad → Lahore</h4>
        <p>Fare: Rs. 3000</p>
      </div>
      <div class="route-card">
        <h4>Lahore → Karachi</h4>
        <p>Fare: Rs. 4000</p>
      </div>
      <div class="route-card">
        <h4>Abbottabad → Peshawar</h4>
        <p>Fare: Rs. 2500</p>
      </div>
      <div class="route-card">
        <h4>Abbottabad → Multan</h4>
        <p>Fare: Rs. 5000</p>
      </div>
    </div>
  </section>

  <section class="gallery" id="gallery">
    <h3>Our Fleet</h3>
    <div class="gallery-grid">
      <img src="img/bus1.jpg" alt="Daewoo Bus 1">
      <img src="img/bus2.jpg" alt="Daewoo Bus 2">
      <img src="img/bus3.jpg" alt="Daewoo Bus 3">
    </div>
  </section>

  <section class="about" id="about">
    <h3>About Us</h3>
    <p>
      We are committed to providing top-notch intercity transport with comfort, safety, and punctuality. 
      Book your seat online and enjoy a hassle-free journey with Daewoo!
    </p>
  </section>

  <section class="contact" id="contact">
    <h3>Contact Us</h3>
    <p>If you face any issue or need help, please email us at:</p>
    <p><strong>Email:</strong> <a href="mailto:daewoopk@gmail.com">daewoopk@gmail.com</a></p>
  </section>

  <footer>
    &copy; <?= date('Y') ?> Daewoo Transport | All Rights Reserved.
  </footer>

  <script>
    function toggleTheme() {
      document.body.classList.toggle('dark-theme');
    }
  </script>

</body>
</html>
