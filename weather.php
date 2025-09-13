<?php
header('Content-Type: application/json');

$city = $_GET['city'];
$apiKey = "29d0b3ea96fee0d6269d5c57e3ab8745"; // Replace with your actual key
$url = "https://api.openweathermap.org/data/2.5/weather?q=$city&appid=$apiKey";

$response = file_get_contents($url);
if (!$response) {
  echo json_encode(['error' => 'Failed to fetch data']);
  exit;
}

$data = json_decode($response, true);
$tempCelsius = round($data['main']['temp'] - 273.15, 2);
$condition = $data['weather'][0]['description'];
$humidity = $data['main']['humidity'];
$wind = $data['wind']['speed'];

// Connect to MySQL
$conn = new mysqli("localhost", "root", "", "weather_db");
if ($conn->connect_error) {
  echo json_encode(['error' => 'Database connection failed']);
  exit;
}

// Insert data
$stmt = $conn->prepare("INSERT INTO weather_logs (city, temperature, `condition`, humidity, wind_speed) VALUES (?, ?, ?, ?, ?)");

$stmt->bind_param("sdsid", $city, $tempCelsius, $condition, $humidity, $wind);
$stmt->execute();
$stmt->close();
$conn->close();

// Return data to frontend
echo json_encode([
  'name' => $data['name'],
  'temp' => $tempCelsius,
  'condition' => $condition,
  'humidity' => $humidity,
  'wind' => $wind
]);
?>
