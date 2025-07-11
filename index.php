<?php
// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Simple login check
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

// Check if user is logged in (for display purposes, no auto-redirect)
$userLoggedIn = isLoggedIn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <meta name="description" content="Workshopr.io - Interactive workshop participation platform">
  <meta name="theme-color" content="#E94B4B">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="default">
  <meta name="apple-mobile-web-app-title" content="Workshopr.io">
  <title>Workshopr.io - Interactive Workshop Platform</title>
  
  <!-- Styles -->
  <link rel="stylesheet" href="assets/css/main.css">
  
  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="assets/favicon.ico">
  
  <style>
    /* iPhone Optimized Landing Page */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
      background-color: #FFFFFF;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
      /* iPhone safe area support */
      padding-top: env(safe-area-inset-top);
      padding-bottom: env(safe-area-inset-bottom);
      padding-left: env(safe-area-inset-left);
      padding-right: env(safe-area-inset-right);
    }
    
    .landing-container {
      max-width: 375px;
      width: 100%;
      text-align: center;
      padding: 40px 20px;
    }
    
    .logo-section {
      margin-bottom: 60px;
    }
    
    .logo-icon {
      font-size: 64px;
      margin-bottom: 16px;
      display: block;
    }
    
    .logo-text {
      font-size: 32px;
      font-weight: 700;
      color: #000000;
      margin-bottom: 8px;
    }
    
    .logo-subtitle {
      font-size: 16px;
      color: #666666;
      font-weight: 400;
    }
    
    .main-content {
      margin-bottom: 60px;
    }
    
    .welcome-title {
      font-size: 28px;
      font-weight: 600;
      color: #000000;
      margin-bottom: 16px;
      line-height: 1.3;
    }
    
    .welcome-description {
      font-size: 16px;
      color: #666666;
      line-height: 1.5;
      margin-bottom: 40px;
    }
    
    .login-button {
      background-color: #E94B4B;
      color: #FFFFFF;
      border: none;
      border-radius: 12px;
      padding: 16px 32px;
      font-size: 18px;
      font-weight: 600;
      text-decoration: none;
      display: inline-block;
      min-height: 44px; /* iOS touch target minimum */
      min-width: 200px;
      transition: all 0.2s ease;
      box-shadow: 0 2px 8px rgba(233, 75, 75, 0.2);
      /* iOS specific button styling */
      -webkit-appearance: none;
      user-select: none;
      -webkit-user-select: none;
      -webkit-touch-callout: none;
      -webkit-tap-highlight-color: transparent;
    }
    
    .login-button:hover,
    .login-button:focus {
      background-color: #D63E3E;
      transform: translateY(-1px);
      box-shadow: 0 4px 16px rgba(233, 75, 75, 0.3);
    }
    
    .login-button:active {
      transform: translateY(0);
      background-color: #C43535;
    }
    
    .footer-info {
      color: #999999;
      font-size: 14px;
      margin-top: 40px;
    }
    
    /* iPhone specific optimizations */
    @media screen and (max-width: 414px) {
      .landing-container {
        padding: 20px 16px;
      }
      
      .logo-icon {
        font-size: 56px;
      }
      
      .logo-text {
        font-size: 28px;
      }
      
      .welcome-title {
        font-size: 24px;
      }
      
      .welcome-description {
        font-size: 15px;
      }
      
      .login-button {
        width: 100%;
        max-width: 280px;
        font-size: 17px;
      }
    }
    
    /* iPhone SE and smaller */
    @media screen and (max-width: 375px) {
      .logo-section {
        margin-bottom: 40px;
      }
      
      .main-content {
        margin-bottom: 40px;
      }
      
      .logo-text {
        font-size: 26px;
      }
      
      .welcome-title {
        font-size: 22px;
      }
    }
    
    /* Dark mode support */
    @media (prefers-color-scheme: dark) {
      body {
        background-color: #000000;
      }
      
      .logo-text,
      .welcome-title {
        color: #FFFFFF;
      }
      
      .logo-subtitle,
      .welcome-description {
        color: #CCCCCC;
      }
      
      .footer-info {
        color: #666666;
      }
    }
    
    /* Reduce motion for accessibility */
    @media (prefers-reduced-motion: reduce) {
      .login-button {
        transition: none;
      }
      
      .login-button:hover,
      .login-button:focus {
        transform: none;
      }
      
      .login-button:active {
        transform: none;
      }
    }
  </style>
</head>
<body>
  <div class="landing-container">
    <!-- Logo Section -->
    <div class="logo-section">
      <div class="logo-icon">📊</div>
      <h1 class="logo-text">Workshopr.io</h1>
      <p class="logo-subtitle">Interactive Workshop Platform</p>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
      <?php if ($userLoggedIn): ?>
        <h2 class="welcome-title">Welcome Back!</h2>
        <p class="welcome-description">Ready to continue your workshop experience?</p>
        <a href="pages/dashboard.php" class="login-button">Go to Dashboard</a>
      <?php else: ?>
        <h2 class="welcome-title">Join Your Workshop</h2>
        <p class="welcome-description">Access collaborative workshop sessions with real-time participation and interactive features.</p>
        <a href="pages/login.php" class="login-button">Login</a>
      <?php endif; ?>
    </div>
    
    <!-- Footer -->
    <div class="footer-info">
      © 2024 Workshopr.io
    </div>
  </div>
</body>
</html>