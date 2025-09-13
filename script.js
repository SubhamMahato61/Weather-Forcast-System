function getWeather() {
  const city = document.getElementById("cityInput").value.trim();
  if (!city) {
    document.getElementById("weatherResult").innerText = "Please enter a city name.";
    return;
  }

  // Fetch current weather from PHP backend
  fetch(`weather.php?city=${city}`)
    .then(response => {
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      return response.json();
    })
    .then(data => {
      if (data.error) {
        document.getElementById("weatherResult").innerText = data.error;
      } else {
        document.getElementById("weatherResult").innerHTML = `
          <h2>${data.name}</h2>
          <p>🌡️ Temperature: ${data.temp}°C</p>
          <p>🌥️ Condition: ${data.condition}</p>
          <p>💧 Humidity: ${data.humidity}%</p>
          <p>💨 Wind Speed: ${data.wind} m/s</p>
        `;
        getForecast(city); // Trigger forecast after current weather
      }
    })
    .catch(error => {
      document.getElementById("weatherResult").innerText = "Error fetching data";
      console.error("Fetch error:", error);
    });
}

function getForecast(city) {
  const apiKey = "29d0b3ea96fee0d6269d5c57e3ab8745"; // Replace with your actual OpenWeatherMap API key
  fetch(`https://api.openweathermap.org/data/2.5/forecast?q=${city}&appid=${apiKey}`)
    .then(response => {
      if (!response.ok) {
        throw new Error("Forecast fetch failed");
      }
      return response.json();
    })
    .then(data => {
      const forecastContainer = document.getElementById("forecastResult");
      forecastContainer.innerHTML = "<h3>📅 3-Day Forecast</h3>";

      // Extract one forecast per day (every 8th item = ~24 hours)
      for (let i = 0; i < data.list.length; i += 8) {
        const item = data.list[i];
        const date = item.dt_txt.split(" ")[0];
        const temp = Math.round(item.main.temp - 273.15);
        const condition = item.weather[0].description;

        forecastContainer.innerHTML += `
          <div style="margin-bottom:15px; padding:10px; background:#f0f0f0; border-radius:6px;">
            <strong>${date}</strong><br>
            🌡️ ${temp}°C<br>
            🌥️ ${condition}
          </div>
        `;
      }
    })
    .catch(error => {
      document.getElementById("forecastResult").innerText = "Error fetching forecast";
      console.error("Forecast error:", error);
    });
}
