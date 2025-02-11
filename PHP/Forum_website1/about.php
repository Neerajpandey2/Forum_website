<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="Partials/style.css">
    <link rel="icon" type="image/jpg" href="images/favicon1.jpg">
    <title>About Our Forum</title>

    <style>
      /* Custom styles for About Page */
      .about-header {
        background-color: #ff6f61;  /* Attractive coral color */
        padding: 60px 0;
        text-align: center;
        border-bottom: 2px solid #dee2e6;
        color: white;
      }

      .about-header h1 {
        font-size: 2.5rem; /* Reduced font size for better responsiveness */
        color: #fff;
      }

      .about-container {
        margin-top: 40px;
      }

      .mission-section, .features-section, .why-join-section {
        margin-top: 40px;
      }

      .mission-section h3, .features-section h3, .why-join-section h3 {
        font-size: 2rem;
        text-align: center;
        margin-bottom: 30px;
        color: #2d3436;
      }

      .features-list {
    display: flex;
    flex-wrap: wrap;
    justify-content: center; /* Horizontally center the items */
    gap: 20px; /* Add some space between items */
    align-items: center; /* Vertically center the items */
    margin-top: 30px;
  }

  .feature-item {
    background-color: #f8f9fa;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
    width: 100%;
    max-width: 350px; /* Limit the max width of feature items */
    text-align: center; /* Center the content within each item */
  }

  .feature-item:hover {
    transform: scale(1.05);
    background-color: #e9ecef;
  }

  /* Make it responsive */
  @media (max-width: 767px) {
    .feature-item {
      padding: 15px; /* Smaller padding on mobile */
    }
  }

      .feature-item:hover {
        transform: scale(1.05);
        background-color: #e9ecef;
      }

      .why-join-section {
        background-color: #f1f1f1;
        padding: 30px;
        border-radius: 8px;
      }

      /* Footer styles */
      .footer {
        background-color: #343a40;
        color: white;
        padding: 20px;
        text-align: center;
        margin-top: 60px;
      }

      .footer a {
        color: #fff;
        text-decoration: none;
      }

      /* Adjusting font size and padding for small screens */
      @media (max-width: 767px) {
        .about-header h1 {
          font-size: 2rem; /* Smaller font size on mobile */
        }
        .mission-section h3, .features-section h3, .why-join-section h3 {
          font-size: 1.5rem; /* Smaller heading size */
        }
        .feature-item {
          padding: 15px; /* Reduced padding for better fit */
        }
      }
    </style>
  </head>

  <!-- Header Section -->
    <?php include "Partials/_header.php" ?>
    <?php include "Partials/login_modal.php"; ?>
    <?php include "Partials/signup_modal.php"; ?>
    <?php include "Partials/admin_login_modal.php"; ?>

  <div class="container about-container">
    <div class="about-header">
      <h1>About Our Forum</h1>
      <p>Welcome to the community-driven forum where ideas, discussions, and solutions come together.</p>
    </div>

    <!-- Mission Section -->
    <div class="mission-section">
      <h3>Our Mission</h3>
      <p class="text-center">
        Our mission is to create a space where individuals from all walks of life can come together to share knowledge, seek advice, and connect with like-minded people. We aim to foster respectful discussions and promote personal growth through engaging conversations.
      </p>
    </div>

    <!-- Features Section -->
    <div class="features-section">
      <h3>Key Features</h3>
      <div class="features-list">
        <div class="feature-item">
          <h4>Community Discussions</h4>
          <p>Engage in thought-provoking discussions on various topics ranging from tech to lifestyle. Share your experiences and ideas with others.</p>
        </div>
        <div class="feature-item">
          <h4>User-friendly Interface</h4>
          <p>Our forum is designed with a clean and easy-to-use interface to ensure seamless navigation and an enjoyable user experience.</p>
        </div>
        <div class="feature-item">
          <h4>Moderation & Security</h4>
          <p>We prioritize the safety of our community with active moderation and secure systems to protect your privacy and maintain a healthy environment.</p>
        </div>
      </div>
    </div>

    <!-- Why Join Us Section -->
    <div class="why-join-section">
      <h3>Why Join Our Forum?</h3>
      <p class="text-center">
        By joining our forum, you gain access to a wealth of knowledge, a supportive community, and the opportunity to contribute your own insights. Whether you're looking to learn, share, or network, our forum is the place to be.
      </p>
    </div>

  </div>

  <!-- Footer Section -->
  <div class="footer">
    <p>&copy; 2024 Forum Website. All rights reserved.</p>
    <p>Follow us on <a href="http://www.linkedin.com/in/neerajpandey-97a457294">LinkedIn</a> | <a href="https://github.com/Neerajpandey2">GitHub</a></p>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</html>
