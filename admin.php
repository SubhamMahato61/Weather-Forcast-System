<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
  header("Location: login.php");
  exit;
}
?>
<form method="POST" action="logout.php" style="text-align:right;">
  <button type="submit" style="padding:6px 12px;">🚪 Logout</button>
</form>


<?php
// Connect to MySQL
$conn = new mysqli("localhost", "root", "", "weather_db");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Fetch all logs
$search = isset($_GET['search']) ? $_GET['search'] : '';
$from = isset($_GET['from_date']) ? $_GET['from_date'] : '';
$to = isset($_GET['to_date']) ? $_GET['to_date'] : '';

$sql = "SELECT * FROM weather_logs WHERE 1";

if ($search !== '') {
  $sql .= " AND city LIKE '%$search%'";
}
if ($from !== '') {
  $sql .= " AND query_time >= '$from'";
}
if ($to !== '') {
  $sql .= " AND query_time <= '$to 23:59:59'";
}

$sql .= " ORDER BY query_time DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Weather Logs - Admin Panel</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f0f4f8;
      padding: 30px;
    }
    h1 {
      text-align: center;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
      background: white;
    }
    th, td {
      padding: 12px;
      border: 1px solid #ccc;
      text-align: center;
    }
    th {
      background-color: #00796b;
      color: white;
    }
    tr:nth-child(even) {
      background-color: #f9f9f9;
    }
  </style>
</head>
<body>
  <h1>🌦️ Weather Query Logs</h1>
 <form method="GET" style="margin-bottom: 20px; text-align: center; display: flex; flex-wrap: wrap; justify-content: center; gap: 10px;">
  <input type="text" name="search" placeholder="Search by city" style="padding: 8px; width: 180px;">
  <input type="date" name="from_date" style="padding: 8px;">
  <input type="date" name="to_date" style="padding: 8px;">
  <button type="submit" style="padding: 8px 16px;">Filter</button>
</form>


<form method="GET" action="export.php" style="text-align: center; margin-bottom: 20px;">
  <button type="submit" style="padding: 8px 16px;">⬇️ Export to CSV</button>
</form>


  <table>
    <tr>
      <th>ID</th>
      <th>City</th>
      <th>Temperature (°C)</th>
      <th>Condition</th>
      <th>Humidity (%)</th>
      <th>Wind Speed (m/s)</th>
      <th>Time Queried</th>
    </tr>

    <?php
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        echo "<tr>
          <td>{$row['id']}</td>
          <td>{$row['city']}</td>
          <td>{$row['temperature']}</td>
          <td>{$row['condition']}</td>
          <td>{$row['humidity']}</td>
          <td>{$row['wind_speed']}</td>
          <td>{$row['query_time']}</td>
        </tr>";
      }
    } else {
      echo "<tr><td colspan='7'>No logs found</td></tr>";
    }
    // Prepare data for chart
$labels = [];
$temps = [];

$result->data_seek(0); // Reset pointer
while($row = $result->fetch_assoc()) {
  $labels[] = $row['query_time'];
  $temps[] = $row['temperature'];
}

    $conn->close();
    ?>
  </table>
  <h2 style="margin-top:40px;">📈 Temperature Trend</h2>
<canvas id="tempChart" width="800" height="400"></canvas>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const labels = <?php echo json_encode($labels); ?>;
  const temps = <?php echo json_encode($temps); ?>;

  const ctx = document.getElementById('tempChart').getContext('2d');
  const tempChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: labels,
      datasets: [{
        label: 'Temperature (°C)',
        data: temps,
        backgroundColor: 'rgba(0, 150, 136, 0.2)',
        borderColor: 'rgba(0, 150, 136, 1)',
        borderWidth: 2,
        fill: true,
        tension: 0.3
      }]
    },
    options: {
      scales: {
        x: {
          title: {
            display: true,
            text: 'Query Time'
          }
        },
        y: {
          title: {
            display: true,
            text: 'Temperature (°C)'
          },
          beginAtZero: false
        }
      }
    }
  });
</script>
</body>
</html>
