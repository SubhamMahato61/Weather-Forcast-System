<?php
$conn = new mysqli("localhost", "root", "", "weather_db");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="weather_logs.csv"');

$output = fopen('php://output', 'w');

// CSV header
fputcsv($output, ['ID', 'City', 'Temperature (°C)', 'Condition', 'Humidity (%)', 'Wind Speed (m/s)', 'Query Time']);

// Fetch data
$sql = "SELECT * FROM weather_logs ORDER BY query_time DESC";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
  fputcsv($output, [
    $row['id'],
    $row['city'],
    $row['temperature'],
    $row['condition'],
    $row['humidity'],
    $row['wind_speed'],
    $row['query_time']
  ]);
}

fclose($output);
$conn->close();
?>
