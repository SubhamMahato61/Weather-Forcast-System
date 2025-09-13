<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
  header("Location: login.php");
  exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Weather App Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f0f4f8;
      padding: 30px;
      text-align: center;
    }
    h1 {
      margin-bottom: 30px;
    }
    .card {
      display: inline-block;
      background: white;
      padding: 20px;
      margin: 10px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      width: 250px;
      vertical-align: top;
    }
    .card a {
      text-decoration: none;
      color: #00796b;
      font-weight: bold;
    }
    .logout {
      text-align: right;
      margin-bottom: 20px;
    }
  </style>
</head>
<body>
  <div class="logout">
    <form method="POST" action="logout.php">
      <button type="submit" style="padding:6px 12px;">🚪 Logout</button>
    </form>
  </div>

  <h1>📊 Weather App Dashboard</h1>

  <div class="card">
    <h3>🌦️ View Logs</h3>
    <p>See all weather queries with filters and charts.</p>
    <a href="admin.php">Go to Admin Panel</a>
  </div>

  <div class="card">
    <h3>📁 Export Data</h3>
    <p>Download weather logs as CSV for analysis.</p>
    <a href="export.php">Export Logs</a>
  </div>

  <div class="card">
    <h3>🌍 Forecast Module</h3>
    <p>Check future weather predictions for any city.</p>
    <a href="index.html">Open Forecast Tool</a>
  </div>
</body>
</html>
